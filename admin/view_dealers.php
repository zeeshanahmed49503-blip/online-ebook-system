<?php
include '../auth.php'; // Aapka database connection ($conn) isme maujood hai
$error = ''; 

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// --- 1. HANDLE DELETE OPERATION ---
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_query = "DELETE FROM dealers WHERE id = $delete_id";
    if (mysqli_query($conn, $delete_query)) {
        header("Location: view_dealers.php?msg=deleted");
        exit();
    } else {
        $error = "Delete Error: " . mysqli_error($conn);
    }
}

// --- 2. HANDLE UPDATE OPERATION ---
if (isset($_POST['update_dealer_btn'])) {
    $dealer_id = intval($_POST['dealer_id']);
    $name      = mysqli_real_escape_string($conn, trim($_POST['name']));
    $phone     = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $timing    = mysqli_real_escape_string($conn, trim($_POST['timing']));
    $address   = mysqli_real_escape_string($conn, trim($_POST['address']));

    if (empty($name) || empty($phone) || empty($timing) || empty($address)) {
        $error = "All fields are required to update dealer data!";
    } else {
        $update_query = "UPDATE dealers SET name='$name', phone='$phone', timing='$timing', address='$address' WHERE id=$dealer_id";
        
        if (mysqli_query($conn, $update_query)) {
            header("Location: view_dealers.php?msg=updated");
            exit();
        } else {
            $error = "Update Error: " . mysqli_error($conn);
        }
    }
}

