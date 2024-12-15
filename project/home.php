<?php
// Imports
require "db.php";

// Variables
global $db;

// Get search term if any
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Simple query with optional search
$sql = "SELECT content.*, users.username 
        FROM content 
        JOIN users ON content.creator_id = users.id 
        WHERE content.status = 'approved'";

// Add search if term entered
if ($search) {
    $sql .= " AND (title LIKE ? OR description LIKE ?)";
}

$stmt = $db->prepare($sql);

// Execute with or without search params
if ($search) {
    $searchTerm = "%$search%";
    $stmt->execute([$searchTerm, $searchTerm]);
} else {
    $stmt->execute();
}

$contents = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulletin Board</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f5f5f8;
            min-height: 100vh;
        }

        .navbar {
            background: #231942;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .nav-actions {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-links {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            font-size: 14px;
            opacity: 0.8;
            transition: opacity 0.3s;
        }

        .nav-link:hover {
            opacity: 1;
        }

        .search-container {
            display: flex;
            gap: 10px;
            background: white;
            padding: 20px;
            margin: 20px auto;
            max-width: 1200px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(35,25,66,0.1);
        }

        .search-box {
            flex: 1;
            display: flex;
            gap: 10px;
        }

        .search-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #231942;
            color: white;
        }

        .btn-primary:hover {
            background: #362a55;
        }

        .btn-outline {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.4);
            color: white;
        }

        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .content-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(35,25,66,0.1);
            transition: transform 0.3s;
        }

        .content-card:hover {
            transform: translateY(-5px);
        }

        .content-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .content-info {
            padding: 20px;
        }

        .content-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #231942;
        }

        .content-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            background: #2d8a61;
            color: white;
        }
        .content-image-container {
            position: relative;
            width: 100%;
            padding-bottom: 66.67%; /* Creates a 3:2 aspect ratio */
            overflow: hidden;
        }

        .content-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
        .content-image {
            width: 100%;
            height: auto; /* removes fixed height */
            max-height: 200px; /* optional: sets maximum height */
            object-fit: contain;
        }
    </style>
</head>
<body>
<nav class="navbar">
    <div class="logo">Bulletin Board</div>
    <div class="nav-actions">
        <div class="nav-links">
            <a href="api/allContentJson.php" class="nav-link">JSON API</a>
        </div>
        <a href="./login.php" class="btn btn-outline">
            <i class="fas fa-user"></i>
            Login
        </a>
    </div>
</nav>

<div class="search-container">
    <form method="GET" action="" class="search-box">
        <input type="text"
               name="search"
               class="search-input"
               placeholder="Search content..."
               value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>

<div class="content-grid">
    <!-- Content Cards -->
    <div class="content-grid">
        <?php foreach ($contents as $content):
            echo "<div class='content-card'>
            <div class='content-image-container'>
                <img src='{$content['image_path']}' 
                     alt='{$content['title']}' 
                     class='content-image'>
            </div>
            <div class='content-info'>
                <div class='content-meta'>
                    <span><i class='fas fa-user'></i> {$content['username']}</span>
                </div>
                <div class='content-title'>{$content['title']}</div>
                <p>{$content['description']}</p>
            </div>
        </div>";
        endforeach; ?>
    </div>
</div>
</body>
</html>