<?php
include '../auth.php'; // Aapka database connection ($conn) isme hona chahiye

$msg = "";
$error = "";

// --- 1. HANDLE DELETE OPERATION ---
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    
    // Pehle purani image ka naam nikaal rahe hain taaki folder se delete kar sakein
    $img_query = "SELECT cover_image FROM books WHERE id = $delete_id";
    $img_res = mysqli_query($conn, $img_query);
    if ($img_row = mysqli_fetch_assoc($img_res)) {
        $old_image = $img_row['cover_image'];
        // FIX: Path ke shuru me ../ lagaya kyunki uploads folder admin ke baher hai
        if (!empty($old_image) && file_exists("../uploads/covers/" . $old_image)) {
            unlink("../uploads/covers/" . $old_image);
        }
    }

    $delete_query = "DELETE FROM books WHERE id = $delete_id";
    if (mysqli_query($conn, $delete_query)) {
        header("Location: viewBooks.php?msg=deleted");
        exit();
    } else {
        $error = "Delete failed: " . mysqli_error($conn);
    }
}

// --- 2. HANDLE UPDATE OPERATION ---
if (isset($_POST['update_book'])) {
    $book_id = intval($_POST['book_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = floatval($_POST['price']);
    $weight = floatval($_POST['weight']);

    // Default query bina image ke liye
    $update_query = "UPDATE books SET title='$title', author='$author', category='$category', price='$price', weight='$weight' WHERE id=$book_id";
    $image_status = "none"; 
    $error_msg = "";

    // Image Upload Handling (Agar admin nayi cover image select kare)
    if (!empty($_FILES['cover_image']['name'])) {
        
        // FIX: Path ke shuru me ../ lagaya
        if (!is_dir("../uploads/covers/")) {
            mkdir("../uploads/covers/", 0777, true);
        }

        $img_name = time() . '_' . basename($_FILES['cover_image']['name']);
        $target = "../uploads/covers/" . $img_name; // FIX: Admin folder se baher uploads me bhej rahe hain
        
        if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target)) {
            
            // Pehle purani image fetch karke usko folder se delete karenge
            $old_img_query = "SELECT cover_image FROM books WHERE id = $book_id";
            $old_img_res = mysqli_query($conn, $old_img_query);
            if ($old_img_row = mysqli_fetch_assoc($old_img_res)) {
                $old_image_name = $old_img_row['cover_image'];
                // FIX: Path ke shuru me ../ lagaya
                if (!empty($old_image_name) && file_exists("../uploads/covers/" . $old_image_name)) {
                    unlink("../uploads/covers/" . $old_image_name); 
                }
            }

            // Nayi image ke sath query update karein (Database me sirf naam jayega, path nahi)
            $update_query = "UPDATE books SET title='$title', author='$author', category='$category', price='$price', weight='$weight', cover_image='$img_name' WHERE id=$book_id";
            $image_status = "success";
            
        } else {
            $image_status = "failed";
            $error_msg = "Image move nahi ho saki. Path galat hai ya permissions nahi hain.";
        }
    }

    // Query execute karein
    if (mysqli_query($conn, $update_query)) {
        if ($image_status == "failed") {
            header("Location: viewBooks.php?msg=image_failed&err=" . urlencode($error_msg));
        } else {
            header("Location: viewBooks.php?msg=updated");
        }
        exit();
    } else {
        $error = "Update failed: " . mysqli_error($conn);
    }
}

