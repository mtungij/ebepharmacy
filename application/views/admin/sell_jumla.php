<?php include('incs/header.php'); ?>
<?php include('incs/nav.php'); ?>
<?php include('incs/side.php'); ?>
<script src="<?php echo base_url('assets/admin/js/jquery.js'); ?>"></script>
<?php
  $evamo_discount_rules = [];
  foreach (($discount_rules ?? []) as $rule) {
    $evamo_discount_rules[] = [
      'discount_name' => $rule->discount_name,
      'discount_type' => $rule->discount_type,
      'discount_basis' => isset($rule->discount_basis) ? $rule->discount_basis : 'line',
      'applies_to' => $rule->applies_to,
      'discount_value' => (float)$rule->discount_value,
      'product_id' => $rule->product_id !== null ? (int)$rule->product_id : null,
      'category' => $rule->category,
      'min_purchase_amount' => (float)$rule->min_purchase_amount,
    ];
  }
?>
<script>
function formatTsh(amount){
    var value = Number(amount || 0);
    return 'Tsh.' + value.toLocaleString('en-US') + '/=';
}

function updateChangeDue(totalDue){
    var paidInput = document.getElementById('evamo-amount-paid-input');
    var changeText = document.getElementById('evamo-change-due-text');
    var changeHidden = document.getElementById('evamo-change-due-input');
    var paid = Number(paidInput ? paidInput.value || 0 : 0);
    if (!Number.isFinite(paid) || paid < 0) {
        paid = 0;
    }
    var payableTotal = Number(totalDue || 0);
    var changeDue = Math.max(0, paid - payableTotal);
    if (changeText) {
        changeText.textContent = formatTsh(changeDue);
    }
    if (changeHidden) {
        changeHidden.value = changeDue;
    }
}

var evamoDiscountRules = <?php echo json_encode($evamo_discount_rules); ?>;

function evamoOriginalCartTotal(){
    var total = 0;
    document.querySelectorAll('.evamo-cart-row').forEach(function(row){
        var qtyInput = row.querySelector('.evamo-qty-input');
        if (!qtyInput) {
            return;
        }
        var qty = Number(qtyInput.value || qtyInput.getAttribute('data-old-qty') || 0);
        var price = Number(qtyInput.getAttribute('data-price') || 0);
        total += qty * price;
    });
    return total;
}

function evamoRuleMatchesRow(rule, row){
    var productId = Number(row.getAttribute('data-product-id') || 0);
    var category = row.getAttribute('data-category') || '';
    if (rule.applies_to === 'product') {
        return !rule.product_id || Number(rule.product_id) === productId;
    }
    if (rule.applies_to === 'category') {
        return rule.category && rule.category === category;
    }
    return false;
}

