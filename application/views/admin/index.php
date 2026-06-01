<?php include('incs/header.php'); ?>
<?php include('incs/nav.php'); ?>
<?php include('incs/side.php'); ?>

<?php
$today_sales_total = (float) (is_object($total_sell) ? ($total_sell->TotalItemsOrdered ?? 0) : 0);
$today_profit_total = (float) (is_object($total_profit) ? ($total_profit->Totalprofit ?? 0) : 0);
$today_expense_total = (float) (is_object($total_matumiz) ? ($total_matumiz->matumiz ?? 0) : 0);
$today_payroll_total = (float) (is_object($mishahara_data) ? ($mishahara_data->mishahara ?? 0) : 0);
$today_indirect_total = (float) (is_object($today_indirect_exp) ? ($today_indirect_exp->total_paytoday ?? 0) : 0);
$today_cashout_total = $today_expense_total + $today_payroll_total + $today_indirect_total;

$all_profit_total = (float) (is_object($total_profit_all) ? ($total_profit_all->total_profit ?? 0) : 0);
$all_expense_total = (float) (is_object($all_matumiz_all) ? ($all_matumiz_all->matumiz ?? 0) : 0);
$all_payroll_total = (float) (is_object($mishahara_data_all) ? ($mishahara_data_all->mishahara ?? 0) : 0);
$all_sales_total = (float) (is_object($all_sell_all) ? ($all_sell_all->TotalItemsOrdered ?? 0) : 0);
$all_indirect_total = (float) (is_object($inderect_expenses_all) ? ($inderect_expenses_all->total_paid_expenses ?? 0) : 0);

$gross_total = $all_profit_total - ($all_expense_total + $all_payroll_total) + ($all_sales_total - $all_profit_total - $all_indirect_total);
$product_total = (int) $this->db->query('SELECT * FROM product')->num_rows();

$movement_fast = (int) ($medicine_movement_summary['fastMoving'] ?? 0);
$movement_slow = (int) ($medicine_movement_summary['slowMoving'] ?? 0);
$movement_dead = (int) ($medicine_movement_summary['deadStock'] ?? 0);

$monthly_labels = array();
$monthly_sales = array();
$monthly_profit = array();
$monthly_gross = array();
$monthly_cashout = array();

