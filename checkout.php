<?php
include 'auth.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if (!isset($_GET['book_id']) || empty($_GET['book_id'])) {
    die("Invalid Selection.");
}

$book_id = intval($_GET['book_id']);
$book_result = mysqli_query($conn, "SELECT * FROM books WHERE id = $book_id");
$book = mysqli_fetch_assoc($book_result);

$book_title = $book['title']; 
$book_price = floatval($book['price']); 
$book_weight = isset($book['weight']) ? floatval($book['weight']) : 0.5;

if (isset($_POST['submit_payment_btn'])) {
    $user_name   = mysqli_real_escape_string($conn, trim($_POST['user_name']));
    $user_email  = mysqli_real_escape_string($conn, trim($_POST['user_email']));
    $user_phone  = mysqli_real_escape_string($conn, trim($_POST['user_phone']));
    $trx_id      = mysqli_real_escape_string($conn, trim($_POST['trx_id']));
    $book_format = mysqli_real_escape_string($conn, $_POST['book_format']); 
    $user_city   = mysqli_real_escape_string($conn, $_POST['user_city']); 
    
    // Backend Validation & Shipping Calculation logic 
    $shipping_cost = 0;
    if ($book_format !== 'pdf') {
        $city_base_rate = ($user_city === 'karachi') ? 150 : 250; 
        $weight_cost = $book_weight * 100; 
        $shipping_cost = $city_base_rate + $weight_cost;
    }
    $final_payable_amount = $book_price + $shipping_cost; 

    if ($user_email !== $_SESSION['email']) {
        $error = "Error: Please use your registered account email.";
    } elseif (empty($user_name) || empty($user_phone) || empty($trx_id) || empty($_FILES['screenshot']['name'])) {
        $error = "Kindly fill all fields and upload payment proof!";
    } else {
        $screenshot_name = $_FILES['screenshot']['name'];
        $screenshot_tmp  = $_FILES['screenshot']['tmp_name'];
        $target_dir      = "uploads/screenshots/"; 
        $ext = pathinfo($screenshot_name, PATHINFO_EXTENSION);
        $new_screenshot_name = "TRX_" . time() . "_" . rand(1000, 9999) . "." . $ext;
        $target_file = $target_dir . $new_screenshot_name;
        
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        
        if (move_uploaded_file($screenshot_tmp, $target_file)) {
            $order_query = "INSERT INTO orders (book_id, user_name, user_email, user_phone, price, transaction_id, screenshot, status, format, shipping_charges, final_price) 
                            VALUES ($book_id, '$user_name', '$user_email', '$user_phone', '$book_price', '$trx_id', '$new_screenshot_name', 'pending', '$book_format', '$shipping_cost', '$final_payable_amount')";
            
            if (mysqli_query($conn, $order_query)) {
                $success = "Payment receipt received! Admin verification will unlock your book within 10-15 minutes.";
            } else {
                $error = "Database Error: " . mysqli_error($conn);
            }
        } else {
            $error = "Failed to upload payment receipt screenshot.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout | E-Books</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* --- 100% CUSTOM PURE CSS --- */
        :root {
            --brand-orange: #ff6b00;
            --brand-orange-hover: #e66000;
            --text-dark: #2b2b2b;
            --text-muted: #6c757d;
            --bg-light: #f8f9fa;
            --border-light: #eef2f5;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body { 
            background-color: var(--bg-light); 
            color: var(--text-dark); 
            /* Font family stays clean so your imported global font works seamlessly */
            font-family: inherit; 
            /* padding: 40px 0;  */
            line-height: 1.5;
        }

        .checkout-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Responsive Flexbox Layout Splitter */
        .checkout-wrapper {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            display: flex;
            overflow: hidden;
            border: 1px solid var(--border-light);
            margin-top: 20px;
        }

        .summary-panel {
            flex: 5;
            background-color: #fafbfc;
            padding: 40px;
            border-right: 1px solid var(--border-light);
        }

        .form-panel {
            flex: 7;
            padding: 40px;
        }

        /* Typography & Utility classes */
        h4 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 25px;
            color: var(--text-dark);
        }

        .text-orange {
            color: var(--brand-orange) !important;
        }

        .small {
            font-size: 13px;
        }

        .text-muted {
            color: var(--text-muted);
        }

        .fw-bold { font-weight: 700; }
        .fw-semibold { font-weight: 600; }
        .text-center { text-align: center; }
        .mb-1 { margin-bottom: 4px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 16px; }
        .mb-4 { margin-bottom: 24px; }
        .me-2 { margin-right: 8px; }
        .mt-1 { margin-top: 4px; }
        .mt-4 { margin-top: 24px; }
        
        .d-none { 
            display: none !important; 
        }

        /* Custom Box Components */
        .receipt-box {
            background: #ffffff;
            border: 1px solid #e1e8ed;
            border-top: 4px solid var(--brand-orange);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
            color: var(--text-muted);
        }

        .price-row.total {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
            border-top: 1px dashed #dee2e6;
            padding-top: 15px;
            margin-top: 5px;
            margin-bottom: 0;
        }

        .bank-details {
            background: #fff9f5;
            border: 1px dashed #ffb880;
            border-radius: 12px;
            padding: 20px;
            color: #cc5500;
        }

        .qr-code-img {
            max-width: 130px;
            height: auto;
            border-radius: 6px;
            background: #ffffff;
            padding: 8px;
            border: 1px solid #dee2e6;
            margin-bottom: 12px;
        }

        /* Custom Form Input Controls */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .full-width {
            grid-column: span 2;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            background-color: #ffffff;
            border: 1px solid #ced4da;
            color: var(--text-dark);
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 14px;
            width: 100%;
            transition: all 0.3s;
            outline: none;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--brand-orange);
            box-shadow: 0 0 0 4px rgba(255, 107, 0, 0.12);
        }

        .bg-readonly {
            background-color: #f1f3f5;
            cursor: not-allowed;
        }

        .form-text {
            font-size: 12px;
            color: var(--text-muted);
        }

        /* Custom Handcrafted Buttons */
        .btn-orange-custom {
            background-color: var(--brand-orange);
            color: #ffffff;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-block;
            text-align: center;
            text-decoration: none;
        }

        .btn-orange-custom:hover {
            background-color: var(--brand-orange-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 0, 0.2);
        }

        .btn-success-shelf {
            background-color: #2b8a3e;
            color: #ffffff;
            padding: 10px 24px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            margin-top: 12px;
            transition: background 0.2s;
        }
        .btn-success-shelf:hover { background-color: #237032; }

        /* Custom Alert Notification Boxes */
        .custom-alert {
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }
        .custom-alert-success {
            background-color: #ebfbee;
            border: 1px solid #b2f2bb;
            color: #2b8a3e;
        }
        .custom-alert-danger {
            background-color: #fff5f5;
            border: 1px solid #ffc9c9;
            color: #c92a2a;
        }

        /* Responsive Breakpoints */
        @media (max-width: 768px) {
            .checkout-wrapper {
                flex-direction: column;
            }
            .summary-panel, .form-panel {
                flex: none;
                width: 100%;
                padding: 30px 20px;
            }
            .summary-panel {
                border-right: none;
                border-bottom: 1px solid var(--border-light);
            }
            .form-grid {
                grid-template-columns: 1fr;
            }
            .full-width, .form-group {
                grid-column: span 1;
            }
        }
        /* --- PREMIUM MODERN RECEIPT CARD CSS --- */
.receipt-card {
    background: #ffffff;
    border: 1px solid #eef2f5;
    border-radius: 14px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
    margin-bottom: 25px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.receipt-card:hover {
    box-shadow: 0 6px 24px rgba(255, 107, 0, 0.06);
    border-color: rgba(255, 107, 0, 0.25);
}

.receipt-header {
    display: flex;
    align-items: center;
    padding: 20px 24px;
    background: #fafbfc;
    gap: 15px;
}

.receipt-icon-box {
    background: rgba(255, 107, 0, 0.1);
    color: var(--brand-orange);
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.receipt-title-area {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.receipt-book-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    line-height: 1.4;
}

.receipt-weight-tag {
    font-size: 12px;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 5px;
}

.receipt-divider {
    border-top: 1px dashed #e1e8ed;
    margin: 0 24px;
}

.receipt-body {
    padding: 20px 24px;
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.price-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 14px;
}

.price-label {
    color: var(--text-muted);
    font-weight: 500;
}

.price-value {
    color: var(--text-dark);
    font-weight: 600;
}

/* Premium Highlighted Footer for Total Bill */
.receipt-footer-total {
    background: linear-gradient(135deg, #fff9f5 0%, #fff4ec 100%);
    padding: 20px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid #ffe6d5;
}

.total-label {
    font-size: 15px;
    font-weight: 700;
    color: #5c2400;
}

.total-amount {
    font-size: 20px;
    font-weight: 800;
    color: var(--brand-orange);
    letter-spacing: -0.5px;
}
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="checkout-container">

    <?php if (!empty($success)): ?>
        <div class="custom-alert custom-alert-success text-center">
            <h4 style="color: #2b8a3e; margin-bottom: 8px;"><i class="fa-solid fa-circle-check me-2"></i>Order Placed Successfully!</h4>
            <p><?php echo $success; ?></p>
            <a href="my_books.php" class="btn btn-success-shelf">Go to My Bookshelf</a>
        </div>
    <?php exit(); endif; ?>

    <?php if (!empty($error)): ?>
        <div class="custom-alert custom-alert-danger">
            <i class="fa-solid fa-triangle-exclamation me-2"></i><?php echo $error; ?>
        </div>
    <?php endif; ?>

    <div class="checkout-wrapper">
        
        <div class="summary-panel">
            <h4 class="fw-bold mb-4"><i class="fa-solid fa-cart-shopping text-orange me-2"></i>Order Summary</h4>
            
           <div class="receipt-card">
    
    <div class="receipt-header">
        <div class="receipt-icon-box">
            <i class="fa-solid fa-book"></i>
        </div>
        <div class="receipt-title-area">
            <h6 class="receipt-book-title"><?php echo htmlspecialchars($book_title); ?></h6>
            <span class="receipt-weight-tag">
                <i class="fa-solid fa-scale-balanced"></i> Item Weight: <?php echo $book_weight; ?> kg
            </span>
        </div>
    </div>

    <div class="receipt-divider"></div>

    <div class="receipt-body">
        <div class="price-item">
            <span class="price-label">Book Base Price</span>
            <span class="price-value">Rs. <?php echo number_format($book_price); ?></span>
        </div>
        
        <div class="price-item">
            <span class="price-label">Delivery & Handling</span>
            <span class="price-value" id="ship_fee_display">Rs. 0</span>
        </div>
    </div>

    <div class="receipt-footer-total">
        <span class="total-label">Total Payable</span>
        <span class="total-amount">Rs. <span id="total_payable_display"><?php echo number_format($book_price); ?></span></span>
    </div>
    
</div>
            <div class="bank-details text-center">
                <h6 class="fw-bold mb-3" style="font-size: 15px;"><i class="fa-solid fa-building-columns me-2"></i>Manual Bank Transfer</h6>
                <img src="assets/images/my_qr_code.png" alt="QR Code" class="qr-code-img" onerror="this.src='https://api.qrserver.com/v1/create-qr-code/?size=140x140&data=ManualPayment';">
                <p class="small mb-1" style="color: var(--text-dark);"><strong>Bank Name:</strong> HBL Bank Ltd</p>
                <p class="small" style="color: var(--text-dark);"><strong>Account No:</strong> PK64HBL00112233445566</p>
            </div>
        </div>

        <div class="form-panel">
            <h4 class="fw-bold mb-4">Checkout Details</h4>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-grid">
                    
                    <div class="form-group full-width">
                        <label class="form-label">Select Edition / Format</label>
                        <select name="book_format" id="bookFormat" class="form-select" required>
                            <option value="pdf">E-Book (Digital PDF Download - Free Delivery)</option>
                            <option value="hardcopy">Hard Copy (Printed Book Edition)</option>
                            <option value="cd">Interactive Disc Version (CD Edition)</option>
                        </select>
                    </div>

                    <div class="form-group full-width d-none" id="cityContainer">
                        <label class="form-label">Delivery Destination City</label>
                        <select name="user_city" id="userCity" class="form-select">
                            <option value="karachi">Karachi (Local City Distribution Hub)</option>
                            <option value="other">Other Domestic Cities (Standard Distribution)</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Depositor Full Name</label>
                        <input type="text" name="user_name" class="form-control" placeholder="Enter the name on the receipt" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="user_email" class="form-control bg-readonly" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" readonly required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">WhatsApp Number</label>
                        <input type="text" name="user_phone" class="form-control" placeholder="03XXXXXXXXX" required>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Transaction ID (TID)</label>
                        <input type="text" name="trx_id" class="form-control" placeholder="Enter 11-digit TID from your bank" required>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Upload Payment Receipt</label>
                        <input type="file" name="screenshot" class="form-control" accept="image/*" required>
                        <div class="form-text mt-1"><i class="fa-solid fa-circle-info me-1"></i>Please upload a clear screenshot of your successful transaction.</div>
                    </div>
                    
                    <div class="form-group full-width mt-4">
                        <button type="submit" name="submit_payment_btn" class="btn-orange-custom">
                            <i class="fa-solid fa-lock me-2"></i>Submit Secure Order
                        </button>
                    </div>
                    
                </div>
            </form>
        </div>

    </div>
</div>

<script>
const basePrice = <?php echo $book_price; ?>;
const weight = <?php echo $book_weight; ?>;

const formatSelect = document.getElementById('bookFormat');
const citySelect = document.getElementById('userCity');
const cityContainer = document.getElementById('cityContainer');
const shipFeeDisplay = document.getElementById('ship_fee_display');
const totalPayableDisplay = document.getElementById('total_payable_display');

function calculateTotal() {
    let shippingCost = 0;
    const format = formatSelect.value;
    
    if (format === 'pdf') {
        cityContainer.classList.add('d-none');
        citySelect.required = false;
        shippingCost = 0; 
    } else {
        cityContainer.classList.remove('d-none');
        citySelect.required = true;
        
        const cityBase = (citySelect.value === 'karachi') ? 150 : 250;
        const weightAddon = weight * 100; 
        shippingCost = cityBase + weightAddon;
    }
    
    let finalBill = basePrice + shippingCost;
    
    shipFeeDisplay.innerText = "Rs. " + shippingCost.toLocaleString();
    totalPayableDisplay.innerText = finalBill.toLocaleString();
}

formatSelect.addEventListener('change', calculateTotal);
citySelect.addEventListener('change', calculateTotal);
</script>

</body>
</html>