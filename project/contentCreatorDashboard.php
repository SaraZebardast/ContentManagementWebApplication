<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'content_creator') {
    header("Location:login.php");
    exit();
}

require "db.php";

$CCName = $_SESSION['username']; 
$contentCreatorId = $_SESSION['user_id']; 
global $db;


//Content deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_content_id'])) {
    $contentId = $_POST['delete_content_id'];

    $stmt = $db->prepare("DELETE FROM content WHERE id = :id AND creator_id = :creator_id");
    $stmt->execute([':id' => $contentId, ':creator_id' => $_SESSION['user_id']]);

    if ($stmt->rowCount() > 0) {
        header("Location: contentCreatorDashboard.php?delete=success");
        exit();
    } else {
        header("Location: contentCreatorDashboard.php?delete=failure");
        exit();
    }
}


//Content search
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'search_and_filter') {
    $searchQuery = $_POST['query'] ?? '';
    $filter = $_POST['filter'] ?? 'all';

    $searchQuery = '%' . $searchQuery . '%'; 

    $query = "SELECT * FROM content WHERE creator_id = :creator_id";

    
    if ($filter === 'approved') {
        $query .= " AND status = 'approved'";
    } elseif ($filter === 'pending') {
        $query .= " AND status = 'pending'";
    }

    
    if (!empty($_POST['query'])) {
        $query .= " AND (title LIKE :query OR description LIKE :query)";
    }

    $stmt = $db->prepare($query);

    $params = [':creator_id' => $_SESSION['user_id']];
    if (!empty($_POST['query'])) {
        $params[':query'] = $searchQuery;
    }

    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);
    exit();
}

