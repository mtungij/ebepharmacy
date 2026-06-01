<!doctype html>
<html lang="en">


<!-- Mirrored from www.wrraptheme.com/templates/lucid/hospital/light/app-appointment.html by HTTraQt Website Copier/1.x [Karbofos 2012-2017] J2, 22 Mac 2020 06:00:00 GMT -->
<head>
<title>Pharmacy - Admin</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="description" content="Lucid Bootstrap 4.1.1 Admin Template">
<meta name="theme-color" content="#0f766e">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="Afyasoft">
<link rel="icon" href="<?php echo base_url() ?>assets/out/assets/images/fundi.png" type="image/x-icon" />
<link rel="manifest" href="<?php echo base_url('manifest.webmanifest'); ?>">
<link rel="apple-touch-icon" href="<?php echo base_url('assets/admin/img/afyasoft.jpg'); ?>">
<meta name="author" content="WrapTheme, design by: ThemeMakker.com">

<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
	theme: {
		extend: {
			colors: {
				primary: {
					50: '#f0fdfa',
					100: '#ccfbf1',
					200: '#99f6e4',
					300: '#5eead4',
					400: '#2dd4bf',
					500: '#14b8a6',
					600: '#0d9488',
					700: '#0f766e',
					800: '#115e59',
					900: '#134e4a'
				}
			}
		}
	}
}
</script>
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