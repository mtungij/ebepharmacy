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
<div class="card">
<div class="header">
  <div class="row">
    <div class="col-md-6">
<h2>profile picture</h2>
</div>
   <div class="col-md-6">
    <div class="pull-right">
   <a href="<?php echo base_url("seller/setting"); ?>" class="btn btn-info btn-sm"><i class="icon-arrow-left"></i></a>
   </div>
</div>
</div>
</div>
<div class="body">
<?php echo form_open_multipart("seller/modify_profilepc/{$my->user_id}"); ?>
<div class="row clearfix">
       <div class="col-sm-4">
        <?php if ($my->img == TRUE) {
         ?>
        <img src="<?php echo base_url().'assets/admin/img/'.$my->img; ?>" class="rounded-circle user-photo" style="width:200px; height:200px">
      <?php }elseif ($my->img == FALSE) {
       ?>
    <img src="<?php echo base_url() ?>assets/admin/img/wateja.png" class="rounded-circle user-photo" style="width:180px; height:180px">
       <?php } ?>
    </div>
       <div class="col-sm-6">
        <br><br>
      <div class="form-group">
        <span>profile picture</span>
            <input type="file" autocomplete="off" required name="img" class="form-control evamo-passport-input" accept="image/*" capture="environment" placeholder="Phone number">
            <?php echo form_error("img"); ?>
            <small class="text-muted">On small devices, camera opens first. Crop then submit automatically.</small>
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
    <!--  <a href="<?php //echo base_url("admin/index"); ?>" class="btn btn-info"><i class="icon-arrow-left"></i></a> -->
      </div>
    </div>
</div>
<?php form_close(); ?>
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
  var isSmall = window.matchMedia('(max-width: 767.98px)').matches;

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

      if (isSmall) {
        form.submit();
      }
    }, 'image/jpeg', 0.92);
  });

  cancelBtn.addEventListener('click', closeModal);
  modal.querySelector('.evamo-cropper-overlay').addEventListener('click', closeModal);
})();
</script>
