
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STORE REPORT</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      color: #1f2937;
      background: #ffffff;
    }

    .report-wrap {
      width: 92%;
      margin: 0 auto;
      padding-top: 64px;
    }

    .report-head {
      text-align: center;
      margin-bottom: 12px;
      font-size: 12px;
      line-height: 1.6;
    }

    .table-responsive {
      width: 100%;
      overflow-x: auto;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
      border: 1px solid #e2e8f0;
      font-size: 12px;
    }

    .table th,
    .table td {
      border: 1px solid #e2e8f0;
      text-align: left;
      padding: 7px;
      vertical-align: middle;
      white-space: nowrap;
    }

    .table-hover tbody tr:nth-child(even) {
      background: #f8fafc;
    }

    .thead-primary th {
      background-color: #0f766e;
      color: #ffffff;
      border-color: #0f766e;
      font-weight: 600;
      letter-spacing: 0.2px;
    }

    .summary-row th {
      border: none;
      background: transparent;
      color: #111827;
    }
  </style>
</head>
<body>

<div class="report-wrap">
  <div class="report-head">
    <p>
      <?php echo $shop->shop_name; ?><br>
      <?php echo $shop->po_box; ?> <?php echo $shop->location; ?><br>
      Mob: <?php echo $shop->phone; ?><br>
      Email: <?php echo $shop->email; ?><br>
      <b>STORE REPORT</b>
    </p>
  </div>

  <div class="table-responsive">
    <table class="table table-hover table-custom">
      <thead class="thead-primary">
        <tr>
          <th>S/N</th>
          <th>Product</th>
          <th>Reason</th>
          <th>Brand</th>
          <th>All Product</th>
          <th>Unit</th>
          <th>Transifor Product</th>
          <th>Remaining Product</th>
          <th>Buy Price</th>
          <th>Total Buy</th>
          <th>Retail Sell Price</th>
          <th>Total Retail Sell</th>
          <th>Wholesell price</th>
          <th>Total Wholesell price</th>
        </tr>
      </thead>
      <tbody>
        <?php $sn = 1; ?>
        <?php foreach ($store_product as $store_products): ?>
          <tr>
            <td><?php echo $sn++; ?></td>
            <td><?php echo $store_products->name; ?></td>
            <td><?php echo (isset($store_products->reason) && $store_products->reason !== '') ? ucfirst($store_products->reason) : 'Purchased'; ?></td>
            <td><?php echo $store_products->bland; ?></td>
            <td><?php echo $store_products->quantity_product + $store_products->moved_qnty; ?></td>
            <td><?php echo $store_products->unit; ?></td>
            <td><?php echo $store_products->moved_qnty; ?></td>
            <td><?php echo $store_products->quantity_product; ?></td>
            <td><?php echo number_format($store_products->buy_price); ?></td>
            <td><?php echo number_format($store_products->total_buy_price); ?></td>
            <td><?php echo number_format($store_products->price); ?></td>
            <td><?php echo number_format($store_products->total_sell_price); ?></td>
            <td><?php echo number_format($store_products->ju_price); ?></td>
            <td><?php echo number_format($store_products->total_sellju_price); ?></td>
          </tr>
        <?php endforeach; ?>
        <tr class="summary-row">
          <th></th>
          <th>TOTAL</th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th><?php echo number_format($buy_price->total_buy); ?></th>
          <th></th>
          <th><?php echo number_format($sell_price->total_sell); ?></th>
          <th><?php echo number_format($sell_price->total_sell); ?></th>
          <th><?php echo number_format($whole_sale->total_wholesale); ?></th>
        </tr>
        <tr class="summary-row">
          <th></th>
          <th>PROFIT</th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th><?php echo number_format($sell_price->total_sell - $buy_price->total_buy); ?></th>
          <th></th>
          <th></th>
          <th></th>
        </tr>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>




