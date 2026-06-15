<?php
include("auth.php"); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 1. Logged-in user ki exact email nikalte hain users table se
$user_query = mysqli_query($conn, "SELECT email FROM users WHERE id = '$user_id'");
$user_data = mysqli_fetch_assoc($user_query);
$user_email = $user_data['email'];

// 2. Orders query: format aur tracking_number ke saath data fetch ho rha hai
$orders_query = "SELECT orders.*, IFNULL(books.title, 'Digital E-Book') AS book_title, books.file_name AS ebook_file 
                 FROM orders 
                 LEFT JOIN books ON orders.book_id = books.id 
                 WHERE orders.user_email = '$user_email' 
                 ORDER BY orders.created_at DESC";
$orders_result = mysqli_query($conn, $orders_query);
$total_orders = mysqli_num_rows($orders_result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookshelf - Bookish.</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-orange: #ff6b00;
            --accent-orange: #e05e00;
            --pure-black: #000000;
            --dark-gray: #1f2833;
            --text-light: #c5c6c7;
            --text-muted: #868e96;
        }

        /* ===== PAGE WRAPPER ===== */
        .shelf-section {
            width: 100%;
            background: #ffffff;
            margin-top: 20px;
            border-radius: 20px;
        }

        .shelf-container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ===== HERO HEADER (competitions.php style) ===== */
        .shelf-header {
            background: linear-gradient(135deg, #111111 0%, #222222 100%);
            padding: 35px;
            border-radius: 20px;
            border: 1px solid rgba(255, 107, 0, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            margin: 30px 0 40px 0;
            flex-wrap: wrap;
        }

        .shelf-header .desk-badge {
            background: rgba(255, 107, 0, 0.08);
            padding: 6px 14px;
            border-radius: 50px;
            border: 1px solid rgba(255, 107, 0, 0.25);
            color: var(--primary-orange);
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 14px;
        }

        .shelf-header .desk-title {
            font-size: 2.4rem;
            font-weight: 800;
            color: #ffffff;
            margin: 0 0 12px 0;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        .shelf-header .desk-title span {
            background: linear-gradient(to right, #ff6b00, #ff9f43);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .shelf-header .desk-subtitle {
            color: var(--text-light);
            font-size: 0.95rem;
            max-width: 600px;
            line-height: 1.6;
        }

        .shelf-header .desk-subtitle strong {
            color: #ffffff;
        }

        .shelf-stat-box {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 15px 25px;
            min-width: 140px;
            text-align: center;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .shelf-stat-box .stat-icon {
            font-size: 1.8rem;
            color: var(--primary-orange);
        }

        .shelf-stat-box .stat-text {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .shelf-stat-box .stat-num {
            font-size: 1.6rem;
            font-weight: 700;
            font-family: monospace;
            color: #ffffff;
        }

        .shelf-stat-box small {
            color: var(--primary-orange);
            font-weight: 600;
            font-size: 0.7rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        @media (max-width: 768px) {
            .shelf-header {
                flex-direction: column;
                align-items: flex-start;
                padding: 25px;
            }
            .shelf-header .desk-title {
                font-size: 1.8rem;
            }
            .shelf-stat-box {
                width: 100%;
                justify-content: center;
            }
        }

        /* ===== BOOKSHELF GRID (comp-card style) ===== */
        .bookshelf-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
            padding-bottom: 50px;
        }

        .book-card {
            background: #ffffff;
            border: 1px solid #eef0f2;
            border-radius: 20px;
            padding: 28px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .book-card:hover {
            transform: translateY(-6px);
            border-color: rgba(255, 107, 0, 0.25);
            box-shadow: 0 16px 32px rgba(255, 107, 0, 0.08);
        }

        /* Status Badges - top right corner */
        .status-badge {
            position: absolute;
            top: 18px;
            right: 18px;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border: 1px solid transparent;
        }
        .status-badge.pending { background: rgba(255, 193, 7, 0.08); color: #cc9a06; border-color: rgba(255,193,7,0.25); }
        .status-badge.approved { background: rgba(43, 147, 72, 0.08); color: #2b9348; border-color: rgba(43,147,72,0.25); }
        .status-badge.shipped { background: rgba(0, 123, 255, 0.08); color: #0d6efd; border-color: rgba(0,123,255,0.25); }
        .status-badge.rejected { background: rgba(217, 4, 41, 0.06); color: #d90429; border-color: rgba(217,4,41,0.2); }

        /* Format Tags */
        .format-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 50px;
            margin-top: 10px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .format-badge.pdf { background-color: rgba(43, 147, 72, 0.08); color: #2b9348; border: 1px solid rgba(43,147,72,0.2); }
        .format-badge.hardcopy { background-color: rgba(255, 107, 0, 0.08); color: var(--primary-orange); border: 1px solid rgba(255,107,0,0.2); }

        /* Icon Wrapper */
        .book-icon-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 18px;
        }
        .book-icon-wrapper.pending { background: rgba(255, 193, 7, 0.08); color: #cc9a06; }
        .book-icon-wrapper.approved { background: rgba(43, 147, 72, 0.08); color: #2b9348; }
        .book-icon-wrapper.shipped { background: rgba(0, 123, 255, 0.08); color: #0d6efd; }
        .book-icon-wrapper.rejected { background: rgba(217, 4, 41, 0.06); color: #d90429; }

        .book-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--pure-black);
            line-height: 1.4;
            margin-bottom: 4px;
            padding-right: 60px;
        }

        /* Order Meta - dark info strip like comp-meta */
        .order-meta {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin: 16px 0;
            line-height: 1.9;
            background: #fafafa;
            border: 1px solid #f0f0f0;
            padding: 14px 16px;
            border-radius: 12px;
        }

        .order-meta i {
            width: 16px;
            color: var(--primary-orange);
            margin-right: 4px;
        }

        .order-meta strong {
            color: var(--pure-black);
        }

        /* Shipping/Tracking Box - styled like comp-instructions */
        .shipping-box {
            background-color: #f8f9fa;
            border-left: 4px solid var(--primary-orange);
            padding: 12px 15px;
            margin: 0 0 16px 0;
            border-radius: 4px;
            font-size: 0.85rem;
            color: #333;
            line-height: 1.5;
        }
        .shipping-box .tracking-title {
            font-weight: 700;
            color: #111;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 6px;
        }
        .shipping-box .tracking-title i { color: var(--primary-orange); }
        .shipping-box .tracking-code {
            font-family: monospace;
            background: #fff;
            padding: 3px 8px;
            border-radius: 4px;
            border: 1px solid #eee;
            font-weight: 700;
            color: var(--primary-orange);
            letter-spacing: 0.5px;
        }
        .shipping-box small {
            display: block;
            margin-top: 6px;
            font-size: 10px;
            color: var(--text-muted);
        }

        /* ===== ACTION BUTTONS (comp-btn style) ===== */
        .action-btn {
            width: 100%;
            padding: 14px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 700;
            text-align: center;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .btn-download {
            background: linear-gradient(90deg, #ff6b00 0%, #ff8800 100%);
            color: #ffffff;
            box-shadow: 0 6px 16px rgba(255, 107, 0, 0.2);
        }
        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(255, 107, 0, 0.28);
        }

        .btn-status-info {
            background-color: rgba(43, 147, 72, 0.08);
            color: #2b9348;
            cursor: default;
            border: 1px solid rgba(43,147,72,0.15);
        }

        .btn-packing {
            background-color: rgba(0, 123, 255, 0.06);
            color: #0d6efd;
            cursor: default;
            border: 1px solid rgba(0,123,255,0.15);
        }

        .btn-waiting {
            background-color: #f5f5f5;
            color: #a0aec0;
            cursor: not-allowed;
            border: 1px solid #eee;
        }

        .btn-blocked {
            background-color: rgba(217, 4, 41, 0.06);
            color: #d90429;
            cursor: not-allowed;
            border: 1px solid rgba(217,4,41,0.15);
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 70px 20px;
            background: #ffffff;
            border-radius: 20px;
            border: 1px dashed rgba(255, 107, 0, 0.3);
        }
        .empty-state i {
            color: var(--primary-orange);
            margin-bottom: 18px;
            opacity: 0.4;
        }
        .empty-state h4 {
            font-weight: 800;
            color: var(--pure-black);
            font-size: 1.3rem;
            margin-bottom: 8px;
        }
        .empty-state p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* ===== BACK LINK ===== */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary-orange);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 700;
            margin: 10px 0 40px 0;
            transition: gap 0.2s ease;
        }
        .back-link:hover { gap: 12px; }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="app-container">
        <section class="shelf-section">
            <div class="shelf-container">

                <!-- ===== HERO HEADER ===== -->
                <div class="shelf-header">
                    <div class="header-text-side">
                        <span class="desk-badge">
                            <i class="fa-solid fa-book-bookmark"></i> Reader Dashboard
                        </span>
                        <h2 class="desk-title">
                            My <span>Bookshelf</span>
                        </h2>
                        <p class="desk-subtitle">
                            Manage your digital assets and real-time shipping logs for <strong><?php echo htmlspecialchars($user_email); ?></strong>.
                        </p>
                    </div>

                    <div class="shelf-stat-box">
                        <div class="stat-icon"><i class="fa-solid fa-layer-group"></i></div>
                        <div class="stat-text">
                            <span class="stat-num"><?php echo $total_orders; ?></span>
                            <small>Total Orders</small>
                        </div>
                    </div>
                </div>

                <!-- ===== BOOKSHELF GRID ===== -->
                <div class="bookshelf-grid">
                    <?php 
                    if ($total_orders > 0) {
                        while ($order = mysqli_fetch_assoc($orders_result)) {
                            $status = strtolower(trim($order['status']));
                            $format = strtolower(trim($order['format'] ?? 'pdf'));
                            ?>
                            <div class="book-card">

                                <span class="status-badge <?php echo $status; ?>">
                                    <?php 
                                    if($status == 'pending') echo 'Verifying Pay';
                                    elseif($status == 'approved') echo ($format == 'hardcopy') ? 'Packing' : 'Unlocked';
                                    elseif($status == 'shipped') echo 'In Transit';
                                    else echo 'Rejected';
                                    ?>
                                </span>

                                <div>
                                    <div class="book-icon-wrapper <?php echo $status; ?>">
                                        <?php if($status == 'shipped'): ?>
                                            <i class="fa-solid fa-truck-fast"></i>
                                        <?php elseif($status == 'approved'): ?>
                                            <i class="fa-solid fa-<?php echo ($format == 'hardcopy') ? 'box-open' : 'book-open'; ?>"></i>
                                        <?php elseif($status == 'pending'): ?>
                                            <i class="fa-solid fa-lock"></i>
                                        <?php else: ?>
                                            <i class="fa-solid fa-circle-xmark"></i>
                                        <?php endif; ?>
                                    </div>

                                    <div class="book-title"><?php echo htmlspecialchars($order['book_title']); ?></div>
                                    <div>
                                        <?php if($format == 'hardcopy'): ?>
                                            <span class="format-badge hardcopy"><i class="fa-solid fa-box"></i> Hardcopy Edition</span>
                                        <?php else: ?>
                                            <span class="format-badge pdf"><i class="fa-solid fa-file-pdf"></i> PDF E-Book</span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="order-meta">
                                        <div><i class="fa-solid fa-receipt"></i> Trx ID: <strong><?php echo htmlspecialchars($order['transaction_id']); ?></strong></div>
                                        <div><i class="fa-solid fa-money-bill-wave"></i> Final Paid: <strong>Rs. <?php echo htmlspecialchars($order['final_price'] ?? $order['price']); ?></strong></div>
                                    </div>

                                    <?php if($format == 'hardcopy' && $status == 'shipped' && !empty($order['tracking_number'])): ?>
                                        <div class="shipping-box">
                                            <div class="tracking-title">
                                                <i class="fa-solid fa-map-location-dot"></i> Courier Dispatch Info
                                            </div>
                                            <div>
                                                Tracking Number: <span class="tracking-code"><?php echo htmlspecialchars($order['tracking_number']); ?></span>
                                            </div>
                                            <small><i class="fa-solid fa-circle-info"></i> Share this code with the courier company to track delivery.</small>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div>
                                    <?php if($status == 'rejected'): ?>
                                        <button class="action-btn btn-blocked" disabled>
                                            <i class="fa-solid fa-ban"></i> Access Denied
                                        </button>
                                    <?php elseif($status == 'pending'): ?>
                                        <button class="action-btn btn-waiting" disabled>
                                            <i class="fa-solid fa-hourglass-half"></i> Awaiting Verification
                                        </button>
                                    <?php else: ?>
                                        <?php if($format == 'pdf'): ?>
                                            <a href="uploads/ebooks/<?php echo $order['ebook_file']; ?>" class="action-btn btn-download" download>
                                                <i class="fa-solid fa-cloud-arrow-down"></i> Download PDF Asset
                                            </a>
                                        <?php else: ?>
                                            <?php if($status == 'shipped'): ?>
                                                <div class="action-btn btn-status-info">
                                                    <i class="fa-solid fa-circle-check"></i> Dispatched / Out for Delivery
                                                </div>
                                            <?php else: ?>
                                                <div class="action-btn btn-packing">
                                                    <i class="fa-solid fa-warehouse"></i> Preparing &amp; Packing Package
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>

                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="empty-state">
                            <i class="fa-solid fa-box-open fa-3x"></i>
                            <h4>Your bookshelf is empty!</h4>
                            <p>No orders found matching <?php echo htmlspecialchars($user_email); ?></p>
                            <a href="index.php" class="action-btn btn-download" style="width: auto; display: inline-flex; margin-top: 20px; padding: 12px 30px;">Browse Store Now</a>
                        </div>
                        <?php
                    }
                    ?>
                </div>

                <a href="account.php" class="back-link">
                    <i class="fa-solid fa-arrow-left"></i> Back to Account Settings
                </a>

            </div>
        </section>
    </div>

    <?php include 'footer.php'?>

</body>
</html>