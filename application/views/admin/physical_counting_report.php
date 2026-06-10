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
        <div class="col-lg-6">
            <h2>Physical Counting Report</h2>
        </div>
        <div class="col-lg-6">
            <div class="pull-right">
                <?php
                    $selected_branch_id = isset($selected_branch_id) ? $selected_branch_id : null;
                    $print_query = $selected_branch_id ? '?branch_id=' . (int)$selected_branch_id : '';
                    $selected_branch_name = 'All Branches';
                    if ($selected_branch_id && !empty($branches)) {
                        foreach ($branches as $branch) {
                            if ((int)$selected_branch_id === (int)$branch->branch_id) {
                                $selected_branch_name = $branch->branch_name;
                                break;
                            }
                        }
                    }
                ?>
                <span class="badge badge-info mr-2"><?php echo html_escape($selected_branch_name); ?></span>
                <a href="<?php echo base_url('admin/print_physical_counting_report'.$print_query); ?>" class="btn btn-info btn-sm" target="_blank">
                    <i class="icon-printer"></i> Download PDF
                </a>
            </div>
        </div>
    </div>
</div>
<div class="body">
    <div class="table-responsive">
        <table class="table table-hover js-basic-example dataTable table-custom">
            <thead class="thead-primary">
                <tr>
                    <th>Product Name</th>
                    <th>System Qty</th>
                    <th>Buying Price</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Product Name</th>
                    <th>System Qty</th>
                    <th>Buying Price</th>
                </tr>
            </tfoot>
            <tbody>
                <?php foreach ($product as $item): ?>
                <tr>
                    <td><?php echo html_escape($item->name); ?></td>
                    <td><?php echo number_format((int)$item->balance); ?> <?php echo html_escape($item->unit); ?></td>
                    <td>Tsh.<?php echo number_format((float)$item->buy_price); ?>/=</td>
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
