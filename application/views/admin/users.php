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
<div class="col-lg-12 col-md-12 col-sm-12">
<div class="card">
<div class="header">
<h2>Register Users</h2>
</div>
<div class="body">
<?php echo form_open_multipart("admin/create_admin"); ?>
<div class="row clearfix">
    <div class="col-sm-4">
        <div class="form-group">
            <span class="evamo-user-form-label">Full name</span>
            <input type="text" autocomplete="off" required name="full_name" value="<?php echo set_value('full_name'); ?>" class="form-control" placeholder="Full name">
            <?php echo form_error("full_name"); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <span class="evamo-user-form-label">Phone number</span>
            <input type="number" autocomplete="off" required name="phone_number" value="<?php echo set_value('phone_number'); ?>" class="form-control" placeholder="phone number">
            <?php echo form_error("phone_number"); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <span class="evamo-user-form-label">Privillage</span>
            <select class="form-control" required name="role" id="user-role-select">
                <option value="">Select privillage</option>
                <option value="admin" <?php echo set_select('role', 'admin'); ?>>admin</option>
                <option value="seller" <?php echo set_select('role', 'seller'); ?>>seller</option>
                <option value="cashier" <?php echo set_select('role', 'cashier'); ?>>cashier</option>
            </select>
            <?php echo form_error("role"); ?>
        </div>
    </div>
</div>

<div class="row clearfix evamo-system-access" id="seller-system-access" style="display: none;">
    <div class="col-sm-12">
        <div class="form-group">
            <span class="evamo-user-form-label">System Access</span>
            <div class="evamo-access-options">
                <label class="fancy-checkbox evamo-access-option">
                    <input type="checkbox" name="system_access[]" value="seller" <?php echo set_checkbox('system_access[]', 'seller'); ?>>
                    <span>SELLER</span>
                </label>
                <label class="fancy-checkbox evamo-access-option">
                    <input type="checkbox" name="system_access[]" value="product" <?php echo set_checkbox('system_access[]', 'product'); ?>>
                    <span>MANAGE PRODUCT</span>
                </label>
                <label class="fancy-checkbox evamo-access-option">
                    <input type="checkbox" name="system_access[]" value="store" <?php echo set_checkbox('system_access[]', 'store'); ?>>
                    <span>MANAGE STORE</span>
                </label>
            </div>
            <small class="text-muted">Select seller modules this user can access.</small>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-sm-6">
        <div class="form-group">
            <span class="evamo-user-form-label">Password</span>
            <div class="evamo-password-field">
                <input id="register-password" type="password" autocomplete="new-password" required name="password" class="form-control evamo-password-input" placeholder="Enter password">
                <button type="button" class="evamo-password-toggle" data-target="register-password" aria-label="Show password" aria-pressed="false">
                    <svg class="evamo-eye-open" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M2 12s3.6-6 10-6 10 6 10 6-3.6 6-10 6S2 12 2 12z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"></circle>
                    </svg>
                    <svg class="evamo-eye-closed" viewBox="0 0 24 24" fill="none" aria-hidden="true" style="display:none;">
                        <path d="M3 3l18 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                        <path d="M10.6 10.7a2 2 0 102.8 2.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                        <path d="M9.9 5.1A11 11 0 0112 5c6.4 0 10 7 10 7a19 19 0 01-3.6 4.3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M6.2 6.2A18.5 18.5 0 002 12s3.6 6 10 6c1.6 0 3-.3 4.3-.9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>
            <small class="text-muted">Use at least 4 characters.</small>
            <?php echo form_error("password"); ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <span class="evamo-user-form-label">Confirm password</span>
            <div class="evamo-password-field">
                <input id="register-confirm-password" type="password" autocomplete="new-password" required name="confirm_password" class="form-control evamo-password-input" placeholder="Confirm password">
                <button type="button" class="evamo-password-toggle" data-target="register-confirm-password" aria-label="Show password" aria-pressed="false">
                    <svg class="evamo-eye-open" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M2 12s3.6-6 10-6 10 6 10 6-3.6 6-10 6S2 12 2 12z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"></circle>
                    </svg>
                    <svg class="evamo-eye-closed" viewBox="0 0 24 24" fill="none" aria-hidden="true" style="display:none;">
                        <path d="M3 3l18 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                        <path d="M10.6 10.7a2 2 0 102.8 2.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                        <path d="M9.9 5.1A11 11 0 0112 5c6.4 0 10 7 10 7a19 19 0 01-3.6 4.3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M6.2 6.2A18.5 18.5 0 002 12s3.6 6 10 6c1.6 0 3-.3 4.3-.9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>
            <small class="text-muted">Must match the password above.</small>
            <?php echo form_error("confirm_password"); ?>
        </div>
    </div>
</div>

<div class="row clearfix">                            
    <div class="col-sm-12">
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Register</button>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
</div>
</div>
</div>
</div>
<div class="row clearfix">
<div class="col-lg-12">
<div class="card">
<div class="header">
      <h2>Users list</b> </h2>
