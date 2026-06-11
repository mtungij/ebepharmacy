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
<?php
  $is_filtered = !empty($is_filtered);
  $selected_user_id = isset($selected_user_id) ? $selected_user_id : '';
  $from = isset($from) ? $from : date('Y-m-d');
  $to = isset($to) ? $to : date('Y-m-d');
  $total_sell_amount = $is_filtered ? (isset($total_sell->total_mauzo) ? (float)$total_sell->total_mauzo : 0) : (isset($total_sell->TotalItemsOrdered) ? (float)$total_sell->TotalItemsOrdered : 0);
  $total_profit_amount = $is_filtered ? (isset($total_profit->total_profit) ? (float)$total_profit->total_profit : 0) : (isset($total_profit->Totalprofit) ? (float)$total_profit->Totalprofit : 0);
?>
<div class="header">
    <div  class="row">
        <div class="col-md-3">
<h2><?php echo $is_filtered ? 'Seller Sales Report' : 'Salles today'; ?></b> </h2>
</div>
<div class="col-md-3">
  
      Total sell <input type="" readonly placeholder="Tsh.<?php echo number_format($total_sell_amount); ?>/=" name="" class="form-control">
  
</div>
<div class="col-md-3">
   
      Profit <input type="" name="" readonly placeholder="Tsh.<?php echo number_format($total_profit_amount); ?>/=" class="form-control">
</div>
<div class="col-md-3">
     <a href="#seller-report-filter" class="btn btn-sm btn-primary"><i class="icon-magnifier"></i></a></div>
</div>
</div>
<div class="body">
    <div id="seller-report-filter" class="mb-3" style="margin-bottom:16px;">
        <form method="get" action="<?php echo base_url('admin/general_sells_product'); ?>" id="seller-live-filter-form">
        <?php $date = date("Y-m-d"); ?>
        <div class="row clearfix">
            <div class="col-md-4 col-sm-12">
                <div class="form-group">
                    <span>Seller</span>
                    <select type="number" class="form-control" name="user_id" id="seller-live-filter">
                        <option value="">All sellers</option>
                        <?php foreach ($all_seller as $all_sellers): ?>
                            <option value="<?php echo $all_sellers->user_id; ?>" <?php echo ((string)$selected_user_id === (string)$all_sellers->user_id) ? 'selected' : ''; ?>>
                                <?php echo $all_sellers->full_name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="form-group">
                    <span>From</span>
                    <input type="date" class="form-control seller-live-date" value="<?php echo html_escape($from ? $from : $date); ?>" name="from">
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="form-group">
                    <span>To</span>
                    <input type="date" class="form-control seller-live-date" value="<?php echo html_escape($to ? $to : $date); ?>" name="to">
                </div>
            </div>
        </div>
        </form>
    </div>
    <div class="form-group" style="max-width:360px;">
        <span>Search table</span>
        <input type="search" class="form-control seller-report-search" placeholder="Search seller, customer, product..." autocomplete="off">
    </div>
    <div class="table-responsive">
<table class="table table-hover table-custom seller-report-table">
            <thead class="thead-primary">
                <tr>

                    
                    <th>Seller</th>
                    <th>Customer</th>
                    <th>Product name</th>
                    <th>Quantity</th>
                    <th>Sell price</th>
                    <th>Total price</th>
                    <th>Profit</th>
                    <th>Sales status</th>
                    <th>Time</th>
                  
                </tr>
            </thead>
            <tbody>
              <?php foreach ($all_salles as $all_salles_today): ?>
                <?php
                  $sale_qty = isset($all_salles_today->quanty) ? $all_salles_today->quanty : (isset($all_salles_today->qnty) ? $all_salles_today->qnty : 0);
                  $sale_time = !empty($all_salles_today->creat) ? $all_salles_today->creat : (isset($all_salles_today->created_at) ? $all_salles_today->created_at : $all_salles_today->sell_day);
                ?>
                <tr data-report-row="1">
            <td><?php echo $all_salles_today->full_name; ?></td>
            <td><?php echo $all_salles_today->customer; ?></td>
            <td><?php echo $all_salles_today->name; ?></td>
            <td><?php echo $sale_qty ; ?> <?php echo $all_salles_today->unit ; ?></td>
            <td><?php echo  number_format($all_salles_today->new_sell_price) ; ?>/=</td>
            <td><?php echo  number_format($all_salles_today->total_sell_price); ?>/=</td>
            <td><?php echo  number_format($all_salles_today->profit); ?>/=</td>
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
                <?php echo  $sale_time; ?>
            </td>

              
                </tr>
            <?php endforeach; ?>
              <?php if (!$is_filtered): ?>
              <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td>SELLER SUMMARY</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
              </tr>
      <?php foreach ($data_employee as $data_employees): ?>
        
              <tr>
                  <td></td>
                  <td></td>
                  <td><b><?php echo $data_employees->full_name; ?></b></td>
                  <td></td>
                  <td></td>
                  <td><b><?php echo $data_employees->total_mauzo; ?></b></td>
                  <td></td>
                  <td></td>
                  <td></td>
              </tr>

               <?php endforeach; ?>
               <?php endif; ?>
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


<script>
(function () {
  var search = document.querySelector('.seller-report-search');
  var rows = document.querySelectorAll('.seller-report-table tbody tr[data-report-row="1"]');
  var sellerFilter = document.getElementById('seller-live-filter');
  var sellerForm = document.getElementById('seller-live-filter-form');
  var dateFilters = document.querySelectorAll('.seller-live-date');

  if (sellerFilter && sellerForm) {
    sellerFilter.addEventListener('change', function () {
      sellerForm.submit();
    });
  }

  if (sellerForm && dateFilters.length) {
    dateFilters.forEach(function (input) {
      input.addEventListener('change', function () {
        sellerForm.submit();
      });
    });
  }

  if (!search || !rows.length) {
    return;
  }

  search.addEventListener('input', function () {
    var term = search.value.toLowerCase().trim();
    rows.forEach(function (row) {
      row.style.display = row.textContent.toLowerCase().indexOf(term) !== -1 ? '' : 'none';
    });
  });
})();
</script>

<?php include 'incs/footer.php'; ?>
