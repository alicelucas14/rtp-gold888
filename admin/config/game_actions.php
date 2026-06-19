<?php
require '../assets/config-data.php';

// --- Helper Functions ---

/**
 * [UPDATED] Handles game image uploads by saving the file with its original extension
 * but returning only the BASE NAME for storage in the database.
 * This makes the system extension-agnostic.
 *
 * @param string $file_input_name The name of the <input type="file"> field.
 * @return array An array with either a 'basename' key on success or an 'error' key on failure.
 */
function handle_game_image_upload($file_input_name) {
    if (!isset($_FILES[$file_input_name]) || $_FILES[$file_input_name]['error'] != UPLOAD_ERR_OK) {
        return ['error' => 'No image file was uploaded or an upload error occurred.'];
    }

    $target_dir = "../../images/games/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Get file parts
    $original_filename = basename($_FILES[$file_input_name]["name"]);
    $extension = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
    $base_name = pathinfo($original_filename, PATHINFO_FILENAME);

    // Sanitize the base name (allow letters, numbers, hyphens, underscores)
    $safe_base_name = preg_replace("/[^a-zA-Z0-9-_\.]/", "", $base_name);
    $new_full_filename = $safe_base_name . '.' . $extension;
    $target_file = $target_dir . $new_full_filename;
    
    // Check if a file with the same name but DIFFERENT extension already exists to prevent duplicates.
    // e.g., prevent uploading 'game.webp' if 'game.png' already exists.
    $existing_files = glob($target_dir . $safe_base_name . '.*');
    if (!empty($existing_files)) {
         return ['error' => 'An image with this base name but a different extension already exists (e.g., .png or .jpg). Please rename the file or delete the old one.'];
    }

    // Allow certain file formats (added .webp)
    $allowed_types = ['jpg', 'png', 'jpeg', 'gif', 'webp', 'avif'];
    if (!in_array($extension, $allowed_types)) {
        return ['error' => 'Sorry, only JPG, PNG, GIF, WEBP & AVIF files are allowed.'];
    }

    if (move_uploaded_file($_FILES[$file_input_name]["tmp_name"], $target_file)) {
        // [MODIFIED] Return only the safe base name
        return ['basename' => $safe_base_name];
    } else {
        return ['error' => 'Sorry, there was an error uploading the file. Check folder permissions.'];
    }
}

// Function for redirection with a message
function redirect_with_alert($message, $location) {
    echo '<script>
            alert("' . addslashes($message) . '");
            window.location.href = "' . $location . '";
          </script>';
    exit;
}

// --- Main Logic ---

if (!isset($_POST['action'])) {
    redirect_with_alert("No action specified.", "../dashboard.php");
}

$action = $_POST['action'];
$provider_code = isset($_POST['demo_provider']) ? '&game=' . urlencode($_POST['demo_provider']) : '';
$redirect_url = "../dashboard.php?hal=gameimg" . $provider_code;

switch ($action) {
    case 'add_game':
        $provider = mysqli_real_escape_string($data, $_POST['demo_provider']);
        $title = mysqli_real_escape_string($data, $_POST['game_title']);
        $link = mysqli_real_escape_string($data, $_POST['demo_gamelink']);

        $upload_result = handle_game_image_upload('demo_name');
        if (isset($upload_result['error'])) {
            redirect_with_alert($upload_result['error'], $redirect_url);
        }
        // [MODIFIED] Get the base name from the upload result
        $basename = $upload_result['basename'];

        // [MODIFIED] Insert the base name (without extension) into the database
        $sql = "INSERT INTO demo_games (demo_provider, game_title, demo_name, demo_gamelink) VALUES ('$provider', '$title', '$basename', '$link')";
        
        if (mysqli_query($data, $sql)) {
            redirect_with_alert("Game added successfully!", $redirect_url);
        } else {
            redirect_with_alert("Database error: " . mysqli_error($data), $redirect_url);
        }
        break;

    case 'edit_game':
        $id = (int)$_POST['id'];
        $title = mysqli_real_escape_string($data, $_POST['game_title']);
        $link = mysqli_real_escape_string($data, $_POST['demo_gamelink']);

        if (isset($_FILES['demo_name']) && $_FILES['demo_name']['error'] == UPLOAD_ERR_OK) {
            // Delete ALL old files with the same base name (e.g., game.png, game.webp)
            $old_game_res = mysqli_query($data, "SELECT demo_name FROM demo_games WHERE id = $id");
            if ($old_game_row = mysqli_fetch_assoc($old_game_res)) {
                $old_basename = pathinfo($old_game_row['demo_name'], PATHINFO_FILENAME); // Get base name just in case
                $files_to_delete = glob('../../images/games/' . $old_basename . '.*');
                foreach ($files_to_delete as $file) {
                    unlink($file);
                }
            }

            // Upload the new image
            $upload_result = handle_game_image_upload('demo_name');
            if (isset($upload_result['error'])) {
                redirect_with_alert($upload_result['error'], $redirect_url);
            }
            $new_basename = $upload_result['basename'];
            
            // [MODIFIED] Update query with the new base name
            $sql = "UPDATE demo_games SET game_title = '$title', demo_name = '$new_basename', demo_gamelink = '$link' WHERE id = $id";
        } else {
            // Update query without changing the image
            $sql = "UPDATE demo_games SET game_title = '$title', demo_gamelink = '$link' WHERE id = $id";
        }

        if (mysqli_query($data, $sql)) {
            redirect_with_alert("Game updated successfully!", $redirect_url);
        } else {
            redirect_with_alert("Database error: " . mysqli_error($data), $redirect_url);
        }
        break;

    case 'delete_game':
        $id = (int)$_POST['id'];

        // Get the base name to delete all associated image files (e.g., game.webp, game.png)
        $res = mysqli_query($data, "SELECT demo_name FROM demo_games WHERE id = $id");
        if ($row = mysqli_fetch_assoc($res)) {
            $basename = pathinfo($row['demo_name'], PATHINFO_FILENAME); // Get base name just in case
            $files_to_delete = glob('../../images/games/' . $basename . '.*');
            foreach ($files_to_delete as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }

        $sql = "DELETE FROM demo_games WHERE id = $id";
        if (mysqli_query($data, $sql)) {
            redirect_with_alert("Game deleted successfully!", $redirect_url);
        } else {
            redirect_with_alert("Database error: " . mysqli_error($data), $redirect_url);
        }
        break;

    default:
        redirect_with_alert("Invalid action.", $redirect_url);
}
?>