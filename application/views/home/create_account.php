<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>Create Account - helixPos</title>
    <meta name="theme-color" content="#0f766e">
    <link rel="icon" href="<?php echo base_url('assets/images/helixpos.png'); ?>" type="image/png" />
    <link rel="manifest" href="<?php echo base_url('manifest.webmanifest'); ?>">
    <link rel="apple-touch-icon" href="<?php echo base_url('assets/images/helixpos.png'); ?>">

    <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/tailwind-local.css?v=20260611a'); ?>">
    <style>
        .evamo-bg-layer {
            background-image: linear-gradient(135deg, rgba(15, 23, 42, 0.58), rgba(6, 182, 212, 0.25)), url('<?php echo base_url(); ?>assets/images/background.jpg');
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

<div class="font-poppins min-h-screen flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-3xl">
        <div class="mb-5 text-center">
            <h1 class="text-3xl sm:text-4xl font-bold text-white">Create account</h1>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-xl dark:bg-gray-900/95 dark:border-gray-700">
            <div class="p-5 sm:p-8">
                <?php if ($das = $this->session->flashdata('ms')): ?>
                    <div class="mb-5 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg dark:bg-red-800/10 dark:border-red-900 dark:text-red-500" role="alert">
                        <p class="text-sm"><?php echo $das; ?></p>
                    </div>
                <?php endif; ?>

                <?php echo form_open('home/register_account', ['class' => 'grid gap-y-5']); ?>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 dark:text-white">Shop information</h2>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="shop_name" class="block text-sm mb-2 text-gray-700 dark:text-white">Shop Name</label>
                                <input id="shop_name" type="text" name="shop_name" value="<?php echo set_value('shop_name'); ?>" required autocomplete="off" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300" placeholder="Shop name">
                                <?php echo form_error('shop_name'); ?>
                            </div>

                            <div>
                                <label for="branch_name" class="block text-sm mb-2 text-gray-700 dark:text-white">Main Branch</label>
                                <input id="branch_name" type="text" name="branch_name" value="<?php echo set_value('branch_name', 'Main Branch'); ?>" required autocomplete="off" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300" placeholder="Main Branch">
                                <?php echo form_error('branch_name'); ?>
                            </div>

                            <div>
                                <label for="location" class="block text-sm mb-2 text-gray-700 dark:text-white">Location</label>
                                <input id="location" type="text" name="location" value="<?php echo set_value('location'); ?>" required autocomplete="off" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300" placeholder="Location">
                                <?php echo form_error('location'); ?>
                            </div>

                            <div>
                                <label for="shop_phone" class="block text-sm mb-2 text-gray-700 dark:text-white">Shop Phone</label>
                                <input id="shop_phone" type="tel" name="shop_phone" value="<?php echo set_value('shop_phone'); ?>" required autocomplete="off" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300" placeholder="Shop phone">
                                <?php echo form_error('shop_phone'); ?>
                            </div>

                            <div>
                                <label for="po_box" class="block text-sm mb-2 text-gray-700 dark:text-white">P.O Box</label>
                                <input id="po_box" type="text" name="po_box" value="<?php echo set_value('po_box'); ?>" autocomplete="off" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300" placeholder="P.O Box">
                            </div>

                            <div>
                                <label for="email" class="block text-sm mb-2 text-gray-700 dark:text-white">Email</label>
                                <input id="email" type="email" name="email" value="<?php echo set_value('email'); ?>" autocomplete="off" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300" placeholder="Email">
                                <?php echo form_error('email'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-5 dark:border-gray-700">
                        <h2 class="text-lg font-bold text-gray-800 dark:text-white">Admin account</h2>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="admin_name" class="block text-sm mb-2 text-gray-700 dark:text-white">Admin Full Name</label>
                                <input id="admin_name" type="text" name="admin_name" value="<?php echo set_value('admin_name'); ?>" required autocomplete="off" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300" placeholder="Full name">
                                <?php echo form_error('admin_name'); ?>
                            </div>

                            <div>
                                <label for="phone_number" class="block text-sm mb-2 text-gray-700 dark:text-white">Admin Phone Number</label>
                                <input id="phone_number" type="tel" name="phone_number" value="<?php echo set_value('phone_number'); ?>" required autocomplete="off" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300" placeholder="Login phone number">
                                <?php echo form_error('phone_number'); ?>
                            </div>

                            <div>
                                <label for="password" class="block text-sm mb-2 text-gray-700 dark:text-white">Password</label>
                                <input id="password" type="password" name="password" required autocomplete="new-password" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300" placeholder="Password">
                                <?php echo form_error('password'); ?>
                            </div>

                            <div>
                                <label for="confirm_password" class="block text-sm mb-2 text-gray-700 dark:text-white">Confirm Password</label>
                                <input id="confirm_password" type="password" name="confirm_password" required autocomplete="new-password" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300" placeholder="Confirm password">
                                <?php echo form_error('confirm_password'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-5 sm:flex-row sm:items-center sm:justify-between dark:border-gray-700">
                        <a href="<?php echo base_url('home/index'); ?>" class="inline-flex justify-center items-center rounded-lg border border-gray-200 px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800">
                            Back to login
                        </a>
                        <button type="submit" class="inline-flex justify-center items-center rounded-lg border border-transparent bg-cyan-600 px-5 py-3 text-sm font-semibold text-white hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                            Create Account
                        </button>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (prefersDark || document.documentElement.classList.contains('evamo-dark')) {
            document.documentElement.classList.add('dark');
        }
    })();
</script>
</body>
</html>
