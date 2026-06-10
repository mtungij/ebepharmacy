
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Empty product Report</title>
</head>
<body>

<div id="container">
 <!--  <div style='width: 100%;align-items: center; display: flex;justify-content:space-between;flex-direction: row;'>
 </div> -->
  <style>
    .pull{
    text-align: center;
    margin-top: 100px;
    margin-bottom: 0px;
    margin-right: 150px;
    margin-left: 80px;

    }
  </style>
  <style>
    .display{
      display: flex;
      
    }
  </style>

       <div class="pull">
       <?php
        $selected_branch_name = 'All Branches';
        if (!empty($selected_branch_id) && !empty($branches)) {
          foreach ($branches as $branch) {
            if ((int) $selected_branch_id === (int) $branch->branch_id) {
              $selected_branch_name = $branch->branch_name;
              break;
            }
          }
        }
       ?>
       <p style="font-size:12px;"> <?php echo $shop->shop_name; ?><br>
        <?php echo $shop->po_box; ?> <?php echo $shop->location; ?> <br>
        Mob: <?php echo $shop->phone; ?>
        </p> 
         <p style="font-size:12px;"><b>EMPTY PRODUCT REPORT</b></p>
         <p style="font-size:12px;">Branch: <?php echo html_escape($selected_branch_name); ?></p>
       </div>

     
 
  <div id="body">
  <style> 
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 5px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}

</style>
</head>
<body>



<table>
  <tr>
    <th style="font-size:12px;">S/No.</th>
    <th style="font-size:12px;">Product name</th>
    <th style="font-size:12px;">Branch</th>
    <th style="font-size:12px;">Balance</th>
    <th style="font-size:12px;">Buying Price</th>
  </tr>
    <?php $no = 1; ?>
  <?php foreach ($empty_prdData as $empty_prdDatas): ?>
    
 
 <tr>
    <td style="font-size:12px;"><?php echo $no++; ?></td>
    <td style="font-size:12px;"><?php echo $empty_prdDatas->name; ?></td>
    <td style="font-size:12px;"><?php echo !empty($empty_prdDatas->branch_name) ? html_escape($empty_prdDatas->branch_name) : '-'; ?></td>
    <td style="font-size:12px;"><?php echo $empty_prdDatas->balance; ?></td>
    <td style="font-size:12px;"><?php echo number_format($empty_prdDatas->buy_price); ?>/=</td>
  </tr>
 <?php endforeach; ?>
</table>
  </div>

</div>

</body>
</html>



