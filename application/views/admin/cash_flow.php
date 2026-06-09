<?php include('incs/header.php'); ?>
<?php include('incs/nav.php'); ?>
<?php include('incs/side.php'); ?>
<?php
    $expense_total = 0;
    $today_total = 0;
    $today_count = 0;
    $expense_count = (is_array($data_cash) || $data_cash instanceof Countable) ? count($data_cash) : 0;

    foreach ($data_cash as $cash_row) {
        $amount = (float) ($cash_row->price ?? 0);
        $expense_total += $amount;

        if (!empty($cash_row->created) && date('Y-m-d', strtotime($cash_row->created)) === date('Y-m-d')) {
            $today_total += $amount;
            $today_count++;
        }
    }
?>
<script src="<?php echo base_url('assets/admin/js/jquery.js'); ?>"></script>

<style>
    .cashflow-page {
        padding: 22px 0 34px;
    }

    .cashflow-hero {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 16px;
        padding: 18px 20px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06);
    }

    .cashflow-title {
        margin: 0;
        color: #111827;
        font-size: 22px;
        font-weight: 800;
    }

    .cashflow-subtitle {
        margin: 4px 0 0;
        color: #6b7280;
        font-size: 13px;
    }

    .cashflow-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .cashflow-action {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        min-height: 38px;
        padding: 0 12px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        background: #ffffff;
        color: #374151;
        font-size: 13px;
        font-weight: 700;
    }

    .cashflow-action:hover,
    .cashflow-action:focus {
        color: #0f766e;
        border-color: #99f6e4;
        background: #f0fdfa;
        text-decoration: none;
    }

    .cashflow-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 16px;
    }

    .cashflow-stat {
        padding: 14px 16px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
    }

    .cashflow-stat span {
        display: block;
        color: #6b7280;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .cashflow-stat strong {
        display: block;
        margin-top: 6px;
        color: #111827;
        font-size: 20px;
        font-weight: 800;
    }

    .cashflow-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 360px;
        gap: 16px;
        align-items: start;
    }

    .cashflow-panel {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .cashflow-panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 15px 16px;
        border-bottom: 1px solid #eef2f7;
    }

    .cashflow-panel-head h2 {
        margin: 0;
        color: #111827;
        font-size: 15px;
        font-weight: 800;
    }

    .cashflow-panel-head span {
        color: #6b7280;
        font-size: 12px;
        font-weight: 600;
    }

    .cashflow-panel-body {
        padding: 16px;
    }

    .cashflow-form-field {
        margin-bottom: 14px;
    }

    .cashflow-form-field label {
        display: block;
        margin-bottom: 6px;
        color: #374151;
        font-size: 12px;
        font-weight: 800;
    }

    .cashflow-form-field .form-control {
        min-height: 42px;
        border-color: #d1d5db;
        border-radius: 8px;
        box-shadow: none;
    }

    .cashflow-form-field textarea.form-control {
        min-height: 118px;
        resize: vertical;
    }

    .cashflow-save-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        min-height: 42px;
        border: 0;
        border-radius: 8px;
        background: #0f766e;
        color: #ffffff;
        font-size: 14px;
        font-weight: 800;
    }

    .cashflow-save-btn:hover,
    .cashflow-save-btn:focus {
        background: #115e59;
        color: #ffffff;
    }

    .cashflow-table {
        margin-bottom: 0;
    }

    .cashflow-table thead th {
        border-top: 0;
        border-bottom: 1px solid #e5e7eb;
        color: #6b7280;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .cashflow-table tbody td {
        vertical-align: middle;
        color: #374151;
        font-size: 13px;
    }

    .cashflow-amount {
        color: #b91c1c;
        font-weight: 800;
        white-space: nowrap;
    }

    .cashflow-edit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #eff6ff;
        color: #1d4ed8;
    }

    .cashflow-edit-btn:hover,
    .cashflow-edit-btn:focus {
        background: #dbeafe;
        color: #1e40af;
        text-decoration: none;
    }

    .cashflow-alert {
        border-radius: 8px;
        border: 1px solid #bbf7d0;
        background: #f0fdf4;
        color: #166534;
        font-weight: 700;
    }

    @media (max-width: 991.98px) {
        .cashflow-layout {
            grid-template-columns: 1fr;
        }

        .cashflow-stats {
            grid-template-columns: 1fr;
        }
    }
