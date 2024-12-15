<?php
// Start the session for the User
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'content_creator') {
    header("Location:login.php");
    exit();
}

// Imports
require "db.php";

// Variables
$CCName = $_SESSION['username']; // Get name from session
global $db;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Creator Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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

        .search-bar {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .search-input {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 300px;
        }

        .search-input:focus {
            outline: none;
            border-color: #231942;
        }

        .filter-select {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .filter-select:focus {
            outline: none;
            border-color: #231942;
        }

        .btn {
            background: #231942;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #362a55;
        }

        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .content-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(35,25,66,0.1);
            overflow: hidden;
        }

        .content-image {
            width: 100%;
            height: 200px;
            background: #ddd;
            object-fit: cover;
        }

        .content-info {
            padding: 15px;
        }

        .content-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #231942;
        }

        .content-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .status-approved {
            background: #2d8a61;
            color: white;
        }

        .status-pending {
            background: #b8860b;
            color: white;
        }

        .content-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .comments-section {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }

        .comment {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .add-content-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: #231942;
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(35,25,66,0.2);
            transition: transform 0.3s;
        }

        .add-content-btn:hover {
            transform: scale(1.1);
            background: #362a55;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #231942;
        }

        .btn.delete-btn {
            background: #dc3545;
        }

        .btn.delete-btn:hover {
            background: #c82333;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="logo">
            Content Creator
        </div>
        <ul class="nav-links">
            <li>
                <a href="contentCreatorDashboard.php" class="nav-item">
                    <i class="fas fa-pencil-alt"></i>
                    My Content
                </a>
            </li>
            <li>
                <a href="home.php" class="nav-item">
                    <i class="fas fa-globe"></i>
                    All Content
                </a>
            </li>
            <li>
                <a href="./contentCreatorJson.php" class="nav-item">
                    <i class="fas fa-code"></i>
                    JSON API
                </a>
            </li>
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
            <div class="search-bar">
                <input type="text" class="search-input" placeholder="Search content...">
                <select class="filter-select">
                    <option value="all">All Content</option>
                    <option value="approved">Approved</option>
                    <option value="pending">Pending</option>
                </select>
                <button class="btn">Search</button>
            </div>
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span> <?= $CCName ?></span>
            </div>
        </div>

        <div class="content-grid">
            <!-- Content Card Example -->
            <div class="content-card">
                <img src="/api/placeholder/300/200" alt="Content" class="content-image">
                <div class="content-info">
                    <div class="content-title">Article Title</div>
                    <span class="content-status status-approved">Approved</span>
                    <p>Short description of the content goes here...</p>
                    <div class="comments-section">
                        <div class="comment">
                            <i class="fas fa-comment"></i>
                            Editor: Great work, approved!
                        </div>
                    </div>
                    <div class="content-actions">
                        <button class="btn">Edit</button>
                        <button class="btn delete-btn">Delete</button>
                    </div>
                </div>
            </div>

            <!-- Pending Content Example -->
            <div class="content-card">
                <img src="/api/placeholder/300/200" alt="Content" class="content-image">
                <div class="content-info">
                    <div class="content-title">Draft Article</div>
                    <span class="content-status status-pending">Pending</span>
                    <p>Another content description example...</p>
                    <div class="comments-section">
                        <div class="comment">
                            <i class="fas fa-comment"></i>
                            Editor: Please add more details
                        </div>
                    </div>
                    <div class="content-actions">
                        <button class="btn">Edit</button>
                        <button class="btn delete-btn">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Content Button -->
        <a href="contentCreatorAddContent.php">
            <button class="add-content-btn">
                <i class="fas fa-plus"></i>
            </button>
        </a>
    </div>
</body>
</html>