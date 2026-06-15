<?php
include 'auth.php'; // Database connection ($conn) iske andar hona chahiye

// Check karo agar user logged in nahi hai (session mein id ya username nahi hai)
if (!isset($_SESSION['user_id']) && !isset($_SESSION['username'])) {
    // User ko login page par phenk do aur exit kar jao
    header("Location: login.php"); 
    exit();
}

// 1. Search aur Filters Handling Logic
$where_clauses = [];

// Text Search Filter
if (isset($_GET['search_book']) && !empty(trim($_GET['search_book']))) {
    $search = mysqli_real_escape_string($conn, trim($_GET['search_book']));
    $where_clauses[] = "(title LIKE '%$search%' OR author LIKE '%$search%' OR description LIKE '%$search%')";
}

// Category Filter
if (isset($_GET['category']) && !empty(trim($_GET['category']))) {
    $category = mysqli_real_escape_string($conn, trim($_GET['category']));
    $where_clauses[] = "category = '$category'";
}

// Language Filter
if (isset($_GET['language']) && !empty(trim($_GET['language']))) {
    $language = mysqli_real_escape_string($conn, trim($_GET['language']));
    $where_clauses[] = "language = '$language'";
}

// Pricing Filter (Free vs Paid)
if (isset($_GET['price_type']) && !empty(trim($_GET['price_type']))) {
    $price_type = mysqli_real_escape_string($conn, trim($_GET['price_type']));
    if ($price_type === 'free') {
        $where_clauses[] = "price = 0";
    } elseif ($price_type === 'paid') {
        $where_clauses[] = "price > 0";
    }
}

// Base Query
$query = "SELECT * FROM books";

// Agar filters lagaye hain toh WHERE clause add karo
if (count($where_clauses) > 0) {
    $query .= " WHERE " . implode(' AND ', $where_clauses);
}

// New books sabse pehle dikhane ke liye
$query .= " ORDER BY id DESC";
$result = mysqli_query($conn, $query);

