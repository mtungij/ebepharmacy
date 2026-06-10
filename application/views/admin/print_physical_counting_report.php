<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Physical Counting Report</title>
</head>
<body>
<style>
body {
  font-family: arial, sans-serif;
  color: #0f172a;
}
.pull {
  text-align: center;
  margin: 34px 56px 18px 56px;
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
.manual-cell {
  height: 24px;
}
.signature-row {
  margin-top: 34px;
  width: 100%;
}
.signature-row td {
  border: none;
  padding: 8px 0;
  width: 50%;
  font-size: 12px;
  background: #ffffff;
}
.signature-line {
  border-bottom: 1px solid #0f766e;
  display: inline-block;
  width: 220px;
  height: 18px;
}
.date-line {
  border-bottom: 1px solid #0f766e;
  display: inline-block;
  width: 120px;
  height: 18px;
}
.signature-title {
  color: #0f766e;
  font-weight: bold;
  text-transform: uppercase;
  margin-top: 28px;
  margin-bottom: 8px;
  font-size: 12px;
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
  <p class="report-title">Physical Counting Report</p>
  <p class="report-meta">Branch: <?php echo html_escape($selected_branch_name); ?> | Printed: <?php echo date('Y-m-d'); ?></p>
</div>

<table>
  <tr>
    <th>S/No.</th>
    <th>Product Name</th>
    <th>System Qty</th>
    <th>Buying Price</th>
    <th>Physical Qty</th>
    <th>Variance</th>
    <th>Variance Value</th>
    <th>Remarks</th>
  </tr>
  <?php $no = 1; ?>
  <?php foreach ($product as $item): ?>
  <tr>
    <td><?php echo $no++; ?></td>
    <td><?php echo html_escape($item->name); ?></td>
    <td><?php echo number_format((int)$item->balance); ?> <?php echo html_escape($item->unit); ?></td>
    <td><?php echo number_format((float)$item->buy_price); ?>/=</td>
    <td class="manual-cell"></td>
    <td class="manual-cell"></td>
    <td class="manual-cell"></td>
    <td class="manual-cell"></td>
  </tr>
  <?php endforeach; ?>
</table>

<div class="signature-title">Verification</div>
<table class="signature-row">
  <tr>
    <td>Checked By: <span class="signature-line"></span></td>
    <td>Date: <span class="date-line"></span></td>
  </tr>
  <tr>
    <td>Signature: <span class="signature-line"></span></td>
    <td></td>
  </tr>
  <tr>
    <td>Approved By: <span class="signature-line"></span></td>
    <td>Date: <span class="date-line"></span></td>
  </tr>
  <tr>
    <td>Signature: <span class="signature-line"></span></td>
    <td></td>
  </tr>
</table>

</body>
</html>
