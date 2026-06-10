<?php include('incs/header.php'); ?>
<?php include('incs/nav.php'); ?>
<?php include('incs/side.php'); ?>
<script src="<?php echo base_url('assets/admin/js/jquery.js'); ?>"></script>
<style>
#evamoQuantityWarningModal {
  position: fixed;
  inset: 0;
  z-index: 1050;
  align-items: center;
  justify-content: center;
  padding: 16px;
  background: rgba(15, 23, 42, 0.55);
}

#evamoQuantityWarningModal.hidden {
  display: none;
}

#evamoQuantityWarningModal.flex {
  display: flex;
}

.evamo-warning-card {
  width: 100%;
  max-width: 460px;
  border-radius: 16px;
  border: 1px solid var(--evamo-modal-border);
  background: var(--evamo-modal-bg);
  color: var(--evamo-modal-text);
  box-shadow: 0 18px 45px rgba(2, 6, 23, 0.25);
  overflow: hidden;
}

.evamo-warning-head,
.evamo-warning-actions {
  padding: 14px 18px;
  border-color: var(--evamo-modal-border);
}

.evamo-warning-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: 1px solid var(--evamo-modal-border);
}

.evamo-warning-title {
  margin: 0;
  font-size: 16px;
  font-weight: 700;
  letter-spacing: 0.2px;
  color: var(--evamo-modal-accent);
}

.evamo-warning-body {
  padding: 16px 18px;
}

.evamo-warning-text {
  margin: 0;
  color: var(--evamo-modal-muted);
  font-size: 14px;
  line-height: 1.6;
}

.evamo-warning-actions {
  display: flex;
  justify-content: flex-end;
  border-top: 1px solid var(--evamo-modal-border);
}

.evamo-warning-close {
  border: 0;
  border-radius: 8px;
  padding: 2px 8px;
  font-size: 22px;
  line-height: 1;
  cursor: pointer;
  color: var(--evamo-modal-muted);
  background: transparent;
}

.evamo-warning-close:hover {
  color: var(--evamo-modal-text);
  background: var(--evamo-modal-soft);
}

.evamo-warning-ok {
  border: 0;
  border-radius: 10px;
  padding: 9px 16px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  color: #ffffff;
  background: linear-gradient(135deg, #0ea5e9, #0284c7);
  box-shadow: 0 8px 20px rgba(3, 105, 161, 0.28);
}

.evamo-warning-ok:hover {
  filter: brightness(1.06);
}

:root {
  --evamo-modal-bg: #ffffff;
  --evamo-modal-text: #0f172a;
  --evamo-modal-muted: #475569;
  --evamo-modal-border: #dce5f0;
  --evamo-modal-soft: #f1f5f9;
  --evamo-modal-accent: #0f766e;
}

@media (prefers-color-scheme: dark) {
  :root {
    --evamo-modal-bg: #0f172a;
    --evamo-modal-text: #e2e8f0;
    --evamo-modal-muted: #94a3b8;
    --evamo-modal-border: #1e293b;
    --evamo-modal-soft: #1e293b;
    --evamo-modal-accent: #2dd4bf;
  }

  #evamoQuantityWarningModal {
    background: rgba(2, 6, 23, 0.72);
  }

  .evamo-warning-card {
    box-shadow: 0 18px 50px rgba(0, 0, 0, 0.45);
  }

  .evamo-warning-title {
    color: #2dd4bf;
  }
}
</style>
<script>
var evamoQtyUpdateTimers = {};

function showQuantityWarning(message){
  var textNode = document.getElementById('evamo-quantity-warning-text');
  if (textNode) {
    textNode.textContent = message;
  }
  var modal = document.getElementById('evamoQuantityWarningModal');
  if (modal) {
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    modal.setAttribute('aria-hidden', 'false');
  }
}

function hideQuantityWarning(){
  var modal = document.getElementById('evamoQuantityWarningModal');
  if (modal) {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    modal.setAttribute('aria-hidden', 'true');
  }
}

