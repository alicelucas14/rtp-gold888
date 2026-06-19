<div class="row">
    <!-- Add Provider Card -->
    <div class="col-12 mb-3">
        <div class="card border border-success">
            <div class="card-header bg-success">
                <h4 class="card-title mb-0 text-white"><i class="fas fa-plus-circle"></i> Add New Provider</h4>
            </div>
            <form method="POST" action="config/provider_actions.php" class="form-horizontal" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add_provider">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6"><div class="form-group">
                            <label for="provider_name">Provider Name</label>
                            <input type="text" class="form-control" name="provider_name" placeholder="e.g., Pragmatic Play" required>
                        </div></div>
                        <div class="col-md-6"><div class="form-group">
                            <label for="provider_code">Provider Code (Short Name)</label>
                            <input type="text" class="form-control" name="provider_code" placeholder="e.g., pp (lowercase, no spaces)" required>
                        </div></div>
                        <div class="col-md-6"><div class="form-group">
                            <label for="provider_logo">Provider Logo File</label>
                            <input type="file" class="form-control" name="provider_logo" required>
                        </div></div>
                        <div class="col-md-6"><div class="form-group">
                            <label for="provider_rating">Provider Rating (1.0 - 5.9)</label>
                            <input type="number" step="0.1" min="1.0" max="5.9" class="form-control" name="provider_rating" value="5.0" required>
                        </div></div>
                        <div class="col-md-6"><div class="form-group">
                            <label for="category_id">Category</label>
                            <select name="category_id" class="form-control" required>
                                <option value="">-- Select a Category --</option>
                                <?php
                                $cat_sql = "SELECT id, category_name FROM provider_categories WHERE is_active = 1 ORDER BY display_order ASC";
                                $cat_query = mysqli_query($data, $cat_sql);
                                while ($cat = mysqli_fetch_assoc($cat_query)) {
                                    echo '<option value="' . $cat['id'] . '">' . htmlspecialchars($cat['category_name']) . '</option>';
                                }
                                ?>
                            </select>
                        </div></div>
                    </div>
                </div>
                <div class="border-top"><div class="card-body">
                    <button type="submit" class="btn btn-success float-right">Add Provider</button>
                </div></div>
            </form>
        </div>
    </div>

    <!-- List of Providers -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Provider Management</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Logo</th>
                                <th>Provider Name</th>
                                <th>Category</th>
                                <th>Code</th>
                                <th>Rating</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $s_providers = "SELECT p.*, c.category_name FROM providers p LEFT JOIN provider_categories c ON p.category_id = c.id ORDER BY p.provider_name ASC";
                            $q_providers = mysqli_query($data, $s_providers);

                            if (mysqli_num_rows($q_providers) > 0) {
                                while ($row = mysqli_fetch_assoc($q_providers)) {
                                    $status_badge = $row['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                                    $category_name = $row['category_name'] ? htmlspecialchars($row['category_name']) : '<span class="text-muted">Unassigned</span>';
                                    $status_action = $row['is_active'] ? 'deactivate' : 'activate';
                                    
                                    echo '<tr>
                                            <td><img src="../' . htmlspecialchars($row['provider_logo']) . '?t=' . time() . '" width="100" class="border"></td>
                                            <td>' . htmlspecialchars($row['provider_name']) . '</td>
                                            <td>' . $category_name . '</td>
                                            <td><code>' . htmlspecialchars($row['provider_code']) . '</code></td>
                                            <td>' . htmlspecialchars($row['provider_rating']) . '</td>
                                            <td>' . $status_badge . '</td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm edit-btn" data-toggle="modal" data-target="#editProviderModal"
                                                        data-id="' . $row['id'] . '"
                                                        data-name="' . htmlspecialchars($row['provider_name']) . '"
                                                        data-code="' . htmlspecialchars($row['provider_code']) . '"
                                                        data-rating="' . htmlspecialchars($row['provider_rating']) . '"
                                                        data-categoryid="' . $row['category_id'] . '">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <a href="config/provider_actions.php?action='.$status_action.'&id=' . $row['id'] . '" class="btn btn-sm ' . ($row['is_active'] ? 'btn-secondary' : 'btn-success') . '">
                                                    <i class="fas ' . ($row['is_active'] ? 'fa-eye-slash' : 'fa-eye') . '"></i>
                                                </a>
                                            </td>
                                          </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="7" class="text-center">No providers found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Provider Modal -->
<div class="modal fade" id="editProviderModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="config/provider_actions.php" enctype="multipart/form-data">
                <input type="hidden" name="action" value="edit_provider">
                <input type="hidden" name="id" id="edit_provider_id">
                <div class="modal-header"><h5 class="modal-title" id="editProviderModalLabel">Edit Provider</h5><button type="button" class="close" data-dismiss="modal">×</button></div>
                <div class="modal-body"><div class="row">
                    <div class="col-md-6"><div class="form-group">
                        <label>Provider Name</label>
                        <input type="text" name="provider_name" id="edit_provider_name" class="form-control" required>
                    </div></div>
                    <div class="col-md-6"><div class="form-group">
                        <label>Provider Code</label>
                        <input type="text" name="provider_code" id="edit_provider_code" class="form-control" required>
                    </div></div>
                    <div class="col-md-6"><div class="form-group">
                        <label>New Logo (Optional)</label>
                        <input type="file" name="provider_logo" class="form-control">
                    </div></div>
                    <div class="col-md-6"><div class="form-group">
                        <label>Provider Rating</label>
                        <input type="number" step="0.1" name="provider_rating" id="edit_provider_rating" class="form-control" required>
                    </div></div>
                    <div class="col-md-6"><div class="form-group">
                        <label>Category</label>
                        <select name="category_id" id="edit_category_id" class="form-control" required>
                             <option value="">-- Select a Category --</option>
                            <?php
                            mysqli_data_seek($cat_query, 0); // Reset pointer for reuse
                            while ($cat = mysqli_fetch_assoc($cat_query)) {
                                echo '<option value="' . $cat['id'] . '">' . htmlspecialchars($cat['category_name']) . '</option>';
                            }
                            ?>
                        </select>
                    </div></div>
                    <div class="col-md-6 text-center">
                        <p>Current Logo:</p>
                        <img src="" id="current_logo_img" width="100" class="border">
                    </div>
                </div></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>