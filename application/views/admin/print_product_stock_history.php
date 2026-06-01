<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Product Stock History</title>
  <style>
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; }
    .header { text-align: center; margin-bottom: 12px; }
    .sub { margin: 6px 0 10px 0; }
    table { border-collapse: collapse; width: 100%; margin-bottom: 14px; }
    th, td { border: 1px solid #dddddd; padding: 6px; text-align: left; }
    th { background: #f3f4f6; }
  </style>
</head>
<body>
  <div class="header">
    <div><b><?php echo $shop->shop_name; ?></b></div>
    <div><?php echo $shop->po_box; ?> <?php echo $shop->location; ?></div>
    <div>Mob: <?php echo $shop->phone; ?> | Email: <?php echo $shop->email; ?></div>
    <div><b>PRODUCT STOCK HISTORY REPORT</b></div>
  </div>

  <div class="sub">
    <b>Product:</b> <?php echo $product_data->name; ?> (<?php echo $product_data->unit; ?>)
    <br>
    <b>Filter:</b>
    <?php echo !empty($from_date) ? $from_date : 'All'; ?> to <?php echo !empty($to_date) ? $to_date : 'All'; ?>
  </div>

  <table>
    <thead>
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
          <td colspan="4">No daily stock summary found for selected filter.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <table>
    <thead>
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
            <td><?php echo number_format((int)$item->running_balance); ?></td>
            <td><?php echo number_format((int)$item->current_balance); ?></td>
            <td><?php echo !empty($item->full_name) ? $item->full_name : '-'; ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="7">No product history found for selected filter.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
