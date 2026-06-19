<?php
require '../assets/config-data.php';

// --- Helper Functions ---

// Function to handle category logo uploads
function handle_category_logo_upload($file_input_name, $category_id) {
    if (!isset($_FILES[$file_input_name]) || $_FILES[$file_input_name]['error'] != UPLOAD_ERR_OK) {
        return ['error' => 'No image file was uploaded or an upload error occurred.'];
    }

    $target_dir = "../../images/category_icons/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $imageFileType = strtolower(pathinfo($_FILES[$file_input_name]["name"], PATHINFO_EXTENSION));
    // Create a unique filename to avoid conflicts
    $new_filename = 'cat_' . $category_id . '_' . time() . '.' . $imageFileType;
    $target_file = $target_dir . $new_filename;

    // Check file size (e.g., 1MB limit for icons)
    if ($_FILES[$file_input_name]["size"] > 1000000) {
        return ['error' => 'Sorry, the file is too large (Max 1MB).'];
    }

    $allowed_types = ['jpg', 'png', 'jpeg', 'gif', 'webp', 'svg'];
    if (!in_array($imageFileType, $allowed_types)) {
        return ['error' => 'Sorry, only JPG, PNG, GIF, WEBP & SVG files are allowed.'];
    }

    if (move_uploaded_file($_FILES[$file_input_name]["tmp_name"], $target_file)) {
        return ['path' => 'images/category_icons/' . $new_filename];
    } else {
        return ['error' => 'Sorry, there was an error uploading the file. Check folder permissions.'];
    }
}

// Function for redirection
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
$redirect_url = "../dashboard.php?hal=categories";

switch ($action) {
    case 'add_category':
        $name = mysqli_real_escape_string($data, $_POST['category_name']);
        $order = (int)$_POST['display_order'];

        $sql = "INSERT INTO provider_categories (category_name, display_order) VALUES ('$name', '$order')";
        
        if (mysqli_query($data, $sql)) {
            $new_id = mysqli_insert_id($data);
            // Check for logo upload after we have an ID
            if (isset($_FILES['category_logo']) && $_FILES['category_logo']['error'] == UPLOAD_ERR_OK) {
                $upload = handle_category_logo_upload('category_logo', $new_id);
                if (isset($upload['path'])) {
                    mysqli_query($data, "UPDATE provider_categories SET category_logo = '" . $upload['path'] . "' WHERE id = $new_id");
                } else {
                     redirect_with_alert("Category saved, but logo upload failed: " . $upload['error'], $redirect_url);
                }
            }
            redirect_with_alert("Category added successfully!", $redirect_url);
        } else {
            redirect_with_alert("Database error: " . mysqli_error($data), $redirect_url);
        }
        break;

    case 'edit_category':
        $id = (int)$_POST['id'];
        $name = mysqli_real_escape_string($data, $_POST['category_name']);
        $order = (int)$_POST['display_order'];
        $is_active = (int)$_POST['is_active'];

        $sql = "UPDATE provider_categories SET category_name = '$name', display_order = '$order', is_active = '$is_active' WHERE id = $id";
        
        if (mysqli_query($data, $sql)) {
            // Check for a new logo upload
            if (isset($_FILES['category_logo']) && $_FILES['category_logo']['error'] == UPLOAD_ERR_OK) {
                // Delete old logo if it exists
                $old_logo_res = mysqli_query($data, "SELECT category_logo FROM provider_categories WHERE id = $id");
                if($row = mysqli_fetch_assoc($old_logo_res)){
                    if(!empty($row['category_logo']) && file_exists('../../' . $row['category_logo'])){
                        unlink('../../' . $row['category_logo']);
                    }
                }
                
                $upload = handle_category_logo_upload('category_logo', $id);
                if (isset($upload['path'])) {
                    mysqli_query($data, "UPDATE provider_categories SET category_logo = '" . $upload['path'] . "' WHERE id = $id");
                } else {
                     redirect_with_alert("Category updated, but logo upload failed: " . $upload['error'], $redirect_url);
                }
            }
            redirect_with_alert("Category updated successfully!", $redirect_url);
        } else {
            redirect_with_alert("Database error: " . mysqli_error($data), $redirect_url);
        }
        break;

    case 'delete_category':
        $id = (int)$_POST['id'];

        // Before deleting the category, set providers in this category to NULL
        mysqli_query($data, "UPDATE providers SET category_id = NULL WHERE category_id = $id");

        // Delete the logo file
        $res = mysqli_query($data, "SELECT category_logo FROM provider_categories WHERE id = $id");
        if ($row = mysqli_fetch_assoc($res)) {
            if (!empty($row['category_logo']) && file_exists('../../' . $row['category_logo'])) {
                unlink('../../' . $row['category_logo']);
            }
        }
        
        // Delete the category record
        $sql = "DELETE FROM provider_categories WHERE id = $id";
        if (mysqli_query($data, $sql)) {
            redirect_with_alert("Category deleted successfully!", $redirect_url);
        } else {
            redirect_with_alert("Database error: " . mysqli_error($data), $redirect_url);
        }
        break;

    default:
        redirect_with_alert("Invalid action.", $redirect_url);
}
?>