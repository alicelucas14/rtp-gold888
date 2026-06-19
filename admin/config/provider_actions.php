<?php
require '../assets/config-data.php';

// --- Helper Functions ---

// Function to handle file uploads
function handle_upload($file_input_name, $provider_code) {
    if (!isset($_FILES[$file_input_name]) || $_FILES[$file_input_name]['error'] != UPLOAD_ERR_OK) {
        return ['error' => 'No file was uploaded or an upload error occurred.'];
    }

    $target_dir = "../../images/icons/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $imageFileType = strtolower(pathinfo($_FILES[$file_input_name]["name"], PATHINFO_EXTENSION));
    $new_filename = $provider_code . '.' . $imageFileType;
    $target_file = $target_dir . $new_filename;
    
    // Allow certain file formats
    $allowed_types = ['jpg', 'png', 'jpeg', 'gif', 'webp'];
    if (!in_array($imageFileType, $allowed_types)) {
        return ['error' => 'Sorry, only JPG, JPEG, PNG, GIF & WEBP files are allowed.'];
    }

    // Move the uploaded file, overwriting if it exists
    if (move_uploaded_file($_FILES[$file_input_name]["tmp_name"], $target_file)) {
        return ['path' => 'images/icons/' . $new_filename];
    } else {
        return ['error' => 'Sorry, there was an error uploading your file.'];
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

$action = $_POST['action'] ?? $_GET['action'];
$redirect_url = "../dashboard.php?hal=providers";

switch ($action) {
    case 'add_provider':
        $provider_name = mysqli_real_escape_string($data, $_POST['provider_name']);
        $provider_code = mysqli_real_escape_string($data, strtolower($_POST['provider_code']));
        $provider_rating = (float)$_POST['provider_rating'];
        $category_id = (int)$_POST['category_id']; // Get the category ID

        $upload_result = handle_upload('provider_logo', $provider_code);
        if (isset($upload_result['error'])) {
            redirect_with_alert($upload_result['error'], $redirect_url);
        }
        $logo_path = $upload_result['path'];

        $sql = "INSERT INTO providers (category_id, provider_name, provider_code, provider_logo, provider_rating) VALUES ($category_id, '$provider_name', '$provider_code', '$logo_path', '$provider_rating')";
        
        if (mysqli_query($data, $sql)) {
            redirect_with_alert("Provider added successfully!", $redirect_url);
        } else {
            redirect_with_alert("Error adding provider: " . mysqli_error($data), $redirect_url);
        }
        break;

    case 'edit_provider':
        $id = (int)$_POST['id'];
        $provider_name = mysqli_real_escape_string($data, $_POST['provider_name']);
        $provider_code = mysqli_real_escape_string($data, strtolower($_POST['provider_code']));
        $provider_rating = (float)$_POST['provider_rating'];
        $category_id = (int)$_POST['category_id']; // Get the category ID

        $set_clauses = "provider_name = '$provider_name', provider_code = '$provider_code', provider_rating = '$provider_rating', category_id = $category_id";

        if (isset($_FILES['provider_logo']) && $_FILES['provider_logo']['error'] == UPLOAD_ERR_OK) {
            $upload_result = handle_upload('provider_logo', $provider_code);
            if (isset($upload_result['error'])) {
                redirect_with_alert($upload_result['error'], $redirect_url);
            }
            $logo_path = $upload_result['path'];
            $set_clauses .= ", provider_logo = '$logo_path'";
        }
        
        $sql = "UPDATE providers SET $set_clauses WHERE id = $id";

        if (mysqli_query($data, $sql)) {
            redirect_with_alert("Provider updated successfully!", $redirect_url);
        } else {
            redirect_with_alert("Error updating provider: " . mysqli_error($data), $redirect_url);
        }
        break;

    case 'activate':
        $id = (int)$_GET['id'];
        mysqli_query($data, "UPDATE providers SET is_active = 1 WHERE id = $id");
        redirect_with_alert("Provider activated!", $redirect_url);
        break;

    case 'deactivate':
        $id = (int)$_GET['id'];
        mysqli_query($data, "UPDATE providers SET is_active = 0 WHERE id = $id");
        redirect_with_alert("Provider deactivated!", $redirect_url);
        break;

    default:
        redirect_with_alert("Invalid action.", $redirect_url);
}
?>