function evamoRuleDiscountAmount(rule, lineTotal, qty, cartTotal){
    var baseTotal = rule.discount_basis === 'cart' ? cartTotal : lineTotal;
    if (rule.discount_type === 'percentage') {
        return baseTotal * (Number(rule.discount_value || 0) / 100);
    }
    if (rule.discount_basis === 'cart') {
        return Number(rule.discount_value || 0);
    }
    return Number(rule.discount_value || 0) * qty;
}
function updateAvailableDiscounts(){
  var originalTotal = evamoOriginalCartTotal();
  var cartDiscountInput = document.getElementById("evamo-cart-discount-input");
  var cartDiscountHint = document.getElementById("evamo-cart-discount-hint");
  var bestCartRule = null;
  var bestCartAmount = 0;
  var cartDiscountRow = document.querySelector(".evamo-cart-discount-row");
  var hasCartDiscountRule = evamoDiscountRules.some(function(rule){ return rule.discount_basis === "cart"; });

  if (cartDiscountRow) {
    cartDiscountRow.style.display = hasCartDiscountRule ? "" : "none";
  }
  if (!hasCartDiscountRule && cartDiscountInput && cartDiscountHint) {
    cartDiscountInput.value = 0;
    cartDiscountInput.readOnly = true;
    cartDiscountInput.removeAttribute("max");
    cartDiscountHint.textContent = "";
  }

  evamoDiscountRules.forEach(function(rule){
    if (rule.discount_basis !== "cart" || originalTotal < Number(rule.min_purchase_amount || 0)) {
      return;
    }
    var matchesCart = false;
    document.querySelectorAll(".evamo-cart-row").forEach(function(row){
      if (evamoRuleMatchesRow(rule, row)) {
        matchesCart = true;
      }
    });
    if (!matchesCart) {
      return;
    }
    var amount = evamoRuleDiscountAmount(rule, 0, 0, originalTotal);
    if (amount > bestCartAmount) {
      bestCartAmount = amount;
      bestCartRule = rule;
    }
  });

  if (cartDiscountInput && cartDiscountHint) {
    if (bestCartRule && bestCartAmount > 0) {
      cartDiscountInput.value = bestCartAmount;
      cartDiscountInput.readOnly = true;
      cartDiscountInput.setAttribute("max", bestCartAmount);
      cartDiscountHint.textContent = "Available cart total discount: " + formatTsh(bestCartAmount) + " (" + bestCartRule.discount_name + ")";
      cartDiscountHint.className = "text-success";
    } else {
      cartDiscountInput.value = 0;
      cartDiscountInput.readOnly = true;
      cartDiscountInput.removeAttribute("max");
      cartDiscountHint.textContent = evamoDiscountRules.some(function(rule){ return rule.discount_basis === "cart"; }) ? "No cart total discount available yet" : "";
      cartDiscountHint.className = "text-muted";
    }
  }

  document.querySelectorAll(".evamo-cart-row").forEach(function(row){
    var qtyInput = row.querySelector(".evamo-qty-input");
    var discountInput = row.querySelector(".evamo-discount-input");
    var hint = row.querySelector(".evamo-discount-hint");
    if (!qtyInput || !discountInput || !hint) {
      return;
    }
    var qty = Number(qtyInput.value || qtyInput.getAttribute("data-old-qty") || 0);
    var price = Number(qtyInput.getAttribute("data-price") || 0);
    var lineTotal = qty * price;
    var best = null;
    var bestAmount = 0;
    evamoDiscountRules.forEach(function(rule){
      if (rule.discount_basis === "cart") {
        return;
      }
      if (!evamoRuleMatchesRow(rule, row) || originalTotal < Number(rule.min_purchase_amount || 0)) {
        return;
      }
      var amount = evamoRuleDiscountAmount(rule, lineTotal, qty, originalTotal);
      if (amount > bestAmount) {
        bestAmount = amount;
        best = rule;
      }
    });
    if (best && bestAmount > 0) {
      discountInput.readOnly = false;
      discountInput.setAttribute("max", bestAmount);
      hint.textContent = "Available item discount: " + formatTsh(bestAmount) + " (" + best.discount_name + ")";
      hint.className = "evamo-discount-hint text-success";
    } else {
      discountInput.value = 0;
      discountInput.readOnly = true;
      discountInput.removeAttribute("max");
      hint.textContent = evamoDiscountRules.some(function(rule){ return rule.discount_basis !== "cart"; }) ? "No item discount available yet" : "";
      hint.className = "evamo-discount-hint text-muted";
    }
  });
}