</div>
<div class="body">
    <div class="table-responsive">
<table class="table table-hover js-basic-example dataTable table-custom">
            <thead class="thead-primary">
                <tr>
                    <th>Profile</th>
                    <th>Full name</th>
                    <th>Phone number</th>
                    <th>Privillage</th>
                    <th>System Access</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Profile</th>
                    <th>Full name</th>
                    <th>Phone number</th>
                    <th>Privillage</th>
                                        <th>System Access</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </tfoot>
            <tbody>
              <?php foreach ($admin as $admins): ?>
                        <?php $user_access = isset($privillage_map[$admins->user_id]) ? $privillage_map[$admins->user_id] : array(); ?>
            <tr>
            <td>
              <?php if ($admins->img == TRUE) {
               ?>
            <img src="<?php echo base_url().'assets/admin/img/'.$admins->img; ?>" class="rounded-circle user-photo" style="width:60px; height:60px">
             <?php }elseif ($admins->img == FALSE) {
            ?>
            <img src="<?php echo base_url() ?>assets/admin/img/wateja.png" class="rounded-circle user-photo" style="width:60px; height:60px">
            <?php  }  ?>
            </td>
            <td><?php echo $admins->full_name; ?></td>
            <td><?php echo $admins->phone_number; ?></td>
            <td><?php echo $admins->role; ?></td>
                        <td>
                            <?php if ($admins->role === 'seller'): ?>
                                <?php if (!empty($user_access)): ?>
                                    <?php foreach ($user_access as $access): ?>
                                        <span class="badge badge-info evamo-access-badge">
                                            <?php
                                                if ($access === 'seller') {
                                                    echo 'SELLER';
                                                } elseif ($access === 'product') {
                                                    echo 'MANAGE PRODUCT';
                                                } elseif ($access === 'store') {
                                                    echo 'MANAGE STORE';
                                                } else {
                                                    echo strtoupper($access);
                                                }
                                            ?>
                                        </span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-muted">No access set</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
            <td><?php echo substr($admins->created_at, 0,10); ?></td>
            <td>
<div class="btn-group" role="group" aria-label="Button group with nested dropdown">

<div class="btn-group" role="group">
    <button id="btnGroupDrop1" type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
   Action
    </button>
    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
    <a class="dropdown-item" href="<?php echo base_url("admin/edit_user/{$admins->user_id}"); ?>">Edit</a>
    <a class="dropdown-item" href="<?php echo base_url("admin/delete_user/{$admins->user_id}"); ?>" onclick="return confirm('Are you sure?')">Delete</a>
     <a class="dropdown-item" href="<?php echo base_url("admin/privillage/{$admins->user_id}"); ?>">User Privilage</a>
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

<style>
.evamo-password-field {
    position: relative;
}

.evamo-user-form-label {
    display: inline-block;
    margin-bottom: 6px;
}

.evamo-system-access {
    margin-bottom: 4px;
}

.evamo-access-options {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    margin-top: 8px;
}

.evamo-access-option {
    margin-bottom: 0;
}

.evamo-access-option span {
    display: inline-block;
}

.evamo-access-badge {
    display: inline-block;
    margin: 2px 6px 2px 0;
}

.evamo-password-input {
    padding-right: 2.5rem;
}

.evamo-password-toggle {
    position: absolute;
    top: 50%;
    right: 0.6rem;
    transform: translateY(-50%);
    border: 0;
    background: transparent;
    color: #6b7280;
    width: 28px;
    height: 28px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.evamo-password-toggle svg {
    width: 18px;
    height: 18px;
}

html.evamo-dark .evamo-password-toggle {
    color: #9ca3af;
}

html.evamo-dark .evamo-user-form-label {
    color: #ffffff;
}

html.evamo-dark .evamo-access-option span {
    color: #ffffff;
}
</style>

<script>
(function () {
    var toggleButtons = document.querySelectorAll('.evamo-password-toggle');
    var roleSelect = document.getElementById('user-role-select');
    var systemAccessBlock = document.getElementById('seller-system-access');

    function toggleSystemAccess() {
        if (!roleSelect || !systemAccessBlock) {
            return;
        }

        systemAccessBlock.style.display = roleSelect.value === 'seller' ? 'block' : 'none';
    }

    toggleButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var targetId = btn.getAttribute('data-target');
            var input = document.getElementById(targetId);
            if (!input) {
                return;
            }

            var openIcon = btn.querySelector('.evamo-eye-open');
            var closedIcon = btn.querySelector('.evamo-eye-closed');
            var show = input.type === 'password';

            input.type = show ? 'text' : 'password';
            btn.setAttribute('aria-pressed', show ? 'true' : 'false');
            btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');

            if (openIcon && closedIcon) {
                openIcon.style.display = show ? 'none' : 'block';
                closedIcon.style.display = show ? 'block' : 'none';
            }
        });
    });

    if (roleSelect) {
        roleSelect.addEventListener('change', toggleSystemAccess);
        toggleSystemAccess();
    }
})();
</script>


<?php include 'incs/footer.php'; ?>

