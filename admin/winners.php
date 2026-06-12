<?php
session_start();
include('../auth.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php"); 
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publisher Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --sidebar-bg: #212529;
            --main-bg: #f8f9fa;
        }
        body {
            background-color: var(--main-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* ==========================================================================
           Sidebar Layout (Fixed as requested)
           ========================================================================== */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--sidebar-bg);
            padding-top: 20px;
            z-index: 100;
        }
        .sidebar .brand-title {
            color: #fff;
            font-size: 20px;
            font-weight: 700;
            padding: 10px 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid #343a40;
        }
        .sidebar .nav-link {
            color: #c2c7d0;
            padding: 12px 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255,255,255,0.1);
            border-left: 4px solid #0d6efd;
        }

        /* ==========================================================================
           Main Content Space Config (Prevents Sidebar Overlapping)
           ========================================================================== */
        .main-content {
            margin-left: 260px; /* Exact width of sidebar */
            padding: 40px 30px;
            min-height: 100vh;
        }

        /* ==========================================================================
           Modern & Responsive Submissions Grid Layout
           ========================================================================== */
        .submissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding-top: 15px;
        }

        /* Premium Modern Card Design */
        .modern-card {
            background-color: #ffffff;
            border: 1px solid #eef2f5;
            border-radius: 12px;
            padding: 20px;
            position: relative;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .modern-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        }

        /* Sub ID Badge (Top Right) */
        .card-id {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: #f1f3f5;
            color: #636e72;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
        }

        /* User Meta Profile Area */
        .user-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
        }

        .avatar-placeholder {
            width: 45px;
            height: 45px;
            background-color: #e7f5ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0d6efd;
            font-size: 14px;
            font-weight: 600;
        }

        .user-details h5 {
            font-size: 15px;
            font-weight: 700;
            color: #2d3436;
            margin: 0;
        }

        .user-details span {
            color: #636e72;
            font-size: 12px;
        }

        /* Competition Event Info Box */
        .comp-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #e1e8ed;
            margin-bottom: 20px;
        }

        .comp-info .label {
            font-size: 10px;
            color: #636e72;
            text-transform: uppercase;
            font-weight: 700;
            display: block;
            margin-bottom: 3px;
        }

        .comp-info .title-text {
            font-weight: 500;
            font-size: 13px;
            color: #495057;
            margin: 0;
        }

        /* Action Buttons Flex Area */
        .card-actions {
            display: flex;
            gap: 10px;
        }

        .btn-action {
            flex: 1;
            padding: 10px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        /* View Document Button Styling */
        .btn-view-doc {
            background-color: #e7f5ff;
            color: #0d6efd;
            border: 1px solid #a5d8ff;
        }

        .btn-view-doc:hover {
            background-color: #d0ebff;
            color: #0056b3;
        }

        /* Select Winner Button Styling */
        .btn-select-winner {
            background-color: #e6fcf5;
            color: #20c997;
            border: 1px solid #96f2d7;
        }

        .btn-select-winner:hover {
            background-color: #c3fae8;
            color: #087f5b;
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="brand-title">
            <i class="fa-solid fa-book-open me-2"></i> E-Book Admin
        </div>
        <nav class="nav flex-column">
            <a class="nav-link active" href="#"><i class="fa-solid fa-plus-circle"></i> Add New Book</a>
            <a class="nav-link" href="viewBooks.php"><i class="fa-solid fa-book"></i> View All Books</a>
            <a class="nav-link" href="#"><i class="fa-solid fa-shopping-cart"></i> Manage Orders</a>
            <a class="nav-link" href="#"><i class="fa-solid fa-users"></i> Book Dealers</a>
            <a class="nav-link" href="competition.php"><i class="fa-solid fa-trophy"></i>Competitions</a>
            <a class="nav-link" href="winners.php"><i class="fa-solid fa-trophy"></i>Winners</a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="container-fluid">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark m-0">Admin Control Center</h2>
                <p class="text-muted small">Live responses from writers participating across monthly active challenges.</p>
                </div>
            </div>

          <div class="submissions-grid">
<?php
$sub_query = "SELECT s.*, c.title as comp_title 
              FROM submissions s 
              LEFT JOIN competitions c ON s.competition_id = c.id 
              ORDER BY s.id DESC";
$sub_result = mysqli_query($conn, $sub_query);

while ($sub = mysqli_fetch_assoc($sub_result)) {
    $initials = strtoupper(substr($sub['user_name'], 0, 2));
?>
    <div class="modern-card">
        <span class="card-id">ID #<?php echo $sub['id']; ?></span>
        <div>
            <div class="user-meta">
                <div class="avatar-placeholder"><?php echo $initials; ?></div>
                <div class="user-details">
                    <h5><?php echo $sub['user_name']; ?></h5>
                    <span><?php echo $sub['competition_type']; ?></span>
                </div>
            </div>
            <div class="comp-info">
                <span class="label">Competition Event</span>
                <p class="title-text"><?php echo $sub['comp_title'] ?? $sub['title']; ?></p>
            </div>
        </div>
        <a href="#" class="btn-action btn-view-doc">
            <i class="fa-solid fa-file-invoice"></i> View Story
        </a>
        <br>
        <div class="card-actions">
            <a href="#" class="btn-action btn-select-winner">
                <i class="fa-solid fa-medal"></i> Winner
            </a>
            <a href="#" class="btn-action btn-select-winner">
                <i class="fa-solid fa-medal"></i> Runner up
            </a>
        </div>
    </div>
<?php } ?>
</div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>