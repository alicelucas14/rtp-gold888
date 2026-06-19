<?php
// --- Custom CSS for this page ---
?>
<style>
    .category-logo-thumbnail {
        width: 100px;
        height: auto;
        border-radius: 0.25rem;
        border: 1px solid #dee2e6;
    }
</style>

<div class="row">
    <!-- Add Category Card -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-success">
                <h4 class="card-title mb-0 text-white"><i class="fas fa-plus-circle"></i> Add New Category</h4>
            </div>
            <form method="POST" action="config/category_actions.php" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add_category">
                <div class="card-body">
                    <div class="form-group">
                        <label for="category_name">Category Name</label>
                        <input type="text" class="form-control" name="category_name" placeholder="e.g., Hot Slots" required>
                    </div>
                    <div class="form-group">
                        <label for="display_order">Display Order</label>
                        <input type="number" class="form-control" name="display_order" value="0" required>
                        <small class="form-text text-muted">Lower numbers appear first.</small>
                    </div>
                    <div class="form-group">
                        <label for="category_logo">Category Logo (Optional)</label>
                        <input type="file" class="form-control" name="category_logo">
                        <small class="form-text text-muted">A small icon to represent the category.</small>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success float-right">Save Category</button>
                </div>
            </form>
        </div>
    </div>

    <!-- List of Categories -->
    <div class="col-md-7">
        <div class="card">
             <div class="card-header bg-light">
                <h4 class="card-title mb-0"><i class="fas fa-list-ul"></i> Existing Categories</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Order</th>
                                <th>Logo</th>
                                <th>Category Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $s_categories = "SELECT * FROM provider_categories ORDER BY display_order ASC";
                            $q_categories = mysqli_query($data, $s_categories);

                            if (mysqli_num_rows($q_categories) > 0) {
                                while ($row = mysqli_fetch_assoc($q_categories)) {
                                    $status_badge = $row['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>';
                                    $logo_display = !empty($row['category_logo']) ? '<img src="../' . htmlspecialchars($row['category_logo']) . '?t=' . time() . '" class="category-logo-thumbnail">' : 'No Logo';
                                    
                                    echo '<tr>
                                            <td class="align-middle">' . htmlspecialchars($row['display_order']) . '</td>
                                            <td class="align-middle">' . $logo_display . '</td>
                                            <td class="align-middle">' . htmlspecialchars($row['category_name']) . '</td>
                                            <td class="align-middle">' . $status_badge . '</td>
                                            <td class="align-middle">
                                                <button type="button" class="btn btn-info btn-sm edit-category-btn" data-toggle="modal" data-target="#editCategoryModal"
                                                        data-id="' . $row['id'] . '"
                                                        data-name="' . htmlspecialchars($row['category_name']) . '"
                                                        data-order="' . htmlspecialchars($row['display_order']) . '"
                                                        data-active="' . $row['is_active'] . '"
                                                        data-logo="' . htmlspecialchars($row['category_logo']) . '">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm delete-category-btn" data-toggle="modal" data-target="#deleteCategoryModal"
                                                        data-id="' . $row['id'] . '"
                                                        data-name="' . htmlspecialchars($row['category_name']) . '">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                          </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="5" class="text-center">No categories found. Add one using the form on the left.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="config/category_actions.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="edit_category">
                <input type="hidden" name="id" id="edit_category_id">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Category Name</label>
                        <input type="text" name="category_name" id="edit_category_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Display Order</label>
                        <input type="number" name="display_order" id="edit_display_order" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>New Category Logo (Optional)</label>
                        <input type="file" name="category_logo" class="form-control">
                        <small>Only select a file to replace the current logo.</small>
                    </div>
                    <div class="form-group">
                        <p>Current Logo:</p>
                        <img src="" id="edit_category_logo_preview" class="category-logo-thumbnail" alt="No Logo">
                    </div>
                     <div class="form-group">
                        <label for="edit_is_active">Status</label>
                        <select name="is_active" id="edit_is_active" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Category Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="config/category_actions.php" method="POST">
                <input type="hidden" name="action" value="delete_category">
                <input type="hidden" name="id" id="delete_category_id">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the category: <strong id="delete_category_name"></strong>?</p>
                    <p class="text-danger"><b>Warning:</b> Deleting a category will un-assign all providers within it. It will not delete the providers themselves.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Category</button>
                </div>
            </form>
        </div>
    </div>
</div>