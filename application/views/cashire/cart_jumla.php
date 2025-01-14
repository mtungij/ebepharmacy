<?php include('incs/header.php'); ?>
<?php include('incs/nav.php'); ?>
<?php include('incs/side.php'); ?>
<script src="<?php echo base_url('assets/admin/js/jquery.js'); ?>"></script>
<script>
/* Update item quantity */
function updateCartItem(obj, rowid,item_id){
     console.log();
    $.get("<?php echo base_url('seller/updateItemQty/'); ?>",{rowid:rowid, qty:obj.value,item_id:item_id}, function(resp){
        console.log("response",resp)
        if(resp == 'ok'){
            location.reload();
        }else{
            document.querySelector("#cartp").value =(obj.value - 1) 
            alert('The product is not enough.');
        }
    });
}
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

<div class="row clearfix">
<div class="col-lg-6">
<div class="card">
<div class="header">
  <div class="row">
    <div class="col-md-6">
      <h2>Product List</h2>
      </div>
      <div class="col-md-6">
        <?php if (count($kwisha) == 0) {
         ?>
       <?php }else{ ?>
        <div class="pull-right">
          <?php $kwiaha = $this->db->query("SELECT * FROM tbl_store WHERE balance =0"); 
           ?>
      <h2><a href="<?php echo base_url("seller/get_emptyItm"); ?>">No balance Item<span class="badge badge-danger"><i><?php echo $kwiaha->num_rows(); ?></span></i></a></h2>
      </div>
      <?php } ?>
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
                <a href="<?php echo base_url("seller/addToCart/{$datas->id}"); ?>">
               <b> <?php echo $datas->name; ?></b>
                <?php } ?> 
        <?php if ($datas->balance <= $limit->stock) {
                ?>
              <span class="badge badge-danger">
              <?php echo $datas->balance; ?></span></a>
               <?php }elseif ($datas->balance >= $limit->stock) {
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
                <a href="<?php echo base_url("seller/addToCart_jumla/{$datas->id}"); ?>">
               <b> <?php echo $datas->name; ?></b>
                <?php } ?> 
        <?php if ($datas->balance <= $limit->stock) {
                ?>
              <span class="badge badge-danger">
              <?php echo $datas->balance; ?></span></a>
               <?php }elseif ($datas->balance >= $limit->stock) {
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
    <div class="table-responsive">
<table class="table table-hover js-basi-example dataTable table-custom">
    <?php echo form_open("seller/sell_jumla"); ?>
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
            <tr>
            <td><b><?php echo $item['name']; ?></b></td>
            <td>Tsh.<?php echo number_format($item['ju_price']); ?>/=
              <input type="hidden" name="new_sell_price[]" value="<?php echo $item['ju_price']; ?>">
              <input type="hidden" name="user_id[]" value="<?php echo $_SESSION['user_id']; ?>">
            </td>
            <td>
              <?php 
              $cart_id = $item["rowid"];
              $item_id = $item["id"];
                 ?>
      <input type="number" value="<?php echo $item["qty"]; ?>" id='cartp'  onchange="updateCartItem(this, '<?php echo $cart_id; ?>','<?php echo $item_id; ?>')" class="form-control" style="width: 80px">
       <input type="hidden" name="quantity[]" value="<?php echo $item["qty"]; ?>">
            </td> 
            <td><?php echo 'Tsh.'.number_format($item["sub"]).'/='; ?>
              <input type="hidden" name="total_sell_price[]" value="<?php echo $item["sub"]; ?>">
            <input type="hidden" name="profit[]" value="<?php echo $item['sub'] - $item['buy_price'] * $item['qty']; ?>">
            <input type="hidden" name="product_id[]" value="<?php echo $item['id']; ?>">
            <input type="hidden" name="sell_status[]" value="whole">
            </td> 
           <!--  <td><?php //echo $item['subtotal'] - $item['buy_price'] * $item['qty']; ?></td> -->
            <td><a href="<?php echo base_url('cart_jumla/removeItemdata/'.$item["rowid"]); ?>" class="btn btn-danger btn-sm"><i class=" icon-close"></i></a><?php //echo $products->p_name; ?></td>
              </tr>
    <?php endforeach; ?>
              <tr>
                <th>Total</th>
                <th></th>
                <th></th>
                <th><?php if($this->cart->total_items() > 0){ ?>
                        <span><?php echo 'Tsh.'.number_format($this->cart->tota()).'/='; ?>
                    <?php } ?>
                  </th>
                <th></th>
              </tr>
              <th></th>
              <th></th>
              <th><input type="submit" value="Sell" class="btn btn-info btn-sm"></th>
              <th>
                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#addcontact"><i class="icon-printer">Recept</i></button>
              </th>
              <th></th>
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



