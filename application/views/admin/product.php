<?php include('incs/header.php'); ?>
<?php include('incs/nav.php'); ?>
<?php include('incs/side.php'); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/select2.min.css">

<style>
.evamo-product-form-grid > div,
.evamo-product-list-grid > div {
    margin-bottom: 14px;
}

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

.evamo-product-list-grid .table thead th,
.evamo-product-list-grid .table tfoot th {
    white-space: nowrap;
    font-size: 12px;
    letter-spacing: 0.2px;
}

.evamo-product-list-grid .table td {
    vertical-align: middle;
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
    .evamo-product-form-grid textarea,
    .evamo-product-form-grid .form-control {
        background: #0f172a !important;
        border-color: #334155 !important;
        color: #ffffff !important;
    }

    .evamo-product-form-grid input[type="date"] {
        color-scheme: dark;
    }

    .evamo-product-form-grid input::placeholder,
    .evamo-product-form-grid textarea::placeholder {
        color: #cbd5e1 !important;
        opacity: 1;
    }

    .evamo-product-form-grid input:-webkit-autofill,
    .evamo-product-form-grid input:-webkit-autofill:hover,
    .evamo-product-form-grid input:-webkit-autofill:focus,
    .evamo-product-form-grid textarea:-webkit-autofill,
    .evamo-product-form-grid select:-webkit-autofill {
        -webkit-text-fill-color: #ffffff;
        -webkit-box-shadow: 0 0 0px 1000px #0f172a inset;
        transition: background-color 9999s ease-in-out 0s;
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
html.evamo-dark .evamo-product-form-grid textarea,
html.evamo-dark .evamo-product-form-grid .form-control {
    background: #0f172a !important;
    border-color: #334155 !important;
    color: #ffffff !important;
}

html.evamo-dark .evamo-product-form-grid input::placeholder,
html.evamo-dark .evamo-product-form-grid textarea::placeholder {
    color: #cbd5e1 !important;
    opacity: 1;
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

html.evamo-dark .evamo-product-list-grid .table,
html.evamo-dark .evamo-product-list-grid .table th,
html.evamo-dark .evamo-product-list-grid .table td,
html.evamo-dark .card .header h2 {
    color: #cbd5e1;
}
</style>


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

<?php if ($das = $this->session->flashdata('mas')): ?>
<div class="row">
<div class="col-md-12">
<div class="alert alert-dismisible alert-danger">
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
<h2>Add Product</h2>
</div>
<div class="body">
<?php echo form_open_multipart("admin/create_product"); ?>
<div class="row clearfix evamo-product-form-grid">
       <div class="col-sm-6">
        <div class="form-group">
              <span>Product name <span class="text-danger">*</span></span>
            <input type="text" autocomplete="off" required name="name" class="form-control" placeholder="product name">
            <?php echo form_error("name"); ?>
        </div>
    </div>
       <div class="col-sm-6">
      <div class="form-group">
                <span>Category <span class="text-danger">*</span></span>
            <select required name="category" class="form-control" style="width: 100%;">
                <option value="">Select category</option>
                <option value="Medicines" <?php echo set_select('category', 'Medicines'); ?>>Medicines</option>
                <option value="Cosmetics" <?php echo set_select('category', 'Cosmetics'); ?>>Cosmetics</option>
                <option value="Skin Care" <?php echo set_select('category', 'Skin Care'); ?>>Skin Care</option>
                <option value="Medical Equipment" <?php echo set_select('category', 'Medical Equipment'); ?>>Medical Equipment</option>
            </select>
            <?php echo form_error("category"); ?>
        </div>
    </div>
       <div class="col-sm-6">
      <div class="form-group">
                <span>Branch <span class="text-danger">*</span></span>
            <?php
              $selected_branch_id = isset($selected_branch_id) ? $selected_branch_id : null;
              $selected_branch_name = '';
              if ($selected_branch_id && !empty($branches)) {
                foreach ($branches as $branch) {
                  if ((int)$selected_branch_id === (int)$branch->branch_id) {
                    $selected_branch_name = $branch->branch_name;
                    break;
                  }
                }
              }
            ?>
            <?php if ($selected_branch_id): ?>
              <input type="hidden" name="branch_id" value="<?php echo (int)$selected_branch_id; ?>">
              <input type="text" class="form-control" value="<?php echo html_escape($selected_branch_name); ?>" readonly>
              <small class="text-muted">Using selected admin branch.</small>
            <?php else: ?>
              <select required name="branch_id" class="form-control" style="width: 100%;">
                  <option value="">Select branch</option>
                  <?php foreach ($branches as $branch): ?>
                      <option value="<?php echo $branch->branch_id; ?>" <?php echo set_select('branch_id', $branch->branch_id); ?>>
                          <?php echo $branch->branch_name; ?>
                      </option>
                  <?php endforeach; ?>
              </select>
            <?php endif; ?>
            <?php echo form_error("branch_id"); ?>
        </div>
    </div>
       <div class="col-sm-6">
      <div class="form-group">
                <span>Unit <span class="text-danger">*</span></span>
            <select required name="unit" class="form-control evamo-unit-select" style="width: 100%;">
                <option value="">Search or select unit</option>
                <option value="syrup">Syrup</option>
                <option value="tab">Tab</option>
                <option value="capsule">Capsule</option>
                <option value="injection">Injection</option>
                <option value="box">Box</option>
                <option value="pc">Pc</option>
                <option value="bottle">Bottle</option>
                <option value="vial">Vial</option>
                <option value="strip">Strip</option>
                <option value="sachet">Sachet</option>
                <option value="tube">Tube</option>
                <option value="drop">Drop</option>
                <option value="other">Other</option>
            </select>
            <?php echo form_error("unit"); ?>
        </div>
    </div>
         <div class="col-sm-6">
      <div class="form-group">
        <span>Container <span class="text-danger">*</span></span>
            <input type="number" autocomplete="off" id="cont1" name="" class="form-control" placeholder="container" value="1" required>
            <?php //echo form_error("unit"); ?>
        </div>
    </div>
         <div class="col-sm-6">
      <div class="form-group">
          <span>Pc</span>
            <input type="number" autocomplete="off" id="cont2" name="" class="form-control" placeholder="pc">
            <?php //echo form_error("unit"); ?>
        </div>
    </div>

             <div class="col-sm-6">
      <div class="form-group">
                <span>Quantity </span>
            <input type="number" autocomplete="off" id="total_cont" required name="quantity" class="form-control" placeholder="quantity" readonly>
            <?php echo form_error("quantity"); ?>
        </div>
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
    </div>
            <div class="col-sm-6">
      <div class="form-group">
                <span>Buy price <span class="text-danger">*</span></span>
            <input type="text" autocomplete="off" required name="buy_price" class="form-control" placeholder="buy price">
            <?php echo form_error("buy_price"); ?>
        </div>
    </div>
             <div class="col-sm-6">
      <div class="form-group">
        <span>Retail Sale price <span class="text-danger">*</span></span>
            <input type="text" autocomplete="off" name="price" class="form-control" placeholder="Retail Sale price">
            <?php echo form_error("price"); ?>
        </div>
    </div>
            <div class="col-sm-6">
      <div class="form-group">
        <span>WholeSale Price <span class="text-danger">*</span></span>
            <input type="text" autocomplete="off"  name="ju_price" class="form-control" placeholder="WholeSale Price">
            <?php //echo form_error("ju_price"); ?>
            <small class="text-muted">Fill at least one or both: Retail Sale price or WholeSale Price.</small>
        </div>
    </div>

       <div class="col-sm-6">
      <div class="form-group">
        <span>Product Stock Limit</span>
            <input type="number" autocomplete="off"  name="stock_limit" class="form-control" placeholder="Product Stock Limit" value="0">
            <?php //echo form_error("ju_price"); ?>
        </div>
    </div>

     <div class="col-sm-6">
      <div class="form-group">
        <span>Expire Date</span>
            <input type="date" autocomplete="off"  name="exp_date" class="form-control" placeholder="">
            <?php //echo form_error("ju_price"); ?>
        </div>
    </div>
</div>

<div class="row clearfix">                            
    <div class="col-sm-12">
    	<div class="text-center">
        <button type="submit" class="btn btn-primary btn-sm">Save</button>
      <!--   <button type="submit" class="btn btn-outline-secondary">Cancel</button> -->
      </div>
    </div>
</div>
<?php echo form_close(); ?>
</div>
</div>
</div>
</div>
<div class="row clearfix">
<div class="col-lg-12 col-md-12 col-sm-12">
<div class="card">
<div class="header">
<!-- <h2>Import Products From Excel</h2> -->
</div>
<!-- <div class="body">
<?php echo form_open_multipart("admin/import_product"); ?>
<div class="row clearfix">
    <div class="col-sm-8">
      <div class="form-group">
        <span>Excel file (.xlsx, .xls, .csv)</span>
        <input type="file" name="attachment" class="form-control" accept=".xlsx,.xls,.csv" required>
        <small class="text-muted">
          Columns: Product Name, Category, Unit, Brand, Opening Quantity, Buying Price, Retail Price, Wholesale Price, Stock Alert Limit, Expiry Date, Branch ID.
          If a branch is selected above, branch_id can be empty.
          <a href="<?php echo base_url('assets/templates/product_import_template.csv'); ?>" download>Download template</a>
        </small>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="form-group">
        <span>&nbsp;</span>
        <button type="submit" class="btn btn-info btn-block">Import Products</button>
      </div>
    </div>
</div>
<?php echo form_close(); ?>
</div> -->
</div>
</div>
</div>
<div class="row clearfix">
<div class="col-lg-12">
<div class="card">
<div class="header">
  <div class="row">
  <div class="col-lg-6">
      <h2>Product List</b> </h2>
      </div>
       <div class="col-lg-6">
        <div class="pull-right">
       <a href="<?php echo base_url("admin/all_product"); ?>" class="btn btn-primary"><i class="icon-eye"></i>View All product</a>
      </div>
      </div>
      </div>
</div>
<div class="body">
    <div class="table-responsive evamo-product-list-grid">
<table class="table table-hover js-basic-example dataTable table-custom">
            <thead class="thead-primary">
                <tr>
                    <th>Product name</th>
                    <th>Category</th>
                    <th>Branch</th>
                    <th>Reason</th>
                    <th>Buy price</th>
                    <th>Retail Sell price</th>
                    <th>WholeSell price</th>
                    <th>Expire Status</th>
                    <th>Stock Limit</th>
                    <th>Expire Date</th>
                    <th>Action</th>
                </tr>
            </thead>
          
            <tbody>
              <?php foreach ($product as $products): ?>
            <tr>
           
            <td><?php echo $products->name; ?></td>
            <td><?php echo !empty($products->category) ? html_escape($products->category) : '-'; ?></td>
            <td><?php echo !empty($products->branch_name) ? $products->branch_name : '-'; ?></td>
            <td><?php echo (isset($products->reason) && $products->reason !== '') ? ucfirst($products->reason) : 'Purchased'; ?></td>
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
                        <a class="dropdown-item" href="<?php echo base_url("admin/product_stock_history/{$products->id}"); ?>">View Full History</a>
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
<script src="<?php echo base_url('assets/admin/js/select2.min.js'); ?>"></script>

 <script>
        $(document).ready(function () { 
        function validateAtLeastOneSellPrice() {
            var retailInput = document.querySelector('input[name="price"]');
            var wholeInput = document.querySelector('input[name="ju_price"]');
            if (!retailInput || !wholeInput) {
                return true;
            }

            var hasRetail = retailInput.value.trim() !== '';
            var hasWhole = wholeInput.value.trim() !== '';
            var isValid = hasRetail || hasWhole;
            var message = 'Please fill at least one price: Retail Sale price or WholeSale Price.';

            retailInput.setCustomValidity(isValid ? '' : message);
            wholeInput.setCustomValidity(isValid ? '' : message);
            return isValid;
        }

        if ($.fn.select2) {
            $('.evamo-unit-select').select2({
                placeholder: 'Search or select unit',
                allowClear: true,
                width: '100%'
            });
        }

        $('input[name="price"], input[name="ju_price"]').on('input', function(){
            validateAtLeastOneSellPrice();
        });

        $('form').on('submit', function(){
            return validateAtLeastOneSellPrice();
        });

        $("#cont1,#cont2").change(function() {
    $("#total_cont").val ($("#cont1").val() * $("#cont2").val());
            });
        });
    </script>