if (!empty($datamonth)) {
    foreach ($datamonth as $month_row) {
        $label = date('M', strtotime($month_row->sell_day));
        $monthly_labels[] = $label;
        $monthly_sales[] = (float) ($month_row->total_sellPRICE ?? 0);
        $monthly_profit[] = (float) ($month_row->total_profit ?? 0);
        $month_cashout = (float) (($month_row->total_expenses ?? 0) + ($month_row->total_payrole ?? 0) + ($month_row->total_indirect_expenses ?? 0));
        $monthly_cashout[] = $month_cashout;

        $month_gross = ((float) ($month_row->total_profit ?? 0))
            - ((float) (($month_row->total_expenses ?? 0) + ($month_row->total_payrole ?? 0)))
            + ((float) (($month_row->total_retail_sale ?? 0) - ($month_row->total_retail_profit ?? 0)
            + (($month_row->total_wholesale ?? 0) - ($month_row->total_whole_profit ?? 0))))
            - ((float) ($month_row->total_indirect_expenses ?? 0));
        $monthly_gross[] = $month_gross;
    }
}

  $today = date('Y-m-d');
  $current_start = date('Y-m-d', strtotime('-6 days', strtotime($today)));
  $previous_end = date('Y-m-d', strtotime('-7 days', strtotime($today)));
  $previous_start = date('Y-m-d', strtotime('-13 days', strtotime($today)));

  $sum_between = function ($table, $column, $date_column, $from, $to) {
    if (!$this->db->table_exists($table)) {
      return 0.0;
    }

    $query = $this->db->query(
      "SELECT SUM($column) AS total FROM $table WHERE DATE($date_column) BETWEEN "
      . $this->db->escape($from)
      . " AND "
      . $this->db->escape($to)
    );

    if (!$query || !$query->row()) {
      return 0.0;
    }

    return (float) ($query->row()->total ?? 0);
  };

  $calc_change_pct = function ($current, $previous) {
    if ((float) $previous == 0.0) {
      return 0.0;
    }
    return (($current - $previous) / abs($previous)) * 100;
  };

  $current_sales_7d = $sum_between('tbl_sell', 'total_sell_price', 'sell_day', $current_start, $today);
  $previous_sales_7d = $sum_between('tbl_sell', 'total_sell_price', 'sell_day', $previous_start, $previous_end);

  $current_profit_7d = $sum_between('tbl_sell', 'profit', 'sell_day', $current_start, $today);
  $previous_profit_7d = $sum_between('tbl_sell', 'profit', 'sell_day', $previous_start, $previous_end);

  $current_cashflow_7d = $sum_between('cash_flow', 'price', 'created', $current_start, $today);
  $previous_cashflow_7d = $sum_between('cash_flow', 'price', 'created', $previous_start, $previous_end);

  $current_payroll_7d = $sum_between('tbl_mishahara', 'pay_amount', 'date', $current_start, $today);
  $previous_payroll_7d = $sum_between('tbl_mishahara', 'pay_amount', 'date', $previous_start, $previous_end);

  $current_indirect_7d = $sum_between('tbl_payment', 'pay_amount', 'pay_date', $current_start, $today);
  $previous_indirect_7d = $sum_between('tbl_payment', 'pay_amount', 'pay_date', $previous_start, $previous_end);

  $current_cashout_7d = $current_cashflow_7d + $current_payroll_7d + $current_indirect_7d;
  $previous_cashout_7d = $previous_cashflow_7d + $previous_payroll_7d + $previous_indirect_7d;

  $current_gross_7d = $current_profit_7d - $current_cashout_7d + ($current_sales_7d - $current_profit_7d);
  $previous_gross_7d = $previous_profit_7d - $previous_cashout_7d + ($previous_sales_7d - $previous_profit_7d);

  $trend_data = array(
    'sales' => array(
      'pct' => $calc_change_pct($current_sales_7d, $previous_sales_7d),
      'up' => $calc_change_pct($current_sales_7d, $previous_sales_7d) >= 0,
    ),
    'profit' => array(
      'pct' => $calc_change_pct($current_profit_7d, $previous_profit_7d),
      'up' => $calc_change_pct($current_profit_7d, $previous_profit_7d) >= 0,
    ),
    'cashout' => array(
      'pct' => $calc_change_pct($current_cashout_7d, $previous_cashout_7d),
      'up' => $calc_change_pct($current_cashout_7d, $previous_cashout_7d) <= 0,
    ),
    'gross' => array(
      'pct' => $calc_change_pct($current_gross_7d, $previous_gross_7d),
      'up' => $calc_change_pct($current_gross_7d, $previous_gross_7d) >= 0,
    ),
  );
?>

<style>
.dashboard-shell {
  display: grid;
  gap: 1rem;
  min-width: 0;
}

.dashboard-shell > * {
  min-width: 0;
  max-width: 100%;
}

#main-content .container-fluid {
  padding-left: 0.75rem;
  padding-right: 0.75rem;
}

.dash-title {
  font-size: 1.35rem;
  font-weight: 700;
  margin: 0;
}

.dash-subtitle {
  margin: 0.2rem 0 0;
  color: #64748b;
  font-size: 0.9rem;
}

.kpi-grid {
  display: grid;
  grid-template-columns: repeat(4, minmax(210px, 1fr));
  gap: 0.9rem;
  min-width: 0;
}

.kpi-card {
  position: relative;
  overflow: hidden;
  border-radius: 0.95rem;
  padding: 1rem;
  color: #fff;
  box-shadow: 0 14px 28px rgba(15, 23, 42, 0.16);
  min-width: 0;
}

