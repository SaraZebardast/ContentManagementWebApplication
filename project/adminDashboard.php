<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(35,25,66,0.1);
        }

        .stat-card {
            padding: 20px;
            border-radius: 8px;
            background: #fff;
            border: 1px solid #e9ecef;
        }

        .stat-card h3 {
            color: #231942;
            margin-bottom: 10px;
            font-size: 18px;
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: bold;
            color: #362a55;
        }

        .stat-card .description {
            color: #6c757d;
            font-size: 14px;
            margin-top: 5px;
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
            <h1>Dashboard Overview</h1>
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span>Welcome, Admin</span>
            </div>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <h3>Content Creators</h3>
                <div class="number">24</div>
                <div class="description">Active content creators in the system</div>
            </div>
            <div class="stat-card">
                <h3>Editors</h3>
                <div class="number">12</div>
                <div class="description">Active editors managing content</div>
            </div>
            <div class="stat-card">
                <h3>Total Users</h3>
                <div class="number">36</div>
                <div class="description">Total registered users</div>
            </div>
        </div>
    </div>
</body>
</html>