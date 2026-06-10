<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Sales Profit Report</title>
</head>
<body>
<style>
body {
  font-family: arial, sans-serif;
  color: #0f172a;
}
.pull {
  text-align: center;
  margin: 30px 48px 18px 48px;
}
.report-title {
  color: #0f766e;
  font-size: 18px;
  font-weight: bold;
  letter-spacing: 0.5px;
  text-transform: uppercase;
  margin: 8px 0 4px 0;
}
.report-meta {
  color: #334155;
  font-size: 12px;
  margin: 0;
}
.summary-table {
  margin-bottom: 14px;
}
.summary-table td {
  border: 1px solid #99f6e4;
  background: #f0fdfa;
  color: #0f172a;
  font-size: 12px;
  font-weight: bold;
  padding: 8px;
  width: 33.33%;
}
table {
  border-collapse: collapse;
  width: 100%;
}
td, th {
  border: 1px solid #cbd5e1;
  text-align: left;
  padding: 6px;
  font-size: 11px;
}
th {
  background: #0f766e;
  color: #ffffff;
  font-weight: bold;
  text-transform: uppercase;
}
tr:nth-child(even) {
  background-color: #f0fdfa;
}
.text-right {
  text-align: right;
}
</style>

<div class="pull">
  <?php
    $selected_branch_name = 'All Branches';
    if (!empty($selected_branch_id) && !empty($branches)) {
      foreach ($branches as $branch) {
        if ((int)$selected_branch_id === (int)$branch->branch_id) {
          $selected_branch_name = $branch->branch_name;
          break;
        }
      }
    }
  ?>
  <p style="font-size:12px;">
    <?php echo $shop->shop_name; ?><br>
    <?php echo $shop->po_box; ?> <?php echo $shop->location; ?><br>
    Mob: <?php echo $shop->phone; ?>
  </p>
  <p class="report-title">Sales & Profit Report</p>
  <p class="report-meta">Branch: <?php echo html_escape($selected_branch_name); ?> | Period: <?php echo html_escape($from); ?> to <?php echo html_escape($to); ?> | Printed: <?php echo date('Y-m-d'); ?></p>
</div>

<table class="summary-table">
  <tr>
    <td>Total Product Sold: <?php echo number_format((float)(isset($totals->qty_sold) ? $totals->qty_sold : 0)); ?></td>
    <td>Total Sales Amount: Tsh.<?php echo number_format((float)(isset($totals->sales_amount) ? $totals->sales_amount : 0)); ?>/=</td>
    <td>Total Profit: Tsh.<?php echo number_format((float)(isset($totals->profit) ? $totals->profit : 0)); ?>/=</td>
  </tr>
</table>

<table>
  <tr>
    <th>S/No.</th>
    <th>Date</th>
    <th>Branch</th>
    <th>Product Sold</th>
    <th>Qty Sold</th>
    <th>Sales Amount</th>
    <th>Profit</th>
    <th>Sale Type</th>
  </tr>
  <?php $no = 1; ?>
  <?php foreach ($sales as $item): ?>
  <?php $sale_type = strtolower((string)$item->sell_status) === 'whole' ? 'Wholesale' : 'Retail'; ?>
  <tr>
    <td><?php echo $no++; ?></td>
    <td><?php echo date('Y-m-d', strtotime($item->sell_day)); ?></td>
    <td><?php echo html_escape($item->branch_name); ?></td>
    <td><?php echo html_escape($item->product_name); ?></td>
    <td class="text-right"><?php echo number_format((float)$item->qty_sold); ?> <?php echo html_escape($item->unit); ?></td>
    <td class="text-right">Tsh.<?php echo number_format((float)$item->sales_amount); ?>/=</td>
    <td class="text-right">Tsh.<?php echo number_format((float)$item->profit); ?>/=</td>
    <td><?php echo $sale_type; ?></td>
  </tr>
  <?php endforeach; ?>
</table>

</body>
</html>
