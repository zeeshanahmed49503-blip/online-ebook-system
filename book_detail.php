<?php
include 'auth.php'; // Database connection ($conn) aur security checks iske andar hain

// Check karo agar user logged in nahi hai (session mein id ya username nahi hai)
if (!isset($_SESSION['user_id']) && !isset($_SESSION['username'])) {
    // User ko login page par phenk do aur exit kar jao
    header("Location: login.php"); 
    exit();
}

// 1. URL se Book ID pakadna aur use validate karna
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $book_id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Database query sirf is ek book ka data uthane ke liye
    $query = "SELECT * FROM books WHERE id = $book_id";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $book = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Error: Book database me nahi mili!'); window.location.href='books.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid Request! ID missing hai.'); window.location.href='books.php';</script>";
    exit();
}

// 2. Images Fallback handling 
$cover = (!empty($book['cover_image'])) ? $book['cover_image'] : 'default_cover.jpg';
$rating = (isset($book['rating']) && !empty($book['rating'])) ? $book['rating'] : '4.5';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['title']); ?> - Details</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* --- Brutalist Specs Layout matching your theme --- */
        .details-wrapper {
            margin-top: 40px;
            margin-bottom: 60px;
        }

        .brutalist-detail-card {
            background: white;
            border: 4px solid var(--primary-dark, #000);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 12px 12px 0px var(--primary-dark, #000);
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 40px;
        }

        /* Cover Display engine */
        .detail-cover-container {
            width: 100%;
            border: 3px solid var(--primary-dark, #000);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 6px 6px 0px var(--primary-dark, #000);
            background: #f8f9fa;
            height: 460px;
        }

        .detail-cover-container img {
            width: 100%;
            height: 100%;
            object-fit: fill;
        }

        /* Content Meta Layouts */
        .book-headline {
            font-size: 42px;
            font-weight: 800;
            color: var(--primary-dark, #000);
            line-height: 1.2;
            margin-bottom: 5px;
        }

        .book-sub-author {
            font-size: 18px;
            color: #656565;
            margin-bottom: 25px;
            font-weight: 500;
        }

        .rating-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff9e6;
            border: 2px solid var(--primary-dark, #000);
            padding: 4px 12px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .rating-pill i { color: #ffb703; }

        /* Meta Matrix Grid */
        .specs-brutalist-matrix {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .matrix-cell {
            background: #fafafa;
            border: 2px solid var(--primary-dark, #000);
            padding: 12px;
            border-radius: 8px;
        }

        .cell-label {
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 700;
            color: #656565;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .cell-value {
            font-size: 15px;
            font-weight: 800;
            color: var(--primary-dark, #000);
        }

        .cell-value.price-accent {
            color: var(--retro-orange, #f26419);
        }

        /* Description container box */
        .synopsis-header {
            font-size: 16px;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 10px;
            color: var(--primary-dark, #000);
        }

        .synopsis-body {
            font-size: 15px;
            line-height: 1.7;
            color: #333;
            background: #fff9e6;
            padding: 20px;
            border-radius: 12px;
            border: 2px dashed var(--primary-dark, #000);
            margin-bottom: 35px;
        }

        /* Call To Action Buttons custom set */
        .action-flex-row {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .master-cta-btn {
            padding: 15px 35px;
            font-size: 16px;
            font-weight: 800;
            text-decoration: none;
            border-radius: 10px;
            border: 3px solid var(--primary-dark, #000);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-master-download {
            background-color: #2a9d8f;
            color: white;
            box-shadow: 4px 4px 0px var(--primary-dark, #000);
        }

        .btn-master-buy {
            background-color: var(--retro-orange, #f26419);
            color: white;
            box-shadow: 4px 4px 0px var(--primary-dark, #000);
        }

        .btn-master-back {
            background-color: white;
            color: var(--primary-dark, #000);
            box-shadow: 4px 4px 0px #6c757d;
        }

        .master-cta-btn:hover {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0px var(--primary-dark, #000);
        }
        
        .btn-master-back:hover {
            box-shadow: 2px 2px 0px #6c757d;
        }

        @media (max-width: 992px) {
            .brutalist-detail-card { grid-template-columns: 1fr; padding: 20px; }
            .detail-cover-container { height: 380px; max-width: 280px; margin: 0 auto; }
            .book-headline { font-size: 30px; text-align: center; }
            .book-sub-author { text-align: center; }
            .rating-center-wrap { text-align: center; }
            .action-flex-row { flex-direction: column; width: 100%; }
            .master-cta-btn { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    
    <?php include 'navbar.php'; ?>

    <div class="app-container details-wrapper" style=" margin-top: 50px">
        <div class="brutalist-detail-card">
            
            <div class="detail-cover-container">
                <img src="uploads/covers/<?php echo $cover; ?>" alt="Cover Illustration" onerror="this.src='https://placehold.co/320x460/f26419/ffffff?text=<?php echo urlencode($book['title']); ?>'">
            </div>

            <div>
                <h1 class="book-headline"><?php echo htmlspecialchars($book['title']); ?></h1>
                <p class="book-sub-author">By <?php echo htmlspecialchars($book['author']); ?></p>

                <div class="rating-center-wrap">
                    <div class="rating-pill">
                        <i class="fa-solid fa-star"></i> Public Score: <?php echo $rating; ?> / 5.0
                    </div>
                </div>

                <div class="specs-brutalist-matrix">
                    <div class="matrix-cell">
                        <div class="cell-label">Valuation Price</div>
                        <div class="cell-value price-accent">
                            <?php echo ($book['price'] == 0) ? 'FREE ACCESS' : 'Rs. ' . number_format($book['price'], 0); ?>
                        </div>
                    </div>
                    <div class="matrix-cell">
                        <div class="cell-label">Genre/Category</div>
                        <div class="cell-value"><?php echo htmlspecialchars($book['category']); ?></div>
                    </div>
                    <div class="matrix-cell">
                        <div class="cell-label">System Language</div>
                        <div class="cell-value"><?php echo htmlspecialchars($book['language']); ?></div>
                    </div>
                    <div class="matrix-cell">
                        <div class="cell-label">Document ID</div>
                        <div class="cell-value text-monospace">#EB-<?php echo $book['id']; ?></div>
                    </div>
                </div>

                <div class="synopsis-header"><i class="fa-solid fa-feather-pointed"></i> Synopsis / Book Summary</div>
                <div class="synopsis-body">
                    <?php echo nl2br(htmlspecialchars($book['description'])); ?>
                </div>

                <div class="action-flex-row">
                    <?php if ($book['price'] == 0): ?>
                        <a href="uploads/pdf/<?php echo htmlspecialchars($book['file_name']); ?>" download class="master-cta-btn btn-master-download">
                            <i class="fa-solid fa-cloud-arrow-down"></i> Download Free eBook (PDF)
                        </a>
                    <?php else: ?>
                        <a href="checkout.php?book_id=<?php echo $book['id']; ?>" class="master-cta-btn btn-master-buy">
                            <i class="fa-solid fa-wallet"></i> Purchase Premium Access
                        </a>
                    <?php endif; ?>

                    <a href="books.php" class="master-cta-btn btn-master-back">
                        <i class="fa-solid fa-arrow-left-long"></i> Return to Library
                    </a>
                </div>

            </div>

        </div>
    </div>
  <?php include 'footer.php'?>

</body>
</html>