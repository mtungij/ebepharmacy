<?php include('incs/header.php'); ?>
<?php include('incs/nav.php'); ?>
<?php include('incs/side.php'); ?>
<style>
.evamo-history-table-wrap {
    margin-top: 14px;
}

.evamo-history-filter .form-group {
    margin-bottom: 0;
}

.evamo-history-filter .evamo-filter-label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #4a5568;
}

.evamo-history-filter .evamo-history-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    justify-content: flex-start;
}

.evamo-history-filter .evamo-history-actions .btn {
    min-width: 94px;
}

.evamo-stock-table {
    min-width: 760px;
}

.evamo-stock-table td {
    vertical-align: middle;
}

.evamo-stock-table .btn {
    white-space: nowrap;
}

@media (max-width: 767.98px) {
    .evamo-history-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .evamo-history-actions .btn {
        flex: 1 1 auto;
    }

    .evamo-history-filter .evamo-filter-action-col {
        padding-top: 0 !important;
    }

    .evamo-stock-table th,
    .evamo-stock-table td {
        white-space: nowrap;
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
<h2>Stock Balance History</h2>
</div>
<div class="body">
    <form method="get" action="<?php echo base_url('admin/stock_balance_history'); ?>" class="evamo-history-filter">
        <div class="row clearfix">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="evamo-filter-label">Filter Product</label>
                    <select name="product_id" class="form-control">
                        <option value="">All Products</option>
                        <?php foreach ($product as $row): ?>
                        <option value="<?php echo $row->product_id; ?>" <?php echo ((string)$selected_product_id === (string)$row->product_id) ? 'selected' : ''; ?>>
                            <?php echo $row->name; ?> (<?php echo $row->unit; ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 evamo-filter-action-col" style="padding-top: 30px;">
                <div class="evamo-history-actions">
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    <a href="<?php echo base_url('admin/stock_balance_history'); ?>" class="btn btn-secondary btn-sm">Reset</a>
                </div>
            </div>
        </div>
    </form>

    <div class="table-responsive evamo-history-table-wrap">
        <table class="table table-hover table-custom evamo-stock-table">
            <thead class="thead-primary">
                <tr>
                    <th>S/N</th>
                    <th>Product</th>
                    <th>Reason</th>
                    <th>Current Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($product)): ?>
                    <?php $sn = 1; ?>
                    <?php foreach ($product as $item): ?>
                    <?php if ($selected_product_id !== null && (int)$item->product_id !== (int)$selected_product_id) { continue; } ?>
                    <tr>
                        <td><strong><?php echo $sn++; ?></strong></td>
                        <td><?php echo $item->name; ?> (<?php echo $item->unit; ?>)</td>
                        <td><?php echo (isset($item->reason) && $item->reason !== '') ? ucfirst($item->reason) : 'Purchased'; ?></td>
                        <td><strong><?php echo number_format((int)$item->balance); ?></strong></td>
                        <td>
                            <a href="<?php echo base_url('admin/product_stock_history/'.$item->product_id); ?>" class="btn btn-primary btn-sm">View Full History</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No products found.</td>
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
