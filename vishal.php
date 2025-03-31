<?php 
session_start();

// Check if this is a JSON test request
if (isset($_GET['json_test'])) {
    // Send only pure JSON with proper headers
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'JSON test successful']);
    exit;
}

// Handle site logout
if(isset($_POST['logout'])) {
    // Destroy the session
    session_unset();
    session_destroy();
    
    // Redirect to login page
    header("Location: index.html");
    exit;
}

// Check for messages in URL parameters
$show_message = '';
if(isset($_GET['status'])) {
    if($_GET['status'] == 'updated') {
        $show_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
            User updated successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    } elseif($_GET['status'] == 'error') {
        $show_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Failed to update user. Please try again.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }
}

// Check if user is logged in
if(!isset($_SESSION["user_id"])) {
    header("Location: index.html");
    exit;
}

include 'dbconnect.php';

// Get user ID for display
$uid = $_SESSION["user_id"];

// Hardcoded vishal password - change this to your desired password
$admin_password = "flix2u"; 

// Handle password verification
$password_verified = isset($_SESSION['vishal_verified']) && $_SESSION['vishal_verified'] === true;
$password_error = "";

if(isset($_POST['verify_password'])) {
    $entered_password = $_POST['admin_password'];
    
    if($entered_password === $admin_password) {
        $_SESSION['vishal_verified'] = true;
        $password_verified = true;
    } else {
        $password_error = "Incorrect password. Please try again.";
    }
}

// Handle vishal logout
if(isset($_POST['logout_vishal'])) {
    $_SESSION['vishal_verified'] = false;
    $password_verified = false;
    // Redirect to refresh the page
    header("Location: vishal.php");
    exit;
}

