<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>Pharmacy Home</title>
    <meta name="description" content="Pharmacy login page">
    <meta name="theme-color" content="#0f766e">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Afyasoft">
    <link rel="icon" href="<?php echo base_url('assets/admin/img/logoo.png'); ?>" type="image/x-icon" />
    <link rel="manifest" href="<?php echo base_url('manifest.webmanifest'); ?>">
    <link rel="apple-touch-icon" href="<?php echo base_url('assets/admin/img/logoo.png'); ?>">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        poppins: ['Poppins', 'Segoe UI', 'sans-serif']
                    }
                }
            }
        };
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        .evamo-bg-layer {
            background-image: linear-gradient(135deg, rgba(15, 23, 42, 0.52), rgba(6, 182, 212, 0.25)), url('<?php echo base_url(); ?>assets/images/background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        body {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-800 evamo-bg-layer">

<div class="font-poppins min-h-screen flex flex-col items-center justify-center py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-6 text-center">
        <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 dark:text-white">
            <span class="text-green-600 dark:text-green-500"><?php echo $shop->shop_name; ?></span>
        </h2>
    </div>

    <div class="w-full max-w-md">
        <div class="bg-white border border-gray-200 rounded-xl shadow-xl dark:bg-gray-900/95 dark:border-gray-700">
            <div class="p-5 sm:p-8">
                <div class="text-center">
                    <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Sign in</h1>
                </div>

                <?php if ($das = $this->session->flashdata('massage')): ?>
                    <div class="mt-4 mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg dark:bg-green-800/10 dark:border-green-900 dark:text-green-500" role="alert">
                        <p class="text-sm"><?php echo $das; ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($das = $this->session->flashdata('ms')): ?>
                    <div class="mt-4 mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg dark:bg-red-800/10 dark:border-red-900 dark:text-red-500" role="alert">
                        <p class="text-sm"><?php echo $das; ?></p>
                    </div>
                <?php endif; ?>

                <?php echo form_open("home/signin", ['class' => 'mt-5 grid gap-y-4']); ?>
                    <div>
                        <label for="phone_number" class="block text-sm mb-2 text-gray-700 dark:text-white">Phone Number</label>
                        <input
                            type="tel"
                            id="phone_number"
                            name="phone_number"
                            value="<?php echo set_value('phone_number'); ?>"
                            class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 dark:placeholder-gray-500 dark:focus:ring-gray-600"
                            required
                            autocomplete="off"
                            placeholder="Enter phone number"
                        >
                        <?php if (form_error('phone_number')): ?>
                            <p class="text-xs text-red-600 mt-2"><?php echo strip_tags(form_error('phone_number')); ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <div class="flex flex-wrap justify-between items-center gap-2">
                            <label for="password" class="block text-sm text-gray-700 dark:text-white">Password</label>
                        </div>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="mt-2 py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 dark:placeholder-gray-500 dark:focus:ring-gray-600"
                            required
                            autocomplete="off"
                            placeholder="******"
                        >
                        <?php if (form_error('password')): ?>
                            <p class="text-xs text-red-600 mt-2"><?php echo strip_tags(form_error('password')); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" class="shrink-0 mt-0.5 border-gray-300 rounded text-cyan-600 focus:ring-cyan-500 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-cyan-500 dark:checked:border-cyan-500 dark:focus:ring-offset-gray-900">
                        <label for="remember-me" class="ms-3 text-sm text-gray-700 dark:text-white">Remember me</label>
                    </div>

                    <button type="submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-cyan-600 text-white hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 disabled:opacity-50 disabled:pointer-events-none">
                        Login
                    </button>
                <?php echo form_close(); ?>

                <div class="mt-6 border-t border-gray-200 pt-5 dark:border-gray-700">
                    <p class="text-center text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Support</p>
                    <a href="https://wa.me/255747384847" target="_blank" rel="noopener noreferrer" class="mt-3 w-full inline-flex items-center justify-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-2.5 text-sm font-semibold text-green-700 transition hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:border-green-900/60 dark:bg-green-900/20 dark:text-green-300 dark:hover:bg-green-900/35 dark:focus:ring-offset-gray-900" aria-label="Unahitaji msaada? Wasiliana WhatsApp">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="h-5 w-5 shrink-0" aria-hidden="true">
                            <circle cx="16" cy="16" r="16" fill="#25D366"/>
                            <path fill="#FFFFFF" d="M26.5 15.8c0-5.7-4.6-10.3-10.3-10.3-5.7 0-10.3 4.6-10.3 10.3 0 1.8.5 3.5 1.4 5l-1.5 5.6 5.8-1.5c1.4.8 2.9 1.2 4.5 1.2h.1c5.7 0 10.3-4.6 10.3-10.3zm-10.3 8.6h-.1c-1.4 0-2.8-.4-4-1.1l-.3-.2-3.4.9.9-3.3-.2-.3a8.5 8.5 0 0 1-1.3-4.5c0-4.7 3.8-8.5 8.5-8.5 2.3 0 4.4.9 6 2.5a8.4 8.4 0 0 1 2.5 6c0 4.7-3.8 8.5-8.6 8.5zm4.7-6.3c-.3-.2-1.6-.8-1.8-.8-.2-.1-.4-.1-.6.2-.2.3-.7.8-.8 1-.2.2-.3.2-.6.1-1.8-.9-3-1.7-4.1-3.8-.1-.3 0-.4.1-.5.1-.1.3-.3.4-.5.1-.2.2-.3.3-.5.1-.2 0-.4 0-.5-.1-.1-.6-1.4-.9-2-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.5.1-.7.3-.2.2-.9.9-.9 2.1s.9 2.4 1 2.6c.1.2 1.8 2.8 4.5 3.9.6.3 1.1.5 1.5.6.6.2 1.2.2 1.7.1.5-.1 1.6-.7 1.8-1.3.2-.6.2-1.1.2-1.2-.1-.2-.3-.2-.6-.4z"/>
                        </svg>
                        Unahitaji msaada? Wasiliana nasi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (prefersDark) {
            document.documentElement.classList.add('dark');
        }
        if (document.documentElement.classList.contains('evamo-dark')) {
            document.documentElement.classList.add('dark');
        }
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

</body>
</html>