function recalculateCartTotals(){
    var grandTotal = 0;

    document.querySelectorAll('.evamo-cart-row').forEach(function(row){
        var qtyInput = row.querySelector('.evamo-qty-input');
        var qtyHidden = row.querySelector('.evamo-qty-hidden');
        var discountInput = row.querySelector('.evamo-discount-input');
        var rowTotalText = row.querySelector('.evamo-row-total-text');
        var rowTotalHidden = row.querySelector('.evamo-row-total-hidden');
        var profitHidden = row.querySelector('.evamo-profit-hidden');

        if(!qtyInput){
            return;
        }

        var qty = Number(qtyInput.value || 0);
        if (!Number.isFinite(qty) || qty < 1) {
            qty = 1;
            qtyInput.value = 1;
        }

        var price = Number(qtyInput.getAttribute('data-price') || 0);
        var buyPrice = Number(qtyInput.getAttribute('data-buy-price') || 0);
        var lineSubtotal = qty * price;
        var discount = Number(discountInput ? discountInput.value || 0 : 0);
        if (!Number.isFinite(discount) || discount < 0) {
            discount = 0;
        }
        if (discount > lineSubtotal) {
            discount = lineSubtotal;
            if (discountInput) {
                discountInput.value = discount;
            }
        }
        var subtotal = lineSubtotal - discount;
        var profit = subtotal - (buyPrice * qty);
        grandTotal += subtotal;

        if (qtyHidden) {
            qtyHidden.value = qty;
        }
        if (rowTotalText) {
            rowTotalText.textContent = formatTsh(subtotal);
        }
        if (rowTotalHidden) {
            rowTotalHidden.value = subtotal;
        }
        if (profitHidden) {
            profitHidden.value = profit;
        }
    });

    var cartDiscountInput = document.getElementById("evamo-cart-discount-input");
  var cartDiscount = Number(cartDiscountInput ? cartDiscountInput.value || 0 : 0);
  if (!Number.isFinite(cartDiscount) || cartDiscount < 0) {
    cartDiscount = 0;
  }
  if (cartDiscount > grandTotal) {
    cartDiscount = grandTotal;
    if (cartDiscountInput) {
      cartDiscountInput.value = cartDiscount;
    }
  }
  grandTotal -= cartDiscount;

  var grandTotalText = document.getElementById('evamo-grand-total-text');
    var grandTotalHidden = document.getElementById('evamo-grand-total-input');
    if (grandTotalText) {
        grandTotalText.textContent = formatTsh(grandTotal);
    }
    if (grandTotalHidden) {
        grandTotalHidden.value = grandTotal;
    }
    updateChangeDue(grandTotal);
    updateAvailableDiscounts();
}

/* Update item quantity */
function updateCartItem(obj, rowid,item_id){
        var oldQty = Number(obj.getAttribute('data-old-qty') || obj.value || 1);
        recalculateCartTotals();

    $.get("<?php echo base_url('admin/updateItemQty/'); ?>",{rowid:rowid, qty:obj.value,item_id:item_id}, function(resp){
        if(String(resp).trim() == 'ok'){
                        obj.setAttribute('data-old-qty', obj.value);
                        recalculateCartTotals();
        }else{
                        obj.value = oldQty;
                        recalculateCartTotals();
            alert('The product is not enough.');
        }
    });
}

document.addEventListener('DOMContentLoaded', function(){
    var paidInput = document.getElementById('evamo-amount-paid-input');
    if (paidInput) {
        paidInput.addEventListener('input', recalculateCartTotals);
    }
    recalculateCartTotals();
});
</script>
<div id="main-content">
<div class="container-fluid">
<br>
<?php if ($das = $this->session->flashdata('massage')): ?>
<div class="row">
<div class="col-md-12">
<div class="alert alert-dismisible alert-success">
<a href="" class="close">&times;</a>
<?php echo $das;?>
</div>
</div>
</div>
<?php endif; ?>
<?php if ($err = $this->session->flashdata('error')): ?>
<div class="row">
<div class="col-md-12">
<div class="alert alert-danger">
<?php echo $err; ?>
</div>
</div>
</div>
<?php endif; ?>

<div class="row clearfix">
<div class="col-lg-6">
<div class="card">
<div class="header">
  <div class="row">
    <div class="col-md-6">
      <h2>Product List</h2>
      </div>
      <div class="col-md-6">
      </div>
      </div>
</div>
<div class="body">
    <div class="table-striped">
