<?php include('incs/header.php'); ?>
<?php include('incs/nav.php'); ?>
<?php include('incs/side.php'); ?>
<link rel="stylesheet" href="<?php echo base_url('assets/admin/vendor/cropperjs/cropper.min.css'); ?>">


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
<div class="col-lg-12 col-md-12 col-sm-12">
<div class="card">
<div class="header">
  <div class="row">
    <div class="col-md-4">
<h2>Parsonal Information</h2>
</div>

 
</div>
</div>
<div class="body">
<?php
$current_user = is_object($my ?? null) ? (array) $my : [];
$current_user_id = isset($current_user['user_id']) ? $current_user['user_id'] : 0;
$current_full_name = isset($current_user['full_name']) ? $current_user['full_name'] : '';
$current_phone_number = isset($current_user['phone_number']) ? $current_user['phone_number'] : '';
$current_img = isset($current_user['img']) ? $current_user['img'] : '';
?>
<?php echo form_open_multipart("admin/modify_mydata/{$current_user_id}", array('id' => 'profile-settings-form')); ?>
<div class="row clearfix">
       <div class="col-sm-6">
        <div class="form-group">
           <span>Full name</span>
      <input type="text" autocomplete="off" value="<?php echo $current_full_name; ?>" required name="full_name" class="form-control" placeholder="Full name">
            <?php echo form_error("full_name"); ?>
        </div>
    </div>
       <div class="col-sm-6">
      <div class="form-group">
        <span>Phone number</span>
      <input type="number" autocomplete="off" value="<?php echo $current_phone_number; ?>" required name="phone_number" class="form-control" placeholder="Phone number">
            <?php echo form_error("phone_number"); ?>
        </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        <span>Profile picture</span>
        <input type="file" id="profile-image-input" name="img" class="form-control" accept="image/png,image/jpeg,image/jpg,image/gif,image/webp">
        <small class="text-muted">Allowed: JPG, PNG, GIF, WEBP.</small>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        <span>Current picture</span>
        <div>
          <?php if (!empty($current_img)) { ?>
            <img src="<?php echo base_url().'assets/admin/img/'.$current_img; ?>" alt="Profile picture" style="width:64px; height:64px; object-fit:cover; border-radius:9999px; border:1px solid #e5e7eb;">
          <?php } else { ?>
            <img src="<?php echo base_url() ?>assets/admin/img/wateja.png" alt="Profile picture" style="width:64px; height:64px; object-fit:cover; border-radius:9999px; border:1px solid #e5e7eb;">
          <?php } ?>
        </div>
      </div>
    </div>
        <div class="col-sm-3">
     <!--  <div class="form-group">
        <span>Privillage</span>
        <select type="text" class="form-control" required name="role">
          <option value="">Select privillage</option>
          <option>admin</option>
          <option>seller</option>
        </select>
        <?php //echo form_error("role"); ?>
        </div> -->
    </div>
</div>

<div class="row clearfix">                            
    <div class="col-sm-12">
    	<div class="text-center">
        <button type="submit" class="btn btn-primary">Update</button>
     <a href="<?php echo base_url("admin/index"); ?>" class="btn btn-info"><i class="icon-arrow-left"></i></a>
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

</div>

<style>
.evamo-cropper-modal {
  position: fixed;
  inset: 0;
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 2100;
}

.evamo-cropper-modal.is-open {
  display: flex;
}

.evamo-cropper-overlay {
  position: absolute;
  inset: 0;
  background: rgba(2, 6, 23, 0.72);
}

.evamo-cropper-dialog {
  position: relative;
  width: min(92vw, 560px);
  background: #ffffff;
  border: 1px solid #d1d5db;
  border-radius: 12px;
  padding: 12px;
  box-shadow: 0 20px 48px rgba(2, 6, 23, 0.4);
}