//Content filtering
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'filter') {
    $filter = $_POST['filter'];
    $stmt = $db->prepare("SELECT * FROM content WHERE creator_id = :creator_id AND approved = :filter");
    $stmt->execute([':creator_id' => $contentCreatorId, ':filter' => $filter]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit();
}

//JSON API
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['json_content_id'])) {
    $contentId = $_GET['json_content_id'];
    $stmt = $db->prepare("SELECT * FROM content WHERE id = :id AND creator_id = :creator_id");
    $stmt->execute([':id' => $contentId, ':creator_id' => $contentCreatorId]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    exit();
}

// Fetch and display all content
$stmt = $db->prepare("SELECT * FROM content WHERE creator_id = :creator_id");
$stmt->execute([':creator_id' => $contentCreatorId]);
$contents = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'filter') {
    $filter = $_POST['filter'];
    $query = "SELECT * FROM content WHERE creator_id = :creator_id";
    if ($filter === 'approved') {
        $query .= " AND approved = 1";
    } elseif ($filter === 'pending') {
        $query .= " AND approved = 0";
    }
    $stmt = $db->prepare($query);
    $stmt->execute([':creator_id' => $contentCreatorId]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Creator Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        a {
            text-decoration: none;
        }
    </style>
    <script>

//search
$(document).ready(function () {
   
    function performSearchAndFilter() {
        const query = $('.search-input').val().trim(); 
        const filter = $('.filter-select').val(); 

        // AJAX POST request
        $.ajax({
            url: 'contentCreatorDashboard.php',
            method: 'POST',
            data: {
                action: 'search_and_filter',
                query: query,
                filter: filter
            },
            success: function (response) {
                console.log('AJAX response:', response);

                try {
                    const data = JSON.parse(response);
                    $('.content-grid').empty(); 

                    if (data.length === 0) {
                        $('.content-grid').append('<p>No results found.</p>');
                        return;
                    }

                    data.forEach(content => {
                        $('.content-grid').append(`
                            <div class="content-card">
                                <img src="${content.image_path}" alt="${content.title}" class="content-image">
                                <div class="content-info">
                                    <div class="content-title">${content.title}</div>
                                    <span class="content-status ${content.status === 'approved' ? 'status-approved' : 'status-pending'}">
                                        ${content.status === 'approved' ? 'Approved' : 'Pending'}
                                    </span>
                                    <p>${content.description}</p>
                                    <div class="content-actions">
                                        <a href="edit.php?content_id=${content.id}" class="btn">Edit</a>
                                        <form method="POST" action="" style="display:inline;">
                                            <input type="hidden" name="delete_content_id" value="${content.id}">
                                            <button type="submit" class="btn delete-btn">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        `);
                    });
                } catch (err) {
                    console.error('Error parsing JSON:', err);
                    alert('Failed to load search results.');
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', status, error);
                alert('Error performing search. Please try again.');
            }
        });
    }

    $('#search-btn').on('click', function (e) {
        e.preventDefault();
        performSearchAndFilter();
    });

    $('.filter-select').change(function () {
        performSearchAndFilter();
    });
});


//delete-btn
$(document).on('click', '.delete-btn', function () {
    const contentId = $(this).data('id');
    if (confirm('Are you sure you want to delete this content?')) {
        $.ajax({
            url: '',
            type: 'POST',
            data: { action: 'delete', content_id: contentId },
            success: function (response) {
                try {
                    const result = JSON.parse(response);
                    if (result.success) {
                        alert('Content deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + (result.error || 'Failed to delete content.'));
                    }
                } catch (error) {
                    console.error('Invalid JSON response:', response);
                    alert('Unexpected error occurred.');
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', status, error);
                alert('Failed to send request. Please try again.');
            }
        });
    }
});

$('.filter-select').change(function() {
    const filter = $(this).val();
    $.post('', { action: 'filter', filter: filter }, function(response) {
        const data = JSON.parse(response);
        $('.content-grid').empty();
        data.forEach(content => {
            $('.content-grid').append(`
                <div class="content-card">
                    <img src="/uploads/${content.image}" alt="Content" class="content-image">
                    <div class="content-info">
                        <div class="content-title">${content.title}</div>
                        <span class="content-status ${content.approved ? 'status-approved' : 'status-pending'}">
                            ${content.approved ? 'Approved' : 'Pending'}
                        </span>
                        <p>${content.description}</p>
                        <div class="content-actions">
                            <a href="edit.php?content_id=${content.id}" class="btn">Edit</a>
                            <button class="btn delete-btn" data-id="<?= $content['id'] ?>">Delete</button>

                        </div>
                    </div>
                </div>
            `);
        });
    });
});

$(document).on('click', '.json-btn', function() {
    const contentId = $(this).data('id');
    $.get('', { json_content_id: contentId }, function(response) {
        alert(JSON.stringify(response));
    });
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
                <button class="btn" id="search-btn">Search</button>
            </div>
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span><?= $CCName ?></span>
            </div>
        </div>

        <div class="content-grid">
    <?php foreach ($contents as $content): ?>
        <div class="content-card">
            <img src="<?= htmlspecialchars($content['image_path'] ?? '/api/placeholder/300/200', ENT_QUOTES) ?>" alt="Content" class="content-image">
            <div class="content-info">
                <div class="content-title"><?= htmlspecialchars($content['title'] ?? 'Untitled', ENT_QUOTES) ?></div>
                <span class="content-status <?= isset($content['status']) && $content['status'] === 'approved' ? 'status-approved' : 'status-pending' ?>">
                    <?= htmlspecialchars(ucfirst($content['status'] ?? 'Pending'), ENT_QUOTES) ?>
                </span>
                <p><?= htmlspecialchars($content['description'] ?? 'No description available.', ENT_QUOTES) ?></p>
                <div class="comments-section">
                    <?php
                    $commentStmt = $db->prepare("SELECT * FROM comments WHERE content_id = :content_id");
                    $commentStmt->execute([':content_id' => $content['id']]);
                    $comments = $commentStmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($comments as $comment): ?>
                        <div class="comment">
                            <i class="fas fa-comment"></i>
                            <?= htmlspecialchars($comment['comment'] ?? 'No comment', ENT_QUOTES) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="content-actions">
                <a href="edit.php?content_id=<?= $content['id'] ?>" class="btn">Edit</a>
                <form method="POST" action="contentCreatorDashboard.php" style="display:inline;">
                    <input type="hidden" name="delete_content_id" value="<?= $content['id'] ?>">
                    <button type="submit" class="btn delete-btn">Delete</button>
                </form>
            </div>
            </div>
        </div>
    <?php endforeach; ?>
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
