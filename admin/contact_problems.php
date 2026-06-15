<?php
include('../auth.php');

// Security Check: Sirf admin hi dekh sake
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php"); 
    exit();
}

// Data Fetch Logic from contact_problem table
// Note: Agar aapke database table ka naam ya columns ke naam thode alag hain toh unhein query me badal lena
$problem_query = "SELECT * FROM contact_problem ORDER BY id DESC";
$problem_result = mysqli_query($conn, $problem_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Issues & Queries - Admin</title>
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
        
        /* Sidebar Layout */
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

        /* Main Content Space Config */
        .main-content {
            margin-left: 260px; 
            padding: 40px 30px;
            min-height: 100vh;
        }

        /* Grid Layout for Problems */
        .problems-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
            padding-top: 15px;
        }

        /* Premium Card Setup */
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

        /* Card Top Badge */
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

        /* User Profile Area */
        .user-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
            padding-right: 50px; /* Space for ID badge */
        }

        .avatar-placeholder {
            width: 45px;
            height: 45px;
            background-color: #fff0f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #d6336c;
            font-size: 14px;
            font-weight: 600;
        }

        .user-details h5 {
            font-size: 15px;
            font-weight: 700;
            color: #2d3436;
            margin: 0;
            word-break: break-all;
        }

        .user-details span {
            color: #636e72;
            font-size: 12px;
            word-break: break-all;
        }

        /* Issue Details Box */
        .issue-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #e1e8ed;
            margin-bottom: 15px;
            flex-grow: 1;
        }

        .issue-info .label {
            font-size: 10px;
            color: #495057;
            text-transform: uppercase;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 6px;
            background-color: #e9ecef;
            padding: 2px 8px;
            border-radius: 4px;
        }

        .issue-info .description-text {
            font-size: 13px;
            color: #2d3436;
            margin: 0;
            line-height: 1.5;
            text-align: justify;
        }

        /* Email Reply Action Button */
        .btn-reply {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.2s ease;
            background-color: #f4fce3;
            color: #74b816;
            border: 1px solid #d8f5a2;
        }

        .btn-reply:hover {
            background-color: #d8f5a2;
            color: #5c940d;
        }
    </style>
</head>
<body>

   <aside class="sidebar">
    <div class="brand-title">
        <i class="fa-solid fa-book-open me-2"></i> E-Book Admin
    </div>
    <nav class="nav flex-column">
        <a class="nav-link" href="dashboard.php">
            <i class="fa-solid fa-plus-circle"></i> Add New Book
        </a>
        
        <a class="nav-link" href="viewBooks.php">
            <i class="fa-solid fa-book"></i> View All Books
        </a>
        
        <a class="nav-link" href="manage_orders.php">
            <i class="fa-solid fa-shopping-cart"></i> Manage Orders
        </a>
        
        <a class="nav-link" href="view_dealers.php">
            <i class="fa-solid fa-users"></i> Book Dealers
        </a>
        <a class="nav-link" href="dealers.php">
            <i class="fa-solid fa-users"></i> Add Dealers
        </a>
         <a class="nav-link" href="competition.php">
            <i class="fa-solid fa-trophy"></i>Add Competitions
        </a>
        
        <a class="nav-link" href="view_competition.php">
            <i class="fa-solid fa-trophy"></i> Competitions Board
        </a>
        
        <a class="nav-link" href="winners.php">
            <i class="fa-solid fa-award"></i> Winners Board
        </a>

        <a class="nav-link" href="contact_problems.php">
            <i class="fa-solid fa-envelope-open-text"></i> Contact & Problems
        </a>
        
        <a class="nav-link text-danger mt-5" href="logout.php">
            <i class="fa-solid fa-sign-out-alt"></i> Logout
        </a>
    </nav>
</aside>
    <main class="main-content">
        <div class="container-fluid">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark m-0">User Support Desk</h2>
                    <p class="text-muted small">Manage problems, technical queries, and complaints filed by users via contact form.</p>
                </div>
            </div>

            <div class="problems-grid">
                <?php
                if (mysqli_num_rows($problem_result) > 0) {
                    while ($row = mysqli_fetch_assoc($problem_result)) {
                        // User ke naam se pehle 2 alphabets initials ke liye
                        $name_trim = trim($row['name']);
                        $initials = strtoupper(substr($name_trim, 0, 2));
                ?>
                    <div class="modern-card">
                        <span class="card-id">Ticket #<?php echo $row['id']; ?></span>
                        
                        <div>
                            <div class="user-meta">
                                <div class="avatar-placeholder"><?php echo $initials; ?></div>
                                <div class="user-details">
                                    <h5><?php echo htmlspecialchars($row['name']); ?></h5>
                                    <span><i class="fa-regular fa-envelope me-1"></i><?php echo htmlspecialchars($row['email']); ?></span>
                                </div>
                            </div>
                            
                            <div class="issue-info">
                                <span class="label"><i class="fa-solid fa-tags me-1"></i><?php echo htmlspecialchars($row['query_type']); ?></span>
                                <p class="description-text">
                                    <?php echo nl2br(htmlspecialchars($row['issue_description'])); ?>
                                </p>
                            </div>
                        </div>
                        
                        <a href="mailto:<?php echo $row['email']; ?>?subject=Regarding Your Query: <?php echo urlencode($row['query_type']); ?>" class="btn-reply">
                            <i class="fa-solid fa-paper-plane"></i> Reply via Email
                        </a>
                    </div>
                <?php 
                    }
                } else {
                    echo "<div class='col-12 text-center py-5'><h4 class='text-muted'>Sukun hi sukun! Kisi user ne koi complaint darj nahi ki.</h4></div>";
                } 
                ?>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>