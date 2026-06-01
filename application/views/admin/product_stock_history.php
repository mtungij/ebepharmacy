<?php include('incs/header.php'); ?>
<?php include('incs/nav.php'); ?>
<?php include('incs/side.php'); ?>

<style>
.evamo-history-filter .form-group {
    margin-bottom: 0;
}

.evamo-history-filter .evamo-filter-label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #4a5568;
}

.evamo-history-actions {
    display: flex;
    flex-wrap: nowrap;
    gap: 8px;
    align-items: center;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.evamo-history-actions .btn {
    white-space: nowrap;
    flex: 0 0 auto;
}

.evamo-history-table-wrap {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.evamo-history-table {
    min-width: 720px;
}

@media (max-width: 767.98px) {
    .evamo-history-actions {
        flex-wrap: wrap;
    }

    .evamo-history-actions .btn {
        flex: 1 1 auto;
    }

    .evamo-history-filter .evamo-filter-action-col {
        padding-top: 0 !important;
    }

    .evamo-mobile-date-row .form-group {
        margin-bottom: 8px;
    }

    .evamo-mobile-content-spacing {
        padding-bottom: 0;
    }
}
</style>

<div id="main-content">
<div class="container-fluid">
<br>

<div class="row clearfix">
<div class="col-lg-12 col-md-12 col-sm-12">
<div class="card">
<div class="header">
<h2>Product History: <?php echo $product_data->name; ?> (<?php echo $product_data->unit; ?>)</h2>
</div>
<div class="body evamo-mobile-content-spacing">
    <form method="get" action="<?php echo base_url('admin/product_stock_history/'.$product_data->id); ?>" class="evamo-history-filter">
        <div class="row clearfix evamo-mobile-date-row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="evamo-filter-label">From Date</label>
                    <input type="date" name="from" class="form-control" value="<?php echo !empty($from_date) ? $from_date : ''; ?>">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="evamo-filter-label">To Date</label>
                    <input type="date" name="to" class="form-control" value="<?php echo !empty($to_date) ? $to_date : ''; ?>">
                </div>
            </div>
            <div class="col-sm-6 evamo-filter-action-col" style="padding-top: 30px;">
                <div class="evamo-history-actions">
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    <a href="<?php echo base_url('admin/print_product_stock_history/'.$product_data->id.'?from='.$from_date.'&to='.$to_date); ?>" class="btn btn-danger btn-sm" target="_blank">Download PDF</a>
                    <a href="<?php echo base_url('admin/stock_balance_history'); ?>" class="btn btn-info btn-sm">Back to balance</a>
                </div>
            </div>
        </div>
    </form>

    <div class="table-responsive evamo-history-table-wrap" style="margin-top: 14px;">
        <table class="table table-hover table-custom evamo-history-table">
            <thead class="thead-primary">
                <tr>
                    <th>Date</th>
                    <th>Stock In</th>
                    <th>Stock Out</th>
                    <th>Net Change</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($daily_summary)): ?>
                    <?php foreach ($daily_summary as $day): ?>
                    <?php $net_change = (int)$day->total_in - (int)$day->total_out; ?>
                    <tr>
                        <td><?php echo !empty($day->mov_date) ? date('Y/m/d', strtotime($day->mov_date)) : '-'; ?></td>
                        <td><?php echo number_format((int)$day->total_in); ?></td>
                        <td><?php echo number_format((int)$day->total_out); ?></td>
                        <td><?php echo number_format($net_change); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No daily stock summary found for selected filter.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="table-responsive evamo-history-table-wrap">
        <table class="table table-hover table-custom evamo-history-table">
            <thead class="thead-primary">
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Stock In</th>
                    <th>Stock Out</th>
                    <th>Running Total</th>
                    <th>Stock Now</th>
                    <th>User</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($history)): ?>
                    <?php foreach ($history as $item): ?>
                    <tr>
                        <td><?php echo !empty($item->mov_date) ? date('Y/m/d', strtotime($item->mov_date)) : '-'; ?></td>
                        <td><?php echo $item->mov_status; ?></td>
                        <td><?php echo number_format((int)$item->qty_in); ?></td>
                        <td><?php echo number_format((int)$item->qty_out); ?></td>
                        <td><b><?php echo number_format((int)$item->running_balance); ?></b></td>
                        <td><?php echo number_format((int)$item->current_balance); ?></td>
                        <td><?php echo !empty($item->full_name) ? $item->full_name : '-'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No product history found for selected filter.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>
</div>

</div>
</div>

<?php include 'incs/footer.php'; ?>
