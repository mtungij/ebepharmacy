
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

<div class="col-lg-12 col-md-12">
<div class="card">
<div class="header">
<h2>Admin panel <b><?php //echo $category_production->category_name; ?></b></h2>

</div>

<section id="minimal-statistics">
    <div class="row">
      <!-- <div class="col-12 mt-3 mb-1">
        <h4 class="text-uppercase">Minimal Statistics Cards</h4>
        <p>Statistics on minimal cards.</p>
      </div> -->
    </div>
    <div class="row">
      <!-- <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="media-body text-left">
                  <h3 class="primary"><?php echo number_format($mishahara_data->mishahara); ?></h3>
                  <span>Total Payroll</span>
                </div>
                <div class="align-self-center">
                  <i class="icon-book-open primary font-large-2 float-right"></i>
                </div>
              </div>
              <div class="progress mt-1 mb-0" style="height: 7px;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
      </div> -->
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="media-body text-left">
                  <h3 class="warning"><?php echo number_format($total_matumiz->matumiz + $mishahara_data->mishahara + $today_indirect_exp->total_paytoday); ?></h3>
                  <span>Total Cashout</span>
                </div>
                <div class="align-self-center">
                  <i class="icon-bubbles warning font-large-2 float-right"></i>
                </div>
              </div>
              <div class="progress mt-1 mb-0" style="height: 7px;">
                <div class="progress-bar bg-warning" role="progressbar" style="width: 35%" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="media-body text-left">
                  <h3 class="success"><?php echo number_format($total_profit_all->total_profit -($all_matumiz_all->matumiz + $mishahara_data_all->mishahara) + ($all_sell_all->TotalItemsOrdered - $total_profit_all->total_profit - $inderect_expenses_all->total_paid_expenses)); ; ?></h3>
                  <span>Gross Total</span>
                </div>
                <div class="align-self-center">
                  <i class="icon-cup success font-large-2 float-right"></i>
                </div>
              </div>
              <div class="progress mt-1 mb-0" style="height: 7px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="media-body text-left">
                <?php 
            $product = $this->db->query("SELECT * FROM product");
                          ?>
                  <h3 class="danger"><?php echo $product->num_rows(); ?></h3>
                  <span>Total Products</span>
                </div>
                <div class="align-self-center">
                  <i class="icon-direction danger font-large-2 float-right"></i>
                </div>
              </div>
              <div class="progress mt-1 mb-0" style="height: 7px;">
                <div class="progress-bar bg-danger" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="media-body text-left">
                  <h3 class="primary"><?php echo number_format($total_sell->TotalItemsOrdered ?? 0); ?></h3>
                  <span>Today Sales</span>
                </div>
                <div class="align-self-center">
                  <i class="icon-book-open primary font-large-2 float-right"></i>
                </div>
              </div>
              <div class="progress mt-1 mb-0" style="height: 7px;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="media-body text-left">
                  <h3 class="warning"><?php echo number_format($total_profit->Totalprofit ?? 0); ?></h3>
                  <span>Today Profit</span>
                </div>
                <div class="align-self-center">
                  <i class="icon-bubbles warning font-large-2 float-right"></i>
                </div>
              </div>
              <div class="progress mt-1 mb-0" style="height: 7px;">
                <div class="progress-bar bg-warning" role="progressbar" style="width: 35%" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <div class="col-xl-3 col-sm-6 col-12">
        <!-- <div class="card">
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="media-body text-left">
                  <h3 class="success"><?php echo number_format($total_profit_all->total_profit -($all_matumiz_all->matumiz + $mishahara_data_all->mishahara) + ($all_sell_all->TotalItemsOrdered - $total_profit_all->total_profit - $inderect_expenses_all->total_paid_expenses)); ; ?></h3>
                  <span>Gross Total</span>
                </div>
                <div class="align-self-center">
                  <i class="icon-cup success font-large-2 float-right"></i>
                </div>
              </div>
              <div class="progress mt-1 mb-0" style="height: 7px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div> -->
      </div>
      <div class="col-xl-3 col-sm-6 col-12">
        <!-- <div class="card">
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="media-body text-left">
                <?php 
            $product = $this->db->query("SELECT * FROM product");
                          ?>
                  <h3 class="danger"><?php echo $product->num_rows(); ?></h3>
                  <span>Total Products</span>
                </div>
                <div class="align-self-center">
                  <i class="icon-direction danger font-large-2 float-right"></i>
                </div>
              </div>
              <div class="progress mt-1 mb-0" style="height: 7px;">
                <div class="progress-bar bg-danger" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div> -->
      </div>
    </div>
  </section>


</div>
</div>
</div>




<div class="row clearfix">
<div class="col-lg-12">
<div class="card">
<div class="header">

<div  class="row">
<div class="col-md-2">
<h2>Monthly statistics</b> </h2>
</div>

