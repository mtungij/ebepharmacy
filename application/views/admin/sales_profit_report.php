<?php include('incs/header.php'); ?>
<?php include('incs/nav.php'); ?>
<?php include('incs/side.php'); ?>

<div id="main-content">
<div class="container-fluid">
<br>

<div class="row clearfix">
<div class="col-lg-12">
<div class="card">
<div class="header">
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <h2>Sales & Profit Report</h2>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="pull-right">
                <?php
                    $selected_branch_id = isset($selected_branch_id) ? $selected_branch_id : null;
                    $selected_branch_name = 'All Branches';
                    if ($selected_branch_id && !empty($branches)) {
                        foreach ($branches as $branch) {
                            if ((int)$selected_branch_id === (int)$branch->branch_id) {
                                $selected_branch_name = $branch->branch_name;
                                break;
                            }
                        }
                    }
                    $print_query = '?from=' . urlencode($from) . '&to=' . urlencode($to);
                    if ($selected_branch_id) {
                        $print_query .= '&branch_id=' . (int)$selected_branch_id;
                    }
                ?>
                <span class="badge badge-info mr-2"><?php echo html_escape($selected_branch_name); ?></span>
                <a href="<?php echo base_url('admin/print_sales_profit_report'.$print_query); ?>" class="btn btn-info btn-sm" target="_blank">
                    <i class="icon-printer"></i> Download PDF
                </a>
            </div>
        </div>
    </div>
</div>

<div class="body">
    <style>
        .sales-profit-summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 18px;
        }
        .sales-profit-metric {
            border: 1px solid #d7f7f4;
            background: #f0fdfa;
            border-radius: 6px;
            padding: 14px 16px;
        }
        .sales-profit-metric small {
            display: block;
            color: #5b6876;
            margin-bottom: 6px;
        }
        .sales-profit-metric h4 {
            color: #0f766e;
            font-weight: 700;
            margin: 0;
        }
        @media (max-width: 767px) {
            .sales-profit-summary {
                grid-template-columns: 1fr;
            }
            .pull-right {
                float: none !important;
                margin-top: 12px;
            }
        }
    </style>

    <form method="get" action="<?php echo base_url('admin/sales_profit_report'); ?>" class="evamo-live-filter">
        <div class="evamo-filter-field">
            <label>From</label>
            <input type="date" name="from" value="<?php echo html_escape($from); ?>" class="form-control" data-live-submit="1">
        </div>
        <div class="evamo-filter-field">
            <label>To</label>
            <input type="date" name="to" value="<?php echo html_escape($to); ?>" class="form-control" data-live-submit="1">
        </div>
        <?php if ($selected_branch_id): ?>
            <input type="hidden" name="branch_id" value="<?php echo (int)$selected_branch_id; ?>">
        <?php endif; ?>
        <div class="evamo-filter-actions">
            <button type="submit" class="btn btn-primary"><i class="icon-magnifier"></i> Filter</button>
            <a href="<?php echo base_url('admin/sales_profit_report'); ?>" class="btn btn-secondary">Today</a>
        </div>
    </form>

    <div class="sales-profit-summary">
        <div class="sales-profit-metric">
            <small>Total Product Sold</small>
            <h4><?php echo number_format((float)(isset($totals->qty_sold) ? $totals->qty_sold : 0)); ?></h4>
        </div>
        <div class="sales-profit-metric">
            <small>Total Sales Amount</small>
            <h4>Tsh.<?php echo number_format((float)(isset($totals->sales_amount) ? $totals->sales_amount : 0)); ?>/=</h4>
        </div>
        <div class="sales-profit-metric">
            <small>Total Profit</small>
            <h4>Tsh.<?php echo number_format((float)(isset($totals->profit) ? $totals->profit : 0)); ?>/=</h4>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover js-basic-example dataTable table-custom">
            <thead class="thead-primary">
                <tr>
                    <th>Date</th>
                    <th>Branch</th>
                    <th>Product Sold</th>
                    <th>Qty Sold</th>
                    <th>Sales Amount</th>
                    <th>Profit</th>
                    <th>Sale Type</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Date</th>
                    <th>Branch</th>
                    <th>Product Sold</th>
                    <th>Qty Sold</th>
                    <th>Sales Amount</th>
                    <th>Profit</th>
                    <th>Sale Type</th>
                </tr>
            </tfoot>
            <tbody>
                <?php foreach ($sales as $item): ?>
                <?php
                    $sale_type = strtolower((string)$item->sell_status) === 'whole' ? 'Wholesale' : 'Retail';
                    $badge_class = $sale_type === 'Wholesale' ? 'badge-success' : 'badge-info';
                ?>
                <tr>
                    <td><?php echo date('Y-m-d', strtotime($item->sell_day)); ?></td>
                    <td><?php echo html_escape($item->branch_name); ?></td>
                    <td><?php echo html_escape($item->product_name); ?></td>
                    <td><?php echo number_format((float)$item->qty_sold); ?> <?php echo html_escape($item->unit); ?></td>
                    <td>Tsh.<?php echo number_format((float)$item->sales_amount); ?>/=</td>
                    <td>Tsh.<?php echo number_format((float)$item->profit); ?>/=</td>
                    <td><span class="badge <?php echo $badge_class; ?>"><?php echo $sale_type; ?></span></td>
                </tr>
                <?php endforeach; ?>
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