.evamo-cropper-box {
  width: 100%;
  min-height: 320px;
  max-height: 62vh;
  border: 1px solid #d1d5db;
  border-radius: 10px;
  overflow: hidden;
  background: #0f172a;
}

.evamo-cropper-actions {
  margin-top: 10px;
  display: flex;
  justify-content: flex-end;
  gap: 8px;
}

html.evamo-dark .evamo-cropper-dialog {
  background: #0f172a;
  border-color: #334155;
}

html.evamo-dark .evamo-cropper-box {
  border-color: #334155;
  background: #020617;
}
</style>

<div id="profile-crop-modal" class="evamo-cropper-modal" aria-hidden="true">
  <div class="evamo-cropper-overlay" data-close-modal="profile"></div>
  <div class="evamo-cropper-dialog" role="dialog" aria-modal="true" aria-label="Crop profile image">
    <div class="evamo-cropper-box">
      <img id="profile-crop-image" alt="Crop profile" style="max-width:100%; display:block;">
    </div>
    <div class="evamo-cropper-actions">
      <button type="button" id="profile-cancel-crop" class="btn btn-sm btn-secondary">Cancel</button>
      <button type="button" id="profile-crop-submit" class="btn btn-sm btn-info">Crop & Upload</button>
    </div>
  </div>
</div>

<script src="<?php echo base_url('assets/admin/vendor/cropperjs/cropper.min.js'); ?>"></script>
<script>
(function () {
  var form = document.getElementById('profile-settings-form');
  var input = document.getElementById('profile-image-input');
  var modal = document.getElementById('profile-crop-modal');
  var image = document.getElementById('profile-crop-image');
  var submitCropBtn = document.getElementById('profile-crop-submit');
  var cancelCropBtn = document.getElementById('profile-cancel-crop');
  var cropper = null;
  var pendingSubmit = false;

  if (!form || !input || typeof Cropper === 'undefined') {
    return;
  }

  function openModal() {
    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
  }

  function closeModal() {
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
  }

  function destroyCropper() {
    if (cropper) {
      cropper.destroy();
      cropper = null;
    }
  }

  function createCropper(src) {
    image.src = src;
    openModal();
    destroyCropper();
    cropper = new Cropper(image, {
      aspectRatio: 1,
      viewMode: 1,
      autoCropArea: 1,
      responsive: true,
      background: false
    });
  }

  function applyCropAndSubmit() {
    return new Promise(function (resolve) {
      if (!cropper) {
        resolve(true);
        return;
      }

      var canvas = cropper.getCroppedCanvas({
        width: 600,
        height: 600,
        imageSmoothingQuality: 'high'
      });

      if (!canvas) {
        resolve(false);
        return;
      }

      canvas.toBlob(function (blob) {
        if (!blob) {
          resolve(false);
          return;
        }

        var file = new File([blob], 'profile-cropped-' + Date.now() + '.png', { type: 'image/png' });
        var dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
        closeModal();
        resolve(true);
      }, 'image/png', 0.95);
    });
  }

  input.addEventListener('change', function () {
    var file = input.files && input.files[0];
    if (!file) {
      return;
    }

    if (!/^image\//i.test(file.type)) {
      return;
    }

    var reader = new FileReader();
    reader.onload = function (e) {
      createCropper(e.target.result);
    };
    reader.readAsDataURL(file);
  });

  submitCropBtn.addEventListener('click', function () {
    pendingSubmit = true;
    applyCropAndSubmit().then(function (ok) {
      if (ok && pendingSubmit) {
        pendingSubmit = false;
        form.submit();
      }
    });
  });

  cancelCropBtn.addEventListener('click', function () {
    input.value = '';
    destroyCropper();
    closeModal();
  });

  modal.addEventListener('click', function (e) {
    var closeTarget = e.target.getAttribute('data-close-modal');
    if (closeTarget === 'profile') {
      input.value = '';
      destroyCropper();
      closeModal();
    }
  });
})();
</script>


<?php include 'incs/footer.php'; ?>
