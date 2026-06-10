<?php include('incs/header.php'); ?>
<?php include('incs/nav.php'); ?>
<?php include('incs/side.php'); ?>

<style>
    .branches-page {
        padding: 22px 0 34px;
    }

    .branches-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 16px;
        padding: 18px 20px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06);
    }

    .branches-header h1 {
        margin: 0;
        color: #111827;
        font-size: 22px;
        font-weight: 800;
    }

    .branches-header p {
        margin: 4px 0 0;
        color: #6b7280;
        font-size: 13px;
    }

    .branches-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 360px;
        gap: 16px;
        align-items: start;
    }

    .branches-panel {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .branches-panel-head {
        padding: 15px 16px;
        border-bottom: 1px solid #eef2f7;
    }

    .branches-panel-head h2 {
        margin: 0;
        color: #111827;
        font-size: 15px;
        font-weight: 800;
    }

    .branches-panel-body {
        padding: 16px;
    }

    .branches-table {
        margin-bottom: 0;
    }

    .branches-table thead th {
        border-top: 0;
        border-bottom: 1px solid #e5e7eb;
        color: #6b7280;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .branches-table tbody td {
        vertical-align: middle;
        color: #374151;
        font-size: 13px;
    }

    .branch-badge {
        display: inline-flex;
        align-items: center;
        min-height: 24px;
        padding: 0 8px;
        border-radius: 999px;
        background: #ecfdf5;
        color: #047857;
        font-size: 11px;
        font-weight: 800;
    }

    .branch-status {
        color: #0f766e;
        font-weight: 800;
        text-transform: capitalize;
    }

    .branch-field {
        margin-bottom: 14px;
    }

    .branch-field label {
        display: block;
        margin-bottom: 6px;
        color: #374151;
        font-size: 12px;
        font-weight: 800;
    }

    .branch-field .form-control {
        min-height: 42px;
        border-color: #d1d5db;
        border-radius: 8px;
        box-shadow: none;
    }

    .branch-save-btn {
        width: 100%;
        min-height: 42px;
        border: 0;
        border-radius: 8px;
        background: #0f766e;
        color: #ffffff;
        font-size: 14px;
        font-weight: 800;
    }

    .branch-save-btn:hover,
    .branch-save-btn:focus {
        background: #115e59;
        color: #ffffff;
    }

    .branch-maintenance {
        margin-top: 16px;
    }

    .branch-maintenance p {
        margin: 6px 0 14px;
        color: #6b7280;
        font-size: 13px;
        line-height: 1.5;
    }

    .branch-backfill-btn {
        width: 100%;
        min-height: 42px;
        border: 1px solid #f59e0b;
        border-radius: 8px;
        background: #fffbeb;
        color: #92400e;
        font-size: 14px;
        font-weight: 800;
    }

    .branch-backfill-btn:hover,
    .branch-backfill-btn:focus {
        background: #fef3c7;
        color: #78350f;
    }

    @media (max-width: 991.98px) {
        .branches-layout {
            grid-template-columns: 1fr;
        }
    }
</style>

<div id="main-content">
    <div class="container-fluid branches-page">
        <?php if ($das = $this->session->flashdata('massage')): ?>
            <div class="alert alert-success alert-dismissible">
                <a href="" class="close">&times;</a>
                <?php echo $das; ?>
            </div>
        <?php endif; ?>

        <?php if ($err = $this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible">
                <a href="" class="close">&times;</a>
                <?php echo $err; ?>
            </div>
        <?php endif; ?>

        <div class="branches-header">
            <div>
                <h1>Branches</h1>
                <p>Register and view shop branches.</p>
            </div>
        </div>

        <div class="branches-layout">
            <div class="branches-panel">
                <div class="branches-panel-head">
                    <h2>Branch List</h2>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-custom branches-table">
                        <thead>
                            <tr>
                                <th>Branch</th>
                                <th>Location</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($branches)) { ?>
                                <?php foreach ($branches as $branch) { ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo $branch->branch_name; ?></strong>
                                            <?php if ((int) $branch->is_main === 1) { ?>
                                                <span class="branch-badge">Main</span>
                                            <?php } ?>
                                        </td>
                                        <td><?php echo $branch->location; ?></td>
                                        <td><?php echo $branch->phone; ?></td>
                                        <td><?php echo $branch->email; ?></td>
                                        <td><span class="branch-status"><?php echo $branch->status; ?></span></td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No branches registered yet.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <div class="branches-panel">
                    <div class="branches-panel-head">
                        <h2>Add New Branch</h2>
                    </div>
                    <div class="branches-panel-body">
                        <?php echo form_open('admin/create_branch'); ?>
                            <div class="branch-field">
                                <label for="branch-name">Branch Name</label>
                                <input id="branch-name" type="text" name="branch_name" value="<?php echo set_value('branch_name'); ?>" required autocomplete="off" class="form-control" placeholder="Branch name">
                                <?php echo form_error('branch_name'); ?>
                            </div>

                            <div class="branch-field">
                                <label for="branch-location">Location</label>
                                <input id="branch-location" type="text" name="location" value="<?php echo set_value('location'); ?>" required autocomplete="off" class="form-control" placeholder="Location">
                                <?php echo form_error('location'); ?>
                            </div>

                            <div class="branch-field">
                                <label for="branch-phone">Phone</label>
                                <input id="branch-phone" type="text" name="phone" value="<?php echo set_value('phone'); ?>" required autocomplete="off" class="form-control" placeholder="Phone number">
                                <?php echo form_error('phone'); ?>
                            </div>

                            <div class="branch-field">
                                <label for="branch-email">Email</label>
                                <input id="branch-email" type="email" name="email" value="<?php echo set_value('email'); ?>" autocomplete="off" class="form-control" placeholder="Email">
                                <?php echo form_error('email'); ?>
                            </div>

                            <input type="hidden" name="status" value="open">

                            <button type="submit" class="branch-save-btn">Save Branch</button>
                        <?php echo form_close(); ?>
                    </div>
                </div>

                <div class="branches-panel branch-maintenance">
                    <div class="branches-panel-head">
                        <h2>Existing Data</h2>
                    </div>
                    <div class="branches-panel-body">
                        <p>Assign records without a branch to the Main Branch.</p>
                        <?php echo form_open('admin/backfill_branch_data'); ?>
                            <button type="submit" class="branch-backfill-btn" onclick="return confirm('Assign all old records without branch to Main Branch?');">Backfill to Main Branch</button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'incs/footer.php'; ?>
