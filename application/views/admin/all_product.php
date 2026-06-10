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
      <h2>Product List</b> </h2>
</div>
<div class="body">
    <?php
      $selected_branch_id = isset($selected_branch_id) ? $selected_branch_id : null;
      $pdf_query = '?download=1' . ($selected_branch_id ? '&branch_id=' . (int) $selected_branch_id : '');
    ?>
    <form method="get" action="<?php echo base_url('admin/all_product'); ?>" class="evamo-live-filter">
        <div class="evamo-filter-field">
            <label>Branch</label>
            <select name="branch_id" class="form-control" data-live-submit="1">
                <option value="">All Branches</option>
                <?php if (!empty($branches)): ?>
                    <?php foreach ($branches as $branch): ?>
                        <option value="<?php echo $branch->branch_id; ?>" <?php echo ((int) $selected_branch_id === (int) $branch->branch_id) ? 'selected' : ''; ?>>
                            <?php echo html_escape($branch->branch_name); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="evamo-filter-actions">
            <button type="submit" class="btn btn-primary"><i class="icon-magnifier"></i> Filter</button>
            <a href="<?php echo base_url('admin/print_data' . $pdf_query); ?>" target="_blank" class="btn btn-info">
                <i class="icon-printer"></i> Download PDF
            </a>
        </div>
    </form>
    <div class="table-responsive">
<table class="table table-hover js-basic-example dataTable table-custom">
            <thead class="thead-primary">
                <tr>
                    <th>Product name</th>
                    <th>Branch</th>
                    <th>Brand</th>
                    <th>Buy price</th>
                    <th>Retail Sale Price</th>
                    <th>WholeSale Price</th>
                    <th>Expire Staus</th>
                    <th>Stock Limit</th>
                    <th>Expire Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Product name</th>
                    <th>Branch</th>
                    <th>Brand</th>
                    <th>Buy price</th>
                    <th>Retail Sale Price</th>
                    <th>WholeSale Price</th>
                    <th>Expire Staus</th>
                    <th>Stock Limit</th>
                    <th>Expire Date</th>
                    <th>Action</th>
                </tr>
            </tfoot>
            <tbody>
              <?php foreach ($product as $products): ?>
            <tr>
           
            <td><?php echo $products->name; ?></td>
            <td><?php echo $products->branch_name ? html_escape($products->branch_name) : '-'; ?></td>
            <td><?php echo $products->bland; ?></td>
            <td>Tsh.<?php echo number_format($products->buy_price); ?>/=</td>
            <td>Tsh.<?php echo number_format($products->price); ?>/=</td>
            <td>Tsh.<?php echo number_format($products->ju_price); ?>/=</td>
            <td>
                <?php $date = date("Y-m-d"); ?>
                 <?php if($products->exp_date == FALSE){ ?>
                        -//-
                <?php }elseif($products->exp_date <= $date) {
                 ?>
            <a href="javascript:;" class="badge badge-danger">Expired</a>
             <?php }else{ ?>
            <a href="javascript:;" class="badge badge-success">Active</a>
                <?php } ?>
            </td>
            <td><?php echo $products->stock_limit; ?> /<?php echo $products->unit; ?></td>
            <td><?php echo $products->exp_date; ?></td>
            <td>
                 <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                             
                      <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item" href="<?php echo base_url("admin/edit_product/{$products->id}"); ?>">Edit</a>
                       <a class="dropdown-item" href="<?php echo base_url("admin/delete_product/{$products->id}"); ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                    </div>
                                </div>
                            </div>
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
</div>

</div>


<?php include 'incs/footer.php'; ?>
