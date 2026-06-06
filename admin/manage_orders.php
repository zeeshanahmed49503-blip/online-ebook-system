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
            <a class="nav-link " href="#"><i class="fa-solid fa-plus-circle"></i> Add New Book</a>
            <a class="nav-link active" href="viewBooks.php"><i class="fa-solid fa-book"></i> View All Books</a>
            <a class="nav-link" href="#"><i class="fa-solid fa-shopping-cart"></i> Manage Orders</a>
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
                <i class="fa-solid fa-cart-shopping text-warning me-2"></i> Order Fulfillment Stream
            </h5>
            <small class="text-muted">Track user purchases, verify payment receipts, and manage shipping dispatch for hard copies/CDs.</small>
        </div>
        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill" style="font-size: 13px; font-weight: 600;">
            <i class="fa-solid fa-clock me-1"></i> Pending Orders: 2
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle m-0">
            <thead class="table-dark">
                <tr>
                    <th style="width: 90px;">Order ID</th>
                    <th>Customer Details</th>
                    <th>Book Purchased</th>
                    <th>Format Type</th>
                    <th>Total Price</th>
                    <th>Delivery/Shipping Address</th>
                    <th>Status</th>
                    <th style="width: 150px;" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                
                <tr>
                    <td class="text-center fw-bold text-secondary">#ORD-9482</td>
                    <td>
                        <h6 class="fw-bold mb-0" style="font-size: 14px;">Zeeshan Ali</h6>
                        <small class="text-muted">zeeshan@email.com</small>
                    </td>
                    <td><span class="fw-semibold">The Amazing Spider-Man</span></td>
                    <td>
                        <span class="badge bg-danger text-white text-uppercase" style="font-size: 10px; font-weight: 700;"><i class="fa-solid fa-file-pdf me-1"></i> E-Book (PDF)</span>
                    </td>
                    <td class="fw-bold text-success">Rs. 450.00</td>
                    <td>
                        <span class="text-muted small"><em>No Shipping Required (Digital Download)</em></span>
                    </td>
                    <td>
                        <span class="badge bg-danger-subtle text-danger px-2 py-1" style="font-size: 11px; font-weight: 600;">Pending Payment</span>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-success fw-bold w-100" style="font-size: 12px;">
                            <i class="fa-solid fa-check me-1"></i> Approve Payment
                        </button>
                    </td>
                </tr>

                <tr>
                    <td class="text-center fw-bold text-secondary">#ORD-9481</td>
                    <td>
                        <h6 class="fw-bold mb-0" style="font-size: 14px;">Ayesha Khan</h6>
                        <small class="text-muted">+92 300 1234567</small>
                    </td>
                    <td><span class="fw-semibold">Harry Potter (Hard Cover)</span></td>
                    <td>
                        <span class="badge bg-primary text-white text-uppercase" style="font-size: 10px; font-weight: 700;"><i class="fa-solid fa-compact-disc me-1"></i> CD / Printed</span>
                    </td>
                    <td class="fw-bold text-success">Rs. 1,450.00 <br><small class="text-muted" style="font-size: 11px;">(Inc. Shipping)</small></td>
                    <td>
                        <div class="small text-dark" style="font-size: 12px; line-height: 1.4;">
                            <strong>Address:</strong> House 45, Street 3, Gulshan-e-Iqbal, Karachi. <br>
                            <strong>Weight:</strong> 0.65 kg
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-success-subtle text-success px-2 py-1" style="font-size: 11px; font-weight: 600;">Paid & Dispatched</span>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-secondary fw-bold w-100" style="font-size: 12px;" disabled>
                            <i class="fa-solid fa-circle-check"></i> Order Closed
                        </button>
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