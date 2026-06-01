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
                    <div class="row">
                        <div class="col-sm-6">
                    <h2>Change password</h2>
                    </div>
                    <div class="col-sm-6">
                        <div class="pull-right">
                       <!--  <a href="<?php //echo base_url("admin/admin_profile") ?>" class="btn btn-primary"><i class="icon-arrow-left"></i></a> -->
                        </div>
                    </div>
                   
                    </div>
                </div>
                <div class="body">
              <?php echo form_open_multipart("admin/change_password"); ?>
                    <div class="row clearfix">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <span>Old Password</span>
                                <input type="password" value="<?php echo set_value('oldpass') ?>" autocomplete="off"  name="oldpass" required class="form-control" placeholder="******">
                                <?php echo form_error("oldpass"); ?>
                            </div>
                            
                        </div>

                          <div class="col-sm-4">
                            <div class="form-group">
                                <span>New-password</span>
                                                                <div class="evamo-password-field">
                                                                    <input id="newpass-input" type="password" autocomplete="off" name="newpass" value="<?php echo set_value('newpass') ?>" required class="form-control evamo-password-input" placeholder="******">
                                                                    <button type="button" class="evamo-password-toggle" data-target="newpass-input" aria-label="Show password" aria-pressed="false">
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
                                <?php echo form_error("newpass"); ?>
                            </div>
                            
                        </div>
                         <div class="col-sm-4">
                            <div class="form-group">
                                <span>Confirm password</span>
                                <input type="password" autocomplete="off"  name="passconf" value="<?php echo set_value('passconf') ?>" required class="form-control" placeholder="******">
                                <?php echo form_error("passconf"); ?>
                            </div>
                            
                        </div>
                      
                    </div>
                    <div class="row clearfix">                    
                        <div class="col-sm-12">
                          <div class="text-center">
                            <button type="submit" class="btn btn-primary">Change password</button>
                          <a href="<?php echo base_url("admin/setting"); ?>" class="btn btn-info"><i class="icon-arrow-left"></i></a>
                          </div>
                        </div>
                    </div>
                    <?php form_close(); ?>
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
</style>

<script>
(function () {
    var toggleButtons = document.querySelectorAll('.evamo-password-toggle');

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
})();
</script>


<?php include 'incs/footer.php'; ?>
