<?php include('incs/header.php'); ?>
<link rel="stylesheet" href="<?php echo base_url('assets/admin/vendor/cropperjs/cropper.min.css'); ?>">
<style>
.evamo-passport-wrap {
  width: 100%;
  padding: 6px 6px 18px;
}

.evamo-passport-card {
  border-radius: 14px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
  overflow: hidden;
}

.evamo-passport-head {
  background: linear-gradient(135deg, #0f766e, #0891b2);
  color: #ffffff;
  padding: 16px 20px;
}

.evamo-passport-logo-wrap {
  margin-bottom: 12px;
  text-align: center;
}

.evamo-passport-logo {
  width: 76px;
  height: 76px;
  border-radius: 999px;
  border: 3px solid rgba(255, 255, 255, 0.85);
  object-fit: cover;
  box-shadow: 0 8px 18px rgba(2, 6, 23, 0.28);
}

.evamo-passport-head h2 {
  margin: 0;
  font-size: 24px;
  font-weight: 700;
}

.evamo-passport-head p {
  margin: 6px 0 0;
  opacity: 0.92;
  font-size: 13px;
}

.evamo-passport-body {
  padding: 22px 20px;
}

.evamo-avatar-box {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 210px;
  height: 210px;
  border-radius: 999px;
  border: 3px solid #ccfbf1;
  background: #f8fafc;
  overflow: hidden;
}

.evamo-avatar-box img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.evamo-passport-label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: #0f172a;
}

.evamo-passport-input {
  min-height: 44px;
}

.evamo-passport-help {
  display: block;
  margin-top: 7px;
  color: #64748b;
}

.evamo-passport-submit {
  min-width: 220px;
  min-height: 44px;
  font-weight: 600;
}

@media (max-width: 991.98px) {
  .evamo-passport-head h2 {
    font-size: 21px;
  }

  .evamo-avatar-box {
    width: 180px;
    height: 180px;
  }
}

@media (max-width: 767.98px) {
  .evamo-passport-wrap {
    padding: 2px 0 14px;
  }

  .evamo-passport-card {
    border-radius: 10px;
  }

  .evamo-passport-head {
    padding: 14px 14px;
  }

  .evamo-passport-body {
    padding: 16px 14px;
  }

  .evamo-avatar-box {
    width: 160px;
    height: 160px;
  }

  .evamo-passport-submit {
    width: 100%;
  }

  .evamo-passport-logo {
    width: 66px;
    height: 66px;
  }
}
</style>

<div id="main-content">
<div class="container-fluid">
<div class="evamo-passport-wrap">
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
<?php if ($das = $this->session->flashdata('ms')): ?>
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
<div class="card evamo-passport-card">
<div class="evamo-passport-head">
<div class="evamo-passport-logo-wrap">
  <img src="<?php echo base_url('assets/admin/img/afyasoft.jpg'); ?>" alt="Afyasoft logo" class="evamo-passport-logo">
</div>
<h2>Upload Passport Picture</h2>
<p>Please complete this step once to continue to your seller dashboard.</p>
</div>
<div class="evamo-passport-body">
<?php echo form_open_multipart("seller/modify_profilepc/{$my->user_id}"); ?>
<div class="row clearfix align-items-center">
<div class="col-lg-4 col-md-4 col-sm-12 text-center" style="margin-bottom:14px;">
  <div class="evamo-avatar-box">
  <?php if ($my->img == TRUE) { ?>
    <img src="<?php echo base_url().'assets/admin/img/'.$my->img; ?>" alt="Current profile picture">
  <?php } else { ?>
    <img src="<?php echo base_url() ?>assets/admin/img/wateja.png" alt="Default profile picture">
  <?php } ?>
  </div>
</div>
<div class="col-lg-8 col-md-8 col-sm-12">
  <div class="form-group">
    <label class="evamo-passport-label">Profile picture</label>
    <input type="file" autocomplete="off" required name="img" class="form-control evamo-passport-input" accept="image/*" capture="environment" placeholder="Phone number">
    <?php echo form_error("img"); ?>
    <small class="evamo-passport-help">On small devices, camera opens first. Crop then submit automatically.</small>
  </div>
  <div style="margin-top:14px;">
    <button type="submit" class="btn btn-primary evamo-passport-submit">Crop & Continue</button>
  </div>