function formatTsh(amount){
  var value = Number(amount || 0);
  return 'Tsh.' + value.toLocaleString('en-US') + '/=';
}

function recalculateCartTotals(){
  var grandTotal = 0;
  var totalItems = 0;

  document.querySelectorAll('.evamo-cart-row').forEach(function(row){
    var qtyInput = row.querySelector('.evamo-qty-input');
    var qtyHidden = row.querySelector('.evamo-qty-hidden');
    var rowTotalText = row.querySelector('.evamo-row-total-text');
    var rowTotalHidden = row.querySelector('.evamo-row-total-hidden');

    if(!qtyInput){
      return;
    }

    var rawQty = qtyInput.value;
    var qty = Number(rawQty || 0);
    if (rawQty === '') {
      qty = Number(qtyInput.getAttribute('data-old-qty') || 1);
    } else if (!Number.isFinite(qty) || qty < 1) {
      qty = 1;
      qtyInput.value = 1;
    }

    var price = Number(qtyInput.getAttribute('data-price') || 0);
    var subtotal = qty * price;
    totalItems += qty;
    grandTotal += subtotal;

    if (qtyHidden) {
      qtyHidden.value = qty;
    }
    if (rowTotalText) {
      rowTotalText.textContent = formatTsh(subtotal);
    }
    if (rowTotalHidden) {
      rowTotalHidden.value = subtotal;
    }
  });

  var grandTotalText = document.getElementById('evamo-grand-total-text');
  var grandTotalHidden = document.getElementById('evamo-grand-total-input');
  if (grandTotalText) {
    grandTotalText.textContent = formatTsh(grandTotal);
  }
  if (grandTotalHidden) {
    grandTotalHidden.value = grandTotal;
  }

  var itemCountText = document.getElementById('evamo-item-count-text');
  if (itemCountText) {
    itemCountText.textContent = totalItems > 0 ? totalItems + ' Items' : 'Empty';
  }
}

function scheduleCartItemUpdate(obj, rowid, item_id){
  var maxStock = Number(obj.getAttribute('data-max-stock') || 0);
  var rawQty = obj.value;
  var nextQty = Number(rawQty || 0);

  if (rawQty !== '' && maxStock > 0 && Number.isFinite(nextQty) && nextQty > maxStock) {
    obj.value = 1;
    obj.setAttribute('data-old-qty', 1);
    recalculateCartTotals();
    showQuantityWarning('Only ' + maxStock + ' item(s) available in stock. Quantity has been reset to 1.');
    if (evamoQtyUpdateTimers[rowid]) {
      clearTimeout(evamoQtyUpdateTimers[rowid]);
    }
    updateCartItem(obj, rowid, item_id);
    return;
  }

  recalculateCartTotals();
  if (evamoQtyUpdateTimers[rowid]) {
    clearTimeout(evamoQtyUpdateTimers[rowid]);
  }
  evamoQtyUpdateTimers[rowid] = setTimeout(function(){
    updateCartItem(obj, rowid, item_id);
  }, 300);
}

/* Update item quantity */
function updateCartItem(obj, rowid,item_id){
  var rawQty = obj.value;
  var oldQty = Number(obj.getAttribute('data-old-qty') || 1);
  var maxStock = Number(obj.getAttribute('data-max-stock') || 0);

  if (rawQty === '') {
    return;
  }

  var normalizedQty = Number(rawQty);
  if (!Number.isFinite(normalizedQty) || normalizedQty < 1) {
    normalizedQty = 1;
  }

  if (maxStock > 0 && normalizedQty > maxStock) {
    obj.value = 1;
    obj.setAttribute('data-old-qty', 1);
    recalculateCartTotals();
    showQuantityWarning('Only ' + maxStock + ' item(s) available in stock. Quantity has been reset to 1.');
    return;
  }

  obj.value = normalizedQty;

  recalculateCartTotals();

    $.get("<?php echo base_url('seller/updateItemQty/'); ?>",{rowid:rowid, qty:normalizedQty,item_id:item_id}, function(resp){
        if(resp == 'ok'){
      obj.setAttribute('data-old-qty', normalizedQty);
      recalculateCartTotals();
        }else{
      obj.value = 1;
      obj.setAttribute('data-old-qty', 1);
      recalculateCartTotals();
      showQuantityWarning(maxStock > 0 ? 'Only ' + maxStock + ' item(s) available in stock. Quantity has been reset to 1.' : 'Selected quantity is not available. Quantity has been reset to 1.');
        }
    });
}

