<?php

// Imports
global $db;
require "db.php";

// Fill the users dropdown from the database
$stmtUser = $db->prepare("SELECT * FROM users WHERE type IN ('editor', 'content_creator') ORDER BY username");
$stmtUser->execute();
$Users = $stmtUser->fetchAll();

// Get the selected user ID if form was submitted
$selectedUserId = isset($_POST['user']) ? $_POST['user'] : '';

//


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Assign Rights</title>
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

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(35,25,66,0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #231942;
            font-weight: 500;
        }

        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
        }

        .form-group select:focus {
            outline: none;
            border-color: #231942;
        }

        .permissions-list {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .permission-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            background: white;
            border-radius: 4px;
        }

        .permission-item:last-child {
            margin-bottom: 0;
        }

        .permission-item input[type="checkbox"] {
            margin-right: 10px;
            width: 18px;
            height: 18px;
            accent-color: #231942;
        }

        .permission-item label {
            color: #231942;
        }

        .btn {
            background: #231942;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            width: 100%;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #362a55;
        }

        .logout-link {
            margin-top: auto;
            border-top: 1px solid #362a55;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="logo">
            Admin Panel
        </div>
        <ul class="nav-links">
            <li>
                <a href="adminDashboard.php" class="nav-item">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="adminCreateUser.php" class="nav-item">
                    <i class="fas fa-user-plus"></i>
                    Create User
                </a>
            </li>
            <li>
                <a href="adminAssignRights.php" class="nav-item">
                    <i class="fas fa-user-shield"></i>
                    Assign Rights
                </a>
            </li>
            <li class="logout-link">
                <a href="logout.html" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1>Assign User Rights</h1>
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span>Welcome, Admin</span>
            </div>
        </div>

        <div class="form-container">
            <form action="" method="POST">
                <div class="form-group">
                    <label for="user">Select User</label>
                    <select id="user" name="user" required>
                        <option value="">Choose a user</option>

                        <?php
                        foreach ($Users as $u) {
                            $selected = ($selectedUserId == $u['id']) ? 'selected' : '';
                            echo '<option value="' . $u['id'] . '" ' . $selected . '>' . $u['username'] . '</option>';
                        }
                        ?>

                    </select>
                </div>

                <div class="permissions-list">
                    <div class="permission-item">
                        <input type="checkbox" id="view_content" name="permissions[]" value="view_content">
                        <label for="view_content">View Content</label>
                    </div>
                    
                    <div class="permission-item">
                        <input type="checkbox" id="create_content" name="permissions[]" value="create_content">
                        <label for="create_content">Create Content</label>
                    </div>

                    <div class="permission-item">
                        <input type="checkbox" id="edit_content" name="permissions[]" value="edit_content">
                        <label for="edit_content">Edit Content</label>
                    </div>

                    <div class="permission-item">
                        <input type="checkbox" id="delete_content" name="permissions[]" value="delete_content">
                        <label for="delete_content">Delete Content</label>
                    </div>

                    <div class="permission-item">
                        <input type="checkbox" id="approve_content" name="permissions[]" value="approve_content">
                        <label for="approve_content">Approve Content</label>
                    </div>

                    <div class="permission-item">
                        <input type="checkbox" id="manage_comments" name="permissions[]" value="manage_comments">
                        <label for="manage_comments">Manage Comments</label>
                    </div>
                </div>

                <button type="submit" class="btn">Update Permissions</button>
            </form>
        </div>
    </div>
</body>
</html>