<?php
include 'auth.php'; // Database connection ($conn) iske andar hona chahiye

// 1. Competitions Data Query
$comp_query = "SELECT * FROM competitions WHERE status = 'active' ORDER BY id ASC LIMIT 2";
$comp_result = mysqli_query($conn, $comp_query);

// 2. New Releases Books Query (Sabse latest 5 books uthane ke liye)
$new_books_query = "SELECT * FROM books ORDER BY id DESC LIMIT 5";
$new_books_result = mysqli_query($conn, $new_books_query);

// 3. Highly Rated Books Query (Aapke schema ke mutabiq dynamic order)
$rated_books_query = "SELECT * FROM books ORDER BY id ASC LIMIT 5";
$rated_books_result = mysqli_query($conn, $rated_books_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookish - Online E-Book & Portal</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .fr {
            color: #2a9d8f !important;
            text-transform: uppercase;
            font-weight: 800;
        }

        .btn-icon {
            position: relative;
            font-size: 1.2rem;
            color: var(--primary-dark);
            text-decoration: none;
        }

        /* Cart badge setting */
        .cart-badge {
            position: absolute;
            top: -4px;
            right: -5px;
            background-color: var(--retro-orange, #f26419);
            color: white;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 50%;
            font-weight: 600;
        }

        .btn-account {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: var(--primary-dark);
            font-weight: 500;
        }

        .btn-logout {
            color: #e63946; /* Red color for logout */
            font-size: 1.1rem;
            transition: transform 0.2s ease;
        }

        .btn-logout:hover {
            transform: scale(1.1);
        }

        /* Brutalist Style Adjustment to match storefront exactly */
        .book-cover-wrap {
            width: 100%;
            height: 350px; 
            border-radius: 8px;
            margin-bottom: 12px;
            border: 2px solid var(--primary-dark, #000);
            overflow: hidden;
            background-color: #f8f9fa;
        }
        .book-cover-wrap img {
            width: 100%;
            height: 100%;
            object-fit: fill;
        }
        
        .btn-bag {
            width: 100%;
            padding: 10px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            text-align: center;
            border-radius: 6px;
            border: 2px solid var(--primary-dark, #000);
            background: #fff;
            color: var(--primary-dark, #000);
            box-shadow: 2px 2px 0px var(--primary-dark, #000);
            display: inline-block;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .btn-bag:hover {
            background: var(--retro-yellow, #fff9e6);
            transform: translate(1px, 1px);
            box-shadow: 1px 1px 0px var(--primary-dark, #000);
        }
    </style>
</head>

<body>
<?php include 'navbar.php'; ?>
    <div class="app-container">
        <section class="retro-hero">
            <div class="hero-content-wrapper">
                <div class="hero-text-side">
                    <span class="hero-tagline">BOOK CLUB</span>
                    <h1 class="hero-main-title">What Book Are You <span>Looking For?</span></h1>
                    <p class="hero-subtext">Next Book What To Read Next? Explore Our Catalog Of Classic Diamond Books
                        With Our Editors.</p>

                    <div class="hero-action-row">
                        <a href="books.php" class="btn-explore">Explore Now</a>
                        <button class="btn-square-dots"><i class="fa-solid fa-ellipsis"></i></button>
                    </div>

                    <div class="hero-community-widget">
                        <div class="avatar-stack">
                            <img src="images/banners/landing.webp" alt="User 1" class="mini-avatar-img">
                            <img src="images/banners/landing.webp" alt="User 2" class="mini-avatar-img">
                            <img src="images/banners/landing.webp" alt="User 3" class="mini-avatar-img">
                        </div>
                        <p>Join Book Lovers Series!</p>
                    </div>
                </div>

                <div class="hero-graphics-side">
                    <img src="images/banners/landing.webp" alt="">
                </div>
            </div>

            <div class="retro-color-steps-container">
                <div class="step-strip step-yellow"></div>
                <div class="step-strip step-orange"></div>
                <div class="step-strip step-red"></div>
                <div class="step-strip step-purple"></div>
            </div>
        </section>

        <section class="featured-categories">
            <div class="section-header">
                <h2>Featured Categories</h2>
                <a href="books.php" class="view-all">All Categories <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="categories-grid">
                <div class="cat-card p1" onclick="window.location.href='books.php?category=Novels'" style="cursor:pointer;"><i class="fa-solid fa-wand-magic-sparkles"></i>
                    <h4>Novels</h4>
                </div>
                <div class="cat-card p2" onclick="window.location.href='books.php?category=Comics'" style="cursor:pointer;"><i class="fa-solid fa-heart"></i>
                    <h4>Comics</h4>
                </div>
                <div class="cat-card p3" onclick="window.location.href='books.php?category=Academic'" style="cursor:pointer;"><i class="fa-solid fa-mask"></i>
                    <h4>GK & Science</h4>
                </div>
                <div class="cat-card p4" onclick="window.location.href='books.php'" style="cursor:pointer;"><i class="fa-solid fa-brain"></i>
                    <h4>Story Books</h4>
                </div>
                <div class="cat-card p5" onclick="window.location.href='books.php'" style="cursor:pointer;"><i class="fa-solid fa-user-shield"></i>
                    <h4>Journals</h4>
                </div>
            </div>
        </section>

        <section class="trending-books">
            <div class="section-header">
                <h2>New Releases</h2>
                <div class="slider-arrows">
                    <a href="books.php" class="view-all">View All <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="books-grid">
                <?php 
                if (mysqli_num_rows($new_books_result) > 0) {
                    while ($row = mysqli_fetch_assoc($new_books_result)) {
                        $cover = (!empty($row['cover_image'])) ? $row['cover_image'] : 'default_cover.jpg';
                        // Dynamic rating setup placeholder 
                        $rating = '4.6';
                ?>
                        <div class="book-card">
                            <div class="book-cover-wrap">
                                <img src="uploads/covers/<?php echo $cover; ?>" alt="Book Cover" onerror="this.src='https://placehold.co/250x300/f26419/ffffff?text=<?php echo urlencode($row['title']); ?>'">
                            </div>
                            <h4 style="font-size: 16px; font-weight:800; margin-bottom:4px;"><?php echo htmlspecialchars($row['title']); ?></h4>
                            <p class="author" style="margin-bottom:8px;">By <?php echo htmlspecialchars($row['author']); ?></p>
                            <div class="rating-price-row" style="margin-bottom: 12px;">
                                <span class="book-price <?php echo ($row['price'] == 0) ? 'fr' : ''; ?>">
                                    <?php echo ($row['price'] == 0) ? 'FREE' : 'Rs. ' . number_format($row['price'], 0); ?>
                                </span>
                                <span class="book-rating"><i class="fa-solid fa-star" style="color:#ffb703;"></i> <? openings = $rating; echo $rating; ?></span>
                            </div>
                            <a href="book_detail.php?id=<?php echo $row['id']; ?>" class="btn-bag">View Details</a>
                        </div>
                <?php 
                    }
                } else {
                    echo "<p class='text-muted'>No books uploaded yet.</p>";
                }
                ?>
            </div>
        </section>
        
        <section class="trending-books">
            <div class="section-header">
                <h2>Highly Rated Books</h2>
                <div class="slider-arrows">
                    <a href="books.php" class="view-all">View All <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="books-grid">
                <?php 
                if (mysqli_num_rows($rated_books_result) > 0) {
                    while ($row = mysqli_fetch_assoc($rated_books_result)) {
                        $cover = (!empty($row['cover_image'])) ? $row['cover_image'] : 'default_cover.jpg';
                        $rating = '4.8';
                ?>
                        <div class="book-card">
                            <div class="book-cover-wrap">
                                <img src="uploads/covers/<?php echo $cover; ?>" alt="Book Cover" onerror="this.src='https://placehold.co/250x300/f26419/ffffff?text=<?php echo urlencode($row['title']); ?>'">
                            </div>
                            <h4 style="font-size: 16px; font-weight:800; margin-bottom:4px;"><?php echo htmlspecialchars($row['title']); ?></h4>
                            <p class="author" style="margin-bottom:8px;">By <?php echo htmlspecialchars($row['author']); ?></p>
                            <div class="rating-price-row" style="margin-bottom: 12px;">
                                <span class="book-price <?php echo ($row['price'] == 0) ? 'fr' : ''; ?>">
                                    <?php echo ($row['price'] == 0) ? 'FREE' : 'Rs. ' . number_format($row['price'], 0); ?>
                                </span>
                                <span class="book-rating"><i class="fa-solid fa-star" style="color:#ffb703;"></i> <?php echo $rating; ?></span>
                            </div>
                            <a href="book_detail.php?id=<?php echo $row['id']; ?>" class="btn-bag">View Details</a>
                        </div>
                <?php 
                    }
                } else {
                    echo "<p class='text-muted'>No books uploaded yet.</p>";
                }
                ?>
            </div>
        </section>

         <section class="competition-section">
    <div class="section-container">
        <h2 class="section-title">Ongoing Competitions</h2>
        <p class="section-subtitle">Join our exciting writing contests!</p>
        
        <div class="comp-grid">
            <?php
            // Re-running because the variable was overridden below top container
            $comp_query = "SELECT * FROM competitions WHERE status = 'active' ORDER BY id ASC LIMIT 2";
            $comp_result = mysqli_query($conn, $comp_query);
            
            while ($comp_row = mysqli_fetch_assoc($comp_result)) {
            ?>
                <div class="comp-card">
                    <div class="comp-badge status-active"><?php echo $comp_row['status']; ?></div>
                    <div class="comp-content">
                        <h3><?php echo $comp_row['title']; ?></h3>
                        <p class="comp-desc"><?php echo $comp_row['description']; ?></p>
                        
                        <div class="comp-meta">
                            <span><i class="fa-solid fa-clock"></i> <strong>Deadline:</strong> <?php echo $comp_row['deadline']; ?></span>
                            <span><i class="fa-solid fa-trophy"></i> <strong>Prize:</strong> <?php echo $comp_row['reward']; ?></span>
                        </div>
                        
                        <a href="competitions.php" class="comp-btn btn-primary">
                            View Competition <i class="fa-solid fa-arrow-right-long"></i>
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>


        <section class="winners-section">
            <div class="section-container">
                <h2 class="section-title">Our Proud Winners</h2>
                <p class="section-subtitle">Pre-register today and prep your drafts! Submit your custom creative story documents online once the portal officially unlocks next week.</p>
               <div class="winners-grid">
<?php
$winners_query = "SELECT s.*, c.title as comp_title 
                   FROM submissions s 
                   LEFT JOIN competitions c ON s.competition_id = c.id 
                   WHERE s.status IN ('winner', 'runner_up') 
                   ORDER BY s.status ASC, s.id DESC
                   LIMIT 2";
$winners_result = mysqli_query($conn, $winners_query);

while ($win = mysqli_fetch_assoc($winners_result)) {
    $tilt_class = ($win['status'] == 'winner') ? 'card-tilt-left' : 'card-tilt-right';
    $tag_class = ($win['status'] == 'winner') ? 'tag-current' : 'tag-previous';
    $tag_icon = ($win['status'] == 'winner') ? 'fa-crown' : 'fa-star';
    $tag_text = ($win['status'] == 'winner') ? 'Current Winner' : 'Previous Winner';
    $comp_title = $win['comp_title'] ?? $win['title'];
?>
    <div class="winner-card <?php echo $tilt_class; ?>">
        <div class="winner-img-container">
            <img src="images/pr22.webp" alt="Competition Winner" class="winner-img">
        </div>
        <div class="winner-info">
            <span class="winner-tag <?php echo $tag_class; ?>"><i class="fa-solid <?php echo $tag_icon; ?>"></i> <?php echo $tag_text; ?></span>
            <h4><?php echo $win['user_name']; ?></h4>
            <p class="winner-achievement"><?php echo $comp_title; ?></p>
            <div class="winner-reward-box">
                <p class="winner-reward"><strong>Reward:</strong> <?php echo $win['prize'] ?? 'TBA'; ?></p>
            </div>
        </div>
    </div>
<?php } ?>
</div>
            </div>
        </section>

           
        <section class="competition-section upcoming-section" style="padding-top: 0;">
    <div class="section-container">
        <h2 class="section-title">Upcoming Competitions</h2>
        <p class="section-subtitle">Prepare your drafts early, sharpen your skills, and get ready to compete with the finest writers for massive prizes and global recognition!</p>
        
        <div class="comp-grid">
            <?php
            // 1. Yahan 'launch_date' ki jagah 'starting_date' kar diya hai order karne ke liye
            $comp_query = "SELECT * FROM competitions WHERE status = 'upcoming' ORDER BY starting_at ASC";
            $comp_result = mysqli_query($conn, $comp_query);

            if (mysqli_num_rows($comp_result) > 0) {
                $counter = 0;
                while ($comp = mysqli_fetch_assoc($comp_result)) {
                    
                    // Alternating tilt classes logic
                    $tilt_class = ($counter % 2 == 0) ? 'dynamic-tilt-left' : 'dynamic-tilt-right';
                    
                    // 2. Yahan bhi '$comp['starting_date']' fetch kiya hai aapke database column ke hisab se
                    $formatted_date = date("F d, Y", strtotime($comp['starting_at']));
                    
                    // Icon Logic
                    $prize_icon = (isset($comp['prize_type']) && $comp['prize_type'] == 'award') ? 'fa-award' : 'fa-trophy';
                    
                    $counter++;
                    ?>
                    <div class="comp-card <?php echo $tilt_class; ?>">
                        <div class="comp-badge" style="background-color: #f77f00; color: #ffffff;">Starting on <?php echo $formatted_date; ?></div>
                        
                        <div class="comp-content">
                            <h3><?php echo htmlspecialchars($comp['title']); ?></h3>
                            <p class="comp-desc"><?php echo htmlspecialchars($comp['description']); ?></p>
                            
                            <div class="comp-meta">
                                <span><i class="fa-regular fa-calendar"></i> <strong>Launch Date:</strong> <?php echo $formatted_date; ?></span>
                                <span><i class="fa-solid <?php echo $prize_icon; ?>"></i> <strong>Prize:</strong> <?php echo htmlspecialchars($comp['reward']); ?></span>
                            </div>

                            <a href="javascript:void(0)" class="comp-btn btn-secondary" style="cursor: not-allowed; background-color: #6c757d; color: #ffffff;">
                                <i class="fa-solid fa-lock"></i> Registration Opening Soon
                            </a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<div style="grid-column: 1/-1; text-align: center; padding: 40px; background: #ffffff; border-radius: 16px; border: 1px dashed rgba(247, 127, 0, 0.3);">
                        <i class="fa-solid fa-hourglass-start fa-2x" style="color: #f77f00; margin-bottom: 12px; display:block;"></i>
                        <h4 style="font-weight: 600; color: #222;">Stay Tuned!</h4>
                        <p style="color: #6c757d; font-size: 0.9rem; mt-1">We are cooking up some massive challenges for you. Check back soon!</p>
                      </div>';
            }
            ?>
        </div>
    </div>
</section>

       
<section class="quote-banner">
            <h3>"I do believe something very magical can happen <br> when you read a good book."</h3>
            <p>( J.K - Rowling )</p>
        </section>

      <section class="dealers-section">
    <div class="section-container">
        <h2 class="section-title">Find a <span>Dealer</span> Near You</h2>
        <p class="section-subtitle">Want to skip the shipping wait? Easily connect with our certified neighborhood hubs to grab official hard copies or audio CDs instantly.</p>
        <br>

        <div class="dealers-grid">
            <?php
            // Database se dealers ka data nikalne ki query 
            // Humne 'LIMIT 3' lagaya hai taake sirf top 3 dealers hi screen par dikhein
            $dealer_query = "SELECT * FROM dealers ORDER BY id DESC LIMIT 3";
            $dealer_result = mysqli_query($conn, $dealer_query);

            if (mysqli_num_rows($dealer_result) > 0) {
                while ($dealer = mysqli_fetch_assoc($dealer_result)) {
                    ?>
                    <div class="dealer-card">
                        <div class="dealer-header">
                            <div class="dealer-icon"><i class="fa-solid fa-location-dot"></i></div>
                            <h4><?php echo htmlspecialchars($dealer['name']); ?></h4>
                        </div>
                        <div class="dealer-body">
                            <p class="dealer-address"><?php echo htmlspecialchars($dealer['address']); ?></p>
                            <div class="dealer-meta-wrapper">
                                <p class="dealer-contact">
                                    <i class="fa-solid fa-phone"></i> <span><?php echo htmlspecialchars($dealer['phone']); ?></span>
                                </p>
                                <p class="dealer-timing">
                                    <i class="fa-regular fa-clock"></i> <span><?php echo htmlspecialchars($dealer['timing']); ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                // Agar database khali ho toh layout kharab na ho balki ek pyara sa message aaye
                echo '<div style="grid-column: 1/-1; text-align: center; padding: 40px; background: #ffffff; border-radius: 12px; border: 1px dashed #ddd; color: #6c757d;">
                        <i class="fa-solid fa-store-slash fa-2x" style="margin-bottom: 10px; display:block; color: #ccc;"></i>
                        <p style="font-weight: 500;">No certified dealers found near you at the moment.</p>
                      </div>';
            }
            ?>
        </div>
    </div>
</section>

    </div>
  <?php include 'footer.php'?>
</body>
</html>