// Handle actions if password verified
if($password_verified && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    // User management actions
    if($action == 'delete_user') {
        $user_id = $_POST['user_id'];
        $stmt = $con->prepare("DELETE FROM `regform` WHERE userid = ?");
        $stmt->bind_param("s", $user_id);
        if($stmt->execute()) {
            echo "<script>alert('User deleted successfully.');</script>";
        } else {
            echo "<script>alert('Failed to delete user.');</script>";
        }
    }
    
    // Content management actions
    elseif($action == 'delete_content') {
        $content_id = $_POST['content_id'];
        $stmt = $con->prepare("DELETE FROM `content` WHERE id = ?");
        $stmt->bind_param("i", $content_id);
        if($stmt->execute()) {
            echo "<script>alert('Content deleted successfully.');</script>";
        } else {
            echo "<script>alert('Failed to delete content.');</script>";
        }
    }
    elseif($action == 'update_content') {
        // Start output buffering to capture and discard unwanted output
        ob_start();
        
        $content_id = $_POST['content_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $content = $_POST['content'];
        
        // Debug information
        $debug_info = "Content ID: $content_id, Title: $title";
        error_log("Attempting content update: $debug_info");
        
        // Direct update without redirects
        $stmt = $con->prepare("UPDATE `content` SET title = ?, descr = ?, content = ? WHERE id = ?");
        $stmt->bind_param("sssi", $title, $description, $content, $content_id);
        
        $result = $stmt->execute();
        $affected = $stmt->affected_rows;
        
        // Clean any buffered output before sending JSON
        ob_end_clean();
        
        // Set proper JSON header
        header('Content-Type: application/json');
        
        if($result) {
            // Check if any rows were actually updated
            if($stmt->affected_rows > 0) {
                $success_message = "Content ID #$content_id has been updated successfully.";
                error_log("Content update success: " . $success_message);
                echo json_encode(['success' => true, 'message' => $success_message, 'debug' => $debug_info]);
            } else {
                $warning_message = "Content update statement executed but no rows were affected. This usually means the data you entered is identical to what's already in the database.";
                error_log("Content update warning: " . $warning_message);
                echo json_encode(['success' => true, 'message' => $warning_message, 'debug' => $debug_info]);
            }
        } else {
            $error_message = "Failed to update content ID #$content_id. Error: " . $stmt->error;
            error_log("Content update error: " . $error_message);
            echo json_encode(['success' => false, 'message' => $error_message, 'debug' => $debug_info]);
        }
        
        // Exit to prevent further output
        exit;
    }
    elseif($action == 'update_user') {
        // Disable any error output that could contaminate JSON
        ini_set('display_errors', 0);
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
        
        // Start output buffering to capture and discard any unwanted output
        ob_start();
        ob_clean(); // Clean any previous output
        
        $user_id = $_POST['user_id'];
        $email = $_POST['email'];
        $gender = $_POST['gender'];
        $phone = $_POST['phone'];
        
        // Debug information
        $debug_info = "User ID: $user_id, Email: $email, Gender: $gender, Phone: $phone";
        error_log("Attempting user update: $debug_info");
        
        // Prepare and execute update
        $update_successful = false;
        $response_message = '';
        $affected_rows = 0;
        
        try {
            // Directly update without any redirects
            $stmt = $con->prepare("UPDATE `regform` SET email = ?, gender = ?, phone = ? WHERE userid = ?");
            $stmt->bind_param("ssss", $email, $gender, $phone, $user_id);
            $result = $stmt->execute();
            $affected_rows = $stmt->affected_rows;
            
            if($result) {
                if($affected_rows > 0) {
                    $update_successful = true;
                    $response_message = "User '$user_id' has been updated successfully.";
                } else {
                    $update_successful = true;
                    $response_message = "Update executed, but no rows were affected. Data might be identical.";
                }
            } else {
                $response_message = "Database update failed: " . $stmt->error;
            }
        } catch (Exception $e) {
            $response_message = "Exception: " . $e->getMessage();
        }
        
        // Clear the output buffer completely
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Make sure nothing has been sent yet
        if (!headers_sent()) {
            // Set headers for JSON response
            header('Content-Type: application/json');
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Pragma: no-cache");
        } else {
            error_log("Headers already sent before JSON output!");
        }
        
        // Build the response array
        $response = [
            'success' => $update_successful,
            'message' => $response_message,
            'debug' => $debug_info,
            'affected_rows' => $affected_rows
        ];
        
        // Encode and output the JSON - make this the ONLY output
        echo json_encode($response);
        
        // End script execution immediately to prevent any other output
        exit();
    }
}

// Display any update messages from session
$update_message = '';
if(isset($_SESSION['update_message'])) {
    $update_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
        ' . $_SESSION['update_message'] . '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    unset($_SESSION['update_message']); // Clear the message after displaying
}

$update_error = '';
if(isset($_SESSION['update_error'])) {
    $update_error = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        ' . $_SESSION['update_error'] . '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    unset($_SESSION['update_error']); // Clear the error after displaying
}

// Functions to get data
function getUsers($con) {
    $sql = "SELECT * FROM `regform` ORDER BY userid";
    $result = $con->query($sql);
    return $result;
}

function getContent($con) {
    $sql = "SELECT * FROM `content` ORDER BY id DESC";
    $result = $con->query($sql);
    return $result;
}

// Add these variables near the top of the file where other messages are defined
$success_message = '';
$error_message = '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Lekhak - Vishal Dashboard">
    <meta name="theme-color" content="#2c3e50">
    <title>Lekhak - Vishal Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-section {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            overflow: hidden;
        }
        .admin-header {
            background-color: var(--primary-color);
            color: var(--text-light);
            padding: 1rem 1.5rem;
            border-bottom: 3px solid var(--secondary-color);
        }
        .table-responsive {
            max-height: 600px;
            overflow-y: auto;
        }
        .action-btns .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .tab-content {
            padding: 1.5rem;
        }
        .nav-tabs .nav-link {
            color: var(--primary-color);
            font-weight: 600;
        }
        .nav-tabs .nav-link.active {
            color: var(--secondary-color);
            font-weight: 700;
            border-bottom: 3px solid var(--secondary-color);
        }
        .badge-admin {
            background-color: var(--secondary-color);
        }
        .content-preview {
            max-height: 100px;
            overflow: hidden;
            position: relative;
        }
        .content-preview:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 30px;
            background: linear-gradient(transparent, white);
        }
        .password-section {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 2rem;
            margin-bottom: 2rem;
            border-left: 5px solid var(--secondary-color);
        }
    </style>