// --- 3. FETCH ALL DEALERS ---
$dealers_result = mysqli_query($conn, "SELECT * FROM dealers ORDER BY id DESC");
$total_dealers  = mysqli_num_rows($dealers_result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Authorized Dealers - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root { 
            --sidebar-bg: #11141a; 
            --main-bg: #f4f6fa; 
            --premium-blue: #2563eb;
        }
        body { background-color: var(--main-bg); font-family: 'Segoe UI', system-ui, sans-serif; }
        
        /* Sidebar Navigation Layout */
        .sidebar { width: 260px; height: 100vh; position: fixed; top: 0; left: 0; background-color: var(--sidebar-bg); padding-top: 20px; z-index: 100; }
        .sidebar .brand-title { color: #fff; font-size: 20px; font-weight: 700; padding: 10px 20px; margin-bottom: 20px; border-bottom: 1px solid #222a36; }
        .sidebar .nav-link { color: #a0aec0; padding: 12px 20px; font-weight: 500; display: flex; align-items: center; gap: 12px; transition: all 0.3s; text-decoration: none; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: rgba(255, 255, 255, 0.05); border-left: 4px solid #3b82f6; }
        
        /* Main Container Content */
        .main-content { margin-left: 260px; padding: 40px; min-height: 100vh; }
        
        /* Premium Cards Redesign Architecture */
        .dealer-card {
            background: #ffffff;
            border-radius: 20px;
            border: none;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(165, 173, 197, 0.1);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
        }
        .dealer-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #3b82f6, #1d4ed8);
        }
        .dealer-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(165, 173, 197, 0.25);
        }

        .info-wrapper {
            background-color: #f8fafc;
            border: 1px solid #edf2f7;
            border-radius: 14px;
        }

        /* Action Controls Layout Elements */
        .btn-edit-custom {
            background-color: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .btn-edit-custom:hover { background-color: #2563eb; color: #ffffff; }
        
        .btn-delete-custom {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .btn-delete-custom:hover { background-color: #dc2626; color: #ffffff; }

        @media (max-width: 768px) { .sidebar { width: 100%; height: auto; position: relative; } .main-content { margin-left: 0; padding: 20px; } }
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
        
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-radius: 12px; background-color: #fef2f2; color: #991b1b;">
                <i class="fa-solid fa-user-xmark me-2"></i> <strong>Removed!</strong> Dealer profile deleted permanently from active clusters.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-radius: 12px; background-color: #f0fdf4; color: #166534;">
                <i class="fa-solid fa-user-check me-2"></i> <strong>Success!</strong> Dealer operational details updated successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-radius: 12px;">
                <i class="fa-solid fa-circle-exclamation me-2"></i> <strong>Error!</strong> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-5 gap-3">
            <div>
                <h3 class="fw-bold text-dark m-0">Registered Book Dealers</h3>
                <p class="text-muted small m-0 mt-1">Monitor distributors logs, modify retail store addresses, and track operating shift timings.</p>
            </div>
            <div class="bg-white px-4 py-2 rounded-4 shadow-sm border text-dark fw-bold d-flex align-items-center gap-2" style="font-size: 14px;">
                <span class="p-2 bg-primary-subtle text-primary rounded-3"><i class="fa-solid fa-store"></i></span> Total Dealers: <?php echo $total_dealers; ?>
            </div>
        </div>

        <div class="row g-4">
            <?php if($total_dealers > 0): ?>
                <?php while($row = mysqli_fetch_assoc($dealers_result)): ?>
                    <div class="col-xl-4 col-md-6 col-sm-12">
                        <div class="card dealer-card p-4">
                            
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary-subtle text-primary rounded-circle p-3 d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                    <i class="fa-solid fa-shop fs-5"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <h5 class="fw-bold text-dark m-0 text-truncate" style="font-size: 17px; letter-spacing: -0.2px;"><?php echo htmlspecialchars($row['name']); ?></h5>
                                    <small class="text-muted"><i class="fa-solid fa-id-badge me-1"></i> ID: #<?php echo $row['id']; ?></small>
                                </div>
                            </div>
                            
                            <p class="text-secondary small mb-3 text-start" style="-webkit-line-clamp: 2; display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden; height: 38px; line-height: 1.4;">
                                <i class="fa-solid fa-location-dot text-muted me-1"></i> <?php echo htmlspecialchars($row['address']); ?>
                            </p>
                            
                            <div class="info-wrapper p-3 mb-4">
                                <div class="row g-2">
                                    <div class="col-12 small text-dark">
                                        <span class="text-muted d-inline-block" style="width: 70px;">Phone:</span>
                                        <b class="text-primary"><i class="fa-solid fa-phone me-1"></i> <?php echo htmlspecialchars($row['phone']); ?></b>
                                    </div>
                                    <div class="col-12 small text-dark border-top pt-2 mt-2">
                                        <span class="text-muted d-inline-block" style="width: 70px;">Timings:</span>
                                        <b><i class="fa-solid fa-clock text-warning me-1"></i> <?php echo htmlspecialchars($row['timing']); ?></b>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-sm btn-edit-custom px-3 py-2 edit-dealer-btn" 
                                        data-id="<?php echo $row['id']; ?>"
                                        data-name="<?php echo htmlspecialchars($row['name']); ?>"
                                        data-phone="<?php echo htmlspecialchars($row['phone']); ?>"
                                        data-timing="<?php echo htmlspecialchars($row['timing']); ?>"
                                        data-address="<?php echo htmlspecialchars($row['address']); ?>">
                                    <i class="fa-solid fa-user-gear me-1"></i> Edit Logs
                                </button>
                                <a href="view_dealers.php?delete_id=<?php echo $row['id']; ?>" 
                                   class="btn btn-sm btn-delete-custom px-3 py-2" 
                                   onclick="return confirm('Kya aap is Book Dealer ki profile registry hamesha ke liye drop karna chahte hain?');">
                                    <i class="fa-solid fa-trash-can me-1"></i> Remove
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="text-center bg-white p-5 rounded-4 shadow-sm border text-muted">
                        <i class="fa-solid fa-users-slash fs-1 mb-3 text-secondary d-block"></i>
                        System register me koi bhi dealer mila nahi mila.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <div class="modal fade" id="editDealerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content" style="border-radius:20px; overflow:hidden; border:none; box-shadow: 0 15px 30px rgba(0,0,0,0.1);">
                <form action="view_dealers.php" method="POST">
                    <div class="modal-header bg-dark text-white border-0 py-3">
                        <h5 class="modal-title fw-bold"><i class="fa-solid fa-user-pen text-warning me-2"></i>Modify Dealer Parameters</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 bg-light">
                        <input type="hidden" name="dealer_id" id="modal_dealer_id">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-secondary">Dealer / Shop Name</label>
                                <input type="text" name="name" id="modal_name" class="form-control border-0 shadow-sm rounded-3" style="padding:12px;" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-secondary">Phone Number</label>
                                <input type="text" name="phone" id="modal_phone" class="form-control border-0 shadow-sm rounded-3" style="padding:12px;" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-secondary">Operating Shift Timings</label>
                                <input type="text" name="timing" id="modal_timing" class="form-control border-0 shadow-sm rounded-3" style="padding:12px;" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-secondary">Store Retail Address</label>
                                <textarea name="address" id="modal_address" class="form-control border-0 shadow-sm rounded-3" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal" style="border-radius:10px;">Cancel</button>
                        <button type="submit" name="update_dealer_btn" class="btn btn-primary px-4 py-2" style="border-radius:10px; background-color: #2563eb;">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // JS function to trap rows data and bind it seamlessly into popup modals on instant trigger
        document.querySelectorAll('.edit-dealer-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('modal_dealer_id').value = this.getAttribute('data-id');
                document.getElementById('modal_name').value = this.getAttribute('data-name');
                document.getElementById('modal_phone').value = this.getAttribute('data-phone');
                document.getElementById('modal_timing').value = this.getAttribute('data-timing');
                document.getElementById('modal_address').value = this.getAttribute('data-address');

                var editModal = new bootstrap.Modal(document.getElementById('editDealerModal'));
                editModal.show();
            });
        });
    </script>
</body>
</html>