</style>

<div id="main-content">
    <div class="container-fluid cashflow-page">
        <?php if ($das = $this->session->flashdata('massage')): ?>
            <div class="alert cashflow-alert alert-dismissible">
                <a href="" class="close">&times;</a>
                <?php echo $das; ?>
            </div>
        <?php endif; ?>

        <div class="cashflow-hero">
            <div>
                <h1 class="cashflow-title">Expenses</h1>
                <p class="cashflow-subtitle">Record daily spending and review cash flow activity.</p>
            </div>
            <div class="cashflow-actions">
                <a href="<?php echo base_url("admin/today_cashflow"); ?>" class="cashflow-action">
                    <i class="icon-clock"></i>
                    <span>Today</span>
                </a>
                <a href="<?php echo base_url("admin/genel_cashflow"); ?>" class="cashflow-action">
                    <i class="icon-calendar"></i>
                    <span>General</span>
                </a>
            </div>
        </div>

        <div class="cashflow-stats">
            <div class="cashflow-stat">
                <span>Today Expenses</span>
                <strong><?php echo number_format($today_total); ?>/=</strong>
            </div>
            <div class="cashflow-stat">
                <span>Today Records</span>
                <strong><?php echo number_format($today_count); ?></strong>
            </div>
            <div class="cashflow-stat">
                <span>All Expenses</span>
                <strong><?php echo number_format($expense_total); ?>/=</strong>
            </div>
        </div>

        <div class="cashflow-layout">
            <div class="cashflow-panel">
                <div class="cashflow-panel-head">
                    <div>
                        <h2>Expense History</h2>
                        <span><?php echo number_format($expense_count); ?> records</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom cashflow-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_cash as $data_cashs): ?>
                                <tr>
                                    <td><?php echo $data_cashs->full_name; ?></td>
                                    <td><?php echo $data_cashs->description; ?></td>
                                    <td><span class="cashflow-amount"><?php echo number_format($data_cashs->price); ?>/=</span></td>
                                    <td><?php echo date('F, j, Y, g:i a', strtotime($data_cashs->created)); ?></td>
                                    <td>
                                        <?php if ($data_cashs->role == 'admin') { ?>
                                            <a href="<?php echo base_url("admin/edit_cashflow/{$data_cashs->id}"); ?>" class="cashflow-edit-btn" title="Edit">
                                                <i class="icon-pencil"></i>
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="cashflow-panel">
                <div class="cashflow-panel-head">
                    <div>
                        <h2>Add Expense</h2>
                        <span><?php echo date('F j, Y'); ?></span>
                    </div>
                </div>
                <div class="cashflow-panel-body">
                    <?php echo form_open_multipart("admin/create_useToday"); ?>
                        <div class="cashflow-form-field">
                            <label for="cashflow-price">Amount</label>
                            <input id="cashflow-price" type="number" autocomplete="off" required name="price" class="form-control" placeholder="0">
                            <?php echo form_error("price"); ?>
                            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                        </div>

                        <div class="cashflow-form-field">
                            <label for="cashflow-description">Description</label>
                            <textarea id="cashflow-description" rows="4" autocomplete="off" required name="description" class="form-control" placeholder="What was paid for?"></textarea>
                            <?php echo form_error("description"); ?>
                        </div>

                        <button type="submit" class="cashflow-save-btn">
                            <i class="icon-check"></i>
                            <span>Save Expense</span>
                        </button>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url() ?>assets/admin/js/jquery.js"></script>
<?php include 'incs/footer.php'; ?>
