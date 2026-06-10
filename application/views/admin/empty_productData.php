<?php include('incs/header.php'); ?>
<?php include('incs/nav.php'); ?>
<?php include('incs/side.php'); ?>


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

<div class="row clearfix">
<div class="col-lg-12">
<div class="card">
<div class="header">
    <div class="row">
        <div class="col-lg-6">
      <h2><b>Empty Product Report</b> </h2>
      </div>
      <div class="col-lg-6">
          <div class="pull-right">
              <?php
                $selected_branch_id = isset($selected_branch_id) ? $selected_branch_id : null;
                $print_query = $selected_branch_id ? '?branch_id=' . (int)$selected_branch_id : '';
              ?>
              <a href="<?php echo base_url("admin/empty_product".$print_query); ?>" class="btn btn-info btn-sm" target="_blank"><i class="icon-printer"></i>Print</a>
          </div>
      </div>
      </div>
</div>
<div class="body">
    <form method="get" action="<?php echo base_url('admin/empty_productData'); ?>" class="evamo-live-filter">
        <div class="evamo-filter-field">
            <label>Branch</label>
            <select name="branch_id" class="form-control" data-live-submit="1">
                <option value="">All Branches</option>
                <?php if (!empty($branches)): ?>
                    <?php foreach ($branches as $branch): ?>
                        <option value="<?php echo $branch->branch_id; ?>" <?php echo ((string)$selected_branch_id === (string)$branch->branch_id) ? 'selected' : ''; ?>>
                            <?php echo html_escape($branch->branch_name); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="evamo-filter-actions">
            <button type="submit" class="btn btn-primary"><i class="icon-magnifier"></i> Filter</button>
            <a href="<?php echo base_url('admin/empty_productData'); ?>" class="btn btn-secondary">Reset</a>
        </div>
    </form>
    <div class="table-responsive">
<table class="table table-hover js-basic-example dataTable table-custom">
            <thead class="thead-primary">
                <tr>
                    <th>Product name</th>
                    <th>Branch</th>
                    <th>Balance</th>
                    <th>Buying Price</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Product name</th>
                    <th>Branch</th>
                    <th>Balance</th>
                    <th>Buying Price</th>
                </tr>
            </tfoot>
            <tbody>
              <?php foreach ($empty_prdData as $products): ?>
            <tr>
           
            <td><?php echo $products->name; ?></td>
            <td><?php echo !empty($products->branch_name) ? html_escape($products->branch_name) : '-'; ?></td>
            <td><?php echo $products->balance; ?></td>
            <td><?php echo number_format($products->buy_price); ?>/=</td>
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
</div>

</div>


<?php include 'incs/footer.php'; ?>
