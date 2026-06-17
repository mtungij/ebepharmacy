<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DiscountService {
    protected $CI;

    public function __construct(){
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->CI->load->model('queries');
        $this->CI->queries->ensure_branch_table();
    }

    public function applyBestDiscount($product_id, $quantity, $unit_price, $branch_id = null, $user_id = null, $cart_total = 0){
        return $this->emptyDiscount($quantity, $unit_price);
    }

    public function originalCartTotal($quantities, $unit_prices){
        $total = 0;
        $count = is_array($quantities) ? count($quantities) : 0;

        for ($i = 0; $i < $count; $i++) {
            $qty = isset($quantities[$i]) ? (float)$quantities[$i] : 0;
            $price = isset($unit_prices[$i]) ? (float)$unit_prices[$i] : 0;
            $total += $qty * $price;
        }

        return $total;
    }

    public function cartLineItems($product_ids, $quantities, $unit_prices){
        $items = [];
        $count = is_array($product_ids) ? count($product_ids) : 0;

        for ($i = 0; $i < $count; $i++) {
            $qty = isset($quantities[$i]) ? (float)$quantities[$i] : 0;
            $price = isset($unit_prices[$i]) ? (float)$unit_prices[$i] : 0;
            $items[$i] = [
                'product_id' => isset($product_ids[$i]) ? (int)$product_ids[$i] : 0,
                'quantity' => $qty,
                'unit_price' => $price,
                'line_total' => $qty * $price,
            ];
        }

        return $items;
    }

    public function applyManualDiscount($product_id, $quantity, $unit_price, $manual_discount_amount, $branch_id = null, $user_id = null, $cart_total = 0, $used_discount_by_rule = []){
        $quantity = (float)$quantity;
        $unit_price = (float)$unit_price;
        $manual_discount_amount = (float)$manual_discount_amount;
        $line_total = $unit_price * $quantity;

        $empty = $this->emptyDiscount($quantity, $unit_price);

        if ($quantity <= 0 || $unit_price <= 0) {
            return $empty;
        }

        if ($manual_discount_amount <= 0) {
            return $empty;
        }

        $product = $this->CI->db
            ->select('id, name, category, bland, buy_price, branch_id')
            ->where('id', (int)$product_id)
            ->get('product')
            ->row();

        if (!$product) {
            return $empty;
        }

        $today = date('Y-m-d');
        $branch_where = $branch_id ? " AND (branch_id IS NULL OR branch_id = 0 OR branch_id = ".(int)$branch_id.")" : "";
        $rules = $this->CI->db->query("
            SELECT *
            FROM tbl_discount_rules
            WHERE status = 'active'
              AND start_date <= '$today'
              AND end_date >= '$today'
              AND applies_to IN ('product', 'category')
              AND discount_basis = 'line'
              $branch_where
            ORDER BY discount_value DESC
        ")->result();

        $best_rule = null;
        $max_allowed_discount = 0;

        foreach ($rules as $rule) {
            if (!$this->matchesRule($rule, $product)) {
                continue;
            }

            if ((float)$rule->min_purchase_amount > 0 && (float)$cart_total < (float)$rule->min_purchase_amount) {
                continue;
            }

            $rule_limit = $this->calculateDiscountAmount($rule, $line_total, $quantity, (float)$cart_total);
            $rule_used = isset($used_discount_by_rule[(int)$rule->discount_id]) ? (float)$used_discount_by_rule[(int)$rule->discount_id] : 0;
            $rule_max = max(0, $rule_limit - $rule_used);

            if ($rule_max > $max_allowed_discount) {
                $max_allowed_discount = $rule_max;
                $best_rule = $rule;
            }
        }

        if (!$best_rule) {
            $empty['blocked_reason'] = 'No active discount rule allows this product or category.';
            return $empty;
        }

        $discount_amount = max(0, min($manual_discount_amount, $line_total));
        if ($discount_amount > $max_allowed_discount) {
            $empty['blocked_reason'] = 'Discount is higher than the allowed rule amount.';
            return $empty;
        }

        $final_line_total = $line_total - $discount_amount;
        $final_unit_price = $final_line_total / $quantity;
        $approved_by = null;

        return [
            'discount_id' => (int)$best_rule->discount_id,
            'discount_name' => $best_rule->discount_name,
            'original_unit_price' => $unit_price,
            'discount_amount' => $discount_amount,
            'final_unit_price' => $final_unit_price,
            'final_line_total' => $final_line_total,
            'approved_by' => $approved_by,
            'blocked_reason' => null,
        ];
    }

    public function applyCartTotalDiscount($items, $manual_discount_amount, $branch_id = null, $user_id = null, $cart_total = 0){
        $manual_discount_amount = (float)$manual_discount_amount;
        $cart_total = (float)$cart_total;
        $empty = [
            'discount_id' => null,
            'discount_name' => null,
            'discount_amount' => 0,
            'allocations' => [],
            'approved_by' => null,
            'blocked_reason' => null,
        ];

        if ($manual_discount_amount <= 0 || $cart_total <= 0 || empty($items)) {
            return $empty;
        }

        $product_ids = [];
        foreach ($items as $item) {
            if (!empty($item['product_id'])) {
                $product_ids[] = (int)$item['product_id'];
            }
        }
        $product_ids = array_values(array_unique($product_ids));
        if (empty($product_ids)) {
            return $empty;
        }

        $products = $this->CI->db
            ->select('id, category')
            ->where_in('id', $product_ids)
            ->get('product')
            ->result();
        $product_map = [];
        foreach ($products as $product) {
            $product_map[(int)$product->id] = $product;
        }

        $today = date('Y-m-d');
        $branch_where = $branch_id ? " AND (branch_id IS NULL OR branch_id = 0 OR branch_id = ".(int)$branch_id.")" : "";
        $rules = $this->CI->db->query("
            SELECT *
            FROM tbl_discount_rules
            WHERE status = 'active'
              AND start_date <= '$today'
              AND end_date >= '$today'
              AND applies_to IN ('product', 'category')
              AND discount_basis = 'cart'
              $branch_where
            ORDER BY discount_value DESC
        ")->result();

        $best_rule = null;
        $best_indexes = [];
        $max_allowed_discount = 0;

        foreach ($rules as $rule) {
            if ((float)$rule->min_purchase_amount > 0 && $cart_total < (float)$rule->min_purchase_amount) {
                continue;
            }

            $matched_indexes = [];
            foreach ($items as $index => $item) {
                $product_id = (int)$item['product_id'];
                if (!isset($product_map[$product_id]) || !$this->matchesRule($rule, $product_map[$product_id])) {
                    continue;
                }
                $matched_indexes[] = $index;
            }

            if (empty($matched_indexes)) {
                continue;
            }

            $rule_max = $this->calculateDiscountAmount($rule, 0, 0, $cart_total);
            if ($rule_max > $max_allowed_discount) {
                $max_allowed_discount = $rule_max;
                $best_rule = $rule;
                $best_indexes = $matched_indexes;
            }
        }

        if (!$best_rule) {
            $empty['blocked_reason'] = 'No active cart total discount rule is available for this cart.';
            return $empty;
        }

        $discount_amount = max(0, min($manual_discount_amount, $cart_total));
        if ($discount_amount > $max_allowed_discount) {
            $empty['blocked_reason'] = 'Cart discount is higher than the allowed rule amount.';
            return $empty;
        }

        $eligible_total = 0;
        foreach ($best_indexes as $index) {
            $eligible_total += (float)$items[$index]['line_total'];
        }

        if ($eligible_total <= 0) {
            return $empty;
        }

        $allocations = [];
        $allocated = 0;
        $last_index = end($best_indexes);
        reset($best_indexes);
        foreach ($best_indexes as $index) {
            if ($index === $last_index) {
                $share = $discount_amount - $allocated;
            } else {
                $share = round($discount_amount * ((float)$items[$index]['line_total'] / $eligible_total), 2);
                $allocated += $share;
            }
            $allocations[$index] = max(0, min($share, (float)$items[$index]['line_total']));
        }

        return [
            'discount_id' => (int)$best_rule->discount_id,
            'discount_name' => $best_rule->discount_name,
            'discount_amount' => $discount_amount,
            'allocations' => $allocations,
            'approved_by' => null,
            'blocked_reason' => null,
        ];
    }

    protected function emptyDiscount($quantity, $unit_price){
        $quantity = (float)$quantity;
        $unit_price = (float)$unit_price;
        $line_total = $unit_price * $quantity;

        return [
            'discount_id' => null,
            'discount_name' => null,
            'original_unit_price' => $unit_price,
            'discount_amount' => 0,
            'final_unit_price' => $unit_price,
            'final_line_total' => $line_total,
            'approved_by' => null,
            'blocked_reason' => null,
        ];
    }

    protected function matchesRule($rule, $product){
        switch ($rule->applies_to) {
            case 'product':
                return empty($rule->product_id) || (int)$rule->product_id === (int)$product->id;
            case 'category':
                return $rule->category !== null && $rule->category !== '' && (string)$rule->category === (string)$product->category;
        }

        return false;
    }

    protected function calculateDiscountAmount($rule, $line_total, $quantity, $cart_total){
        $basis = isset($rule->discount_basis) ? $rule->discount_basis : 'line';
        $base_total = $basis === 'cart' ? (float)$cart_total : (float)$line_total;

        if ($rule->discount_type === 'percentage') {
            return $base_total * ((float)$rule->discount_value / 100);
        }

        $fixed = (float)$rule->discount_value;
        if ($basis === 'cart') {
            return $fixed;
        }

        return $fixed * $quantity;
    }

    public function saveAudit($data){
        if (empty($data['discount_id']) || (float)$data['discount_amount'] <= 0) {
            return false;
        }

        return $this->CI->db->insert('tbl_discount_audit', [
            'discount_id' => (int)$data['discount_id'],
            'transaction_id' => isset($data['transaction_id']) ? (int)$data['transaction_id'] : null,
            'product_id' => isset($data['product_id']) ? (int)$data['product_id'] : null,
            'applied_by_user' => isset($data['applied_by_user']) ? (int)$data['applied_by_user'] : null,
            'approved_by' => !empty($data['approved_by']) ? (int)$data['approved_by'] : null,
            'original_price' => (float)$data['original_price'],
            'discount_amount' => (float)$data['discount_amount'],
            'final_price' => (float)$data['final_price'],
            'quantity' => (float)$data['quantity'],
        ]);
    }
}
