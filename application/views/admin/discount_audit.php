<?php include('incs/header.php'); ?>
<?php include('incs/nav.php'); ?>
<?php include('incs/side.php'); ?>

<div id="main-content">
<div class="container-fluid">
<br>
<div class="row clearfix">
  <div class="col-lg-12">
    <div class="card">
      <div class="header">
        <div class="row">
          <div class="col-md-6"><h2>Discount Audit Trail</h2></div>
          <div class="col-md-6"><div class="pull-right"><a href="<?php echo base_url('admin/discounts'); ?>" class="btn btn-primary btn-sm">Discount Rules</a></div></div>
        </div>
      </div>
      <div class="body">
        <div class="table-responsive">
          <table class="table table-hover js-basic-example dataTable table-custom">
            <thead class="thead-primary">
              <tr>
                <th>Time</th>
                <th>Discount</th>
                <th>Product</th>
                <th>Applied By</th>
                <th>Approved By</th>
                <th>Original Price</th>
                <th>Discount</th>
                <th>Final Price</th>
                <th>Qty</th>
                <th>Transaction</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($audit as $row): ?>
                <tr>
                  <td><?php echo html_escape($row->created_at); ?></td>
                  <td><?php echo html_escape($row->discount_name ?: 'Deleted rule'); ?></td>
                  <td><?php echo html_escape($row->product_name ?: '-'); ?></td>
                  <td><?php echo html_escape($row->applied_by_name ?: '-'); ?></td>
                  <td><?php echo html_escape($row->approved_by_name ?: '-'); ?></td>
                  <td>Tsh.<?php echo number_format((float)$row->original_price); ?>/=</td>
                  <td>Tsh.<?php echo number_format((float)$row->discount_amount); ?>/=</td>
                  <td>Tsh.<?php echo number_format((float)$row->final_price); ?>/=</td>
                  <td><?php echo number_format((float)$row->quantity); ?></td>
                  <td><?php echo (int)$row->transaction_id; ?></td>
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
