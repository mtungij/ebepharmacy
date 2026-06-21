<?php include('incs/header.php'); ?>
<?php include('incs/nav.php'); ?>
<?php include('incs/side.php'); ?>

<div id="main-content">
    <div class="container-fluid py-4">
        <?php if ($err = $this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible">
                <a href="" class="close">&times;</a>
                <?php echo $err; ?>
            </div>
        <?php endif; ?>

        <div class="max-w-3xl">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3 rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                <div>
                    <h1 class="m-0 text-xl font-bold text-slate-900">Edit Branch</h1>
                    <p class="m-0 mt-1 text-sm text-slate-500">Update branch information and return to branch list.</p>
                </div>
                <a href="<?php echo base_url('admin/branches'); ?>" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Back</a>
            </div>

            <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <?php echo form_open('admin/update_branch/'.(int)$branch->branch_id); ?>
                    <input type="hidden" name="branch_id" value="<?php echo (int)$branch->branch_id; ?>">

                    <div class="mb-4">
                        <label for="branch-name" class="mb-2 block text-sm font-bold text-slate-700">Branch Name</label>
                        <input id="branch-name" type="text" name="branch_name" value="<?php echo set_value('branch_name', $branch->branch_name); ?>" required autocomplete="off" class="form-control">
                        <?php echo form_error('branch_name'); ?>
                    </div>

                    <div class="mb-4">
                        <label for="branch-location" class="mb-2 block text-sm font-bold text-slate-700">Location</label>
                        <input id="branch-location" type="text" name="location" value="<?php echo set_value('location', $branch->location); ?>" required autocomplete="off" class="form-control">
                        <?php echo form_error('location'); ?>
                    </div>

                    <div class="mb-4">
                        <label for="branch-phone" class="mb-2 block text-sm font-bold text-slate-700">Phone</label>
                        <input id="branch-phone" type="text" name="phone" value="<?php echo set_value('phone', $branch->phone); ?>" required autocomplete="off" class="form-control">
                        <?php echo form_error('phone'); ?>
                    </div>

                    <div class="mb-4">
                        <label for="branch-email" class="mb-2 block text-sm font-bold text-slate-700">Email</label>
                        <input id="branch-email" type="email" name="email" value="<?php echo set_value('email', $branch->email); ?>" autocomplete="off" class="form-control">
                        <?php echo form_error('email'); ?>
                    </div>

                    <div class="mb-5">
                        <label for="branch-status" class="mb-2 block text-sm font-bold text-slate-700">Status</label>
                        <?php $status = set_value('status', $branch->status ?: 'open'); ?>
                        <select id="branch-status" name="status" required class="form-control">
                            <option value="open" <?php echo $status === 'open' ? 'selected' : ''; ?>>Open</option>
                            <option value="closed" <?php echo $status === 'closed' ? 'selected' : ''; ?>>Closed</option>
                        </select>
                        <?php echo form_error('status'); ?>
                    </div>

                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <a href="<?php echo base_url('admin/branches'); ?>" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Cancel</a>
                        <button type="submit" class="rounded-md bg-cyan-700 px-4 py-2 text-sm font-semibold text-white hover:bg-cyan-800">Update Branch</button>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<?php include 'incs/footer.php'; ?>
