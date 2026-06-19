<?php
/**
 * [NEW] Smart image finder function for the admin dashboard.
 * Searches for an image file with a given base name, prioritizing modern formats.
 * It is also backward-compatible with old database entries that include the extension.
 *
 * @param string $filename_from_db The filename as it is stored in the database.
 * @return string The full, valid path to the best available image, or a placeholder.
 */
function find_game_image_url($filename_from_db) {
    $base_image_path = '../images/games/';
    // It's good practice to create a placeholder image for when no file is found.
    $placeholder_image = '../images/placeholder.png'; 

    // Sanitize to prevent directory traversal issues.
    $filename_from_db = basename($filename_from_db);
    $base_name = pathinfo($filename_from_db, PATHINFO_FILENAME);

    // 1. Backward Compatibility Check:
    // If the database entry already contains an extension (e.g., "game.png").
    if (pathinfo($filename_from_db, PATHINFO_EXTENSION)) {
        if (file_exists($base_image_path . $filename_from_db)) {
            return $base_image_path . $filename_from_db;
        }
    }

    // 2. New "Smart Search" Logic:
    // This array defines the search priority. WEBP is checked first.
    $preferred_extensions = ['webp', 'png', 'jpg', 'jpeg', 'gif'];
    foreach ($preferred_extensions as $ext) {
        $full_path = $base_image_path . $base_name . '.' . $ext;
        if (file_exists($full_path)) {
            return $full_path; // Found the best available format, return it.
        }
    }

    // 3. Fallback: If no image file was found after checking all formats.
    return $placeholder_image;
}
?>
<style>
    /* Styles are unchanged */
    .provider-selector-container { display: flex; flex-wrap: wrap; gap: 0.5rem; padding-bottom: 15px; border-bottom: 1px solid #dee2e6; margin-bottom: 1.5rem; }
    .provider-selector-btn { padding: 0.5rem 1rem; border: 1px solid #dee2e6; border-radius: 0.25rem; transition: all 0.2s ease-in-out; color: #007bff; background-color: #fff; text-decoration: none; }
    .provider-selector-btn:hover { background-color: #f0f0f0; text-decoration: none; }
    .provider-selector-btn.active { background-color: #007bff; color: #fff; border-color: #007bff; }
    .game-image-thumbnail { width: 80px; height: 80px; object-fit: cover; border-radius: 0.25rem; }
    .card-header .card-title { font-size: 1.25rem; font-weight: 500; }
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light">
                <h4 class="card-title mb-0"><i class="fas fa-gamepad"></i> Demo Slot Management</h4>
            </div>
            <div class="card-body">
                <!-- Provider Selector -->
                <h5 class="card-subtitle mb-2">1. Select a Provider</h5>
                <div class="provider-selector-container">
                    <?php
                    $s_providers = "SELECT provider_code, provider_name FROM providers WHERE is_active = 1 ORDER BY provider_name ASC";
                    $q_providers = mysqli_query($data, $s_providers);
                    if (mysqli_num_rows($q_providers) > 0) {
                        while ($f_provider = mysqli_fetch_assoc($q_providers)) {
                            $is_active_class = (isset($_GET['game']) && $_GET['game'] == $f_provider['provider_code']) ? 'active' : '';
                            echo '<a href="?hal=gameimg&game=' . htmlspecialchars($f_provider['provider_code']) . '" class="provider-selector-btn ' . $is_active_class . '">
                                    ' . htmlspecialchars($f_provider['provider_name']) . '
                                  </a>';
                        }
                    } else {
                        echo '<p>No active providers found. Please add one in Provider Management.</p>';
                    }
                    ?>
                </div>

                <?php if (isset($_GET['game'])) : ?>
                    <?php
                    $provider_code = mysqli_real_escape_string($data, $_GET['game']);
                    $limit = 25;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($page - 1) * $limit;
                    $search = isset($_GET['search']) ? mysqli_real_escape_string($data, $_GET['search']) : '';
                    $where_clause = "WHERE demo_provider = '$provider_code'" . (!empty($search) ? " AND game_title LIKE '%$search%'" : '');
                    
                    $games_sql = "SELECT * FROM demo_games $where_clause ORDER BY game_title ASC LIMIT $limit OFFSET $offset";
                    $games_query = mysqli_query($data, $games_sql);
                    $total_games = mysqli_fetch_assoc(mysqli_query($data, "SELECT COUNT(id) AS total FROM demo_games $where_clause"))['total'];
                    $total_pages = ceil($total_games / $limit);
                    ?>
                    
                    <h5 class="card-subtitle mb-3 mt-3">2. Manage Games for <strong style="text-transform: uppercase;"><?php echo htmlspecialchars($provider_code); ?></strong></h5>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <form method="GET" action="" class="flex-grow-1 mr-2">
                            <input type="hidden" name="hal" value="gameimg">
                            <input type="hidden" name="game" value="<?php echo htmlspecialchars($provider_code); ?>">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search by game title..." value="<?php echo htmlspecialchars($search); ?>">
                                <div class="input-group-append"><button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button></div>
                            </div>
                        </form>
                        <button class="btn btn-success" data-toggle="modal" data-target="#addGameModal"><i class="fas fa-plus-circle"></i> Add New Game</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th><i class="fas fa-image"></i> Image</th>
                                    <th><i class="fas fa-heading"></i> Game Title</th>
                                    <th><i class="fas fa-link"></i> Game Link</th>
                                    <th style="width: 200px;"><i class="fas fa-cogs"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($games_query) > 0) : ?>
                                    <?php while ($game = mysqli_fetch_assoc($games_query)) : ?>
                                        <?php
                                            // [MODIFIED] Use the smart function to find the correct image URL
                                            $image_url = find_game_image_url($game['demo_name']);
                                        ?>
                                        <tr>
                                            <td><img src="<?php echo $image_url; ?>?t=<?php echo time();?>" class="game-image-thumbnail" alt="Game Image"></td>
                                            <td class="align-middle"><?php echo htmlspecialchars($game['game_title']); ?></td>
                                            <td class="align-middle"><code><?php echo htmlspecialchars($game['demo_gamelink']); ?></code></td>
                                            <td class="align-middle">
                                                <button class="btn btn-info btn-sm edit-game-btn" data-toggle="modal" data-target="#editGameModal"
                                                        data-id="<?php echo $game['id']; ?>"
                                                        data-title="<?php echo htmlspecialchars($game['game_title']); ?>"
                                                        data-link="<?php echo htmlspecialchars($game['demo_gamelink']); ?>"
                                                        data-image="<?php echo $image_url; // Use the found URL for the modal preview ?>">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <button class="btn btn-danger btn-sm delete-game-btn" data-toggle="modal" data-target="#deleteGameModal"
                                                        data-id="<?php echo $game['id']; ?>"
                                                        data-title="<?php echo htmlspecialchars($game['game_title']); ?>">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <tr><td colspan="4" class="text-center">No games found. Try a different search or add a new game.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if ($total_pages > 1): ?>
                    <nav class="mt-3">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?hal=gameimg&game=<?php echo $provider_code; ?>&page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>
                <?php else : ?>
                    <div class="alert alert-info text-center mt-4">Please select a provider to begin.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modals are unchanged -->
<div class="modal fade" id="addGameModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content"><form action="config/game_actions.php" method="POST" enctype="multipart/form-data"><input type="hidden" name="action" value="add_game"><input type="hidden" name="demo_provider" value="<?php echo isset($_GET['game']) ? htmlspecialchars($_GET['game']) : ''; ?>"><div class="modal-header"><h5 class="modal-title">Add New Game</h5><button type="button" class="close" data-dismiss="modal">×</button></div><div class="modal-body"><div class="form-group"><label>Game Title</label><input type="text" name="game_title" class="form-control" required></div><div class="form-group"><label>Game Image File</label><input type="file" name="demo_name" class="form-control" required><small>Image filename should be unique (e.g., provider-gamename.png)</small></div><div class="form-group"><label>Game Demo Link (Optional)</label><input type="text" name="demo_gamelink" class="form-control" placeholder="#"></div></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Save Game</button></div></form></div></div></div>
<div class="modal fade" id="editGameModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content"><form action="config/game_actions.php" method="POST" enctype="multipart/form-data"><input type="hidden" name="action" value="edit_game"><input type="hidden" name="id" id="edit_game_id"><input type="hidden" name="demo_provider" value="<?php echo isset($_GET['game']) ? htmlspecialchars($_GET['game']) : ''; ?>"><div class="modal-header"><h5 class="modal-title">Edit Game</h5><button type="button" class="close" data-dismiss="modal">×</button></div><div class="modal-body"><div class="form-group"><label>Game Title</label><input type="text" name="game_title" id="edit_game_title" class="form-control" required></div><div class="form-group"><label>New Game Image (Optional)</label><input type="file" name="demo_name" class="form-control"><small>Only select a file if you want to replace the current image.</small></div><div class="form-group"><label>Game Demo Link</label><input type="text" name="demo_gamelink" id="edit_game_link" class="form-control"></div><p>Current Image:</p><img src="" id="edit_game_image_preview" width="100" class="border"></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Save Changes</button></div></form></div></div></div>
<div class="modal fade" id="deleteGameModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><form action="config/game_actions.php" method="POST"><input type="hidden" name="action" value="delete_game"><input type="hidden" name="id" id="delete_game_id"><input type="hidden" name="demo_provider" value="<?php echo isset($_GET['game']) ? htmlspecialchars($_GET['game']) : ''; ?>"><div class="modal-header"><h5 class="modal-title">Confirm Deletion</h5><button type="button" class="close" data-dismiss="modal">×</button></div><div class="modal-body"><p>Are you sure you want to delete this game: <strong id="delete_game_title"></strong>?</p><p class="text-danger">This action cannot be undone.</p></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-danger">Delete Game</button></div></form></div></div></div>