<?php include('incs/header.php'); ?>
<?php include('incs/nav.php'); ?>
<?php include('incs/side.php'); ?>

<style>
.evamo-product-form-grid .form-group {
    margin-bottom: 0;
    padding: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #f8fafc;
}

.evamo-product-form-grid .form-group > span {
    display: inline-block;
    margin-bottom: 6px;
    font-size: 12px;
    font-weight: 600;
    color: #334155;
    letter-spacing: 0.2px;
}

.evamo-product-form-grid .form-control {
    border-radius: 8px;
    border-color: #cbd5e1;
}

.evamo-product-form-grid .form-control:focus {
    border-color: #0ea5a4;
    box-shadow: 0 0 0 0.18rem rgba(14, 165, 164, 0.15);
}

.evamo-product-form-grid .select2-container--default .select2-selection--single {
    height: 38px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.evamo-product-form-grid .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 36px;
    color: #0f172a;
}

.evamo-product-form-grid .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
}

.evamo-product-form-grid .select2-container--default.select2-container--open .select2-selection--single,
.evamo-product-form-grid .select2-container--default .select2-selection--single:hover {
    border-color: #0ea5a4;
    box-shadow: 0 0 0 0.18rem rgba(14, 165, 164, 0.15);
}

.select2-dropdown {
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    overflow: hidden;
}

.select2-container--default .select2-search--dropdown .select2-search__field {
    border: 1px solid #cbd5e1;
    border-radius: 6px;
}

.select2-container--default .select2-search--dropdown .select2-search__field:focus {
    border-color: #0ea5a4;
    box-shadow: 0 0 0 0.16rem rgba(14, 165, 164, 0.14);
    outline: none;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #0f766e;
    color: #ffffff;
}

.select2-container--default .select2-results__option[aria-selected=true] {
    background-color: rgba(15, 118, 110, 0.12);
    color: #115e59;
}

@media (prefers-color-scheme: dark) {
    .evamo-product-form-grid .form-group {
        background: #111827 !important;
        border-color: #334155 !important;
    }

    .evamo-product-form-grid .form-group > span {
        color: #cbd5e1 !important;
        background: transparent !important;
    }

    .evamo-product-form-grid input,
    .evamo-product-form-grid select,
    .evamo-product-form-grid .form-control {
        background: #0f172a !important;
        border-color: #334155 !important;
        color: #ffffff !important;
    }

    .evamo-product-form-grid .select2-container--default .select2-selection--single {
        background: #0f172a !important;
        border-color: #334155 !important;
    }

    .evamo-product-form-grid .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #ffffff !important;
        background: transparent !important;
    }

    .evamo-product-form-grid .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #cbd5e1 !important;
    }

    .evamo-product-form-grid .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #94a3b8 transparent transparent transparent;
    }

    .select2-dropdown {
        background: #0f172a !important;
        border-color: #334155 !important;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
        background: #0f172a !important;
        border-color: #334155 !important;
        color: #ffffff !important;
    }

    .select2-container--default .select2-results__option {
        color: #e2e8f0 !important;
        background: #0f172a !important;
    }

    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: rgba(45, 212, 191, 0.2);
        color: #99f6e4;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #0d9488;
        color: #ffffff;
    }
}

html.evamo-dark .evamo-product-form-grid .form-group {
    background: #111827 !important;
    border-color: #334155 !important;
}

html.evamo-dark .evamo-product-form-grid .form-group > span {
    color: #cbd5e1 !important;
    background: transparent !important;
}

html.evamo-dark .evamo-product-form-grid input,
html.evamo-dark .evamo-product-form-grid select,
html.evamo-dark .evamo-product-form-grid .form-control {
    background: #0f172a !important;
    border-color: #334155 !important;
    color: #ffffff !important;
}

html.evamo-dark .evamo-product-form-grid .select2-container--default .select2-selection--single {
    background: #0f172a !important;
    border-color: #334155 !important;
}

html.evamo-dark .evamo-product-form-grid .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #ffffff !important;
    background: transparent !important;
}

html.evamo-dark .evamo-product-form-grid .select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #cbd5e1 !important;
}

html.evamo-dark .evamo-product-form-grid .select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: #94a3b8 transparent transparent transparent;
}

html.evamo-dark .select2-dropdown {
    background: #0f172a !important;
    border-color: #334155 !important;
}

html.evamo-dark .select2-container--default .select2-search--dropdown .select2-search__field {
    background: #0f172a !important;
    border-color: #334155 !important;
    color: #ffffff !important;
}

html.evamo-dark .select2-container--default .select2-results__option {
    color: #e2e8f0 !important;
    background: #0f172a !important;
}

html.evamo-dark .select2-container--default .select2-results__option[aria-selected=true] {
    background-color: rgba(45, 212, 191, 0.2);
    color: #99f6e4;
}

