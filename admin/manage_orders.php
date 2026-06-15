<?php
// Database connection aur authentication ko include karne ke liye
include '../auth.php'; 

$msg = '';
$error = '';

// --- 1. HANDLE STATUS ACTIONS (APPROVE / REJECT) ---
if (isset($_GET['action']) && isset($_GET['order_id'])) {
    $action = mysqli_real_escape_string($conn, $_GET['action']);
    $order_id = intval($_GET['order_id']);
    
    if ($action == 'approve') {
        $update_query = "UPDATE orders SET status = 'approved' WHERE id = $order_id";
        if (mysqli_query($conn, $update_query)) {
            $msg = "Order #$order_id has been APPROVED successfully! E-Book unlocked for user.";
        } else {
            $error = "Error updating order: " . mysqli_error($conn);
        }
    } elseif ($action == 'reject') {
        $update_query = "UPDATE orders SET status = 'rejected' WHERE id = $order_id";
        if (mysqli_query($conn, $update_query)) {
            $msg = "Order #$order_id has been REJECTED.";
        } else {
            $error = "Error updating order: " . mysqli_error($conn);
        }
    }
}

// --- 2. COUNT PENDING ORDERS FOR THE BADGE ---
$count_query = "SELECT COUNT(*) as pending_total FROM orders WHERE status = 'pending'";
$count_result = mysqli_query($conn, $count_query);
$count_data = mysqli_fetch_assoc($count_result);
$pending_count = $count_data['pending_total'] ?? 0;

// --- 3. FETCH LIVE ORDERS DATA (orders.* automatically fetches format, shipping_charges, final_price)
$orders_query = "SELECT orders.*, books.title AS book_title 
                 FROM orders 
                 LEFT JOIN books ON orders.book_id = books.id 
                 ORDER BY orders.created_at DESC";
$orders_result = mysqli_query($conn, $orders_query);
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
        
        /* Premium Clean White Table Card */
        .content-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            padding: 25px;
            border: 1px solid #eef2f5;
        }
        
        .screenshot-thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #dee2e6;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .screenshot-thumb:hover {
            transform: scale(1.1);
            border-color: #0d6efd;
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
        <a class="nav-link" href="dashboard.php"><i class="fa-solid fa-plus-circle"></i> Add New Book</a>
        <a class="nav-link" href="viewBooks.php"><i class="fa-solid fa-book"></i> View All Books</a>
        <a class="nav-link active" href="manage_orders.php"><i class="fa-solid fa-shopping-cart"></i> Manage Orders</a>
        <a class="nav-link" href="view_dealers.php"><i class="fa-solid fa-users"></i> Book Dealers</a>
        <a class="nav-link" href="dealers.php"><i class="fa-solid fa-users"></i> add Dealers</a>
        <a class="nav-link" href="competition.php"><i class="fa-solid fa-trophy"></i> Add Competitions</a>
        <a class="nav-link" href="view_competition.php"><i class="fa-solid fa-trophy"></i> Competitions Board</a>
        <a class="nav-link" href="winners.php"><i class="fa-solid fa-award"></i> Winners Board</a>
        <a class="nav-link" href="contact_problems.php"><i class="fa-solid fa-envelope-open-text"></i> Contact & Problems</a>
        <a class="nav-link text-danger mt-5" href="logout.php"><i class="fa-solid fa-sign-out-alt"></i> Logout</a>
    </nav>
