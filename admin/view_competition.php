<?php
include '../auth.php'; 
$error = ''; 

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// --- 1. HANDLE DELETE OPERATION ---
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_query = "DELETE FROM competitions WHERE id = $delete_id";
    if (mysqli_query($conn, $delete_query)) {
        header("Location: view_competition.php?msg=deleted");
        exit();
    } else {
        $error = "Delete Error: " . mysqli_error($conn);
    }
}

// --- 2. HANDLE UPDATE OPERATION ---
if (isset($_POST['update_competition_btn'])) {
    $comp_id     = intval($_POST['comp_id']);
    $title       = mysqli_real_escape_string($conn, trim($_POST['title']));
    $status      = mysqli_real_escape_string($conn, trim($_POST['status']));
    $starting    = mysqli_real_escape_string($conn, trim($_POST['starting']));
    $deadline    = mysqli_real_escape_string($conn, trim($_POST['deadline']));
    $reward      = mysqli_real_escape_string($conn, trim($_POST['reward']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));

    if (empty($title) || empty($status) || empty($starting) || empty($deadline) || empty($reward) || empty($description)) {
        $error = "All fields are required for update!";
    } else {
        $update_query = "UPDATE competitions SET title='$title', status='$status', starting_at='$starting', deadline='$deadline', reward='$reward', description='$description' WHERE id=$comp_id";
        
        if (mysqli_query($conn, $update_query)) {
            header("Location: view_competition.php?msg=updated");
            exit();
        } else {
            $error = "Update Error: " . mysqli_error($conn);
        }
    }
}