<table class="table table-hover js-basic-example dataTable table-custom">
            <thead class="thead-primary">
                <tr>
                    <th>RETAILSALE</th>
                    <th>WHOLESALE</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>RETAILSALE</th>
                    <th>WHOLESALE</th>
                </tr>
            </tfoot>
            <tbody>
        
          
                <?php foreach ($datay as $datas): ?>
            <tr>
            <td>
              <?php if ($datas->price == 0) {
               ?>
               -//-//-
             <?php }else{ ?>
                 <?php if($datas->balance == 0){
                 ?>
            <b> <?php echo $datas->name; ?></b>
                
               <?php }else{ ?>
                <a href="<?php echo base_url("admin/addToCart/{$datas->id}"); ?>">
               <b> <?php echo $datas->name; ?></b>
                <?php } ?> 
        <?php if ($datas->balance <= $datas->stock_limit) {
                ?>
              <span class="badge badge-danger">
              <?php echo $datas->balance; ?></span></a>
               <?php }elseif ($datas->balance >= $datas->stock_limit) {
                ?>
                 <span class="badge badge-success">

              <?php echo $datas->balance; ?></span></a>
                <?php }} ?>
              
            </td>

            <td>
              <?php if ($datas->ju_price == 0) {
             ?>
              <p>-//-//-</p>
           <?php  } else{?>
                 <?php if($datas->balance == 0) {
                 ?>
            <b> <?php echo $datas->name; ?></b>
                
               <?php }else{ ?>
                <a href="<?php echo base_url("admin/addToCart_jumla/{$datas->id}"); ?>">
               <b> <?php echo $datas->name; ?></b>
                <?php } ?> 
        <?php if ($datas->balance <= $datas->stock_limit) {
                ?>
              <span class="badge badge-danger">
              <?php echo $datas->balance; ?></span></a>
               <?php }elseif ($datas->balance >= $datas->stock_limit) {
                ?>
                 <span class="badge badge-success">

              <?php echo $datas->balance; ?></span></a>
                <?php }} ?>
              
            </td>
              </tr>
        <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</div>

</div>
<div class="col-lg-6">

    <div class="card">
<div class="header">
      <h2>Item List  <a href="<?php //echo base_url("cart/index") ?>" class="icon-menu"><i class=""></i>(<?php echo ($this->cart->total_items() > 0)?$this->cart->total_items().' Items':'Empty'; ?>)</a></b> </h2>
</div>
<div class="body">
    <div class="table-responsive evamo-cart-table-wrap">