html.evamo-dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #0d9488;
    color: #ffffff;
}
</style>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/select2.min.css">
<script src="<?php //echo base_url('assets/admin/js/jquery.js'); ?>"></script>


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

<?php if ($das = $this->session->flashdata('error')): ?>
<div class="row">
<div class="col-md-12">
<div class="alert alert-dismisible alert-danger">
<a href="" class="close">&times;</a>
<?php echo $das;?>
</div>
</div>
</div>
<?php endif; ?>
<div class="row clearfix">
<div class="col-lg-12 col-md-12 col-sm-12">
<div class="card">
<div class="header">
<h2>Add Product In Store</h2>
</div>
<div class="body">
  
        <?php echo form_open_multipart("admin/add_product_store"); ?>
<div class="row clearfix evamo-product-form-grid">
       <div class="col-sm-6">
        <div class="form-group">
           <span>Select Product</span>
           <select type="number" class="form-control select2 evamo-product-select" name="product_id" id="product">
               <option value="">Select Product</option>
               <?php foreach ($product as $store_products): ?>
               <option value="<?php echo $store_products->product_id; ?>"><?php echo $store_products->name; ?>(<?php echo $store_products->unit; ?>) - <?php echo $store_products->bland; ?> - (<?php echo $store_products->balance; ?>)</option>
                <?php endforeach; ?>
           </select>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <span>Reason <span class="text-danger">*</span></span>
            <select class="form-control" name="reason" required>
                <option value="">Select reason</option>
                <option value="purchased">Purchased</option>
                <option value="adjusted">Adjusted</option>
            </select>
        </div>
    </div>
                <div class="col-6">
                    <span>Container</span>
                        <div class="form-group">                                  
                    <input type="number" name="" id="cont1" required autocomplete="off" class="form-control" placeholder="Container">
                        </div>
                    </div>
                     <div class="col-6">
                        <span>Total</span>
                        <div class="form-group">
                                                            
                    <input type="number" name="customer" id="cont2" required autocomplete="off" class="form-control" placeholder="Total">
                        </div>
                    </div>
       <div class="col-sm-6">
        <div class="form-group">
        <span>Quantity </b> </span>
            <input type="number" autocomplete="off" id="total_cont" required name="balance" class="form-control" placeholder="Quantity" readonly required>
            <?php echo form_error("quantity_product"); ?>
        </div>
    </div>
    </div>    
</div>
<div class="row clearfix">                            
    <div class="col-sm-12">
      <div class="text-center">
        <button type="submit" class="btn btn-primary">Add</button>
     <a href="<?php echo base_url("admin/all_productStore"); ?>" class="btn btn-info"><i class="icon-arrow-left"></i></a>
      </div>
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
<script src="<?php echo base_url('assets/admin/js/select2.min.js'); ?>"></script>

<script>
        $(document).ready(function () { 
        $("#cont1,#cont2").change(function() {
    $("#total_cont").val ($("#cont1").val() * $("#cont2").val());
            });
        });
    </script>
<script>
$(document).ready(function(){
$('#product').change(function(){
var product_id = $('#product').val();
//alert(product_id)
if(product_id != ''){

$.ajax({
url:"<?php echo base_url(); ?>admin/fetch_ward_data",
method:"POST",
data:{product_id:product_id},
success:function(data)
{
$('#stoo').html(data);
$('#district').html('<option value="">All</option>');
}
});
}
else
{
$('#stoo').html('<option value="">Select product</option>');
$('#district').html('<option value="">All</option>');
}
});



// $('#region').change(function(){
// var region_id = $('#region').val();
// if(region_id != '')
// {
// $.ajax({
// url:"<?php //echo base_url(); ?>admin/fetch_data_vipimioData",
// method:"POST",
// data:{region_id:region_id},
// success:function(data)
// {
// $('#district').html(data);
// //$('#malipo_name').html('<option value="">select center</option>');
// }
// });
// }
// else
// {
// $('#district').html('<option value="">All</option>');
// //$('#malipo_name').html('<option value="">chagua vipimio</option>');
// }
// });

// $('#social').change(function(){
//  var district_id = $('#social').val();
//  if(district_id != '')
//  {
//   $.ajax({
//    url:"<?php echo base_url(); ?>user/fetch_data_malipo",
//    method:"POST",
//    data:{district_id:district_id},
//    success:function(data)
//    {
//     $('#malipo_name').html(data);
//     //$('#malipo').html('<option value="">chagua malipo</option>');
//    }
//   });
//  }
//  else
//  {
//   //$('#vipimio').html('<option value="">chagua vipimio</option>');
//   $('#malipo_name').html('<option value="">chagua vipimio</option>');
//  }
// });


});
</script>

<script>
    $('.select2').select2({
        placeholder: 'Select Product',
        allowClear: true,
        width: '100%'
    });
</script>

