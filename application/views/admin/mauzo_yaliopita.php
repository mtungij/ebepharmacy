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
   <form method="get" action="<?php echo base_url('admin/curent_sells'); ?>">
<div class="row clearfix">
<div class="col-lg-12">
<div class="card">
<div class="header">
        <div class="">
            <div class="row">
                <div class="col-md-6">
        <h5>Previous Sales</h5>
      
        </div>
        <div class="col-md-6">
            <div class="pull-right">
                <?php if(count($data) == 0){ ?>
            
        <?php }else{ ?>
            <?php $print_query = !empty($selected_branch_id) ? '?branch_id='.(int)$selected_branch_id : ''; ?>
            <a href="<?php echo base_url("admin/last_salesReport/{$from}/{$to}").$print_query; ?>" target="_blank" class="btn btn-info"><i class="icon-printer"></i>Print</a>
            <?php } ?>
            </div>
        </div>
        </div>
        </div>
        <div  class="row">
          <?php
            $date = date("Y-m-d");
            $from_value = !empty($from) ? $from : $date;
            $to_value = !empty($to) ? $to : $date;
            $selected_branch_id = isset($selected_branch_id) ? $selected_branch_id : null;
          ?>
    <div class="col-md-3">
       
          From : 
           <?php if (count($data) == 0) {
         ?>
         mm/dd/yy
     <?php }else{ ?>
          <?php echo date('F, j, Y', strtotime($from)) ?>
          <?php } ?>
           <input type="date" required value="<?php echo html_escape($from_value); ?>"   name="from" class="form-control">
    </div>
    <div class="col-md-3">
          To:
               <?php if (count($data) == 0) {
         ?>
         mm/dd/yy
     <?php }else{ ?>
          <?php echo date('F, j, Y', strtotime($to)) ?>
          <?php } ?>
          <input type="date" name="to" required  value="<?php echo html_escape($to_value); ?>" class="form-control">
    </div>

    <div class="col-md-2">
          Branch:
          <select name="branch_id" class="form-control">
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

    <div class="col-md-2">
        <br>
    <button type="submit" class="btn btn-info ">Get Data</button>
    </div>

    <div class="col-md-1">
    Total Sale
     <input readonly  placeholder="<?php echo number_format((float) $total_mauzo_pita->total_sell); ?>/=" class="form-control">
        
    </div>
    <div class="col-md-1">
    Profit

     <input readonly  placeholder="<?php echo number_format((float) $total_profit->total_profit); ?>/=" class="form-control">
    </div>
    </div>
    </div>
    </form>
<div class="body">
    <div class="table-responsive">
<table class="table table-hover js-basic-example dataTable table-custom">
            <thead class="thead-primary">
                <tr>

                    
                    <th>Seller</th>
                    <th>Branch</th>
                    <th>Customer</th>
                     <th>Product name</th>
                    <th>Quantity</th>
                    <th>Sale price</th>
                    <th>Total price</th>
                    <th>Seles Staus</th>
                    <th>Time</th>
                  
                </tr>
            </thead>
            <tbody>
                <?php $data; ?>
              <?php foreach ($data as $all_salles_today): ?>
                <tr>
            <td><?php echo $all_salles_today->full_name; ?></td>
            <td><?php echo !empty($all_salles_today->branch_name) ? html_escape($all_salles_today->branch_name) : '-'; ?></td>
            <td><?php echo $all_salles_today->customer; ?></td>
            <td><?php echo $all_salles_today->name; ?></td>
            <td><?php echo $all_salles_today->qnty ; ?> <?php echo $all_salles_today->unit ; ?></td>
            <td><?php echo  number_format($all_salles_today->new_sell_price); ?>/=</td>
            <td><?php echo  number_format($all_salles_today->total_sell_price); ?>/=</td>
            <td>
               <?php 
               $da = $all_salles_today->ju_price - $all_salles_today->new_sell_price;
                 if ($da == 0) {
                   echo "<span class='badge badge-success'>whole sale</span>";
                 }else{
                  echo "<span class='badge badge-info'>Retail sale</span>";
                 }
               ?>
            </td>
            <td>
                <?php echo date('F, j, Y, g:i a', strtotime($all_salles_today->creat)); ?>
                   
                </td>

              
                </tr>
            <?php endforeach; ?>
            </tbody>

            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><b>SELLER SUMMARY</b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
       <?php foreach ($seller_data as $seller_datas): ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td><b><?php echo $seller_datas->full_name; ?></b></td>
                <td></td>
                <td></td>
                <td><b><?php echo $seller_datas->total_mauzo; ?></b></td>
                <td></td>
                <td></td>
            </tr>
            <?php endforeach; ?>
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
