<?php include('incs/header.php'); ?>
<?php include('incs/nav.php'); ?>
<?php include('incs/side.php'); ?>

<?php
$selected_branch_id = isset($selected_branch_id) ? $selected_branch_id : null;
$branch_query = $selected_branch_id ? '?branch_id=' . (int) $selected_branch_id : '';
$export_query = $selected_branch_id ? '?branch_id=' . (int) $selected_branch_id : '';
$row_count = is_array($rows) ? count($rows) : 0;
?>

<style>
.dashboard-detail-shell {
  padding: 18px 0 28px;
}

.dashboard-detail-header {
  display: flex;
  justify-content: space-between;
  gap: 16px;
  align-items: flex-start;
  flex-wrap: wrap;
}

.dashboard-detail-header h2 {
  margin: 0;
  font-weight: 700;
}

.dashboard-detail-meta {
  color: #64748b;
  margin-top: 4px;
}

.dashboard-detail-actions {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.dashboard-detail-filter {
  display: grid;
  grid-template-columns: minmax(180px, 280px) auto auto;
  gap: 10px;
  align-items: end;
  margin: 18px 0;
}

.dashboard-detail-filter label {
  display: block;
  font-weight: 600;
  margin-bottom: 5px;
}

.dashboard-detail-table th,
.dashboard-detail-table td {
  white-space: nowrap;
  vertical-align: middle;
}

@media (max-width: 767px) {
  .dashboard-detail-filter {
    grid-template-columns: 1fr;
  }

  .dashboard-detail-actions .btn,
  .dashboard-detail-filter .btn {
    width: 100%;
  }
}
</style>

<div id="main-content">
  <div class="container-fluid dashboard-detail-shell">
    <div class="row clearfix">
      <div class="col-lg-12">
        <div class="card">
          <div class="header">
            <div class="dashboard-detail-header">
              <div>
                <h2><?php echo html_escape($title); ?></h2>
                <div class="dashboard-detail-meta">
                  <?php echo number_format($row_count); ?> records · <?php echo html_escape(date('F j, Y', strtotime($today))); ?>
                </div>
              </div>
              <div class="dashboard-detail-actions">
                <a href="<?php echo base_url('admin/index') . $branch_query; ?>" class="btn btn-secondary">
                  <i class="icon-arrow-left"></i> Dashboard
                </a>
                <a href="<?php echo base_url('admin/dashboard_card_export/' . $type . '/pdf') . $export_query; ?>" class="btn btn-info">
                  <i class="icon-printer"></i> Download PDF
                </a>
                <a href="<?php echo base_url('admin/dashboard_card_export/' . $type . '/excel') . $export_query; ?>" class="btn btn-success">
                  <i class="icon-doc"></i> Export Excel
                </a>
              </div>
            </div>
          </div>
          <div class="body">
            <form method="get" action="<?php echo base_url('admin/dashboard_card/' . $type); ?>" class="dashboard-detail-filter">
              <div>
                <label>Branch</label>
                <select name="branch_id" class="form-control">
                  <option value="">All Branches</option>
                  <?php if (!empty($branches)): ?>
                    <?php foreach ($branches as $branch): ?>
                      <option value="<?php echo $branch->branch_id; ?>" <?php echo ((string) $selected_branch_id === (string) $branch->branch_id) ? 'selected' : ''; ?>>
                        <?php echo html_escape($branch->branch_name); ?>
                      </option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </div>
              <button type="submit" class="btn btn-primary"><i class="icon-magnifier"></i> Filter</button>
              <a href="<?php echo base_url('admin/dashboard_card/' . $type) . '?branch_id='; ?>" class="btn btn-secondary">Reset</a>
            </form>

            <div class="table-responsive">
              <table class="table table-hover table-custom dashboard-detail-table">
                <thead class="thead-primary">
                  <tr>
                    <?php foreach ($columns as $column): ?>
                      <th><?php echo html_escape($column); ?></th>
                    <?php endforeach; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($rows)): ?>
                    <?php foreach ($rows as $row): ?>
                      <tr>
                        <?php foreach ($row as $cell): ?>
                          <td><?php echo html_escape((string) $cell); ?></td>
                        <?php endforeach; ?>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="<?php echo count($columns); ?>" class="text-center text-muted">No records found.</td>
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

<?php include('incs/footer.php'); ?>
