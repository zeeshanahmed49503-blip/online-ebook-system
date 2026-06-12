<?php
session_start();
include '../auth.php';


$error = ''; 

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['deploy_competition_btn'])) {
    
    // 1. Pehle POST se data pakad lein
    $title       = trim($_POST['title']);
    $status      = trim($_POST['status']);
    $deadline    = trim($_POST['deadline']);
    $reward      = trim($_POST['reward']);
    $description = trim($_POST['description']);
    $starting = trim($_POST['starting']);

    // 2. CHECK: Agar koi bhi field empty (khali) hai to error set karein
    if (empty($title) || empty($status) || empty($deadline) || empty($reward) || empty($description)) {
        $error = "All fields are required! Please fill out the complete form.";
    } else {
        // 3. Agar sab field bhari hui hain, to data ko secure karein aur insert karein
        $title       = mysqli_real_escape_string($conn, $title);
        $status      = mysqli_real_escape_string($conn, $status);
        $deadline    = mysqli_real_escape_string($conn, $deadline);
        $reward      = mysqli_real_escape_string($conn, $reward);
        $description = mysqli_real_escape_string($conn, $description);
        $starting = mysqli_real_escape_string($conn, $starting);

        $query = "INSERT INTO competitions (title, status, deadline, reward, description, starting_at) 
                  VALUES ('$title', '$status', '$deadline', '$reward', '$description','$starting')";

        $result = mysqli_query($conn, $query);

        if ($result) {
            header("Location: competition.php?msg=success");
            exit();
        } else {
            $error = "Database Error: " . mysqli_error($conn);
        }
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

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid #0d6efd;
        }

        /* Main Content Layout */
        .main-content {
            margin-left: 260px;
            padding: 30px;
            min-height: 100vh;
        }

        .content-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 25px;
            border: 1px solid #eef2f5;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <div class="brand-title">
            <i class="fa-solid fa-book-open me-2"></i> E-Book Admin
        </div>
        <nav class="nav flex-column">
            <a class="nav-link" href="#"><i class="fa-solid fa-plus-circle"></i> Add New Book</a>
            <a class="nav-link" href="viewBooks.php"><i class="fa-solid fa-book"></i> View All Books</a>
            <a class="nav-link" href="#"><i class="fa-solid fa-shopping-cart"></i> Manage Orders</a>
            <a class="nav-link" href="#"><i class="fa-solid fa-users"></i> Book Dealers</a>
            <a class="nav-link active" href="competition.php"><i class="fa-solid fa-trophy"></i>Competitions</a>
            <a class="nav-link text-danger mt-5" href="logout.php"><i class="fa-solid fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>

    <main class="main-content">
        
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-radius: 12px;">
                <i class="fa-solid fa-circle-check me-2 fs-5 align-middle"></i>
                <strong class="align-middle">Success!</strong> <span class="align-middle">New competition has been deployed and broadcasted successfully.</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-radius: 12px;">
        <i class="fa-solid fa-triangle-exclamation me-2 fs-5 align-middle"></i>
        <strong class="align-middle">Validation Error!</strong> <span class="align-middle"><?php echo $error; ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

        <div class="content-card border-0 shadow-sm p-4" style="border-radius: 16px; background: #fff; border: 1px solid #f1f3f5 !important;">

            <div class="d-flex align-items-center mb-4 pb-3" style="border-bottom: 1px solid #f8f9fa;">
                <div class="bg-success-subtle p-3 rounded-3 text-success me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fa-solid fa-trophy fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark m-0">Create New Essay & Story Challenge</h4>
                    <p class="text-muted small m-0">Launch and broadcast monthly dynamic competitions for creative writers.</p>
                </div>
            </div>

            <form action="competition.php" method="POST">
                <div class="row g-4">

                    <div class="col-md-8">
                        <label class="form-label fw-semibold text-secondary small mb-2">Competition / Event Title</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 10px 0 0 10px;"><i class="fa-solid fa-font"></i></span>
                            <input type="text" name="title" class="form-control bg-light border-start-0" placeholder="e.g., Annual Sci-Fi Fiction Challenge 2026" style="border-radius: 0 10px 10px 0; padding: 10px 12px;" >
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary small mb-2">Operational Status Tag</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 10px 0 0 10px;"><i class="fa-solid fa-tags"></i></span>
                            <select name="status" class="form-select bg-light border-start-0" style="border-radius: 0 10px 10px 0; padding: 10px 12px;" >
                                <option value="active" selected>Active (Open for Entries)</option>
                                <option value="upcoming">Upcoming (Teaser / Locked)</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary small mb-2">Submission Deadline Date</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 10px 0 0 10px;"><i class="fa-solid fa-calendar-days"></i></span>
                            <input type="date" name="deadline" class="form-control bg-light border-start-0" style="border-radius: 0 10px 10px 0; padding: 10px 12px;" >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary small mb-2">Starting At</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 10px 0 0 10px;"><i class="fa-solid fa-calendar-days"></i></span>
                            <input type="date" name="starting" class="form-control bg-light border-start-0" style="border-radius: 0 10px 10px 0; padding: 10px 12px;" >
                        </div>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-semibold text-secondary small mb-2">Prize Pool & Winner Perks</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 10px 0 0 10px;"><i class="fa-solid fa-gift"></i></span>
                            <input type="text" name="reward" class="form-control bg-light border-start-0" placeholder="e.g., Rs. 15,000 Cash Prize + Magazine Feature Cover" style="border-radius: 0 10px 10px 0; padding: 10px 12px;" >
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold text-secondary small mb-2">Brief Context, Rules & Words Constraint Guidelines</label>
                        <textarea name="description" class="form-control bg-light" rows="5" placeholder="Specify clear rule parameters (e.g., Maximum word limit: 2000 words. Topic focus: Cyberpunk and dystopian futures. Must be original manuscript...)" style="border-radius: 12px; padding: 15px;" ></textarea>
                    </div>

                    <div class="col-md-12 mt-4">
                        <button type="submit" name="deploy_competition_btn" class="btn btn-success w-100 py-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" style="border-radius: 12px; background: #198754; border: none; font-size: 16px; transition: transform 0.2s;">
                            <i class="fa-solid fa-paper-plane"></i> Launch & Publish Challenge Event
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>