</head>
<body>
    <div class="main">
        <div class="container">
            <h1 class="display-4 mb-0"><i class="fas fa-pen-fancy"></i> Lekhak</h1><p class="tagline lead mt-0">A creative space for writers and storytellers</p>
        </div>
    </div>
    
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="vishal.php">
                            <i class="fas fa-user-shield me-2"></i>Vishal Dashboard
                        </a>
                    </li>
                </ul>
            </div>
            <div class="ms-auto">
                <a href="home.php" class="btn btn-outline-light btn-sm me-2">
                    <i class="fas fa-home me-1"></i>Back to Site
                </a>
                <form method="post" class="d-inline">
                    <button type="submit" name="logout" class="btn btn-danger btn-sm me-2">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </button>
                </form>
                <?php echo "<span id='userpara' class='navbar-text'>$uid</span>"?>
            </div>
        </div>
    </nav>
    
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="section-heading">
                    <i class="fas fa-user-shield me-2"></i>Vishal Dashboard
                </h2>
                <p class="text-light">Manage users and moderate content on the Lekhak platform.</p>
                
                <?php 
                // Display message from URL parameter
                if(!empty($show_message)) {
                    echo $show_message;
                }
                
                // Show direct success/error messages
                if(!empty($success_message)) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        ' . $success_message . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }
                
                if(!empty($error_message)) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ' . $error_message . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }
                
                // Also show session messages if they exist
                echo $update_message;
                echo $update_error;
                ?>
                
                <?php 
                // Debug information
                if(isset($_SESSION['update_attempted'])) {
                    echo '<div class="alert alert-info">';
                    echo '<h5>Debug Info:</h5>';
                    echo '<p>Update attempted with: ' . print_r($_SESSION['update_data'], true) . '</p>';
                    if(isset($_SESSION['debug_sql'])) {
                        echo '<p>SQL: ' . $_SESSION['debug_sql'] . '</p>';
                    }
                    echo '</div>';
                    
                    // Clear debug info after showing it once
                    unset($_SESSION['update_attempted']);
                    unset($_SESSION['update_data']);
                    unset($_SESSION['debug_sql']);
                }
                ?>
            </div>
        </div>
        
        <?php if(!$password_verified): ?>
        <!-- Password Verification Section -->
        <div class="row justify-content-center mb-5">
            <div class="col-md-6">
                <div class="password-section">
                    <h3 class="text-center mb-4 position-relative pb-3">
                        <i class="fas fa-lock me-2"></i>Vishal Verification
                        <span class="position-absolute start-50 translate-middle-x bottom-0" style="width: 60px; height: 3px; background-color: var(--secondary-color); border-radius: 3px;"></span>
                    </h3>
                    <p class="text-center mb-4">Please enter the admin password to access the dashboard.</p>
                    
                    <form method="post" action="">
                        <div class="mb-4">
                            <label for="admin_password" class="form-label">
                                <i class="fas fa-key me-2"></i>Password
                            </label>
                            <div class="input-group">
                                <input type="password" name="admin_password" id="admin_password" class="form-control" required>
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <?php if(!empty($password_error)): ?>
                                <div class="text-danger mt-2"><?php echo $password_error; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" name="verify_password" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>Verify & Continue
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php else: ?>
        
        <div class="admin-section">
            <ul class="nav nav-tabs" id="adminTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab" aria-controls="users" aria-selected="true">
                        <i class="fas fa-users me-2"></i>User Management
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="content-tab" data-bs-toggle="tab" data-bs-target="#content" type="button" role="tab" aria-controls="content" aria-selected="false">
                        <i class="fas fa-file-alt me-2"></i>Content Moderation
                    </button>
                </li>
                <li class="nav-item ms-auto">
                    <form method="post" action="">
                        <button type="submit" name="logout_vishal" class="btn btn-outline-danger btn-sm mt-1 me-2">
                            <i class="fas fa-lock me-1"></i>Lock Vishal Panel
                        </button>
                    </form>
                </li>
            </ul>
            
            <div class="tab-content" id="adminTabsContent">
                <!-- User Management Tab -->
                <div class="tab-pane fade show active" id="users" role="tabpanel" aria-labelledby="users-tab">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Gender</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $users = getUsers($con);
                                if($users->num_rows > 0) {
                                    while($user = $users->fetch_assoc()) {
                                        $user_id = $user['userid'];
                                        $is_admin = isset($user['admin']) && $user['admin'] == 1;
                                        $role_badge = $is_admin ? 
                                            '<span class="badge bg-success">Admin</span>' : 
                                            '<span class="badge bg-secondary">User</span>';
                                        
                                        echo '<tr>
                                            <td>'.htmlspecialchars($user_id).'</td>
                                            <td>'.htmlspecialchars($user['email']).'</td>
                                            <td>'.htmlspecialchars($user['gender']).'</td>
                                            <td>'.htmlspecialchars($user['phone']).'</td>
                                            <td>'.$role_badge.'</td>
                                            <td class="action-btns">
                                                <button type="button" class="btn btn-primary btn-sm edit-user" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editUserModal" 
                                                    data-id="'.htmlspecialchars($user_id).'"
                                                    data-email="'.htmlspecialchars($user['email']).'"
                                                    data-gender="'.htmlspecialchars($user['gender']).'"
                                                    data-phone="'.htmlspecialchars($user['phone']).'">
                                                    <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                <form method="post" class="d-inline" onsubmit="return confirm(\'Are you sure you want to delete this user? This action cannot be undone.\')">
                                                    <input type="hidden" name="action" value="delete_user">
                                                    <input type="hidden" name="user_id" value="'.$user_id.'">
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash-alt"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="6" class="text-center">No users found.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Content Moderation Tab -->
                <div class="tab-pane fade" id="content" role="tabpanel" aria-labelledby="content-tab">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>User</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $contents = getContent($con);
                                if($contents->num_rows > 0) {
                                    while($content = $contents->fetch_assoc()) {
                                        $content_id = $content['id'];
                                        $category = $content['category'];
                                        $badge_class = "bg-primary";
                                        
                                        if($category == "poem") {
                                            $badge_class = "bg-danger";
                                        } else if($category == "article") {
                                            $badge_class = "bg-success";
                                        }
                                        
                                        echo '<tr>
                                            <td>'.$content_id.'</td>
                                            <td>'.htmlspecialchars($content['title']).'</td>
                                            <td>'.htmlspecialchars($content['userid']).'</td>
                                            <td><span class="badge '.$badge_class.'">'.htmlspecialchars($category).'</span></td>
                                            <td>'.htmlspecialchars($content['descr']).'</td>
                                            <td class="action-btns">
                                                <button type="button" class="btn btn-info btn-sm view-content" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#viewContentModal" 
                                                    data-id="'.$content_id.'"
                                                    data-title="'.htmlspecialchars($content['title']).'"
                                                    data-desc="'.htmlspecialchars($content['descr']).'"
                                                    data-content="'.htmlspecialchars($content['content']).'"
                                                    data-category="'.htmlspecialchars($category).'">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                                <button type="button" class="btn btn-primary btn-sm edit-content" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editContentModal" 
                                                    data-id="'.$content_id.'"
                                                    data-title="'.htmlspecialchars($content['title']).'"
                                                    data-desc="'.htmlspecialchars($content['descr']).'"
                                                    data-content="'.htmlspecialchars($content['content']).'">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <form method="post" class="d-inline" onsubmit="return confirm(\'Are you sure you want to delete this content? This action cannot be undone.\')">
                                                    <input type="hidden" name="action" value="delete_content">
                                                    <input type="hidden" name="content_id" value="'.$content_id.'">
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash-alt"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="6" class="text-center">No content found.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- View Content Modal -->
    <div class="modal fade" id="viewContentModal" tabindex="-1" aria-labelledby="viewContentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewContentModalLabel">View Content</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h3 id="view-title" class="mb-3"></h3>
                    <p><span class="badge" id="view-category"></span></p>
                    <div class="mb-3">
                        <strong>Description:</strong>
                        <p id="view-desc"></p>
                    </div>
                    <div>
                        <strong>Content:</strong>
                        <p id="view-content" style="white-space: pre-line;"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Content Modal -->
    <div class="modal fade" id="editContentModal" tabindex="-1" aria-labelledby="editContentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editContentModalLabel">Edit Content</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-content-form" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update_content">
                        <input type="hidden" name="content_id" id="edit-content-id">
                        
                        <div class="mb-3">
                            <label for="edit-content-title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="edit-content-title" name="title" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit-content-description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="edit-content-description" name="description" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit-content-text" class="form-label">Content</label>
                            <textarea class="form-control" id="edit-content-text" name="content" rows="10" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="save-content-changes">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" id="edit-user-form">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update_user">
                        <input type="hidden" name="user_id" id="edit-user-id">
                        
                        <div class="mb-3">
                            <label for="edit-username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="edit-username" disabled>
                            <div class="form-text text-muted">Username cannot be changed.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit-email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="edit-male" value="male" required>
                                    <label class="form-check-label" for="edit-male">Male</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="edit-female" value="female" required>
                                    <label class="form-check-label" for="edit-female">Female</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit-phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="edit-phone" name="phone" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="save-user-changes">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JSON diagnostic tools -->
    <script>
        // Diagnostic function for checking JSON response issues
        function testJsonEndpoint() {
            // Show a diagnostic message
            const container = document.querySelector('.container.py-4 .row.mb-4 .col-12');
            const diagDiv = document.createElement('div');
            diagDiv.className = 'alert alert-info';
            diagDiv.innerHTML = 'Testing JSON endpoint...';
            container.insertBefore(diagDiv, container.firstChild);
            
            // Test the endpoint
            fetch('vishal.php?json_test=1')
                .then(response => {
                    diagDiv.innerHTML += `<br>Response status: ${response.status}`;
                    diagDiv.innerHTML += `<br>Content-Type: ${response.headers.get('content-type')}`;
                    return response.text();
                })
                .then(text => {
                    diagDiv.innerHTML += `<br>Raw response: ${text}`;
                    try {
                        const json = JSON.parse(text);
                        diagDiv.innerHTML += `<br>Parsed JSON: ${JSON.stringify(json)}`;
                        diagDiv.className = 'alert alert-success';
                        diagDiv.innerHTML += `<br><strong>JSON endpoint is working correctly!</strong>`;
                    } catch (e) {
                        diagDiv.innerHTML += `<br>JSON parse error: ${e.message}`;
                        diagDiv.className = 'alert alert-danger';
                        diagDiv.innerHTML += `<br><strong>There is a problem with JSON responses!</strong>`;
                    }
                })
                .catch(error => {
                    diagDiv.innerHTML += `<br>Fetch error: ${error.message}`;
                    diagDiv.className = 'alert alert-danger';
                });
        }

        // Add a debug button to the top of the page
        function addDebugButton() {
            const container = document.querySelector('.container.py-4 .row.mb-4 .col-12');
            if (!container) return;
            
            const debugBtn = document.createElement('button');
            debugBtn.className = 'btn btn-sm btn-info mb-3';
            debugBtn.innerHTML = 'Test JSON Endpoint';
            debugBtn.addEventListener('click', testJsonEndpoint);
            
            container.insertBefore(debugBtn, container.firstChild);
        }

        // Run these functions when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Add the debug button when on the dashboard
            if (document.querySelector('.container.py-4 .row.mb-4 .col-12')) {
                addDebugButton();
            }
            
            // Run the JSON test if URL parameter is present
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('debug_json')) {
                testJsonEndpoint();
            }
            
            // Handle password toggle visibility
            const togglePassword = document.getElementById('togglePassword');
            if(togglePassword) {
                const passwordInput = document.getElementById('admin_password');
                
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    // Toggle the eye icon
                    this.querySelector('i').classList.toggle('fa-eye');
                    this.querySelector('i').classList.toggle('fa-eye-slash');
                });
            }
            
            // Setup user edit form
            const editUserButtons = document.querySelectorAll('.edit-user');
            const editUserForm = document.getElementById('edit-user-form');
            
            // Add event listeners to all edit user buttons
            editUserButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Get user data from button attributes
                    const id = this.getAttribute('data-id');
                    const email = this.getAttribute('data-email');
                    const gender = this.getAttribute('data-gender');
                    const phone = this.getAttribute('data-phone');
                    
                    console.log('Editing user:', id, email, gender, phone);
                    
                    // Populate the form fields
                    document.getElementById('edit-user-id').value = id;
                    document.getElementById('edit-username').value = id;
                    document.getElementById('edit-email').value = email;
                    document.getElementById('edit-phone').value = phone;
                    
                    // Set gender radio button
                    if (gender && gender.toLowerCase() === 'male') {
                        document.getElementById('edit-male').checked = true;
                    } else if (gender && gender.toLowerCase() === 'female') {
                        document.getElementById('edit-female').checked = true;
                    }
                });
            });
            
            // Handle user edit form submission
            if (editUserForm) {
                editUserForm.addEventListener('submit', function(e) {
                    e.preventDefault(); // Prevent normal form submission
                    
                    // Show a loading indicator
                    const saveButton = this.querySelector('button[type="submit"]');
                    const originalButtonText = saveButton.innerHTML;
                    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                    saveButton.disabled = true;
                    
                    // Get form data
                    const formData = new FormData(this);
                    
                    // Log form data for debugging
                    console.log('Submitting form data:');
                    for (const pair of formData.entries()) {
                        console.log(pair[0] + ': ' + pair[1]);
                    }
                    
                    // Use fetch API to submit form without page reload
                    fetch('vishal.php', {
                        method: 'POST',
                        body: formData,
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            console.warn("Received non-JSON response:", contentType);
                            return response.text().then(text => {
                                console.log("Raw response:", text);
                                throw new Error('Server did not return JSON. Check error logs.');
                            });
                        }
                    })
                    .then(data => {
                        // Reset button state
                        saveButton.innerHTML = originalButtonText;
                        saveButton.disabled = false;
                        
                        // Close the modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
                        modal.hide();
                        
                        // Create appropriate message
                        const alertClass = data.success ? 'alert-success' : 'alert-danger';
                        const alertDiv = document.createElement('div');
                        alertDiv.className = `alert ${alertClass} alert-dismissible fade show`;
                        alertDiv.innerHTML = `
                            ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        
                        // If there's debug info, add it for admins
                        if (data.debug) {
                            const debugInfo = document.createElement('div');
                            debugInfo.className = 'mt-2 small text-muted';
                            debugInfo.textContent = 'Debug: ' + data.debug;
                            alertDiv.appendChild(debugInfo);
                        }
                        
                        // Insert the message at the top of the dashboard
                        const container = document.querySelector('.container.py-4 .row.mb-4 .col-12');
                        container.insertBefore(alertDiv, container.firstChild.nextSibling);
                        
                        // Only update the UI if the update was successful
                        if (data.success) {
                            // Update the user data in the table
                            updateUserInTable(formData);
                            
                            // For debugging - refresh the page after 5 seconds to verify DB state
                            setTimeout(() => {
                                location.reload();
                            }, 5000);
                        }
                    })
                    .catch(error => {
                        // Reset button state
                        saveButton.innerHTML = originalButtonText;
                        saveButton.disabled = false;
                        
                        console.error('Error:', error);
                        
                        // Show error message
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                        alertDiv.innerHTML = `
                            Failed to update user: ${error.message}. Check browser console for details.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        
                        // Insert the error message
                        const container = document.querySelector('.container.py-4 .row.mb-4 .col-12');
                        container.insertBefore(alertDiv, container.firstChild.nextSibling);
                    });
                });
            }
            
            // Function to update the user data in the table without reloading
            function updateUserInTable(formData) {
                const userId = formData.get('user_id');
                const email = formData.get('email');
                const gender = formData.get('gender');
                const phone = formData.get('phone');
                
                console.log('Updating table for user:', userId, email, gender, phone);
                
                // Find the user row in the table
                const userRows = document.querySelectorAll('tbody tr');
                userRows.forEach(row => {
                    const userIdCell = row.querySelector('td:first-child');
                    if (userIdCell && userIdCell.textContent === userId) {
                        console.log('Found user row:', row);
                        
                        // Update the row data
                        row.querySelectorAll('td')[1].textContent = email;
                        row.querySelectorAll('td')[2].textContent = gender;
                        row.querySelectorAll('td')[3].textContent = phone;
                        
                        // Update the data attributes on the edit button
                        const editButton = row.querySelector('.edit-user');
                        if (editButton) {
                            editButton.setAttribute('data-email', email);
                            editButton.setAttribute('data-gender', gender);
                            editButton.setAttribute('data-phone', phone);
                        }
                    }
                });
            }
            
            // Handle content view modal
            const viewButtons = document.querySelectorAll('.view-content');
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const title = this.getAttribute('data-title');
                    const desc = this.getAttribute('data-desc');
                    const content = this.getAttribute('data-content');
                    const category = this.getAttribute('data-category');
                    
                    document.getElementById('view-title').textContent = title;
                    document.getElementById('view-desc').textContent = desc;
                    document.getElementById('view-content').textContent = content;
                    
                    const categoryBadge = document.getElementById('view-category');
                    categoryBadge.textContent = category;
                    
                    // Set the badge class based on category
                    categoryBadge.className = 'badge';
                    if (category.toLowerCase() === 'poem') {
                        categoryBadge.classList.add('bg-danger');
                    } else if (category.toLowerCase() === 'article') {
                        categoryBadge.classList.add('bg-success');
                    } else {
                        categoryBadge.classList.add('bg-primary');
                    }
                });
            });
            
            // Handle content edit buttons
            const editContentButtons = document.querySelectorAll('.edit-content');
            editContentButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const title = this.getAttribute('data-title');
                    const desc = this.getAttribute('data-desc');
                    const content = this.getAttribute('data-content');
                    
                    document.getElementById('edit-content-id').value = id;
                    document.getElementById('edit-content-title').value = title;
                    document.getElementById('edit-content-description').value = desc;
                    document.getElementById('edit-content-text').value = content;
                });
            });
        });
    </script>
</body>
</html> 