</div>
</div>
<?php echo form_close(); ?>
</div>
</div>
</div>
</div>

<div id="evamo-cropper-modal" class="evamo-cropper-modal" aria-hidden="true">
  <div class="evamo-cropper-overlay"></div>
  <div class="evamo-cropper-dialog" role="dialog" aria-modal="true" aria-label="Crop image">
    <div class="evamo-cropper-body">
      <img id="evamo-cropper-image" alt="Crop image" style="max-width:100%; display:block;">
    </div>
    <div class="evamo-cropper-actions">
      <button type="button" id="evamo-crop-cancel" class="btn btn-secondary btn-sm">Cancel</button>
      <button type="button" id="evamo-crop-apply" class="btn btn-primary btn-sm">Crop & Continue</button>
    </div>
  </div>
</div>

</div>

</div>
</div>

</div>


<?php include 'incs/footer.php'; ?>
<script src="<?php echo base_url('assets/admin/vendor/cropperjs/cropper.min.js'); ?>"></script>
<style>
.evamo-cropper-modal {
  position: fixed;
  inset: 0;
  display: none;
  z-index: 9999;
}

.evamo-cropper-modal.is-open {
  display: block;
}

.evamo-cropper-overlay {
  position: absolute;
  inset: 0;
  background: rgba(15, 23, 42, 0.65);
}

.evamo-cropper-dialog {
  position: relative;
  width: min(92vw, 520px);
  margin: 5vh auto;
  background: #ffffff;
  border-radius: 12px;
  padding: 12px;
}

.evamo-cropper-body {
  max-height: 70vh;
  overflow: hidden;
}

.evamo-cropper-actions {
  display: flex;
  justify-content: flex-end;
  gap: 8px;
  margin-top: 10px;
}
</style>
<script>
(function () {
  var input = document.querySelector('input[name="img"]');
  var form = input ? input.closest('form') : null;
  var modal = document.getElementById('evamo-cropper-modal');
  var image = document.getElementById('evamo-cropper-image');
  var applyBtn = document.getElementById('evamo-crop-apply');
  var cancelBtn = document.getElementById('evamo-crop-cancel');
  var cropper = null;
  var objectUrl = '';

  if (!input || !form || !modal || !image || !applyBtn || !cancelBtn || typeof Cropper === 'undefined') {
    return;
  }

  function closeModal() {
    modal.classList.remove('is-open');
    if (cropper) {
      cropper.destroy();
      cropper = null;
    }
    if (objectUrl) {
      URL.revokeObjectURL(objectUrl);
      objectUrl = '';
    }
    image.removeAttribute('src');
  }

  input.addEventListener('change', function () {
    var file = input.files && input.files[0] ? input.files[0] : null;
    if (!file || !file.type.match(/^image\//i)) {
      return;
    }

    objectUrl = URL.createObjectURL(file);
    image.src = objectUrl;
    modal.classList.add('is-open');

    if (cropper) {
      cropper.destroy();
    }

    cropper = new Cropper(image, {
      aspectRatio: 1,
      viewMode: 1,
      dragMode: 'move',
      autoCropArea: 1,
      responsive: true,
      background: false
    });
  });

  applyBtn.addEventListener('click', function () {
    if (!cropper) {
      return;
    }

    cropper.getCroppedCanvas({
      width: 600,
      height: 600,
      imageSmoothingQuality: 'high'
    }).toBlob(function (blob) {
      if (!blob) {
        return;
      }

      var dt = new DataTransfer();
      dt.items.add(new File([blob], 'passport-' + Date.now() + '.jpg', { type: 'image/jpeg' }));
      input.files = dt.files;
      closeModal();
      form.submit();
    }, 'image/jpeg', 0.92);
  });

  cancelBtn.addEventListener('click', closeModal);
  modal.querySelector('.evamo-cropper-overlay').addEventListener('click', closeModal);
})();
</script>