document.addEventListener('DOMContentLoaded', function(){
  var okButton = document.getElementById('evamo-quantity-warning-ok');
  var closeButton = document.getElementById('evamo-quantity-warning-close');
  if (okButton) {
    okButton.addEventListener('click', hideQuantityWarning);
  }
  if (closeButton) {
    closeButton.addEventListener('click', hideQuantityWarning);
  }
  recalculateCartTotals();
});
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
<?php if ($err = $this->session->flashdata('error')): ?>
<div class="row">
<div class="col-md-12">
<div class="alert alert-dismisible alert-danger">
<a href="" class="close">&times;</a>
<?php echo $err;?>
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
            <?php if ($datas->price == 0){
             ?>
             -//-//-
           <?php }else{?>
                 <?php if($datas->balance == 0) {
                 ?>
            <b> <?php echo $datas->name; ?> <?php echo $datas->bland; ?></b>
                
               <?php }else{ ?>
                <a href="<?php echo base_url("admin/addToCart/{$datas->id}"); ?>">
               <b> <?php echo $datas->name; ?> - <?php echo $datas->bland; ?></b>
                <?php } ?> 
        <?php if ($datas->balance <= $datas->stock_limit) {
                ?>
              <span class="badge badge-danger">
              <?php echo $datas->balance; ?></span></a>
               <?php }elseif ($datas->balance >= $datas->stock_limit) {
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
                /
               <?php }else{ ?>
                <a href="<?php echo base_url("admin/addToCart_jumla/{$datas->id}"); ?>">
               <b> <?php echo $datas->name; ?></b>
                <?php } ?> 
        <?php if ($datas->balance <= $datas->stock_limit) {
                ?>
              <span class="badge badge-danger">
              <?php echo $datas->balance; ?></span></a>
               <?php }elseif ($datas->balance >= $datas->stock_limit) {
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

     <?php
                 $text = '';
                if(isset($_GET['customer'])){
                  $text =  htmlentities($_GET['customer']);  
                  echo 
                  redirect('seller/print_recept/'. htmlentities($text));     
                }
                 ?>


    <div class="card">
<div class="header">
  <h2>Item List  <a href="<?php //echo base_url("cart/index") ?>" class="icon-menu"><i class=""></i>(<span id="evamo-item-count-text"><?php echo ($this->cart->total_items() > 0)?$this->cart->total_items().' Items':'Empty'; ?></span>)</a></b> </h2>
</div>
<div class="body">
  <div class="table-responsive evamo-cart-table-wrap">
<table class="table table-hover js-basi-example dataTable table-custom evamo-cart-table">
    <?php echo form_open("admin/sell"); ?>
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
            <tr class="evamo-cart-row">
            <td data-label="Name"><b><?php echo $item['name']; ?></b></td>
            <td data-label="Price(Tsh)">Tsh.<?php echo number_format($item['price']); ?>/=
              <input type="hidden" name="new_sell_price[]" value="<?php echo $item['price']; ?>">
              <input type="hidden" name="user_id[]" value="<?php echo $_SESSION['user_id']; ?>">
            </td>
            <td data-label="Quantity">
              <?php 
              $cart_id = $item["rowid"];
              $item_id = $item["id"];
                 ?>
      <input type="number" value="<?php echo $item["qty"]; ?>" data-old-qty="<?php echo $item["qty"]; ?>" data-max-stock="<?php echo isset($item['stock_balance']) ? $item['stock_balance'] : ''; ?>" data-price="<?php echo $item['price']; ?>" oninput="scheduleCartItemUpdate(this, '<?php echo $cart_id; ?>','<?php echo $item_id; ?>')" min="1" class="form-control evamo-qty-input" style="width: 80px">
     <!-- <select type="number" class="form-control" id='cartp' onchange="updateCartItem(this, '<?php echo $cart_id; ?>','<?php echo $item_id; ?>')" style="width: 80px">
       <option value="<?php echo $item["qty"]; ?>"><?php echo $item["qty"]; ?></option>
       <option value="0.25">Robo</option>
       <option value="0.5">Nusu</option>
       <option value="0.75">Robo tatu</option>
     </select> -->
       <input type="hidden" name="quantity[]" value="<?php echo $item["qty"]; ?>" class="evamo-qty-hidden">
            </td> 
            <td data-label="Total(Tsh)"><span class="evamo-row-total-text"><?php echo 'Tsh.'.number_format($item["subtotal"]).'/='; ?></span>
              <input type="hidden" name="total_sell_price[]" value="<?php echo $item["subtotal"]; ?>" class="evamo-row-total-hidden">
            <input type="hidden" name="profit[]" value="<?php echo $item['subtotal'] - $item['buy_price'] * $item['qty']; ?>">
            <input type="hidden" name="product_id[]" value="<?php echo $item['id']; ?>">
            <input type="hidden" name="sell_status[]" value="retail">
            </td> 
           <!--  <td><?php //echo $item['subtotal'] - $item['buy_price'] * $item['qty']; ?></td> -->
            <td data-label="Action"><a href="<?php echo base_url('admin_cart/removeItem/'.$item["rowid"]); ?>" class="btn btn-danger btn-sm"><i class=" icon-close"></i></a><?php //echo $products->p_name; ?></td>
              </tr>
    <?php endforeach; ?>
              <tr class="evamo-cart-summary-row">
                <th>Total</th>
                <th></th>
                <th></th>
                <th><?php if($this->cart->total_items() > 0){ ?>
                  <span id="evamo-grand-total-text"><?php echo 'Tsh.'.number_format($this->cart->total()).'/='; ?></span>
                  <input type="hidden" id="evamo-grand-total-input" name="total_price" value="<?php echo $this->cart->total(); ?>">
                    <?php } ?>
                  </th>
                <th></th>
              </tr>
              <tr class="evamo-cart-customer-row">
              <td colspan="5">
            <input type="text" name="customer" autocomplete="off" placeholder="customer name" class="form-control evamo-cart-customer-input">
              </td>
              </tr>
              <tr class="evamo-cart-actions-row">
              <th></th>
              <th></th>
              <th><input type="submit" value="Sell" class="btn btn-info btn-sm"></th>
              <th><!-- <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#addcontact"><i class="icon-printer">Recept</i></button> --></th>
              <th></th>
              </tr>
                  <?php endif; ?>
            <?php echo form_close(); ?>
            </tbody>
        </table>
    </div>
</div>
</div>

</div>
</div>

<div class="hidden" id="evamoQuantityWarningModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="evamo-warning-card">
    <div class="evamo-warning-head">
      <h6 class="evamo-warning-title">Quantity warning</h6>
      <button type="button" class="evamo-warning-close" id="evamo-quantity-warning-close" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      </div>
    <div class="evamo-warning-body">
      <p id="evamo-quantity-warning-text" class="evamo-warning-text">Selected quantity is not available.</p>
      </div>
    <div class="evamo-warning-actions">
      <button type="button" class="evamo-warning-ok" id="evamo-quantity-warning-ok">OK</button>
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
                  redirect('seller/print_recept/'. htmlentities($text));     
                }
                 ?>
<!-- Default Size -->
<!-- <div class="modal fade" id="addcontact" tabindex="-1" role="dialog">
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
</div> -->


