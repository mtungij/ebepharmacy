<?php include('incs/header.php'); ?>
<?php include('incs/nav.php'); ?>
<?php include('incs/side.php'); ?>

<div id="main-content">
<div class="container-fluid">
<br>
<?php if ($msg = $this->session->flashdata('massage')): ?>
  <div class="alert alert-success"><?php echo $msg; ?></div>
<?php endif; ?>
<?php if ($err = $this->session->flashdata('error')): ?>
  <div class="alert alert-danger"><?php echo $err; ?></div>
<?php endif; ?>

<div class="row clearfix">
  <div class="col-lg-12">
    <div class="card">
      <div class="header">
        <div class="row">
          <div class="col-md-6"><h2>Discount Rules</h2></div>
          <div class="col-md-6">
            <div class="pull-right">
              <a href="<?php echo base_url('admin/create_discount'); ?>" class="btn btn-primary btn-sm">New Discount</a>
              <a href="<?php echo base_url('admin/discount_audit'); ?>" class="btn btn-info btn-sm">Audit Trail</a>
            </div>
          </div>
        </div>
      </div>
      <div class="body">
        <div class="table-responsive">
          <table class="table table-hover js-basic-example dataTable table-custom">
            <thead class="thead-primary">
              <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Calculated From</th>
                <th>Applies To</th>
                <th>Value</th>
                <th>Target</th>
                <th>Branch</th>
                <th>Period</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($discounts as $discount): ?>
                <?php
                  $target = 'All matching items';
                  if ($discount->applies_to === 'product') {
                    $target = $discount->product_id ? ($discount->product_name ?: 'Product #'.$discount->product_id) : 'All products';
                  } elseif ($discount->applies_to === 'category') {
                    $target = $discount->category;
                  } elseif ($discount->applies_to === 'brand') {
                    $target = $discount->brand;
                  } elseif ($discount->applies_to === 'customer_group') {
                    $target = $discount->customer_group;
                  }
                ?>
                <tr>
                  <td><?php echo html_escape($discount->discount_name); ?></td>
                  <td><?php echo ucfirst($discount->discount_type); ?></td>
                  <td><?php echo isset($discount->discount_basis) && $discount->discount_basis === 'cart' ? 'Cart total price' : 'Item price'; ?></td>
                  <td><?php echo ucfirst(str_replace('_', ' ', $discount->applies_to)); ?></td>
                  <td><?php echo $discount->discount_type === 'percentage' ? number_format((float)$discount->discount_value).'%': 'Tsh.'.number_format((float)$discount->discount_value).'/='; ?></td>
                  <td><?php echo html_escape($target); ?></td>
                  <td><?php echo $discount->branch_name ? html_escape($discount->branch_name) : 'All Branches'; ?></td>
                  <td><?php echo html_escape($discount->start_date); ?> to <?php echo html_escape($discount->end_date); ?></td>
                  <td><span class="badge <?php echo $discount->status === 'active' ? 'badge-success' : 'badge-secondary'; ?>"><?php echo ucfirst($discount->status); ?></span></td>
                  <td>
                    <a href="<?php echo base_url('admin/edit_discount/'.$discount->discount_id); ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="<?php echo base_url('admin/delete_discount/'.$discount->discount_id); ?>" onclick="return confirm('Delete this discount?')" class="btn btn-danger btn-sm">Delete</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

</div>
</div>

<?php include 'incs/footer.php'; ?>