</aside>

    <main class="main-content">
       <div class="content-card">
    
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
        <div>
            <h5 class="text-dark fw-bold m-0">
                <i class="fa-solid fa-cart-shopping text-warning me-2"></i> Order Fulfillment Stream
            </h5>
            <small class="text-muted">Track user purchases, verify payment receipts, and manage digital asset clearance.</small>
        </div>
        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill" style="font-size: 13px; font-weight: 600;">
            <i class="fa-solid fa-clock me-1"></i> Pending Orders: <?php echo $pending_count; ?>
        </span>
    </div>

    <?php if(!empty($msg)): ?>
        <div class="alert alert-success border-0 mb-4" style="border-radius: 8px;">
            <i class="fa-solid fa-circle-check me-2"></i> <?php echo $msg; ?>
        </div>
    <?php endif; ?>
    <?php if(!empty($error)): ?>
        <div class="alert alert-danger border-0 mb-4" style="border-radius: 8px;">
            <i class="fa-solid fa-circle-exclamation me-2"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle m-0">
            <thead class="table-dark">
                <tr>
                    <th style="width: 90px;" class="text-center">Order ID</th>
                    <th>Customer Details</th>
                    <th>Book & Format</th>
                    <th>Pricing Breakdown</th>
                    <th>Transaction ID</th>
                    <th class="text-center">Payment Proof</th>
                    <th class="text-center">Status</th>
                    <th style="width: 180px;" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (mysqli_num_rows($orders_result) > 0) {
                    while ($row = mysqli_fetch_assoc($orders_result)) {
                        ?>
                        <tr>
                            <td class="text-center fw-bold text-secondary">#ORD-<?php echo $row['id']; ?></td>
                            <td>
                                <h6 class="fw-bold mb-0" style="font-size: 14px;"><?php echo htmlspecialchars($row['user_name']); ?></h6>
                                <small class="text-muted d-block"><?php echo htmlspecialchars($row['user_email']); ?></small>
                                <small class="text-muted d-block" style="font-size: 11px;"><?php echo htmlspecialchars($row['user_phone']); ?></small>
                            </td>
                            <td>
                                <span class="fw-semibold d-block mb-1"><?php echo htmlspecialchars($row['book_title'] ?? 'Asset Removed'); ?></span>
                                <?php if(!empty($row['format'])): ?>
                                    <?php if(strtolower($row['format']) == 'hardcopy'): ?>
                                        <span class="badge bg-warning-subtle text-warning border border-warning px-2 py-1" style="font-size: 10px; font-weight:600;">
                                            <i class="fa-solid fa-box-open me-1"></i> Hardcopy
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-info-subtle text-info border border-info px-2 py-1" style="font-size: 10px; font-weight:600;">
                                            <i class="fa-solid fa-file-pdf me-1"></i> PDF E-Book
                                        </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary px-2 py-1" style="font-size: 10px;">PDF</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="font-size: 13px; line-height: 1.4; min-width: 140px;">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Base:</span> 
                                        <span>Rs. <?php echo number_format($row['price'] ?? 0); ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Delivery:</span> 
                                        <span>Rs. <?php echo number_format($row['shipping_charges'] ?? 0); ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between border-top pt-1 mt-1 fw-bold text-success" style="font-size: 13.5px;">
                                        <span>Total:</span> 
                                        <span>Rs. <?php echo number_format($row['final_price'] ?? 0); ?></span>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="font-monospace text-secondary" style="font-size: 13px;">
                                <?php echo !empty($row['transaction_id']) ? htmlspecialchars($row['transaction_id']) : '<em>N/A</em>'; ?>
                            </td>
                            
                            <td class="text-center">
                                <?php if(!empty($row['screenshot'])): ?>
                                    <a href="../uploads/screenshots/<?php echo $row['screenshot']; ?>" target="_blank">
                                        <img src="../uploads/screenshots/<?php echo $row['screenshot']; ?>" class="screenshot-thumb" alt="Proof">
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">No File</span>
                                <?php endif; ?>
                            </td>
                            
                            <td class="text-center">
                                <?php if($row['status'] == 'pending'): ?>
                                    <span class="badge bg-danger-subtle text-danger px-2 py-1" style="font-size: 11px; font-weight: 600;">Pending Verification</span>
                                <?php elseif($row['status'] == 'approved'): ?>
                                    <span class="badge bg-success-subtle text-success px-2 py-1" style="font-size: 11px; font-weight: 600;">Approved / Paid</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary px-2 py-1" style="font-size: 11px; font-weight: 600;">Rejected</span>
                                <?php endif; ?>
                            </td>
                            
                            <td class="text-center">
                                <?php if ($row['status'] == 'pending'): ?>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="manage_orders.php?action=approve&order_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success fw-bold py-1" style="font-size: 11px;" onclick="return confirm('Approve payment proof?')">
                                            <i class="fa-solid fa-check"></i> Approve
                                        </a>
                                        <a href="manage_orders.php?action=reject&order_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger fw-bold py-1" style="font-size: 11px;" onclick="return confirm('Reject this entry?')">
                                            Reject
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-outline-secondary fw-bold w-100" style="font-size: 11px;" disabled>
                                        <i class="fa-solid fa-circle-check"></i> Order Closed
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center py-4 text-muted'>No entries found in backend ledger log.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>