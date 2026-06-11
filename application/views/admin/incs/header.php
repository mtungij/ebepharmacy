<!doctype html>
<html lang="en">


<!-- Mirrored from www.wrraptheme.com/templates/lucid/hospital/light/app-appointment.html by HTTraQt Website Copier/1.x [Karbofos 2012-2017] J2, 22 Mac 2020 06:00:00 GMT -->
<head>
<title>helixPos - Admin</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="description" content="Lucid Bootstrap 4.1.1 Admin Template">
<meta name="theme-color" content="#0f766e">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="helixPos">
<link rel="icon" href="<?php echo base_url('assets/images/helixpos.png'); ?>" type="image/png" />
<link rel="manifest" href="<?php echo base_url('manifest.webmanifest'); ?>">
<link rel="apple-touch-icon" href="<?php echo base_url('assets/images/helixpos.png'); ?>">
<meta name="author" content="WrapTheme, design by: ThemeMakker.com">

<script>
(function () {
	try {
		var savedTheme = localStorage.getItem('evamo-theme');
		var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
		if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
			document.documentElement.classList.add('evamo-dark');
		}
	} catch (e) {}
})();
</script>
<script>
(function () {
	if ('serviceWorker' in navigator) {
		window.addEventListener('load', function () {
			navigator.serviceWorker.register('<?php echo base_url('sw.js'); ?>').catch(function () {});
		});
	}
})();
</script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/aset/vendor/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/aset/vendor/jquery-datatable/dataTables.bootstrap4.min.css">

<!-- MAIN CSS -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/main.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/color_skins.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/tailwind-bridge.css?v=20260601n">
<style>
.evamo-live-filter {
	display: flex;
	align-items: flex-end;
	flex-wrap: wrap;
	gap: 10px;
	margin-bottom: 16px;
}

.evamo-live-filter label {
	margin-bottom: 6px;
	font-weight: 600;
	color: #4a5568;
}

.evamo-live-filter .evamo-filter-field {
	display: flex;
	flex-direction: column;
	min-width: 220px;
	flex: 1 1 220px;
}

.evamo-live-filter .evamo-filter-actions {
	display: flex;
	align-items: center;
	gap: 8px;
	flex-wrap: wrap;
}

.evamo-live-filter .form-control {
	min-height: 38px;
}

@media (max-width: 575.98px) {
	.evamo-live-filter {
		display: block;
	}

	.evamo-live-filter .evamo-filter-field,
	.evamo-live-filter .evamo-filter-actions {
		width: 100%;
		margin-bottom: 10px;
	}

	.evamo-live-filter .evamo-filter-actions .btn {
		width: 100%;
		margin-right: 0 !important;
	}
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
	document.querySelectorAll('.evamo-live-filter select[data-live-submit="1"]').forEach(function (select) {
		select.addEventListener('change', function () {
			var clearTarget = select.getAttribute('data-clear-target');
			if (clearTarget) {
				var target = select.form ? select.form.querySelector('[name="' + clearTarget + '"]') : null;
				if (target) {
					target.value = '';
				}
			}
			if (select.form) {
				select.form.submit();
			}
		});
	});
});
</script>
</head>
<body class="theme-cyan">

<!-- Page Loader -->
<!-- <div class="page-loader-wrapper">
<div class="loader">
<div class="m-t-30"><img src="<?php //echo base_url() ?>assets/admin/img/got.png" width="400" height="250" alt="Lucid"></div>
<p>Please wait...</p>        
</div>
</div> -->
<!-- Overlay For Sidebars -->

<div id="wrapper">