// --- 3. FETCH ALL COMPETITIONS ---
$comp_result = mysqli_query($conn, "SELECT * FROM competitions ORDER BY id DESC");
$total_comps = mysqli_num_rows($comp_result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Competitions - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root { 
            --sidebar-bg: #11141a; 
            --main-bg: #f4f6fa; 
            --card-glow-active: #2ec4b6;
            --card-glow-upcoming: #ff9f1c;
        }
        body { background-color: var(--main-bg); font-family: 'Segoe UI', system-ui, sans-serif; }
        
        /* Sidebar Styles */
        .sidebar { width: 260px; height: 100vh; position: fixed; top: 0; left: 0; background-color: var(--sidebar-bg); padding-top: 20px; z-index: 100; }
        .sidebar .brand-title { color: #fff; font-size: 20px; font-weight: 700; padding: 10px 20px; margin-bottom: 20px; border-bottom: 1px solid #222a36; }
        .sidebar .nav-link { color: #a0aec0; padding: 12px 20px; font-weight: 500; display: flex; align-items: center; gap: 12px; transition: all 0.3s; text-decoration: none; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: rgba(255, 255, 255, 0.05); border-left: 4px solid #3b82f6; }
        
        /* Main Layout */
        .main-content { margin-left: 260px; padding: 40px; min-height: 100vh; }
        
        /* Premium Cards Design */
        .premium-card {
            background: #ffffff;
            border-radius: 20px;
            border: none;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(165, 173, 197, 0.1);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
        }
        .premium-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
        }
        /* Top line highlights according to contest status */
        .card-active::before { background: linear-gradient(90deg, #2ec4b6, #00b4d8); }
        .card-upcoming::before { background: linear-gradient(90deg, #ff9f1c, #ffbf69); }

        .premium-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(165, 173, 197, 0.25);
        }

        .meta-box {
            background-color: #f8fafc;
            border: 1px solid #edf2f7;
            border-radius: 14px;
        }

        /* Custom Action Buttons */
        .btn-edit-custom {
            background-color: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .btn-edit-custom:hover {
            background-color: #2563eb;
            color: #ffffff;
        }
        .btn-delete-custom {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .btn-delete-custom:hover {
            background-color: #dc2626;
            color: #ffffff;
        }

        @media (max-width: 768px) { .sidebar { width: 100%; height: auto; position: relative; } .main-content { margin-left: 0; padding: 20px; } }
    </style>
</head>
<body>

    <!-- SIDEBAR PANEL -->
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

    <!-- MAIN DASHBOARD CONTENT -->
    <main class="main-content">
        
        <!-- ALERTS -->
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-radius: 12px; background-color: #fef2f2; color: #991b1b;">
                <i class="fa-solid fa-trash me-2"></i> <strong>Deleted!</strong> Contest configuration removed permanently.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-radius: 12px; background-color: #f0fdf4; color: #166534;">
                <i class="fa-solid fa-circle-check me-2"></i> <strong>Updated!</strong> Competition settings re-saved successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- HEADER SECTION -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-5 gap-3">
            <div>
                <h3 class="fw-bold text-dark m-0">Live Competitions Hub</h3>
                <p class="text-muted small m-0 mt-1">Manage ongoing writing challenges, adjust submission deadlines, and monitor rewards status.</p>
            </div>
            <div class="bg-white px-4 py-2 rounded-4 shadow-sm border text-dark fw-bold d-flex align-items-center gap-2" style="font-size: 14px;">
                <span class="p-2 bg-primary-subtle text-primary rounded-3"><i class="fa-solid fa-trophy"></i></span> Total Contests: <?php echo $total_comps; ?>
            </div>
        </div>

        <!-- CARDS DISPLAY GRID -->
        <div class="row g-4">
            <?php if($total_comps > 0): ?>
                <?php while($row = mysqli_fetch_assoc($comp_result)): ?>
                    <?php 
                        $is_active = ($row['status'] == 'active');
                        $card_class = $is_active ? 'card-active' : 'card-upcoming';
                    ?>
                    <div class="col-xl-6 col-md-12">
                        <div class="card premium-card <?php echo $card_class; ?> p-4">
                            
                            <!-- Card Header (Title & Status Badge) -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="fw-bold text-dark m-0 text-truncate" style="max-width: 75%; font-size: 18px; letter-spacing: -0.3px;"><?php echo htmlspecialchars($row['title']); ?></h5>
                                <?php if($is_active): ?>
                                    <span class="badge px-3 py-2 rounded-pill fw-bold" style="background-color: #e6fffa; color: #00a389; font-size: 11px;"><i class="fa-solid fa-circle-dot me-1 animate-pulse"></i> LIVE</span>
                                <?php else: ?>
                                    <span class="badge px-3 py-2 rounded-pill fw-bold" style="background-color: #fffaf0; color: #dd6b20; font-size: 11px;"><i class="fa-solid fa-clock me-1"></i> UPCOMING</span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Rules Description Text -->
                            <p class="text-secondary small mb-4 text-start" style="-webkit-line-clamp: 2; display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden; height: 40px; line-height: 1.5;">
                                <?php echo htmlspecialchars($row['description']); ?>
                            </p>
                            
                            <!-- Info Meta Box Grid -->
                            <div class="meta-box p-3 mb-4">
                                <div class="row g-3 align-items-center">
                                    <div class="col-6">
                                        <div class="text-muted small" style="font-size: 11px; text-uppercase; font-weight:700;">Launch Date</div>
                                        <div class="fw-bold text-dark mt-1" style="font-size: 13px;"><i class="fa-solid fa-calendar text-primary me-1"></i> <?php echo date('d M, Y', strtotime($row['starting_at'])); ?></div>
                                    </div>
                                    <div class="col-6 border-start ps-3">
                                        <div class="text-muted small" style="font-size: 11px; text-uppercase; font-weight:700;">Deadline</div>
                                        <div class="fw-bold text-danger mt-1" style="font-size: 13px;"><i class="fa-solid fa-stopwatch me-1"></i> <?php echo date('d M, Y', strtotime($row['deadline'])); ?></div>
                                    </div>
                                    <div class="col-12 border-top pt-2 mt-2">
                                        <div class="text-muted small" style="font-size: 11px; text-uppercase; font-weight:700;">Grand Reward Pool</div>
                                        <div class="fw-bold text-success mt-1" style="font-size: 14px;"><i class="fa-solid fa-gift me-1"></i> <?php echo htmlspecialchars($row['reward']); ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Control Controls -->
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-sm btn-edit-custom px-3 py-2 edit-comp-btn" 
                                        data-id="<?php echo $row['id']; ?>"
                                        data-title="<?php echo htmlspecialchars($row['title']); ?>"
                                        data-status="<?php echo $row['status']; ?>"
                                        data-starting="<?php echo $row['starting_at']; ?>"
                                        data-deadline="<?php echo $row['deadline']; ?>"
                                        data-reward="<?php echo htmlspecialchars($row['reward']); ?>"
                                        data-desc="<?php echo htmlspecialchars($row['description']); ?>">
                                    <i class="fa-solid fa-sliders me-1"></i> Adjust Settings
                                </button>
                                <a href="view_competition.php?delete_id=<?php echo $row['id']; ?>" 
                                   class="btn btn-sm btn-delete-custom px-3 py-2" 
                                   onclick="return confirm('Kya aap is competition event ko permanently delete karna chahte hain?');">
                                    <i class="fa-solid fa-trash-can me-1"></i> Drop Event
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="text-center bg-white p-5 rounded-4 shadow-sm border text-muted">
                        <i class="fa-solid fa-inbox fs-1 mb-3 text-secondary d-block"></i>
                        Koi active ya upcoming competition nahi chal raha hai abhi.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- --- BOOTSTRAP EDIT MODAL --- -->
    <div class="modal fade" id="editCompModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:20px; overflow:hidden; border:none; box-shadow: 0 15px 30px rgba(0,0,0,0.1);">
                <form action="view_competition.php" method="POST">
                    <div class="modal-header bg-dark text-white border-0 py-3">
                        <h5 class="modal-title fw-bold"><i class="fa-solid fa-pen-to-square text-warning me-2"></i>Modify Challenge Parameter</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 bg-light">
                        <input type="hidden" name="comp_id" id="modal_comp_id">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold small text-secondary">Competition Title</label>
                                <input type="text" name="title" id="modal_title" class="form-control border-0 shadow-sm rounded-3" style="padding:12px;" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">Status Tag</label>
                                <select name="status" id="modal_status" class="form-select border-0 shadow-sm rounded-3" style="padding:12px;">
                                    <option value="active">Active (Open)</option>
                                    <option value="upcoming">Upcoming (Locked)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">Starting Date</label>
                                <input type="date" name="starting" id="modal_starting" class="form-control border-0 shadow-sm rounded-3" style="padding:12px;" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">Deadline Date</label>
                                <input type="date" name="deadline" id="modal_deadline" class="form-control border-0 shadow-sm rounded-3" style="padding:12px;" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">Prize Pool Structure</label>
                                <input type="text" name="reward" id="modal_reward" class="form-control border-0 shadow-sm rounded-3" style="padding:12px;" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-secondary">Rules Description</label>
                                <textarea name="description" id="modal_desc" class="form-control border-0 shadow-sm rounded-3" rows="5" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal" style="border-radius:10px;">Close</button>
                        <button type="submit" name="update_competition_btn" class="btn btn-primary px-4 py-2" style="border-radius:10px; background-color: #2563eb;">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.edit-comp-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('modal_comp_id').value = this.getAttribute('data-id');
                document.getElementById('modal_title').value = this.getAttribute('data-title');
                document.getElementById('modal_status').value = this.getAttribute('data-status');
                document.getElementById('modal_starting').value = this.getAttribute('data-starting');
                document.getElementById('modal_deadline').value = this.getAttribute('data-deadline');
                document.getElementById('modal_reward').value = this.getAttribute('data-reward');
                document.getElementById('modal_desc').value = this.getAttribute('data-desc');

                var editModal = new bootstrap.Modal(document.getElementById('editCompModal'));
                editModal.show();
            });
        });
    </script>
</body>
</html>