<table class="table table-hover js-basi-example dataTable table-custom evamo-cart-table">
    <?php echo form_open("admin/sell_jumla"); ?>
            <thead class="thead-primary">
                <tr>
                    <th>Name</th>
                    <th>Price(Tsh)</th>
                    <th>Quantity</th>
                    <th>Total(Tsh)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
               <?php if($this->cart->total_items() > 0): ?>          
            <?php foreach ($cartItems as $item):
                $company_id = ""; 
                ?>
                        <tr class="evamo-cart-row" data-product-id="<?php echo (int)$item['id']; ?>" data-category="<?php echo html_escape($product_categories[$item['id']] ?? ($item['category'] ?? '')); ?>">
                        <td data-label="Name"><b><?php echo $item['name']; ?></b></td>
                        <td data-label="Price(Tsh)">Tsh.<?php echo number_format($item['ju_price']); ?>/=
              <input type="hidden" name="new_sell_price[]" value="<?php echo $item['ju_price']; ?>">
              <input type="hidden" name="user_id[]" value="<?php echo $_SESSION['user_id']; ?>">
            </td>
                        <td data-label="Quantity">
              <?php 
              $cart_id = $item["rowid"];
              $item_id = $item["id"];
                 ?>
            <input type="number" value="<?php echo $item["qty"]; ?>" data-old-qty="<?php echo $item["qty"]; ?>" data-price="<?php echo $item['ju_price']; ?>" data-buy-price="<?php echo $item['buy_price']; ?>" onchange="updateCartItem(this, '<?php echo $cart_id; ?>','<?php echo $item_id; ?>')" min="1" class="form-control evamo-qty-input" style="width: 80px">
             <input type="hidden" name="quantity[]" value="<?php echo $item["qty"]; ?>" class="evamo-qty-hidden">
            </td> 
                                                <td data-label="Total(Tsh)"><span class="evamo-row-total-text"><?php echo 'Tsh.'.number_format($item["sub"]).'/='; ?></span>
                            <input type="hidden" name="discount_amount[]" value="0">
                            <input type="hidden" name="total_sell_price[]" value="<?php echo $item["sub"]; ?>" class="evamo-row-total-hidden">
            <input type="hidden" name="profit[]" value="<?php echo $item['sub'] - $item['buy_price'] * $item['qty']; ?>" class="evamo-profit-hidden">
            <input type="hidden" name="product_id[]" value="<?php echo $item['id']; ?>">
            <input type="hidden" name="sell_status[]" value="whole">
            </td> 
           <!--  <td><?php //echo $item['subtotal'] - $item['buy_price'] * $item['qty']; ?></td> -->
                        <td data-label="Action"><a href="<?php echo base_url('admin_cart_jumla/remove/'.$item["rowid"]); ?>" class="btn btn-danger btn-sm"><i class=" icon-close"></i></a><?php //echo $products->p_name; ?></td>
              </tr>
    <?php endforeach; ?>
              <tr class="evamo-cart-discount-row">
                <th>Cart Discount</th>
                <th></th>
                <th></th>
                <th><input type="number" id="evamo-cart-discount-input" name="cart_discount_amount" value="0" min="0" class="form-control" readonly oninput="recalculateCartTotals()" style="width: 120px"><small id="evamo-cart-discount-hint" class="text-muted" style="display:block;margin-top:4px;"></small></th>
                <th></th>
              </tr>
                            <tr class="evamo-cart-summary-row">
                <th>Total</th>
                <th></th>
                <th></th>
                <th><?php if($this->cart->total_items() > 0){ ?>
                    <span id="evamo-grand-total-text"><?php echo 'Tsh.'.number_format($this->cart->tota()).'/='; ?></span>
                    <input type="hidden" id="evamo-grand-total-input" name="total_price" value="<?php echo $this->cart->tota(); ?>">
                    <?php } ?>
                  </th>
                <th></th>
              </tr>
              <tr class="evamo-cart-amount-paid-row">
                <th>Amount Paid</th>
                <th></th>
                <th></th>
                <th><input type="number" id="evamo-amount-paid-input" name="amount_paid" value="" min="0" class="form-control" oninput="recalculateCartTotals()" style="width: 120px"></th>
                <th></th>
              </tr>
              <tr class="evamo-cart-change-row">
                <th>Change Due</th>
                <th></th>
                <th></th>
                <th><span id="evamo-change-due-text">Tsh.0/=</span><input type="hidden" id="evamo-change-due-input" name="change_due" value="0"></th>
                <th></th>
              </tr>
                                <tr class="evamo-cart-customer-row">
                                <td colspan="5">
                        <input type="text" name="customer" autocomplete="off" placeholder="customer name" class="form-control evamo-cart-customer-input" required>
                                </td>
              </tr>
                            <tr class="evamo-cart-actions-row">
              <th></th>
              <th></th>
              <th><input type="submit" value="Sell" class="btn btn-info btn-sm"></th>
              <th><!-- <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#addcontact"><i class="icon-printer">Recept</i></button> --></th>
              <th></th>
                            </tr>
                  <?php endif; ?>
            <?php echo form_close(); ?>
            </tbody>
        </table>
    </div>
</div>
</div>

</div>
</div>
<script src="<?php echo base_url() ?>assets/admin/js/jquery.js"></script>
<?php include 'incs/footer.php'; ?>

   <?php
                 $text = '';
                if(isset($_GET['customer'])){
                  $text =  htmlentities($_GET['customer']);  
                  echo 
                  redirect('seller/print_receptJumla/'. htmlentities($text));     
                }
                 ?>
<!-- Default Size -->
<div class="modal fade" id="addcontact" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="title" id="defaultModalLabel">Customer Name</h6>
            </div>
            <form method='GET' target="_blank">
            <div class="modal-body">
                <div class="row clearfix">
                    <div class="col-12">
                        <div class="form-group">                                    
                    <input type="text" name="customer" required autocomplete="off" class="form-control" placeholder="Enter customer name">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-primary" value="print">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">CLOSE</button>
            </div>
            </form>
        </div>
    </div>
</div>
