<?php
/**
 * Report Submission Script for Cityna Project
 * Processes the citizen report form submission
 */

// Include database connection
require_once 'db_connection.php';

// Start session
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $full_name = trim(filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING));
    $phone_number = trim(filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_STRING));
    $commune = trim(filter_input(INPUT_POST, 'commune', FILTER_SANITIZE_STRING));
    $wilaya = trim(filter_input(INPUT_POST, 'wilaya', FILTER_SANITIZE_STRING));
    $geolocation = trim(filter_input(INPUT_POST, 'geolocation', FILTER_SANITIZE_STRING));
    $description = trim(filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING));
    $category = trim(filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING));
    $subcategory = trim(filter_input(INPUT_POST, 'subcategory', FILTER_SANITIZE_STRING));
    
    // Validate form data
    $errors = [];

    // Validate required fields
    if (empty($full_name)) {
        $errors[] = "Full name is required";
    }
    
    if (empty($phone_number)) {
        $errors[] = "Phone number is required";
    }
    
    if (empty($commune)) {
        $errors[] = "Commune is required";
    }
    
    if (empty($wilaya)) {
        $errors[] = "Wilaya is required";
    }
    
    if (empty($category)) {
        $errors[] = "Category is required";
    }
    
    if (empty($subcategory)) {
        $errors[] = "Subcategory is required";
    }

    // Validate image upload
    if (!isset($_FILES['report_image']) || $_FILES['report_image']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Image upload is required";
    } else {
        // Check file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['report_image']['type'];
        
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "Only JPEG, PNG, and GIF images are allowed";
        }
        
        // Check file size (max 5MB)
        $max_size = 5 * 1024 * 1024; // 5MB in bytes
        if ($_FILES['report_image']['size'] > $max_size) {
            $errors[] = "Image size cannot exceed 5MB";
        }
    }

    // If no errors, proceed with saving the report
    if (empty($errors)) {
        // Get category ID
        $category_sql = "SELECT category_id FROM categories WHERE name = ?";
        $category_result = dbQuery($category_sql, [$category]);
        
        if (!$category_result || count($category_result) === 0) {
            $errors[] = "Invalid category";
        } else {
            $category_id = $category_result[0]['category_id'];
            
            // Get subcategory ID
            $subcategory_sql = "SELECT subcategory_id FROM subcategories WHERE name = ? AND category_id = ?";
            $subcategory_result = dbQuery($subcategory_sql, [$subcategory, $category_id]);
            
            if (!$subcategory_result || count($subcategory_result) === 0) {
                $errors[] = "Invalid subcategory";
            } else {
                $subcategory_id = $subcategory_result[0]['subcategory_id'];
                
                // Process image upload
                $upload_dir = 'uploads/reports/';
                
                // Create directory if it doesn't exist
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                // Generate unique filename
                $file_extension = pathinfo($_FILES['report_image']['name'], PATHINFO_EXTENSION);
                $new_filename = uniqid('report_') . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;
                
                // Move uploaded file
                if (move_uploaded_file($_FILES['report_image']['tmp_name'], $upload_path)) {
                    // Get user ID from session if logged in
                    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
                    
                    // Insert report into database
                    $sql = "INSERT INTO reports (user_id, full_name, phone_number, commune, wilaya, geolocation, 
                            description, category_id, subcategory_id, image_path) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    
                    $report_id = dbInsert($sql, [
                        $user_id, $full_name, $phone_number, $commune, $wilaya, $geolocation,
                        $description, $category_id, $subcategory_id, $upload_path
                    ]);
                    
                    if ($report_id) {
                        // Report successfully saved
                        header("Location: form-filled.html");
                        exit;
                    } else {
                        $errors[] = "Failed to save report. Please try again.";
                        // Delete uploaded file if database insert failed
                        if (file_exists($upload_path)) {
                            unlink($upload_path);
                        }
                    }
                } else {
                    $errors[] = "Failed to upload image. Please try again.";
                }
            }
        }
    }

    // If there are errors, store them in session to display on the form
    if (!empty($errors)) {
        $_SESSION['report_errors'] = $errors;
        $_SESSION['report_form_data'] = [
            'full_name' => $full_name,
            'phone_number' => $phone_number,
            'commune' => $commune,
            'wilaya' => $wilaya,
            'geolocation' => $geolocation,
            'description' => $description,
            'category' => $category,
            'subcategory' => $subcategory
        ];
        header("Location: citizen-form.html");
        exit;
    }
} else {
    // If accessed directly without form submission
    header("Location: citizen-form.html");
    exit;
}
?>