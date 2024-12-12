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
            max-width: 1200px;
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
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">Bulletin Board</div>
        <div class="nav-actions">
            <div class="nav-links">
                <a href="api/all-content.php" class="nav-link">JSON API</a>
            </div>
            <button class="btn btn-outline">
                <i class="fas fa-user"></i>
                Login
            </button>
        </div>
    </nav>

    <div class="search-container">
        <div class="search-box">
            <input type="text" class="search-input" placeholder="Search content...">
            <button class="btn btn-primary">Search</button>
        </div>
    </div>

    <div class="content-grid">
        <!-- Example Content Cards -->
        <div class="content-card">
            <img src="/api/placeholder/300/200" alt="Content" class="content-image">
            <div class="content-info">
                <div class="content-meta">
                    <span><i class="fas fa-user"></i> John Doe</span>
                    <span class="status-badge">Approved</span>
                </div>
                <div class="content-title">Getting Started with Web Development</div>
                <p>Learn the basics of web development with this comprehensive guide...</p>
            </div>
        </div>

        <div class="content-card">
            <img src="/api/placeholder/300/200" alt="Content" class="content-image">
            <div class="content-info">
                <div class="content-meta">
                    <span><i class="fas fa-user"></i> Jane Smith</span>
                    <span class="status-badge">Approved</span>
                </div>
                <div class="content-title">Design Principles for Beginners</div>
                <p>Explore the fundamental principles of good design and how to apply them...</p>
            </div>
        </div>

        <div class="content-card">
            <img src="/api/placeholder/300/200" alt="Content" class="content-image">
            <div class="content-info">
                <div class="content-meta">
                    <span><i class="fas fa-user"></i> Mike Johnson</span>
                    <span class="status-badge">Approved</span>
                </div>
                <div class="content-title">Understanding CSS Grid</div>
                <p>Master CSS Grid layout with practical examples and tips...</p>
            </div>
        </div>
    </div>
</body>
</html>