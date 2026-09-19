<?php include('incs/header.php'); ?>
<?php include('incs/nav.php'); ?>
<?php include('incs/side.php'); ?>

<style>
    .categories-page {
        padding: 22px 0 34px;
    }

    .categories-header {
        margin-bottom: 16px;
        padding: 18px 20px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06);
    }

    .categories-header h1 {
        margin: 0;
        color: #111827;
        font-size: 22px;
        font-weight: 800;
    }

    .categories-header p {
        margin: 4px 0 0;
        color: #6b7280;
        font-size: 13px;
    }

    .categories-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 360px;
        gap: 16px;
        align-items: start;
    }

    .categories-panel {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .categories-panel-head {
        padding: 15px 16px;
        border-bottom: 1px solid #eef2f7;
    }

    .categories-panel-head h2 {
        margin: 0;
        color: #111827;
        font-size: 15px;
        font-weight: 800;
    }

    .categories-panel-body {
        padding: 16px;
    }

    .categories-table {
        margin-bottom: 0;
    }

    .categories-table thead th {
        border-top: 0;
        border-bottom: 1px solid #e5e7eb;
        color: #6b7280;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .categories-table tbody td {
        vertical-align: middle;
        color: #374151;
        font-size: 13px;
    }

    .categories-table .category-rename {
        display: flex;
        gap: 6px;
    }

    .categories-table .category-rename .form-control {
        min-height: 34px;
        border-color: #d1d5db;
        border-radius: 6px;
        box-shadow: none;
    }

    .category-field {
        margin-bottom: 14px;
    }

    .category-field label {
        display: block;
        margin-bottom: 6px;
        color: #374151;
        font-size: 12px;
        font-weight: 800;
    }

    .category-field .form-control {
        min-height: 42px;
        border-color: #d1d5db;
        border-radius: 8px;
        box-shadow: none;
    }

    .category-save-btn {
        width: 100%;
        min-height: 42px;
        border: 0;
        border-radius: 8px;
        background: #0f766e;
        color: #ffffff;
        font-size: 14px;
        font-weight: 800;
    }

    .category-save-btn:hover,
    .category-save-btn:focus {
        background: #115e59;
        color: #ffffff;
    }

    .category-row-btn {
        border: 0;
        border-radius: 6px;
        padding: 6px 10px;
        font-size: 12px;
        font-weight: 800;
    }

    .category-row-btn.update {
        background: #e0f2fe;
        color: #0369a1;
    }

    .category-row-btn.delete {
        background: #fee2e2;
        color: #b91c1c;
    }

    .category-row-btn[disabled] {
        opacity: 0.45;
        cursor: not-allowed;
    }

    html.evamo-dark .categories-header,
    html.evamo-dark .categories-panel {
        background: #111827;
        border-color: #334155;
    }

    html.evamo-dark .categories-panel-head {
        border-bottom-color: #334155;
    }

    html.evamo-dark .categories-header h1,
    html.evamo-dark .categories-panel-head h2 {
        color: #f8fafc;
    }

    html.evamo-dark .categories-header p,
    html.evamo-dark .categories-table thead th {
        color: #94a3b8;
    }

    html.evamo-dark .categories-table thead th {
        border-bottom-color: #334155;
    }

    html.evamo-dark .categories-table tbody td,
    html.evamo-dark .category-field label {
        color: #e2e8f0;
        border-top-color: #334155;
    }

    html.evamo-dark .category-field .form-control,
    html.evamo-dark .categories-table .category-rename .form-control {
        background: #0f172a;
        border-color: #334155;
        color: #ffffff;
    }

    @media (max-width: 991.98px) {
        .categories-layout {
            grid-template-columns: 1fr;
        }
    }
</style>

<div id="main-content">
    <div class="container-fluid categories-page">
        <?php if ($das = $this->session->flashdata('massage')): ?>
            <div class="alert alert-success alert-dismissible">
                <a href="" class="close">&times;</a>
                <?php echo $das; ?>
            </div>
        <?php endif; ?>

        <?php if ($err = $this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible">
                <a href="" class="close">&times;</a>
                <?php echo $err; ?>
            </div>
        <?php endif; ?>

        <div class="categories-header">
            <h1>Categories</h1>
            <p>Register the product categories that appear when adding or editing products.</p>
        </div>

        <div class="categories-layout">
            <div class="categories-panel">
                <div class="categories-panel-head">
                    <h2>Category List</h2>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-custom categories-table">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Products</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($categories)) { ?>
                                <?php foreach ($categories as $category) { ?>
                                    <tr>
                                        <td>
                                            <?php echo form_open('admin/update_category/' . (int)$category->category_id, ['class' => 'category-rename']); ?>
                                                <input type="text" name="category_name" value="<?php echo html_escape($category->category_name); ?>" required maxlength="50" autocomplete="off" class="form-control">
                                                <button type="submit" class="category-row-btn update">Save</button>
                                            <?php echo form_close(); ?>
                                        </td>
                                        <td><?php echo (int)$category->product_count; ?></td>
                                        <td>
                                            <?php echo form_open('admin/delete_category/' . (int)$category->category_id); ?>
                                                <button type="submit" class="category-row-btn delete" <?php echo $category->product_count > 0 ? 'disabled title="In use by products"' : ''; ?> onclick="return confirm('Delete this category?');">Delete</button>
                                            <?php echo form_close(); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No categories registered yet.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="categories-panel">
                <div class="categories-panel-head">
                    <h2>Add New Category</h2>
                </div>
                <div class="categories-panel-body">
                    <?php echo form_open('admin/create_category'); ?>
                        <div class="category-field">
                            <label for="category-name">Category Name</label>
                            <input id="category-name" type="text" name="category_name" value="<?php echo set_value('category_name'); ?>" required maxlength="50" autocomplete="off" class="form-control" placeholder="e.g. Vitamins">
                            <?php echo form_error('category_name'); ?>
                        </div>

                        <button type="submit" class="category-save-btn">Save Category</button>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'incs/footer.php'; ?>