// Total books count for hero stat
$total_books_query = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM books");
$total_books_row = mysqli_fetch_assoc($total_books_query);
$total_books = $total_books_row['cnt'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore E-Books</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Shared Framework Elements */
        .btn-icon { position: relative; font-size: 1.2rem; color: var(--primary-dark); text-decoration: none; }
        .cart-badge { position: absolute; top: -4px; right: -5px; background-color: var(--retro-orange, #f26419); color: white; font-size: 0.7rem; padding: 2px 6px; border-radius: 50%; font-weight: 600; }
        .btn-account { display: flex; align-items: center; gap: 8px; text-decoration: none; color: var(--primary-dark); font-weight: 500; }

        /* --- Cool Unique Book Hero Section --- */
        .premium-hero.book-hero {
            background: linear-gradient(135deg, #ffffff 60%, #fff9e6 100%);
            padding: 50px;
            border-radius: 20px;
            margin-top: 20px;
            border: 3px solid var(--primary-dark, #000);
            box-shadow: 12px 12px 0px var(--primary-dark, #000);
            position: relative;
            overflow: hidden;
        }

        .book-hero-grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 40px;
            align-items: center;
        }

        .book-hero-text h1 {
            font-family: 'normal', sans-serif;
            font-size: 60px;
            font-weight: lighter;
            color: var(--primary-dark, #000);
            line-height: 1.1;
            margin-bottom: 15px;
        }

        .book-hero-text .gradient-text {
            font-family: 'italic', serif;
            font-weight: lighter;
            color: var(--retro-orange, #f26419);
            text-shadow: 2px 2px 0px #fff;
        }

        .book-hero-text .hero-subtitle {
            font-size: 15px;
            color: var(--text-muted, #656565);
            line-height: 1.6;
            margin-bottom: 30px;
            max-width: 650px;
        }

        /* Search Bar Config */
        .book-search-box {
            background: white;
            padding: 10px;
            border-radius: 12px;
            border: 3px solid var(--primary-dark, #000);
            box-shadow: 5px 5px 0px var(--primary-dark, #000);
            max-width: 600px;
        }

        .search-inner-wrap {
            display: flex;
            gap: 10px;
        }

        .search-input-wrapper {
            position: relative;
            flex: 1;
            display: flex;
            align-items: center;
        }

        .search-input-wrapper .input-icon {
            position: absolute;
            left: 15px;
            color: var(--primary-dark, #000);
            font-size: 16px;
        }

        .search-input-wrapper input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            font-size: 14px;
            font-weight: 600;
            color: var(--primary-dark, #000);
            border: 2px solid var(--primary-dark, #000);
            border-radius: 8px;
            outline: none;
            background-color: #fafafa;
        }

        .search-input-wrapper input:focus {
            background-color: var(--retro-yellow, #fff9e6);
        }

        .book-hero .search-submit-btn {
            cursor: pointer;
            border: 2px solid var(--primary-dark, #000);
            box-shadow: 3px 3px 0px var(--primary-dark, #000);
            background-color: var(--cta-orange, #f26419);
            color: white;
            padding: 0 24px;
            border-radius: 8px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .book-hero .search-submit-btn:hover {
            transform: translate(1px, 1px);
            box-shadow: 2px 2px 0px var(--primary-dark, #000);
        }

        /* --- Hero Illustration Side --- */
        .book-hero-visual {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            min-height: 320px;
        }

        .hero-blob {
            position: absolute;
            width: 280px;
            height: 280px;
            background: var(--retro-yellow, #fff3cf);
            border: 3px solid var(--primary-dark, #000);
            border-radius: 50% 50% 45% 55% / 55% 45% 55% 45%;
            z-index: 0;
        }

        .hero-stack-svg {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 320px;
            filter: drop-shadow(8px 10px 0px rgba(0,0,0,1));
        }

        .floating-badge {
            position: absolute;
            background: white;
            border: 2.5px solid var(--primary-dark, #000);
            border-radius: 12px;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 4px 4px 0px var(--primary-dark, #000);
            z-index: 3;
            animation: floatY 3.5s ease-in-out infinite;
        }

        .floating-badge.badge-top {
            top: 5px;
            right: 0px;
            color: var(--retro-orange, #f26419);
            animation-delay: 0s;
        }

        .floating-badge.badge-bottom {
            bottom: 15px;
            left: -10px;
            color: #2a9d8f;
            animation-delay: 1.2s;
        }

        .floating-badge i {
            font-size: 16px;
        }

        @keyframes floatY {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        @media (max-width: 992px) {
            .book-hero-grid {
                grid-template-columns: 1fr;
            }
            .book-hero-visual {
                min-height: 260px;
                margin-top: 20px;
            }
            .hero-blob {
                width: 220px;
                height: 220px;
            }
            .hero-stack-svg {
                max-width: 240px;
            }
        }

        /* --- Books Layout Grid Wrapper --- */
        .books-browse-container {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
            margin-top: 40px;
            align-items: start;
        }

        /* --- Side Filter Panel Design --- */
        .filter-sidebar {
            background: white;
            border: 3px solid var(--primary-dark, #000);
            border-radius: 16px;
            padding: 25px;
            box-shadow: 6px 6px 0px var(--primary-dark, #000);
            position: sticky;
            top: 20px;
        }

        .filter-sidebar h3 {
            font-size: 18px;
            font-weight: 800;
            color: var(--primary-dark, #000);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 2px dashed #e1e8ed;
            padding-bottom: 10px;
        }

        .filter-group {
            margin-bottom: 22px;
        }

        .filter-group h4 {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--primary-dark, #000);
            margin-bottom: 12px;
        }

        .custom-filter-control {
            display: block;
            position: relative;
            padding-left: 28px;
            margin-bottom: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: var(--primary-dark, #000);
            user-select: none;
        }

        .custom-filter-control input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .checkmark {
            position: absolute;
            top: 2px;
            left: 0;
            height: 18px;
            width: 18px;
            background-color: white;
            border: 2px solid var(--primary-dark, #000);
        }

        .custom-filter-control:hover input ~ .checkmark {
            background-color: var(--retro-yellow, #fff9e6);
        }

        .custom-filter-control input:checked ~ .checkmark {
            background-color: var(--retro-orange, #f26419);
        }

        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        .custom-filter-control input:checked ~ .checkmark:after {
            display: block;
        }

        .radio-control .checkmark {
            border-radius: 50%;
        }
        .radio-control .checkmark:after {
            left: 4px;
            top: 4px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: white;
            border: none;
        }

        .filter-action-btn {
            width: 100%;
            padding: 12px;
            border: 2px solid var(--primary-dark, #000);
            font-weight: 700;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.2s ease;
        }

        .btn-apply-filters {
            background-color: var(--primary-dark, #000);
            color: white;
            box-shadow: 3px 3px 0px var(--retro-orange, #f26419);
            margin-bottom: 10px;
        }

        .btn-clear-filters {
            background-color: #6c757d;
            color: white;
            box-shadow: 3px 3px 0px var(--primary-dark, #000);
        }
        
        .btn-clear-filters:hover {
            background-color: #5a6268;
        }

        /* --- Main Content Cards Area --- */
        .books-display-space {
            flex: 1;
        }

        .books-placeholder-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
        }

        /* PREMIUM BRUTALIST CARD WITH RATINGS & DETAILS BUTTON */
        .premium-book-card {
            background: white;
            border: 3px solid var(--primary-dark, #000);
            border-radius: 14px;
            padding: 15px;
            box-shadow: 5px 5px 0px var(--primary-dark, #000);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            transition: transform 0.2s ease;
        }

        .premium-book-card:hover {
            transform: translateY(-2px);
        }

        /* FIXED HEIGHT COVER AREA FOR EBOOK LOOK */
        .book-img-holder {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 12px;
            border: 2px solid var(--primary-dark, #000);
            overflow: hidden;
            background-color: #f8f9fa;
        }

        /* IMAGE FORCED TO FILL THE WHOLE CONTAINER */
        .book-img-holder img {
            width: 100%;
            height: 100%;
            object-fit: fill; /* Changed to 'fill' or 'cover' to ensure it takes 100% height and width without leaving borders */
        }

        .book-card-title {
            margin: 0 0 4px 0;
            font-size: 16px;
            font-weight: 800;
            color: var(--primary-dark, #000);
            line-height: 1.3;
        }

        .book-card-author {
            margin: 0 0 8px 0;
            font-size: 13px;
            color: #6c757d;
            font-weight: 500;
        }

        /* Rating Stars Style */
        .book-card-rating {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 12px;
            font-size: 12px;
        }

        .book-card-rating .stars-icon {
            color: #ffb703; /* Gold star color */
        }

        .book-card-rating .rating-num {
            font-weight: 700;
            color: var(--primary-dark, #000);
            background: #fff9e6;
            padding: 1px 6px;
            border: 1px solid var(--primary-dark, #000);
            border-radius: 4px;
        }

        .book-card-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            border-top: 2px dashed #e1e8ed;
            padding-top: 10px;
        }

        .book-price-badge {
            font-size: 14px;
            font-weight: 700;
            color: var(--retro-orange, #f26419);
        }

        .book-lang-tag {
            font-size: 11px;
            background: #eef2f5;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 700;
            border: 1px solid var(--primary-dark, #000);
        }

        /* Dual Action Buttons Group */
        .card-actions-group {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            flex: 1;
            padding: 10px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            text-align: center;
            border-radius: 6px;
            border: 2px solid var(--primary-dark, #000);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            transition: all 0.2s ease;
        }

        /* View Details Button */
        .btn-view-details {
            background: #fff;
            color: var(--primary-dark, #000);
            box-shadow: 2px 2px 0px var(--primary-dark, #000);
        }

        .btn-view-details:hover {
            background: var(--retro-yellow, #fff9e6);
            transform: translate(1px, 1px);
            box-shadow: 1px 1px 0px var(--primary-dark, #000);
        }

        /* Read PDF Button */
        .btn-read-pdf {
            background: var(--primary-dark, #000);
            color: white;
            box-shadow: 2px 2px 0px var(--retro-orange, #f26419);
        }

        .btn-read-pdf:hover {
            background: #222;
            transform: translate(1px, 1px);
            box-shadow: 1px 1px 0px var(--retro-orange, #f26419);
        }

        @media (max-width: 992px) {
            .books-browse-container { grid-template-columns: 1fr; }
            .book-hero-text h1 { font-size: 38px; }
            .filter-sidebar { position: relative; top: 0; }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="app-container">

        <section class="premium-hero book-hero">
            <div class="book-hero-grid">

                <div class="book-hero-text">
                    <div class="badge-container" style="margin-bottom: 15px;">
                        <span style="padding: 5px 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; border-radius: 20px; background:#e6e0f8; border: 2px solid var(--primary-dark, #000); box-shadow: 2px 2px 0px var(--primary-dark, #000); display: inline-block;">
                            <i class="fa-solid fa-book-bookmark me-1"></i> Digital Library
                        </span>
                    </div>
                    <h1>Read On The Go.<br><span class="gradient-text">Unchain Knowledge.</span></h1>
                    <p class="hero-subtitle">
                        Explore our full catalogue of high-octane indie comics, academic master-classes, and thrilling mystery novels. Filter instantly down to your vibe and start reading.
                    </p>

                    <div class="book-search-box">
                        <form action="" method="GET" class="search-inner-wrap">
                            <?php if(isset($_GET['category'])): ?><input type="hidden" name="category" value="<?php echo htmlspecialchars($_GET['category']); ?>"><?php endif; ?>
                            <?php if(isset($_GET['language'])): ?><input type="hidden" name="language" value="<?php echo htmlspecialchars($_GET['language']); ?>"><?php endif; ?>
                            <?php if(isset($_GET['price_type'])): ?><input type="hidden" name="price_type" value="<?php echo htmlspecialchars($_GET['price_type']); ?>"><?php endif; ?>

                            <div class="search-input-wrapper">
                                <i class="fa-solid fa-search input-icon"></i>
                                <input type="text" name="search_book" value="<?php echo isset($_GET['search_book']) ? htmlspecialchars($_GET['search_book']) : ''; ?>" placeholder="Search by title, author, keywords...">
                            </div>
                            <button type="submit" class="search-submit-btn">
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- ===== HERO VISUAL: SVG Book Stack Illustration ===== -->
                <div class="book-hero-visual">
                    <div class="hero-blob"></div>

                    <span class="floating-badge badge-top">
                        <i class="fa-solid fa-layer-group"></i> <?php echo number_format($total_books); ?>+ Titles
                    </span>

                    <svg class="hero-stack-svg" viewBox="0 0 320 320" xmlns="http://www.w3.org/2000/svg">
                        <!-- Bottom book (orange) -->
                        <g transform="translate(40,200)">
                            <rect x="0" y="0" width="220" height="40" rx="6" fill="#f26419" stroke="#000" stroke-width="3"/>
                            <rect x="0" y="0" width="220" height="10" rx="6" fill="#ff8a50"/>
                        </g>
                        <!-- Middle book (teal) -->
                        <g transform="translate(60,150) rotate(-3 110 25)">
                            <rect x="0" y="0" width="200" height="42" rx="6" fill="#2a9d8f" stroke="#000" stroke-width="3"/>
                            <rect x="0" y="0" width="200" height="10" rx="6" fill="#52c9bb"/>
                            <text x="20" y="28" font-family="sans-serif" font-size="14" font-weight="800" fill="#fff">FICTION</text>
                        </g>
                        <!-- Top book (yellow) -->
                        <g transform="translate(50,98) rotate(2 105 25)">
                            <rect x="0" y="0" width="210" height="46" rx="6" fill="#ffd166" stroke="#000" stroke-width="3"/>
                            <rect x="0" y="0" width="210" height="10" rx="6" fill="#ffe199"/>
                            <text x="20" y="32" font-family="sans-serif" font-size="15" font-weight="800" fill="#000">E-BOOK HUB</text>
                        </g>
                        <!-- Open book on top -->
                        <g transform="translate(75,30)">
                            <path d="M0 10 C 0 5, 5 0, 80 0 L 80 70 C 5 70, 0 65, 0 60 Z" fill="#ffffff" stroke="#000" stroke-width="3"/>
                            <path d="M160 10 C 160 5, 155 0, 80 0 L 80 70 C 155 70, 160 65, 160 60 Z" fill="#ffffff" stroke="#000" stroke-width="3"/>
                            <line x1="20" y1="18" x2="65" y2="18" stroke="#cbd5e0" stroke-width="3"/>
                            <line x1="20" y1="32" x2="65" y2="32" stroke="#cbd5e0" stroke-width="3"/>
                            <line x1="20" y1="46" x2="60" y2="46" stroke="#cbd5e0" stroke-width="3"/>
                            <line x1="95" y1="18" x2="140" y2="18" stroke="#cbd5e0" stroke-width="3"/>
                            <line x1="95" y1="32" x2="140" y2="32" stroke="#cbd5e0" stroke-width="3"/>
                            <line x1="95" y1="46" x2="135" y2="46" stroke="#cbd5e0" stroke-width="3"/>
                            <path d="M80 0 L80 70" stroke="#000" stroke-width="3"/>
                        </g>
                        <!-- Sparkle accents -->
                        <g stroke="#000" stroke-width="3" stroke-linecap="round">
                            <line x1="260" y1="50" x2="260" y2="70"/>
                            <line x1="250" y1="60" x2="270" y2="60"/>
                        </g>
                        <circle cx="35" cy="80" r="6" fill="#f26419" stroke="#000" stroke-width="2"/>
                    </svg>

                    <span class="floating-badge badge-bottom">
                        <i class="fa-solid fa-bolt"></i> Instant Filters
                    </span>
                </div>

            </div>
        </section>

        <div class="books-browse-container">
            
            <aside class="filter-sidebar">
                <h3><i class="fa-solid fa-sliders"></i> Filter Engine</h3>
                <form action="" method="GET">
                    <?php if(!empty($_GET['search_book'])): ?>
                        <input type="hidden" name="search_book" value="<?php echo htmlspecialchars($_GET['search_book']); ?>">
                    <?php endif; ?>

                    <div class="filter-group">
                        <h4>Categories</h4>
                        <label class="custom-filter-control">
                            <input type="radio" name="category" value="" <?php echo (!isset($_GET['category']) || $_GET['category'] == '') ? 'checked' : ''; ?>> All Genres
                            <span class="checkmark radio-control"></span>
                        </label>
                        <label class="custom-filter-control">
                            <input type="radio" name="category" value="Comics" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Comics') ? 'checked' : ''; ?>> Comics & Manga
                            <span class="checkmark radio-control"></span>
                        </label>
                        <label class="custom-filter-control">
                            <input type="radio" name="category" value="Novels" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Novels') ? 'checked' : ''; ?>> Novels & Fiction
                            <span class="checkmark radio-control"></span>
                        </label>
                        <label class="custom-filter-control">
                            <input type="radio" name="category" value="Academic" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Academic') ? 'checked' : ''; ?>> Academic & Guides
                            <span class="checkmark radio-control"></span>
                        </label>
                    </div>

                    <div class="filter-group">
                        <h4>Language</h4>
                        <label class="custom-filter-control">
                            <input type="radio" name="language" value="" <?php echo (!isset($_GET['language']) || $_GET['language'] == '') ? 'checked' : ''; ?>> Any Language
                            <span class="checkmark radio-control"></span>
                        </label>
                        <label class="custom-filter-control">
                            <input type="radio" name="language" value="English" <?php echo (isset($_GET['language']) && $_GET['language'] == 'English') ? 'checked' : ''; ?>> English
                            <span class="checkmark radio-control"></span>
                        </label>
                        <label class="custom-filter-control">
                            <input type="radio" name="language" value="Urdu" <?php echo (isset($_GET['language']) && $_GET['language'] == 'Urdu') ? 'checked' : ''; ?>> Urdu
                            <span class="checkmark radio-control"></span>
                        </label>
                    </div>

                    <div class="filter-group">
                        <h4>License Type</h4>
                        <label class="custom-filter-control">
                            <input type="radio" name="price_type" value="" <?php echo (!isset($_GET['price_type']) || $_GET['price_type'] == '') ? 'checked' : ''; ?>> All Access
                            <span class="checkmark radio-control"></span>
                        </label>
                        <label class="custom-filter-control">
                            <input type="radio" name="price_type" value="free" <?php echo (isset($_GET['price_type']) && $_GET['price_type'] == 'free') ? 'checked' : ''; ?>> Free E-Books
                            <span class="checkmark radio-control"></span>
                        </label>
                        <label class="custom-filter-control">
                            <input type="radio" name="price_type" value="paid" <?php echo (isset($_GET['price_type']) && $_GET['price_type'] == 'paid') ? 'checked' : ''; ?>> Premium Paid
                            <span class="checkmark radio-control"></span>
                        </label>
                    </div>

                    <button type="submit" class="filter-action-btn btn-apply-filters">
                        <i class="fa-solid fa-bolt"></i> Apply Filters
                    </button>
                    
                    <?php if(!empty($_GET['category']) || !empty($_GET['language']) || !empty($_GET['price_type']) || !empty($_GET['search_book'])): ?>
                        <a href="books.php" class="filter-action-btn btn-clear-filters">
                            <i class="fa-solid fa-arrow-rotate-left"></i> Clear All Filters
                        </a>
                    <?php endif; ?>
                </form>
            </aside>

            <main class="books-display-space">
                <div class="books-placeholder-grid">
                    <?php 
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $cover = (!empty($row['cover_image'])) ? $row['cover_image'] : 'default_cover.jpg';
                            $rating = (isset($row['rating']) && !empty($row['rating'])) ? $row['rating'] : '4.5';
                            ?>
                            
                            <div class="premium-book-card">
                                <div>
                                    <div class="book-img-holder">
                                        <img src="uploads/covers/<?php echo $cover; ?>" alt="Book Cover" onerror="this.src='https://placehold.co/250x300/f26419/ffffff?text=<?php echo urlencode($row['title']); ?>'">
                                    </div>
                                    <h4 class="book-card-title"><?php echo htmlspecialchars($row['title']); ?></h4>
                                    <p class="book-card-author">By <?php echo htmlspecialchars($row['author']); ?></p>
                                    
                                    <div class="book-card-rating">
                                        <span class="stars-icon">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star-half-stroke"></i>
                                        </span>
                                        <span class="rating-num"><?php echo $rating; ?></span>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="book-card-meta">
                                        <span class="book-price-badge">
                                            <?php echo ($row['price'] == 0) ? 'FREE' : 'Rs. ' . number_format($row['price'], 2); ?>
                                        </span>
                                        <span class="book-lang-tag">
                                            <i class="fa-solid fa-globe"></i> <?php echo htmlspecialchars($row['language']); ?>
                                        </span>
                                    </div>
                                    
                                    <!-- Action Buttons Layout Group -->
<div class="card-actions-group">
    <a href="book_detail.php?id=<?php echo $row['id']; ?>" class="action-btn btn-view-details">
        <i class="fa-solid fa-circle-info"></i> Details
    </a>
    
    <?php if ($row['price'] == 0): ?>
        <!-- Agar book FREE hai toh Download PDF button -->
        <a href="uploads/pdf/<?php echo htmlspecialchars($row['file_name']); ?>" download class="action-btn btn-read-pdf" style="background-color: #2a9d8f; box-shadow: 2px 2px 0px var(--primary-dark, #000);">
            <i class="fa-solid fa-download"></i> Download
        </a>
    <?php else: ?>
        <!-- Agar book PAID hai toh Buy Now button -->
        <a href="checkout.php?book_id=<?php echo $row['id']; ?>" class="action-btn btn-read-pdf" style="background-color: var(--retro-orange, #f26419);">
            <i class="fa-solid fa-cart-shopping"></i> Buy Now
        </a>
    <?php endif; ?>
</div>
                                </div>
                            </div>
                            
                            <?php
                        }
                    } else {
                        echo "
                        <div style='grid-column: 1/-1; text-align: center; padding: 60px 20px; background: #fff; border: 3px dashed var(--primary-dark,#000); border-radius: 16px;'>
                            <i class='fa-solid fa-book-open' style='font-size: 3rem; color: #6c757d; margin-bottom: 15px;'></i>
                            <h3 class='text-muted' style='font-weight: 800; color: #000;'>Right now there is no book according to your requirment.</h3>
                            <p style='color: #6c757d; margin-bottom: 20px;'>Try diffenent filters Or try again later</p>
                            <a href='books.php' class='filter-action-btn btn-apply-filters' style='max-width: 200px; margin: 0 auto;'>Reset Library</a>
                        </div>";
                    }
                    ?>
                </div>
            </main>

        </div>
    </div>
      <?php include 'footer.php'?>

</body>
</html>