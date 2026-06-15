<?php
include('../auth.php');

// Agar session start nahi hai toh start karein
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php"); 
    exit();
}

$message = "";

// Handle Actions (Winner, Runner-up, Delete)
if (isset($_GET['action']) && isset($_GET['id'])) {
    if (isset($conn)) {
        $sub_id = mysqli_real_escape_string($conn, $_GET['id']);
        $action = $_GET['action'];

        // --- 1. DELETE ACTION ---
        if ($action == 'delete') {
            $delete_query = "DELETE FROM submissions WHERE id = '$sub_id'";
            if (mysqli_query($conn, $delete_query)) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?status=deleted");
                exit();
            } else {
                $message = "<div class='alert alert-danger'>Delete Query Error: " . mysqli_error($conn) . "</div>";
            }
        }
        
        // --- 2. STATUS UPDATE ACTION (Winner / Runner up) ---
        else {
            $new_status = '';
            if ($action == 'winner') {
                $new_status = 'winner';
                // Baaki sabhi winners ko pehle 'pending' karo taaki sirf AK hi winner rahe
                mysqli_query($conn, "UPDATE submissions SET status = 'pending' WHERE status = 'winner'");
            } elseif ($action == 'runner_up') {
                $new_status = 'runner_up';
                // Baaki sabhi runner-ups ko pehle 'pending' karo taaki sirf AK hi runner up rahe
                mysqli_query($conn, "UPDATE submissions SET status = 'pending' WHERE status = 'runner_up'");
            }

            if (!empty($new_status)) {
                $update_query = "UPDATE submissions SET status = '$new_status' WHERE id = '$sub_id'";
                if (mysqli_query($conn, $update_query)) {
                    header("Location: " . $_SERVER['PHP_SELF'] . "?status=success");
                    exit();
                } else {
                    $message = "<div class='alert alert-danger'>Update Query Error: " . mysqli_error($conn) . "</div>";
                }
            }
        }
    } else {
        $message = "<div class='alert alert-danger'>Database Connection (\$conn) nahi mila! Check auth.php</div>";
    }
}

// Success Messages Alerts
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') {
        $message = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                        Status kamyabi se update ho gaya hai! (Purana status reset kar diya gaya hai).
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
    } elseif ($_GET['status'] == 'deleted') {
        $message = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                        Submission ko successfully delete kar diya gaya hai!
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
    }
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
        .main-content {
            margin-left: 260px;
            padding: 40px 30px;
            min-height: 100vh;
        }
        .submissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding-top: 15px;
        }
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
        .card-actions {
            display: flex;
            gap: 8px;
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
        .btn-view-doc {
            background-color: #e7f5ff;
            color: #0d6efd;
            border: 1px solid #a5d8ff;
        }
        .btn-view-doc:hover {
            background-color: #d0ebff;
            color: #0056b3;
        }
        .btn-select-winner {
            background-color: #0c211b;
            color: #20c997;
            border: 1px solid #96f2d7;
        }
        .btn-select-runnerup {
            background-color: #e6fcf5;
            color: #20c997;
            border: 1px solid #96f2d7;
        }
        /* Delete Button Premium Styling */
        .btn-delete-submission {
            background-color: #fff5f5;
            color: #fa5252;
            border: 1px solid #ffc9c9;
        }
        .btn-delete-submission:hover {
            background-color: #ffe3e3;
            color: #c92a2a;
        }
        .btn-select-winner:hover, .btn-select-runnerup:hover {
            background-color: #c3fae8;
            color: #087f5b;
        }
        .status-badge {
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<aside class="sidebar">
    <div class="brand-title">
        <i class="fa-solid fa-book-open me-2"></i> E-Book Admin
    </div>
    <nav class="nav flex-column">
        <a class="nav-link" href="dashboard.php"><i class="fa-solid fa-plus-circle"></i> Add New Book</a>
        <a class="nav-link" href="viewBooks.php"><i class="fa-solid fa-book"></i> View All Books</a>
        <a class="nav-link" href="manage_orders.php"><i class="fa-solid fa-shopping-cart"></i> Manage Orders</a>
        <a class="nav-link" href="view_dealers.php"><i class="fa-solid fa-users"></i> Book Dealers</a>
        <a class="nav-link" href="dealers.php"><i class="fa-solid fa-users"></i> Add Dealers</a>
        <a class="nav-link" href="competition.php"><i class="fa-solid fa-trophy"></i> Add Competitions</a>
        <a class="nav-link" href="view_competition.php"><i class="fa-solid fa-trophy"></i> Competitions Board</a>
        <a class="nav-link" href="winners.php"><i class="fa-solid fa-award"></i> Winners Board</a>
        <a class="nav-link" href="contact_problems.php"><i class="fa-solid fa-envelope-open-text"></i> Contact & Problems</a>
        <a class="nav-link text-danger mt-5" href="logout.php"><i class="fa-solid fa-sign-out-alt"></i> Logout</a>
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

        <?php echo $message; ?>

        <div class="submissions-grid">
        <?php
        $sub_query = "SELECT s.*, c.title as comp_title 
                      FROM submissions s 
                      LEFT JOIN competitions c ON s.competition_id = c.id 
                      ORDER BY s.id DESC";
        $sub_result = mysqli_query($conn, $sub_query);

        while ($sub = mysqli_fetch_assoc($sub_result)) {
            $user_name = htmlspecialchars($sub['user_name'] ?? 'Unknown');
            $initials = strtoupper(substr($user_name, 0, 2));
            $current_status = $sub['status'] ?? 'pending';
        ?>
            <div class="modern-card">
                <span class="card-id">ID #<?php echo $sub['id']; ?></span>
                <div>
                    <div class="user-meta">
                        <div class="avatar-placeholder"><?php echo $initials; ?></div>
                        <div class="user-details">
                            <h5><?php echo $user_name; ?></h5>
                            <span><?php echo htmlspecialchars($sub['competition_type'] ?? ''); ?></span>
                        </div>
                    </div>
                    
                    <div>
                        <?php if($current_status == 'winner'): ?>
                            <span class="status-badge bg-success text-white">🏆 Winner</span>
                        <?php elseif($current_status == 'runner_up'): ?>
                            <span class="status-badge bg-warning text-dark">🥈 Runner Up</span>
                        <?php else: ?>
                            <span class="status-badge bg-secondary text-white">⏳ Pending</span>
                        <?php endif; ?>
                    </div>

                    <div class="comp-info">
                        <span class="label">Competition Event</span>
                        <p class="title-text"><?php echo htmlspecialchars($sub['comp_title'] ?? $sub['title'] ?? 'N/A'); ?></p>
                    </div>
                </div>
                
                <a href="#" class="btn-action btn-view-doc mb-2">
                    <i class="fa-solid fa-file-invoice"></i> View Story
                </a>
                
                <div class="card-actions">
                    <a href="?action=winner&id=<?php echo $sub['id']; ?>" class="btn-action btn-select-winner">
                        <i class="fa-solid fa-medal"></i> Winner
                    </a>
                    <a href="?action=runner_up&id=<?php echo $sub['id']; ?>" class="btn-action btn-select-runnerup">
                        <i class="fa-solid fa-medal"></i> Runner up
                    </a>
                    <a href="?action=delete&id=<?php echo $sub['id']; ?>" class="btn-action btn-delete-submission" onclick="return confirm('Kya aap sach me is submission ko delete karna chahte hain?');">
                        <i class="fa-solid fa-trash-can"></i> Delete
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