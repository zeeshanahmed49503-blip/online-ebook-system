<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php"); 
    exit();
}

// Agar control yahan tak pohncha, iska matlab banda admin hai!
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
                    <p class="text-muted m-0">Welcome back, Admin! Manage your book store publication system here.</p>
                </div>
            </div>

            <div class="content-card">
                <h4 class="mb-4 text-primary fw-semibold"><i class="fa-solid fa-upload me-2"></i>Publish a New Book / CD</h4>
                
                <form action="upload_book_process.php" method="POST" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Book Title</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g., Famous Comic Volume 1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Author Name</label>
                            <input type="text" name="author" class="form-control" placeholder="e.g., J.K. Rowling" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="Comics">Comics</option>
                                <option value="Novels">Novels</option>
                                <option value="Story Books">Story Books</option>
                                <option value="Journals">Journals</option>
                                <option value="General Knowledge">General Knowledge</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Base Price (PKR)</label>
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="500" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Weight (kg) <small class="text-muted">(For Shipping)</small></label>
                            <input type="number" step="0.01" name="weight" class="form-control" placeholder="0.3" required>
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