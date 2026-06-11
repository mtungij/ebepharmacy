<?php include('incs/header.php'); ?>
<?php include('incs/nav.php'); ?>
<?php include('incs/side.php'); ?>

<style>
    .select2-container .select2-selection--single{
    height:34px !important;
}
.select2-container--default .select2-selection--single{
         border: 1px solid #ccc !important; 
     border-radius: 0px !important; 
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
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h2>User Information</h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-custom">
                                    <tbody>
                                        <tr>
                                            <th style="width: 180px;">Full Name</th>
                                            <td><?php echo html_escape($cutomer->full_name); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Phone Number</th>
                                            <td><?php echo html_escape($cutomer->phone_number); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Role</th>
                                            <td><?php echo html_escape($cutomer->role); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Branch</th>
                                            <td><?php echo !empty($cutomer->branch_name) ? html_escape($cutomer->branch_name) : '-'; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Registered Date</th>
                                            <td><?php echo !empty($cutomer->created_at) ? html_escape(substr($cutomer->created_at, 0, 10)) : '-'; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h2>Edit Access (<?php echo html_escape($cutomer->full_name); ?>)</h2>
                        </div>
                        <div class="body">
                            <?php $user_access = isset($user_access) ? $user_access : array(); ?>
                            <?php if ($cutomer->role === 'seller'): ?>
                            <?php echo form_open("admin/create_privillage/{$user_id}"); ?>
                                <div class="form-group">
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" name="privillage[]" value="seller" <?php echo in_array('seller', $user_access) ? 'checked' : ''; ?>>
                                        <span>SELLER</span>
                                    </label>
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" name="privillage[]" value="product" <?php echo in_array('product', $user_access) ? 'checked' : ''; ?>>
                                        <span>MANAGE PRODUCT</span>
                                    </label>
                                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" name="privillage[]" value="store" <?php echo in_array('store', $user_access) ? 'checked' : ''; ?>>
                                        <span>MANAGE STORE</span>
                                    </label>
                                </div>
                               
                              
                                <br>
                                <div class="text-center">
                                <button type="submit" class="btn btn-primary">Update Access</button>
                                <a href="<?php echo base_url("admin/users") ?>" class="btn btn-info"><i class="icon-arrow-left"></i></a>
                                </div>
                            <?php echo form_close(); ?>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    Access privileges are only for seller users.
                                </div>
                                <div class="text-center">
                                    <a href="<?php echo base_url("admin/users") ?>" class="btn btn-info"><i class="icon-arrow-left"></i></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if ($cutomer->role === 'seller'): ?>
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h2>Current Access</h2>
                        </div>
                        <div class="body">
                                <div class="form-group">
                                    <?php if ($priv): ?>
                                    <?php foreach ($priv as $privs): ?>
                                    <span class="badge badge-info" style="margin: 0 6px 8px 0; padding: 8px 10px;">
                                        <?php if ($privs->privillage == 'seller') {
                                             ?>
                                          SELLER
                                         <?php }elseif ($privs->privillage == 'store') {
                                          ?>
                                         MANAGE STORE
                                      <?php }elseif ($privs->privillage == 'product') {
                                       ?>
                                       MANAGE PRODUCT
                                       <?php } ?>
                                        <a href="<?php echo base_url("admin/remove_privillage/{$privs->id}") ?>" onclick="return confirm('Remove this access?')" style="color:#fff; margin-left:8px;">x</a>
                                    </span>
                                   <?php endforeach; ?>
                                   <?php else: ?>
                                    <p style="color:red;">No Privillage</p>
                                    <?php endif; ?> 
                                   
                                </div>
                             
                               
                                
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>


</div>
</div>

</div>

<?php include 'incs/footer.php'; ?>