// --- 3. FETCH ALL BOOKS FOR THE TABLE ---
$books_query = "SELECT * FROM books ORDER BY id DESC";
$books_result = mysqli_query($conn, $books_query);
$total_books = mysqli_num_rows($books_result);
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
            text-decoration: none;
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
        <div class="card border-0 shadow-sm p-4" style="border-radius: 12px; background: #fff;">
        
            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Deleted!</strong> Book has been removed successfully from system.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Book logs have been modified successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'image_failed'): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Data Updated, But Image Failed!</strong> Kitab ki details save ho gayi hain, magar cover image upload nahi ho saki. <br>
                    <small><strong>Wajah:</strong> <?php echo htmlspecialchars($_GET['err'] ?? ''); ?></small>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
                <div>
                    <h5 class="text-dark fw-bold m-0">
                        <i class="fa-solid fa-book-bookmark text-primary me-2"></i> Catalog & Publication Management
                    </h5>
                    <small class="text-muted">Review your published collection, modify prices, or remove items from active inventory.</small>
                </div>
                <span class="badge bg-primary px-3 py-2 rounded-pill" style="font-size: 13px;">
                    <i class="fa-solid fa-layer-group me-1"></i> Total Books: <?php echo $total_books; ?>
                </span>
            </div>

            <div class="mb-4" style="position: relative;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                <input type="text" id="searchBox" class="form-control" placeholder="Search books by title, author, or category..." style="padding-left: 38px; border-radius: 8px;">
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
                    <tbody id="booksTableBody">
                        <?php 
                        if ($total_books > 0) {
                            while ($row = mysqli_fetch_assoc($books_result)) {
                                // FIX: Yahan table me image show karne ke liye bhi admin folder se baher jana padega
                                $cover_src = (!empty($row['cover_image'])) ? '../uploads/covers/'.$row['cover_image'] : '';
                        ?>
                            <tr>
                                <td class="text-center">
                                    <?php if(!empty($cover_src) && file_exists($cover_src)): ?>
                                        <img src="<?php echo $cover_src; ?>" alt="Cover" class="rounded border" style="width: 50px; height: 65px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center border" style="width: 55px; height: 65px;">
                                            <i class="fa-solid fa-image text-muted fs-5"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <h6 class="fw-bold text-dark mb-1 book-title"><?php echo htmlspecialchars($row['title']); ?></h6>
                                    <span class="text-muted small" style="font-size: 12px;">
                                        <i class="fa-solid fa-pen-nib me-1"></i>Author: <span class="book-author"><?php echo htmlspecialchars($row['author']); ?></span>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary text-white text-uppercase px-2 py-1 book-category" style="font-size: 11px; font-weight: 600; letter-spacing: 0.5px;">
                                        <?php echo htmlspecialchars($row['category']); ?>
                                    </span>
                                </td>
                                <td class="fw-bold text-success">
                                    <?php echo ($row['price'] == 0) ? 'FREE' : 'Rs. ' . number_format($row['price'], 2); ?>
                                </td>
                                <td class="text-secondary small fw-semibold"><?php echo $row['weight']; ?> kg</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-sm btn-warning fw-bold text-dark px-2 edit-btn" 
                                                data-id="<?php echo $row['id']; ?>"
                                                data-title="<?php echo htmlspecialchars($row['title']); ?>"
                                                data-author="<?php echo htmlspecialchars($row['author']); ?>"
                                                data-category="<?php echo htmlspecialchars($row['category']); ?>"
                                                data-price="<?php echo $row['price']; ?>"
                                                data-weight="<?php echo $row['weight']; ?>"
                                                style="font-size: 13px;">
                                            <i class="fa-solid fa-pen me-1"></i> Edit
                                        </button>
                                        
                                        <a href="viewBooks.php?delete_id=<?php echo $row['id']; ?>" 
                                           class="btn btn-sm btn-danger fw-bold px-2" 
                                           onclick="return confirm('Kya aap sach me is book ko delete karna chahte hain?');"
                                           style="font-size: 13px; text-decoration:none;">
                                            <i class="fa-solid fa-trash me-1"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-4 text-muted'>Koi books nahi mili database me.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div class="modal fade" id="editBookModal" tabindex="-1" aria-labelledby="editBookModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="viewBooks.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title fw-bold" id="editBookModalLabel"><i class="fa-solid fa-edit text-warning me-2"></i>Update Book Log</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="book_id" id="modal_book_id">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Book Title</label>
                            <input type="text" name="title" id="modal_title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Author Name</label>
                            <input type="text" name="author" id="modal_author" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="category" id="modal_category" class="form-select" required>
                                <option value="Novels">Novels</option>
                                <option value="Comics">Comics</option>
                                <option value="Journals">Journals</option>
                                <option value="GK & Science">GK & Science</option>
                                <option value="Story Books">Story Books</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Price (Rs.)</label>
                                <input type="number" step="0.01" name="price" id="modal_price" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Weight (kg)</label>
                                <input type="number" step="0.01" name="weight" id="modal_weight" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-semibold">Change Cover Image <small class="text-muted">(Optional)</small></label>
                            <input type="file" name="cover_image" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary fw-semibold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_book" class="btn btn-primary fw-semibold">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // JS TO INTERCEPT EDIT BUTTON CLICK AND PRE-FILL VALUES IN FORM
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('modal_book_id').value = this.getAttribute('data-id');
                document.getElementById('modal_title').value = this.getAttribute('data-title');
                document.getElementById('modal_author').value = this.getAttribute('data-author');
                document.getElementById('modal_category').value = this.getAttribute('data-category');
                document.getElementById('modal_price').value = this.getAttribute('data-price');
                document.getElementById('modal_weight').value = this.getAttribute('data-weight');
                
                var myModal = new bootstrap.Modal(document.getElementById('editBookModal'));
                myModal.show();
            });
        });

        // LIVE INSTANT JAVASCRIPT SEARCH FILTER
        document.getElementById('searchBox').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#booksTableBody tr');
            
            rows.forEach(row => {
                let title = row.querySelector('.book-title') ? row.querySelector('.book-title').innerText.toLowerCase() : '';
                let author = row.querySelector('.book-author') ? row.querySelector('.book-author').innerText.toLowerCase() : '';
                let category = row.querySelector('.book-category') ? row.querySelector('.book-category').innerText.toLowerCase() : '';
                
                if(title.includes(filter) || author.includes(filter) || category.includes(filter)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    </script>
</body>
</html>