<div class="col-md-6">
     <?php echo form_open("admin/index"); ?>
    <div class="row">   
    <div class="col-md-6"> 
        Select Year <select type="text" name="year" class="form-control" required>
          <option value="">Select Year</option>
           <?php foreach ($years  as $data_year): ?>
          <option value="<?php echo $data_year->year; ?>"><?php echo $data_year->year; ?></option>
      <?php endforeach; ?>
      </select>
      </div>  
      <div class="col-md-6">
        <br>
         <button type="submit" class="btn btn-primary">Filter</button> 
      </div>
      <?php echo form_close(); ?>
</div> 
</div>
<div class="col-md-2"> 
</div>

</div>
</div>


<div class="body">
    <div class="table-responsive">
<table class="table table-hover j-basic-example dataTable table-custom">
            <thead class="thead-primary">
                <tr>

                    
                    <th>Month</th>
                    <th>Total Sell</th>
                    <th>Retail Sell</th>
                    <th>WholelSell</th>
                    <th>Total Profit</th>
                    <th>Retail Profit</th>
                    <th>Whole Profit</th>
                    <th>Direct Expenses</th>
                    <th>Indirect Expenses</th>
                    <!-- <th>Paylor</th> -->
                    <th>Gross Total</th>
                  
                </tr>
            </thead>
            <tbody>
              <?php foreach ($datamonth as $datamonths): ?>
                <tr>
            <td><?php echo  date('F', strtotime($datamonths->sell_day)); ?></td>
            <td> <?php echo number_format($datamonths->total_sellPRICE); ?></td>
            <td><?php echo number_format($datamonths->total_retail_sale); ?></td>
            <td><?php echo number_format($datamonths->total_wholesale) ?></td>
            <td><?php echo number_format($datamonths->total_profit); ?></td>
            <td><?php echo number_format($datamonths->total_retail_profit) ?></td>
            <td>
             <?php echo number_format($datamonths->total_whole_profit); ?>
            </td>
            <td><?php echo number_format($datamonths->total_expenses + $datamonths->total_payrole); ?></td>
            <td><?php echo number_format($datamonths->total_indirect_expenses); ?></td>
          
          <!-- <td><?php //echo number_format($datamonths->total_payrole); ?></td> -->
          <td><?php echo number_format(($datamonths->total_profit) - ($datamonths->total_expenses + $datamonths->total_payrole) + ($datamonths->total_retail_sale - $datamonths->total_retail_profit +($datamonths->total_wholesale - $datamonths->total_whole_profit)) - $datamonths->total_indirect_expenses); ?></td>
              
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>
</div>






<div class="row clearfix">
<div class="col-lg-12">
<div class="card">
<div class="header">
    <div  class="row">
        <div class="col-md-2">
<h2>Sales today</b> </h2>
</div>
<div class="col-md-4">
  
      Total sales today <input type="" readonly placeholder="Tsh.<?php echo number_format($total_sell->TotalItemsOrdered ?? 0); ?>/=" name="" class="form-control">
  
</div>
<div class="col-md-4">
   
      Today Profit <input type="" name="" readonly placeholder="Tsh.<?php echo number_format($total_profit->Totalprofit ?? 0); ?>/=" class="form-control">
</div>
<div class="col-md-2">
   <!--  <a href="" class="btn btn-info"><i class="icon-printer"></i>Print</a> -->
</div>
</div>
</div>
<div class="body">
    <div class="table-responsive">
<table class="table table-hover js-basic-example dataTable table-custom">
            <thead class="thead-primary">
                <tr>

                    
                    <th>Seller</th>
                    <th>Customer</th>
                    <th>Product name</th>
                    <th>Quantity</th>
                    <th>Sell price</th>
                    <th>Total price</th>
                    <th>Sell status</th>
                    <th>Time</th>
                    <th>Action</th>
                  
                </tr>
            </thead>
            <tbody>
              <?php foreach ($all_salles as $all_salles_today): ?>
                <tr>
            <td><?php echo $all_salles_today->full_name; ?></td>
            <td><?php echo $all_salles_today->customer; ?></td>
            <td><?php echo $all_salles_today->name; ?></td>
            <td><?php echo $all_salles_today->quanty ; ?> <?php echo $all_salles_today->unit ; ?></td>
            <td><?php echo  number_format($all_salles_today->new_sell_price ?? 0) ; ?>/=</td>
            <td><?php echo  number_format($all_salles_today->total_sell_price ?? 0); ?>/=</td>
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
            <td><?php echo  $all_salles_today->created_at; ?>
          </td>
          <td>
           
              <a href="<?php echo base_url("admin/delete_mistake_sell/{$all_salles_today->sell_id}") ?>" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm"><i class="icon-close"></i></a>
            

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

<?php include('incs/footer.php'); ?>
<script src="<?php echo base_url() ?>assets/admin/js/chart.min.js"></script>
<script src="<?php echo base_url() ?>assets/admin/js/App.js"></script>









