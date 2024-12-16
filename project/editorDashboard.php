<?php
// Start the session for the User
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'editor') {
    header("Location:login.php");
    exit();
}

// Imports
require "db.php";

// Variables
$editorName = $_SESSION['username']; // Get name from session
global $db;

//TODO display content of the creators (exactly like home implementation)

//TODO the approve button functionality

//TODO the delete button should delete the content from the database entirely

//TODO submit commit functionality should update UI and add the comment to the comments database

//TODO Search should work (both)

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor Dashboard</title>
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

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #231942;
        }

        .search-container {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
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
        }

        .search-input:focus {
            outline: none;
            border-color: #231942;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.3s;
        }

        .btn-primary {
            background: #231942;
            color: white;
        }

        .btn-primary:hover {
            background: #362a55;
        }

        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
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

        .creator-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            color: #666;
        }

        .content-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #231942;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .comment-section {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }

        .comment-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-bottom: 10px;
            resize: vertical;
        }

        .comment-input:focus {
            outline: none;
            border-color: #231942;
        }

        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .tab {
            padding: 10px 20px;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
            color: #231942;
        }

        .tab.active {
            background: #231942;
            color: white;
        }

        .tab:hover {
            background: #f5f5f5;
        }

        .tab.active:hover {
            background: #362a55;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .status-pending {
            background: #b8860b;
            color: white;
        }

        .status-approved {
            background: #2d8a61;
            color: white;
        }

        .btn-delete {
            background: #dc3545;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .btn-comment {
            background: #2d8a61;
        }

        .btn-comment:hover {
            background: #246d4d;
        }
        a {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="logo">Editor Panel</div>
        <ul class="nav-links">
            <li>
                <a href="editorDashboard.php" class="nav-item">
                    <i class="fas fa-home"></i>
                    Dashboard
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
            <h1>Editor Dashboard</h1>
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span> <?= $editorName ?></span>
            </div>
        </div>

        <div class="tabs">
            <div class="tab active">All Content</div>
            <div class="tab">Pending Approval</div>
            <div class="tab">Approved</div>
        </div>

        <div class="search-container">
            <div class="search-box">
                <input type="text" class="search-input" placeholder="Search content...">
                <button class="btn btn-primary">Search</button>
            </div>
            <div class="search-box">
                <input type="text" class="search-input" placeholder="Search creators...">
                <button class="btn btn-primary">Search</button>
            </div>
        </div>

        <div class="content-grid">
            <!-- Content Card Example -->
            <div class="content-card">
                <img src="/api/placeholder/300/200" alt="Content" class="content-image">
                <div class="content-info">
                    <div class="creator-info">
                        <i class="fas fa-user"></i>
                        <span>Created by: John Doe</span>
                    </div>
                    <div class="content-title">Content Title</div>
                    <span class="status-badge status-pending">Pending Approval</span>
                    <p>Content description goes here...</p>
                    
                    <div class="comment-section">
                        <textarea class="comment-input" placeholder="Add a comment..."></textarea>
                        <div class="action-buttons">
                            <a href="" class="btn btn-primary">Approve</a>
                            <a href="" class="btn btn-delete">Delete</a>
                            <a href="" class="btn btn-comment">Submit Comment</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approved Content Example -->
            <div class="content-card">
                <img src="/api/placeholder/300/200" alt="Content" class="content-image">
                <div class="content-info">
                    <div class="creator-info">
                        <i class="fas fa-user"></i>
                        <span>Created by: Jane Smith</span>
                    </div>
                    <div class="content-title">Another Content</div>
                    <span class="status-badge status-approved">Approved</span>
                    <p>Another content description...</p>
                    
                    <div class="comment-section">
                        <p class="comment"><i class="fas fa-comment"></i> Previously approved with minor edits.</p>
                        <textarea class="comment-input" placeholder="Add a comment..."></textarea>
                        <div class="action-buttons">
                            <a href="" class="btn btn-delete">Delete</a>
                            <a href="" class="btn btn-comment">Submit Comment</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>