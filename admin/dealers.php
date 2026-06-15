<?php
include '../auth.php'; // Is file ke andar database connection ($conn) hona zaroori hai

// Sirf tabhi processing hogi jab form submit hoga
if (isset($_POST['add_dealer_btn'])) {
    
    // Form se data lena aur secure karna
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $timing = mysqli_real_escape_string($conn, trim($_POST['timing']));
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));

    // Validation Check
    if (empty($name) || empty($phone) || empty($timing) || empty($address)) {
        echo "<script>
                alert('Error: Sabhi fields ko fill karna zaroori hai!');
                window.location.href=''; // Usi same page par wapas bhejega
              </script>";
        exit();
    } else {
        
        // INSERT Query
        $sql = "INSERT INTO dealers (name, address, phone, timing) VALUES ('$name', '$address', '$phone', '$timing')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                    alert('Success: New dealer added successfully!');
                    window.location.href=''; // Refresh karke form khali kar dega
                  </script>";
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}
// Note: mysqli_close($conn) ko top par nahi likhna taaki connection khula rahe agar neeche kuch database ka kaam ho.
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
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255,255,255,0.1);
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
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            padding: 25px;
            border: 1px solid #eef2f5;
        }
        @media (max-width: 768px) {
            .sidebar { width: 100%; height: auto; position: relative; }
            .main-content { margin-left: 0; padding: 15px; }
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
                    <h2 class="fw-bold text-dark m-0">Admin Control Center</h2>
                    <p class="text-muted m-0">Welcome back, Admin! Manage your book store publication system here.</p>
                </div>
            </div>

            <div class="content-card">
                <h4 class="mb-4 text-primary fw-semibold"><i class="fa-solid fa-user-plus me-2"></i>Add New Book Dealer</h4>
                
                <form action="" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Dealer / Shop Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g., Universal Publications" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="e.g., +92-21-34445678" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Timings</label>
                            <input type="text" name="timing" class="form-control" placeholder="e.g., 10:00 AM - 08:00 PM" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Address</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="e.g., Plot 12-C, Lane 4, Phase 5, D.H.A." required></textarea>
                        </div>
                        <div class="col-md-12 mt-4">
                            <button type="submit" name="add_dealer_btn" class="btn btn-success w-100 py-2 fw-bold">
                                <i class="fa-solid fa-save me-2"></i>Register & Save Dealer Details
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>