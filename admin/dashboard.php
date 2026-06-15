<?php
include '../auth.php';

// Session validation guard check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php"); 
    exit();
}

$status = '';

// Jab button click hoga toh data isi page par process hoga
if (isset($_POST['upload_book_btn'])) {
    
    // 1. Form Inputs Sanitize karein
    $title       = mysqli_real_escape_string($conn, trim($_POST['title']));
    $author      = mysqli_real_escape_string($conn, trim($_POST['author']));
    $category    = mysqli_real_escape_string($conn, trim($_POST['category']));
    
    // Agar price khali (empty) hai, toh iska matlab book FREE hai (0 value set hogi)
    $price_input = trim($_POST['price']);
    $price       = ($price_input === '') ? 0 : mysqli_real_escape_string($conn, $price_input);
    
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    
    $language    = "English"; 
    $rating      = "5.0";

    // 2. Upload directories
    $pdf_dir    = "../uploads/pdf/";
    $cover_dir  = "../uploads/covers/";

    if (!file_exists($pdf_dir)) mkdir($pdf_dir, 0777, true);
    if (!file_exists($cover_dir)) mkdir($cover_dir, 0777, true);

    $pdf_file   = $_FILES['book_pdf'];
    $cover_file = $_FILES['cover_image'];

    $pdf_ext    = strtolower(pathinfo($pdf_file['name'], PATHINFO_EXTENSION));
    $cover_ext  = strtolower(pathinfo($cover_file['name'], PATHINFO_EXTENSION));

    $allowed_cover_exts = ['jpg', 'jpeg', 'png', 'webp'];

    if ($pdf_ext !== 'pdf' || !in_array($cover_ext, $allowed_cover_exts)) {
        $status = 'invalid_file';
    } else {
        // Unique Names
        $new_pdf_name   = "book_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $pdf_ext;
        $new_cover_name = "cover_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $cover_ext;

        $pdf_target   = $pdf_dir . $new_pdf_name;
        $cover_target = $cover_dir . $new_cover_name;

        if (move_uploaded_file($pdf_file['tmp_name'], $pdf_target) && move_uploaded_file($cover_file['tmp_name'], $cover_target)) {
            
            // Query se weight hata diya gaya hai
            $insert_query = "INSERT INTO books (title, author, category, language, price, description, cover_image, file_name) 
                             VALUES ('$title', '$author', '$category', '$language', '$price', '$description', '$new_cover_name', '$new_pdf_name')";

            if (mysqli_query($conn, $insert_query)) {
                $status = 'success';
            } else {
                $status = 'failed';
            }
        } else {
            $status = 'failed';
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

            <?php if ($status == 'success'): ?>
                <div class="alert alert-success alert-dismissible fade show fw-bold mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> Book published and uploaded successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif ($status == 'failed'): ?>
                <div class="alert alert-danger alert-dismissible fade show fw-bold mb-4" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i> Error: Something went wrong during data insertion or folder permissions.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif ($status == 'invalid_file'): ?>
                <div class="alert alert-warning alert-dismissible fade show fw-bold mb-4" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> Error: Please upload a valid PDF or Image format!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="content-card">
                <h4 class="mb-4 text-primary fw-semibold"><i class="fa-solid fa-upload me-2"></i>Publish a New Book / CD</h4>
                
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Book Title</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g., Famous Comic Volume 1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Author Name</label>
                            <input type="text" name="author" class="form-control" placeholder="e.g., J.K. Rowling" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="Comics">Comics</option>
                                <option value="Novels">Novels</option>
                                <option value="Story Books">Story Books</option>
                                <option value="Journals">Journals</option>
                                <option value="General Knowledge">General Knowledge</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Base Price (PKR)</label>
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="e.g., 500 (Leave empty for FREE)">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Brief Synopsis / Description</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Enter a small summary of the book content..." required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-danger"><i class="fa-solid fa-file-pdf"></i> Upload Secure PDF Document</label>
                            <input type="file" name="book_pdf" class="form-control" accept=".pdf" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-success"><i class="fa-solid fa-image"></i> Upload Book Cover Thumbnail</label>
                            <input type="file" name="cover_image" class="form-control" accept="image/*" required>
                        </div>
                        <div class="col-md-12 mt-4">
                            <button type="submit" name="upload_book_btn" class="btn btn-primary w-100 py-2 fw-bold">
                                <i class="fa-solid fa-cloud-arrow-up me-2"></i>Save & Deploy Book To System
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