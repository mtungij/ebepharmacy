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
    <div  class="row">
        <div class="col-md-3">
<h2>Salles today</b> </h2>
</div>
<div class="col-md-3">
  
      Total sell today <input type="" readonly placeholder="Tsh.<?php echo number_format($total_sell->TotalItemsOrdered); ?>/=" name="" class="form-control">
  
</div>
<div class="col-md-3">
   
      Today Profit <input type="" name="" readonly placeholder="Tsh.<?php echo number_format($total_profit->Totalprofit); ?>/=" class="form-control">
</div>
<div class="col-md-3">
    <div class="pull-right">
     <?php
      $selected_branch_id = isset($selected_branch_id) ? $selected_branch_id : null;
      $print_query = $selected_branch_id ? '?branch_id=' . (int)$selected_branch_id : '';
     ?>
     <a href="<?php echo base_url("admin/today_salles_report".$print_query); ?>" class="btn btn-info" target="_blank"><i class="icon-printer"></i>Print</a>
     </div>
</div>
</div>
</div>
<div class="body">
    <form method="get" action="<?php echo base_url('admin/today_salesReport'); ?>" class="evamo-live-filter">
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
            <a href="<?php echo base_url('admin/today_salesReport?branch_id='); ?>" class="btn btn-secondary">All Branches</a>
        </div>
    </form>
    <div class="table-responsive">
<table class="table table-hover js-basic-example dataTable table-custom">
            <thead class="thead-primary">
                <tr>

                    
                    <th>Seller</th>
                    <th>Branch</th>
                    <th>Product name</th>
                    <th>Quantity</th>
                    <th>Sell price</th>
                    <th>Total price</th>
                    <th>Profit</th>
                    <th>Sale Status</th>
                    <th>Time</th>
                  
                </tr>
            </thead>
            <tbody>
              <?php foreach ($all_salles as $all_salles_today): ?>
                <tr>
            <td><?php echo $all_salles_today->full_name; ?></td>
            <td><?php echo !empty($all_salles_today->branch_name) ? html_escape($all_salles_today->branch_name) : '-'; ?></td>
            <td><?php echo $all_salles_today->name; ?></td>
            <td><?php echo $all_salles_today->quanty ; ?> <?php echo $all_salles_today->unit ; ?></td>
            <td><?php echo  number_format($all_salles_today->new_sell_price) ; ?>/=</td>
            <td><?php echo  number_format($all_salles_today->total_sell_price); ?>/=</td>
            <td><?php echo  number_format($all_salles_today->profit); ?>/=</td>
            <td>
                  <?php 
               $da = $all_salles_today->ju_price - $all_salles_today->new_sell_price;
                 if ($da == 0) {
                   echo "<span class='badge badge-success'>whore sale</span>";
                 }else{
                  echo "<span class='badge badge-info'>Retail sale</span>";
                 }
               ?>
            </td>
            <td><?php  echo date('F, j, Y, g:j a', strtotime($all_salles_today->created_at)); ?></td>


              
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
