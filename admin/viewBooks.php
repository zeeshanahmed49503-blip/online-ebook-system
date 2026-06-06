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
            <a class="nav-link " href="dashboard.php"><i class="fa-solid fa-plus-circle"></i> Add New Book</a>
            <a class="nav-link active" href="viewBooks.php"><i class="fa-solid fa-book"></i> View All Books</a>
            <a class="nav-link" href="manage_orders.php"><i class="fa-solid fa-shopping-cart"></i> Manage Orders</a>
            <a class="nav-link" href="#"><i class="fa-solid fa-users"></i> Book Dealers</a>
            <a class="nav-link" href="#"><i class="fa-solid fa-trophy"></i> Winners Board</a>
            <a class="nav-link text-danger mt-5" href="logout.php"><i class="fa-solid fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="card border-0 shadow-sm p-4" style="border-radius: 12px; background: #fff;">
    
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
        <div>
            <h5 class="text-dark fw-bold m-0">
                <i class="fa-solid fa-book-bookmark text-primary me-2"></i> Catalog & Publication Management
            </h5>
            <small class="text-muted">Review your published collection, modify prices, or remove items from active inventory.</small>
        </div>
        <span class="badge bg-primary px-3 py-2 rounded-pill" style="font-size: 13px;">
            <i class="fa-solid fa-layer-group me-1"></i> Total Books: 3
        </span>
    </div>

    <div class="mb-4" style="position: relative;">
        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
        <input type="text" class="form-control" placeholder="Search books by title, author, or category..." style="padding-left: 38px; border-radius: 8px;">
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle m-0">
            <thead class="table-dark">
                <tr>
                    <th style="width: 80px;" class="text-center">Cover</th>
                    <th>Book Details</th>
                    <th>Category</th>
                    <th>Price Structure</th>
                    <th>Weight Parameters</th>
                    <th style="width: 180px;" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                
                <tr>
                    <td class="text-center">
                        <div class="bg-light rounded d-flex align-items-center justify-content-center border" style="width: 60px; height: 80px;">
                            <i class="fa-solid fa-image text-muted fs-5"></i>
                        </div>
                    </td>
                    <td>
                        <h6 class="fw-bold text-dark mb-1">The Amazing Spider-Man (Vol 1)</h6>
                        <span class="text-muted small" style="font-size: 12px;"><i class="fa-solid fa-pen-nib me-1"></i>Author: Stan Lee</span>
                    </td>
                    <td>
                        <span class="badge bg-info text-dark text-uppercase px-2 py-1" style="font-size: 11px; font-weight: 600; letter-spacing: 0.5px;">Comics</span>
                    </td>
                    <td class="fw-bold text-success">Rs. 450.00</td>
                    <td class="text-secondary small fw-semibold">0.25 kg</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-sm btn-warning fw-bold text-dark px-2" style="font-size: 13px;"><i class="fa-solid fa-pen me-1"></i> Edit</button>
                            <button class="btn btn-sm btn-danger fw-bold px-2" style="font-size: 13px;"><i class="fa-solid fa-trash me-1"></i> Delete</button>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="text-center">
                        <div class="bg-light rounded d-flex align-items-center justify-content-center border" style="width: 60px; height: 80px;">
                            <i class="fa-solid fa-image text-muted fs-5"></i>
                        </div>
                    </td>
                    <td>
                        <h6 class="fw-bold text-dark mb-1">Harry Potter & The Sorcerer's Stone</h6>
                        <span class="text-muted small" style="font-size: 12px;"><i class="fa-solid fa-pen-nib me-1"></i>Author: J.K. Rowling</span>
                    </td>
                    <td>
                        <span class="badge bg-secondary text-white text-uppercase px-2 py-1" style="font-size: 11px; font-weight: 600; letter-spacing: 0.5px;">Novels</span>
                    </td>
                    <td class="fw-bold text-success">Rs. 1,250.00</td>
                    <td class="text-secondary small fw-semibold">0.65 kg</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-sm btn-warning fw-bold text-dark px-2" style="font-size: 13px;"><i class="fa-solid fa-pen me-1"></i> Edit</button>
                            <button class="btn btn-sm btn-danger fw-bold px-2" style="font-size: 13px;"><i class="fa-solid fa-trash me-1"></i> Delete</button>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="text-center">
                        <div class="bg-light rounded d-flex align-items-center justify-content-center border" style="width: 60px; height: 80px;">
                            <i class="fa-solid fa-image text-muted fs-5"></i>
                        </div>
                    </td>
                    <td>
                        <h6 class="fw-bold text-dark mb-1">Annual Science Review 2026</h6>
                        <span class="text-muted small" style="font-size: 12px;"><i class="fa-solid fa-pen-nib me-1"></i>Author: Academic Press</span>
                    </td>
                    <td>
                        <span class="badge bg-warning text-dark text-uppercase px-2 py-1" style="font-size: 11px; font-weight: 600; letter-spacing: 0.5px;">Journals</span>
                    </td>
                    <td class="fw-bold text-success">Rs. 850.00</td>
                    <td class="text-secondary small fw-semibold">0.40 kg</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-sm btn-warning fw-bold text-dark px-2" style="font-size: 13px;"><i class="fa-solid fa-pen me-1"></i> Edit</button>
                            <button class="btn btn-sm btn-danger fw-bold px-2" style="font-size: 13px;"><i class="fa-solid fa-trash me-1"></i> Delete</button>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>