<?php
session_start();
require_once "./db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? '';
    $userId = $_SESSION['user_id'] ?? 1; // Default user ID for debugging

    echo "Debug: Title - $title, Description - $description, User ID - $userId<br>";

    // Validate input fields
    if (empty($title) || empty($description) || empty($category)) {
        die("Error: All fields are required.");
    }

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $fileType = $_FILES['image']['type'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Define allowed types and size
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $maxFileSize = 2 * 1024 * 1024; // 2 MB

        echo "Debug: File Name - $fileName, Type - $fileType, Size - $fileSize<br>";

        // Validate file type
        if (!in_array($fileExt, $allowedTypes)) {
            die("Error: Unsupported file type. Allowed types: JPG, PNG, GIF.");
        }

        // Validate file size
        if ($fileSize > $maxFileSize) {
            die("Error: File size exceeds 2 MB.");
        }

        // Upload directory
        $uploadDir = './uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create directory with permissions
        }

        // Generate a unique name for the file
        $newFileName = uniqid('img_', true) . '.' . $fileExt;
        $destPath = $uploadDir . $newFileName;

        // Move the file
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            echo "File uploaded successfully to $destPath<br>";

            // Insert data into the database
            $stmt = $conn->prepare("INSERT INTO content (creator_id, title, description, image_path, img_category, status) VALUES (?, ?, ?, ?, ?, 'pending')");
            if (!$stmt) {
                die("SQL error: ");
            }

            $stmt->bind_param("issss", $userId, $title, $description, $destPath, $category);
            if ($stmt->execute()) {
                echo "Content added successfully!";
            } else {
                echo "Database error: ";
            }
            $stmt->close();
        } else {
            die("Error: Failed to move uploaded file.");
        }
    } else {
        $error = $_FILES['image']['error'];
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => "File exceeds the upload_max_filesize directive.",
            UPLOAD_ERR_FORM_SIZE => "File exceeds the MAX_FILE_SIZE directive.",
            UPLOAD_ERR_PARTIAL => "File only partially uploaded.",
            UPLOAD_ERR_NO_FILE => "No file uploaded.",
            UPLOAD_ERR_NO_TMP_DIR => "Missing temporary folder.",
            UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
            UPLOAD_ERR_EXTENSION => "A PHP extension stopped the file upload."
        ];
        die("Error: " . ($errorMessages[$error] ?? "Unknown error."));
    } 
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Content</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        document.getElementById('image').addEventListener('change', function (event) {
    const [file] = event.target.files;
    if (file) {
        const preview = document.getElementById('preview');
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    }
});

    </script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background: #f5f5f8;
        }

        .sidebar {
            width: 250px;
            background: #231942;
            color: white;
            padding: 20px 0;
            position: fixed;
            height: 100vh;
        }

        .logo {
            text-align: center;
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
            border-bottom: 1px solid #362a55;
            margin-bottom: 20px;
        }

        .nav-links {
            list-style: none;
        }

        .nav-item {
            padding: 15px 25px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            text-decoration: none;
        }

        .nav-item:hover {
            background: #362a55;
        }

        .nav-item i {
            width: 20px;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(35,25,66,0.1);
            margin-bottom: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #231942;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(35,25,66,0.1);
            max-width: 800px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #231942;
            font-weight: 500;
        }

        .form-group input[type="text"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
        }

        .form-group input[type="text"]:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #231942;
        }

        .form-group textarea {
            height: 150px;
            resize: vertical;
        }

        .image-upload {
            border: 2px dashed #ddd;
            padding: 30px;
            text-align: center;
            border-radius: 6px;
            background: #f8f7fa;
            cursor: pointer;
            transition: border-color 0.3s;
        }

        .image-upload:hover {
            border-color: #231942;
        }

        .image-upload i {
            font-size: 40px;
            color: #231942;
            margin-bottom: 10px;
        }

        .image-upload p {
            color: #666;
            margin: 10px 0;
        }

        .image-preview {
            max-width: 100%;
            max-height: 300px;
            margin-top: 20px;
            display: none;
            border-radius: 6px;
        }

        .btn-container {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            flex: 1;
            transition: background 0.3s;
        }

        .btn-primary {
            background: #231942;
            color: white;
        }

        .btn-primary:hover {
            background: #362a55;
        }

        .btn-secondary {
            background: #666;
            color: white;
        }

        .btn-secondary:hover {
            background: #555;
        }

        input[type="file"] {
            display: none;
        }
    </style>
    <script>
    document.getElementById('image').addEventListener('change', function (event) {
        const [file] = event.target.files;
        if (file) {
            const preview = document.getElementById('preview');
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }
    });
</script>
</head>
<body>
<!-- Sidebar -->
<nav class="sidebar">
    <div class="logo">
        Content Creator
    </div>
    <ul class="nav-links">
        <li>
            <a href="logout.php" class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </li>
    </ul>
</nav>

<!-- Main Content -->
<div class="main-content">
    <div class="header">
        <h1>Create New Content</h1>
        <div class="user-info">
            <i class="fas fa-user-circle"></i>
            <span>John Doe</span>
        </div>
    </div>

    <div class="form-container">
        <form action="save_content.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required placeholder="Enter content title">
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="">Select a category</option>
                    <option value="events">Events</option>
                    <option value="sports">Sports</option>
                    <option value="announcement">Announcement</option>
                    <option value="academic">Academic</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required placeholder="Enter content description"></textarea>
            </div>

            <div class="form-group">
                <label>Image</label>
                <label for="image" class="image-upload">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Click to upload image</p>
                    <p class="small">Supported formats: JPG, PNG, GIF</p>
                </label>
                <input type="file" id="image" name="image" accept="image/*" required>
                <img id="preview" class="image-preview">
            </div>

            <div class="btn-container">
                <button type="button" onclick="window.location.href='contentCreatorDashboard.php'" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Content</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