.kpi-card:before {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(130deg, rgba(255, 255, 255, 0.24), rgba(255, 255, 255, 0));
  pointer-events: none;
}

.kpi-card .kpi-label {
  font-size: 0.83rem;
  letter-spacing: 0.02em;
  text-transform: uppercase;
  opacity: 0.95;
}

.kpi-card .kpi-value {
  margin: 0.6rem 0 0;
  font-size: clamp(1.15rem, 2vw, 1.55rem);
  font-weight: 800;
  line-height: 1.15;
}

.kpi-card .kpi-note {
  margin-top: 0.5rem;
  font-size: 0.8rem;
  opacity: 0.95;
}

.trend-chip {
  display: inline-flex;
  align-items: center;
  gap: 0.28rem;
  font-size: 0.74rem;
  font-weight: 700;
  border-radius: 999px;
  padding: 0.18rem 0.5rem;
  background: rgba(255, 255, 255, 0.18);
}

.trend-chip .arrow {
  font-size: 0.72rem;
  line-height: 1;
}

.trend-chip.up {
  background: rgba(16, 185, 129, 0.24);
}

.trend-chip.down {
  background: rgba(239, 68, 68, 0.24);
}

.kpi-note .meta {
  display: block;
  margin-top: 0.35rem;
}

.kpi-sales { background: linear-gradient(135deg, #0f766e, #14b8a6); }
.kpi-profit { background: linear-gradient(135deg, #0d9488, #2dd4bf); }
.kpi-cashout { background: linear-gradient(135deg, #b45309, #f59e0b); }
.kpi-gross { background: linear-gradient(135deg, #4338ca, #6366f1); }

.chart-grid {
  display: grid;
  grid-template-columns: 1fr 1.35fr;
  gap: 1rem;
  align-items: stretch;
  min-width: 0;
}

.chart-card {
  border-radius: 0.95rem;
  min-width: 0;
}

.chart-holder canvas {
  display: block;
  width: 100% !important;
  max-width: 100% !important;
}

.chart-card .header h2 {
  margin: 0;
  font-size: 1.05rem;
  font-weight: 700;
}

.chart-holder {
  position: relative;
  height: clamp(230px, 34vw, 330px);
}

.movement-grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 0.75rem;
  min-width: 0;
}

.movement-pill {
  border-radius: 0.75rem;
  padding: 0.75rem;
  border: 1px solid #e2e8f0;
  background: #f8fafc;
  min-width: 0;
}

.movement-pill h4 {
  margin: 0;
  font-size: 0.9rem;
  font-weight: 700;
}

.movement-pill .value {
  margin: 0.35rem 0 0;
  font-size: 1.3rem;
  font-weight: 800;
}

.movement-fast { color: #0f766e; }
.movement-slow { color: #b45309; }
.movement-dead { color: #b91c1c; }

.section-card .header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.75rem;
  overflow: hidden;
}

.section-card .header .row,
.section-card .body .row {
  margin-left: 0;
  margin-right: 0;
}

.section-header-grid {
  width: 100%;
  align-items: end;
}

.section-header-grid h2 {
  margin: 0;
}

.section-title-cyan {
  color: #0891b2;
}

.table-responsive {
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  min-width: 0;
}

.table-mini {
  min-width: 880px;
}

.table-mini th,
.table-mini td {
  font-size: 0.84rem;
  white-space: nowrap;
}

@media (max-width: 1400px) {
  .kpi-grid {
    grid-template-columns: repeat(3, minmax(200px, 1fr));
  }
}

@media (max-width: 1199px) {
  .kpi-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  .chart-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  #main-content .container-fluid {
    padding-left: 0.55rem;
    padding-right: 0.55rem;
  }

  .dash-title {
    font-size: 1.12rem;
  }

  .kpi-grid,
  .movement-grid {
    grid-template-columns: 1fr;
  }

  .chart-holder {
    height: 270px;
  }

  .section-card .header {
    display: block;
  }

  .section-header-grid > [class*="col-"] {
    margin-bottom: 0.55rem;
    padding-left: 0;
    padding-right: 0;
  }

  .section-card .header,
  .section-card .body {
    padding-left: 0.85rem;
    padding-right: 0.85rem;
  }

  .table-mini {
    min-width: 100%;
  }

  .table-mini th,
  .table-mini td {
    white-space: normal;
    word-break: break-word;
  }

  .table-monthly th:nth-child(n + 4),
  .table-monthly td:nth-child(n + 4) {
    display: none;
  }

  .table-sales th:nth-child(4),
  .table-sales td:nth-child(4),
  .table-sales th:nth-child(5),
  .table-sales td:nth-child(5),
  .table-sales th:nth-child(6),
  .table-sales td:nth-child(6),
  .table-sales th:nth-child(8),
  .table-sales td:nth-child(8) {
    display: none;
  }
}

@media (max-width: 480px) {
  .kpi-card {
    padding: 0.85rem;
  }

  .kpi-card .kpi-label {
    font-size: 0.76rem;
  }

  .trend-chip {
    font-size: 0.7rem;
  }

  .table-mini {
    min-width: 100%;
  }
}

html.evamo-dark .dash-subtitle {
  color: #94a3b8;
}

html.evamo-dark #main-content .dashboard-shell,
html.evamo-dark #main-content .dashboard-shell .section-card,
html.evamo-dark #main-content .dashboard-shell .chart-card,
html.evamo-dark #main-content .dashboard-shell .movement-pill,
html.evamo-dark #main-content .dashboard-shell .table,
html.evamo-dark #main-content .dashboard-shell .table th,
html.evamo-dark #main-content .dashboard-shell .table td,
html.evamo-dark #main-content .dashboard-shell .section-card .header h2,
html.evamo-dark #main-content .dashboard-shell .dash-title,
html.evamo-dark #main-content .dashboard-shell .movement-pill h4,
html.evamo-dark #main-content .dashboard-shell .movement-pill .value,
html.evamo-dark #main-content .dashboard-shell label,
html.evamo-dark #main-content .dashboard-shell .form-control,
html.evamo-dark #main-content .dashboard-shell select.form-control {
  color: #cbd5e1;
}

html.evamo-dark #main-content .dashboard-shell .table thead th,
html.evamo-dark #main-content .dashboard-shell .table tbody td,
html.evamo-dark #main-content .dashboard-shell .table tbody th {
  color: #cbd5e1;
}

html.evamo-dark #main-content .dashboard-shell .dash-subtitle,
html.evamo-dark #main-content .dashboard-shell .kpi-note .meta,
html.evamo-dark #main-content .dashboard-shell .section-card .header p,
html.evamo-dark #main-content .dashboard-shell .movement-pill small {
  color: #94a3b8;
}

html.evamo-dark #main-content .dashboard-shell .kpi-card {
  color: #e2e8f0;
}

html.evamo-dark #main-content .dashboard-shell .kpi-card .kpi-label,
html.evamo-dark #main-content .dashboard-shell .kpi-card .kpi-note {
  color: #cbd5e1;
}

html.evamo-dark #main-content .dashboard-shell .kpi-card .kpi-value {
  color: #f1f5f9;
}

html.evamo-dark #main-content .dashboard-shell .chart-card .header h2.section-title-cyan,
html.evamo-dark #main-content .dashboard-shell .section-card .header h2.section-title-cyan {
  color: #67e8f9;
}

html.evamo-dark .movement-pill {
  background: #111827;
  border-color: #334155;
}

html.evamo-dark .chart-card,
html.evamo-dark .section-card {
  background: #0f172a;
  border-color: #1f2937;
}

html.evamo-dark .trend-chip {
  background: rgba(148, 163, 184, 0.2);
}

html.evamo-dark .trend-chip.up {
  background: rgba(16, 185, 129, 0.3);
}

html.evamo-dark .trend-chip.down {
  background: rgba(239, 68, 68, 0.3);
}
</style>

<div id="main-content">
  <div class="container-fluid">
    <br>

    <?php if ($das = $this->session->flashdata('massage')): ?>
      <div class="row">
        <div class="col-md-12">
          <div class="alert alert-dismisible alert-success">
            <a href="" class="close">&times;</a>
            <?php echo $das; ?>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <div class="dashboard-shell">
      <div class="card section-card">
        <div class="body">
          <h1 class="dash-title">Admin Dashboard</h1>
          <p class="dash-subtitle">Realtime pharmacy overview with responsive analytics in light and dark mode.</p>
        </div>
      </div>

      <div class="kpi-grid">
        <div class="kpi-card kpi-sales">
          <div class="kpi-label">Today Sales</div>
          <div class="kpi-value">Tsh <?php echo number_format($today_sales_total); ?></div>
          <div class="kpi-note">
            <span class="trend-chip <?php echo $trend_data['sales']['up'] ? 'up' : 'down'; ?>">
              <span class="arrow"><?php echo $trend_data['sales']['up'] ? '▲' : '▼'; ?></span>
              <?php echo number_format(abs($trend_data['sales']['pct']), 1); ?>%
            </span>
            <span class="meta">vs previous 7 days</span>
          </div>
        </div>

        <div class="kpi-card kpi-profit">
          <div class="kpi-label">Today Profit</div>
          <div class="kpi-value">Tsh <?php echo number_format($today_profit_total); ?></div>
          <div class="kpi-note">
            <span class="trend-chip <?php echo $trend_data['profit']['up'] ? 'up' : 'down'; ?>">
              <span class="arrow"><?php echo $trend_data['profit']['up'] ? '▲' : '▼'; ?></span>
              <?php echo number_format(abs($trend_data['profit']['pct']), 1); ?>%
            </span>
            <span class="meta">vs previous 7 days</span>
          </div>
        </div>

        <div class="kpi-card kpi-cashout">
          <div class="kpi-label">Today Cashout</div>
          <div class="kpi-value">Tsh <?php echo number_format($today_cashout_total); ?></div>
          <div class="kpi-note">
            <span class="trend-chip <?php echo $trend_data['cashout']['up'] ? 'up' : 'down'; ?>">
              <span class="arrow"><?php echo $trend_data['cashout']['up'] ? '▼' : '▲'; ?></span>
              <?php echo number_format(abs($trend_data['cashout']['pct']), 1); ?>%
            </span>
            <span class="meta">vs previous 7 days (lower is better)</span>
          </div>
        </div>

        <div class="kpi-card kpi-gross">
          <div class="kpi-label">Gross Total</div>
          <div class="kpi-value">Tsh <?php echo number_format($gross_total); ?></div>
          <div class="kpi-note">
            <span class="trend-chip <?php echo $trend_data['gross']['up'] ? 'up' : 'down'; ?>">
              <span class="arrow"><?php echo $trend_data['gross']['up'] ? '▲' : '▼'; ?></span>
              <?php echo number_format(abs($trend_data['gross']['pct']), 1); ?>%
            </span>
            <span class="meta">products: <?php echo number_format($product_total); ?></span>
          </div>
        </div>
      </div>

      <div class="chart-grid">
        <div class="card chart-card">
          <div class="header">
            <h2 class="section-title-cyan">Medicine Movement</h2>
          </div>
          <div class="body">
            <div class="movement-grid">
              <div class="movement-pill movement-fast">
                <h4>Fast Moving</h4>
                <div class="value"><?php echo $movement_fast; ?></div>
              </div>
              <div class="movement-pill movement-slow">
                <h4>Slow Moving</h4>
                <div class="value"><?php echo $movement_slow; ?></div>
              </div>
              <div class="movement-pill movement-dead">
                <h4>Dead Stock</h4>
                <div class="value"><?php echo $movement_dead; ?></div>
              </div>
            </div>
            <div class="chart-holder" style="margin-top: 1rem;">
              <canvas id="medicineMovementChart"></canvas>
            </div>
          </div>
        </div>

        <div class="card chart-card">
          <div class="header">
            <h2 class="section-title-cyan">Monthly Performance</h2>
          </div>
          <div class="body">
            <div class="chart-holder">
              <canvas id="monthlyBarChart"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="row clearfix">
        <div class="col-lg-12">
          <div class="card section-card">
            <div class="header">
              <div class="row section-header-grid">
                <div class="col-md-2">
                  <h2>Monthly statistics</h2>
                </div>
                <div class="col-md-6">
                  <?php echo form_open('admin/index'); ?>
                  <div class="row">
                    <div class="col-md-6">
                      Select Year
                      <select type="text" name="year" class="form-control" required>
                        <option value="">Select Year</option>
                        <?php foreach ($years as $data_year): ?>
                          <option value="<?php echo $data_year->year; ?>"><?php echo $data_year->year; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <br>
                      <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                  </div>
                  <?php echo form_close(); ?>
                </div>
              </div>
            </div>

            <div class="body">
              <div class="table-responsive">
                <table class="table table-hover j-basic-example dataTable table-custom table-mini table-monthly">
                  <thead class="thead-primary">
                    <tr>
                      <th>Month</th>
                      <th>Total Sell</th>
                      <th>Retail Sell</th>
                      <th>WholelSell</th>
                      <th>Total Profit</th>
                      <th>Retail Profit</th>
                      <th>Whole Profit</th>
                      <th>Direct Expenses</th>
                      <th>Indirect Expenses</th>
                      <th>Gross Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($datamonth as $datamonths): ?>
                      <tr>
                        <td><?php echo date('F', strtotime($datamonths->sell_day)); ?></td>
                        <td><?php echo number_format($datamonths->total_sellPRICE); ?></td>
                        <td><?php echo number_format($datamonths->total_retail_sale); ?></td>
                        <td><?php echo number_format($datamonths->total_wholesale); ?></td>
                        <td><?php echo number_format($datamonths->total_profit); ?></td>
                        <td><?php echo number_format($datamonths->total_retail_profit); ?></td>
                        <td><?php echo number_format($datamonths->total_whole_profit); ?></td>
                        <td><?php echo number_format($datamonths->total_expenses + $datamonths->total_payrole); ?></td>
                        <td><?php echo number_format($datamonths->total_indirect_expenses); ?></td>
                        <td>
                          <?php echo number_format(($datamonths->total_profit) - ($datamonths->total_expenses + $datamonths->total_payrole) + ($datamonths->total_retail_sale - $datamonths->total_retail_profit + ($datamonths->total_wholesale - $datamonths->total_whole_profit)) - $datamonths->total_indirect_expenses); ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row clearfix">
        <div class="col-lg-12">
          <div class="card section-card">
            <div class="header">
              <div class="row section-header-grid">
                <div class="col-md-2"><h2 class="section-title-cyan">Sales today</h2></div>
                <div class="col-md-4">
                  Total sales today
                  <input type="text" readonly placeholder="Tsh.<?php echo number_format($today_sales_total); ?>/=" class="form-control">
                </div>
                <div class="col-md-4">
                  Today Profit
                  <input type="text" readonly placeholder="Tsh.<?php echo number_format($today_profit_total); ?>/=" class="form-control">
                </div>
              </div>
            </div>

            <div class="body">
              <div class="table-responsive">
                <table class="table table-hover js-basic-example dataTable table-custom table-mini table-sales">
                  <thead class="thead-primary">
                    <tr>
                      <th>Seller</th>
                      <th>Customer</th>
                      <th>Product name</th>
                      <th>Quantity</th>
                      <th>Sell price</th>
                      <th>Total price</th>
                      <th>Sell status</th>
                      <th>Time</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($all_salles as $all_salles_today): ?>
                      <tr>
                        <td><?php echo $all_salles_today->full_name; ?></td>
                        <td><?php echo $all_salles_today->customer; ?></td>
                        <td><?php echo $all_salles_today->name; ?></td>
                        <td><?php echo $all_salles_today->quanty; ?> <?php echo $all_salles_today->unit; ?></td>
                        <td><?php echo number_format($all_salles_today->new_sell_price ?? 0); ?>/=</td>
                        <td><?php echo number_format($all_salles_today->total_sell_price ?? 0); ?>/=</td>
                        <td>
                          <?php
                          $da = $all_salles_today->ju_price - $all_salles_today->new_sell_price;
                          if ($da == 0) {
                              echo "<span class='badge badge-success'>whole sale</span>";
                          } else {
                              echo "<span class='badge badge-info'>Retail sale</span>";
                          }
                          ?>
                        </td>
                        <td><?php echo $all_salles_today->created_at; ?></td>
                        <td>
                          <a href="<?php echo base_url("admin/delete_mistake_sell/{$all_salles_today->sell_id}") ?>" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm"><i class="icon-close"></i></a>
                        </td>
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
</div>

<?php include('incs/footer.php'); ?>
<script src="<?php echo base_url() ?>assets/admin/js/chart.min.js"></script>
<script src="<?php echo base_url() ?>assets/admin/js/App.js"></script>

<script>
(function () {
  function getThemeColors() {
    var darkMode = document.documentElement.classList.contains('evamo-dark');

    return {
      text: darkMode ? '#cbd5e1' : '#334155',
      grid: darkMode ? 'rgba(148, 163, 184, 0.2)' : 'rgba(148, 163, 184, 0.25)',
      teal500: '#14b8a6',
      teal700: '#0f766e',
      amber: '#f59e0b',
      rose: '#ef4444',
      blue: '#3b82f6'
    };
  }

  var monthlyLabels = <?php echo json_encode($monthly_labels); ?>;
  var monthlySales = <?php echo json_encode($monthly_sales); ?>;
  var monthlyProfit = <?php echo json_encode($monthly_profit); ?>;
  var monthlyGross = <?php echo json_encode($monthly_gross); ?>;

  var movementCtx = document.getElementById('medicineMovementChart');
  var monthlyCtx = document.getElementById('monthlyBarChart');

  var theme = getThemeColors();

  if (movementCtx) {
    new Chart(movementCtx, {
      type: 'doughnut',
      data: {
        labels: ['Fast-Moving', 'Slow-Moving', 'Dead Stock'],
        datasets: [{
          data: [<?php echo $movement_fast; ?>, <?php echo $movement_slow; ?>, <?php echo $movement_dead; ?>],
          backgroundColor: [theme.teal500, theme.amber, theme.rose],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              color: theme.text,
              padding: 14
            }
          }
        }
      }
    });
  }

  if (monthlyCtx) {
    new Chart(monthlyCtx, {
      type: 'bar',
      data: {
        labels: monthlyLabels,
        datasets: [
          {
            label: 'Sales',
            data: monthlySales,
            backgroundColor: theme.blue,
            borderRadius: 8,
            maxBarThickness: 34
          },
          {
            label: 'Profit',
            data: monthlyProfit,
            backgroundColor: theme.teal500,
            borderRadius: 8,
            maxBarThickness: 34
          },
          {
            label: 'Gross',
            data: monthlyGross,
            backgroundColor: theme.teal700,
            borderRadius: 8,
            maxBarThickness: 34
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
          mode: 'index',
          intersect: false
        },
        plugins: {
          legend: {
            position: 'top',
            labels: {
              color: theme.text,
              boxWidth: 14
            }
          }
        },
        scales: {
          x: {
            ticks: { color: theme.text },
            grid: { color: theme.grid }
          },
          y: {
            ticks: {
              color: theme.text,
              callback: function (value) {
                return value.toLocaleString();
              }
            },
            grid: { color: theme.grid }
          }
        }
      }
    });
  }
})();
</script>
