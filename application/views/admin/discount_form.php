<?php include('incs/header.php'); ?>
<?php include('incs/nav.php'); ?>
<?php include('incs/side.php'); ?>

<?php
  $is_edit = !empty($discount);
  $action = $is_edit ? 'admin/update_discount/'.$discount->discount_id : 'admin/create_discount';
  $value = function($field, $default = '') use ($discount) {
    return set_value($field, $discount && isset($discount->$field) ? $discount->$field : $default);
  };
?>

<div id="main-content">
<div class="container-fluid">
<br>
<div class="row clearfix">
  <div class="col-lg-12">
    <div class="card">
      <div class="header">
        <h2><?php echo $is_edit ? 'Edit Discount' : 'Create Discount'; ?></h2>
      </div>
      <div class="body">
        <?php echo form_open($action); ?>
        <div class="row clearfix">
          <div class="col-sm-4">
            <div class="form-group">
              <span>Discount name</span>
              <input type="text" name="discount_name" class="form-control" required value="<?php echo html_escape($value('discount_name')); ?>">
              <?php echo form_error('discount_name'); ?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <span>Discount type</span>
              <select name="discount_type" class="form-control" required>
                <option value="percentage" <?php echo $value('discount_type') === 'percentage' ? 'selected' : ''; ?>>Percentage</option>
                <option value="fixed" <?php echo $value('discount_type') === 'fixed' ? 'selected' : ''; ?>>Fixed amount</option>
              </select>
              <?php echo form_error('discount_type'); ?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <span>Discount value</span>
              <input type="number" step="0.01" name="discount_value" class="form-control" required value="<?php echo html_escape($value('discount_value', 0)); ?>">
              <?php echo form_error('discount_value'); ?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <span>Calculate discount from</span>
              <select name="discount_basis" class="form-control" required>
                <option value="line" <?php echo $value('discount_basis', 'line') === 'line' ? 'selected' : ''; ?>>Item price</option>
                <option value="cart" <?php echo $value('discount_basis') === 'cart' ? 'selected' : ''; ?>>Cart total price</option>
              </select>
              <?php echo form_error('discount_basis'); ?>
            </div>
          </div>

          <div class="col-sm-4">
            <div class="form-group">
              <span>Applies to</span>
              <select name="applies_to" id="discount-applies-to" class="form-control" required>
                <?php foreach (['product' => 'Product', 'category' => 'Category'] as $key => $label): ?>
                  <option value="<?php echo $key; ?>" <?php echo $value('applies_to') === $key ? 'selected' : ''; ?>><?php echo $label; ?></option>
                <?php endforeach; ?>
              </select>
              <?php echo form_error('applies_to'); ?>
            </div>
          </div>
          <div class="col-sm-4 discount-target" data-target="product">
            <div class="form-group">
              <span>Product</span>
              <label style="display:block;margin-bottom:6px;">
                <input type="checkbox" id="discount-all-products" name="all_products" value="1" <?php echo $value('applies_to') === 'product' && !$value('product_id') ? 'checked' : ''; ?>> All products
              </label>
              <div id="discount-product-list" style="max-height:220px;overflow:auto;border:1px solid #ced4da;border-radius:4px;padding:8px;">
                <?php foreach ($products as $product): ?>
                  <label style="display:block;font-weight:normal;margin-bottom:6px;">
                    <input type="checkbox" name="product_ids[]" value="<?php echo $product->id; ?>" <?php echo (string)$value('product_id') === (string)$product->id ? 'checked' : ''; ?>>
                    <?php echo html_escape($product->name); ?>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
          <div class="col-sm-4 discount-target" data-target="category">
            <div class="form-group">
              <span>Category</span>
              <select name="category" class="form-control">
                <option value="">Select category</option>
                <?php foreach ($categories as $category): ?>
                  <option value="<?php echo html_escape($category->category); ?>" <?php echo $value('category') === $category->category ? 'selected' : ''; ?>><?php echo html_escape($category->category); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <span>Branch</span>
              <select name="branch_id" class="form-control">
                <option value="">All Branches</option>
                <?php foreach ($branches as $branch): ?>
                  <option value="<?php echo $branch->branch_id; ?>" <?php echo (string)$value('branch_id') === (string)$branch->branch_id ? 'selected' : ''; ?>><?php echo html_escape($branch->branch_name); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <span>Minimum purchase amount</span>
              <input type="number" step="0.01" name="min_purchase_amount" class="form-control" value="<?php echo html_escape($value('min_purchase_amount', 0)); ?>">
              <small class="text-muted">Checked against the original retail or wholesale cart total before discount.</small>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <span>Start date</span>
              <input type="date" name="start_date" class="form-control" required value="<?php echo html_escape($value('start_date', date('Y-m-d'))); ?>">
              <?php echo form_error('start_date'); ?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <span>End date</span>
              <input type="date" name="end_date" class="form-control" required value="<?php echo html_escape($value('end_date', date('Y-m-d'))); ?>">
              <?php echo form_error('end_date'); ?>
            </div>
          </div>

          <div class="col-sm-3">
            <div class="form-group">
              <span>Status</span>
              <select name="status" class="form-control">
                <option value="active" <?php echo $value('status', 'active') === 'active' ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo $value('status') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
              </select>
            </div>
          </div>
        </div>
        <div class="text-center">
          <button type="submit" class="btn btn-primary btn-sm">Save</button>
          <a href="<?php echo base_url('admin/discounts'); ?>" class="btn btn-secondary btn-sm">Cancel</a>
        </div>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  var select = document.getElementById('discount-applies-to');
  var targets = document.querySelectorAll('.discount-target');
  var allProducts = document.getElementById('discount-all-products');
  var productList = document.getElementById('discount-product-list');
  function syncTargets(){
    var value = select ? select.value : '';
    targets.forEach(function(target){
      target.style.display = target.getAttribute('data-target') === value ? '' : 'none';
    });
    syncProducts();
  }
  function syncProducts(){
    if (!allProducts || !productList) {
      return;
    }
    var disabled = allProducts.checked;
    productList.querySelectorAll('input[type="checkbox"]').forEach(function(input){
      input.disabled = disabled;
      if (disabled) {
        input.checked = false;
      }
    });
    productList.style.opacity = disabled ? '0.55' : '1';
  }
  if (select) {
    select.addEventListener('change', syncTargets);
    syncTargets();
  }
  if (allProducts) {
    allProducts.addEventListener('change', syncProducts);
    syncProducts();
  }
});
</script>

<?php include 'incs/footer.php'; ?>
