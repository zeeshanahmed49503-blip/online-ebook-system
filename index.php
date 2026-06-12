<?php
    session_start();
    include 'auth.php';
    $comp_query = "SELECT * FROM competitions WHERE status = 'active' ORDER BY id ASC LIMIT 2";
$comp_result = mysqli_query($conn, $comp_query);
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
.fr{
    color: green;
    text-transform: uppercase;
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
                        <a href="#" class="btn-explore">Explore Now</a>
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
                <a href="#" class="view-all">All Categories <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="categories-grid">
                <div class="cat-card p1"><i class="fa-solid fa-wand-magic-sparkles"></i>
                    <h4>Novels</h4>
                </div>
                <div class="cat-card p2"><i class="fa-solid fa-heart"></i>
                    <h4>Comics</h4>
                </div>
                <div class="cat-card p3"><i class="fa-solid fa-mask"></i>
                    <h4>GK & Science</h4>
                </div>
                <div class="cat-card p4"><i class="fa-solid fa-brain"></i>
                    <h4>Story Books</h4>
                </div>
                <div class="cat-card p5"><i class="fa-solid fa-user-shield"></i>
                    <h4>Journals</h4>
                </div>
            </div>
        </section>

        <section class="trending-books">
            <div class="section-header">
                <h2>New Releases</h2>
                <div class="slider-arrows">
                    <a href="books.html" class="view-all">View All <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="books-grid">
                <div class="book-card">
                    <div class="book-cover-wrap"><img src="images/ex1.webp" alt=""></div>
                    <h4>The Order of Time</h4>
                    <p class="author">Carlo Rovelli</p>
                    <div class="rating-price-row">
                        <span class="book-price">$20.00</span>
                        <span class="book-rating"><i class="fa-solid fa-star"></i> 4.5</span>
                    </div>
                    <button class="btn-bag">View Details</button>
                </div>
                <div class="book-card">
                    <div class="book-cover-wrap"><img src="images/ex2.webp" alt=""></div>
                    <h4>Neverwhere</h4>
                    <p class="author">Neil Gaiman</p>
                    <div class="rating-price-row">
                        <span class="book-price fr">free</span>
                        <span class="book-rating"><i class="fa-solid fa-star"></i> 4.8</span>
                    </div>
                    <button class="btn-bag">View Details</button>
                </div>
                <div class="book-card">
                    <div class="book-cover-wrap"><img src="images/ex3.webp" alt=""></div>
                    <h4>Ikigai</h4>
                    <p class="author">Héctor García</p>
                    <div class="rating-price-row">
                        <span class="book-price">$26.00</span>
                        <span class="book-rating"><i class="fa-solid fa-star"></i> 4.7</span>
                    </div>
                    <button class="btn-bag">View Details</button>
                </div>
                <div class="book-card">
                    <div class="book-cover-wrap"><img src="images/ex4.webp" alt=""></div>
                    <h4>We Are Not Free</h4>
                    <p class="author">Traci Chee</p>
                    <div class="rating-price-row">
                        <span class="book-price">$26.00</span>
                        <span class="book-rating"><i class="fa-solid fa-star"></i> 4.9</span>
                    </div>
                    <button class="btn-bag">View Details</button>
                </div>
                <div class="book-card">
                    <div class="book-cover-wrap"><img src="images/ex5.webp" alt=""></div>
                    <h4>The Witch</h4>
                    <p class="author">Salem Author</p>
                    <div class="rating-price-row">
                        <span class="book-price">$33.00</span>
                        <span class="book-rating"><i class="fa-solid fa-star"></i> 4.1</span>
                    </div>
                    <button class="btn-bag">View Details</button>
                </div>
            </div>
        </section>
        
        <section class="trending-books">
            <div class="section-header">
                <h2>Highly Rated Books</h2>
                <div class="slider-arrows">
                    <a href="books.html" class="view-all">View All <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="books-grid">
                <div class="book-card">
                    <div class="book-cover-wrap"><img src="images/ex1.webp" alt=""></div>
                    <h4>The Order of Time</h4>
                    <p class="author">Carlo Rovelli</p>
                    <div class="rating-price-row">
                        <span class="book-price">$20.00</span>
                        <span class="book-rating"><i class="fa-solid fa-star"></i> 4.5</span>
                    </div>
                    <button class="btn-bag">View Details</button>
                </div>
                <div class="book-card">
                    <div class="book-cover-wrap"><img src="images/ex2.webp" alt=""></div>
                    <h4>Neverwhere</h4>
                    <p class="author">Neil Gaiman</p>
                    <div class="rating-price-row">
                        <span class="book-price">$25.00</span>
                        <span class="book-rating"><i class="fa-solid fa-star"></i> 4.8</span>
                    </div>
                    <button class="btn-bag">View Details</button>
                </div>
                <div class="book-card">
                    <div class="book-cover-wrap"><img src="images/ex3.webp" alt=""></div>
                    <h4>Ikigai</h4>
                    <p class="author">Héctor García</p>
                    <div class="rating-price-row">
                        <span class="book-price">$26.00</span>
                        <span class="book-rating"><i class="fa-solid fa-star"></i> 4.7</span>
                    </div>
                    <button class="btn-bag">View Details</button>
                </div>
                <div class="book-card">
                    <div class="book-cover-wrap"><img src="images/ex4.webp" alt=""></div>
                    <h4>We Are Not Free</h4>
                    <p class="author">Traci Chee</p>
                    <div class="rating-price-row">
                        <span class="book-price">$26.00</span>
                        <span class="book-rating"><i class="fa-solid fa-star"></i> 4.9</span>
                    </div>
                    <button class="btn-bag">View Details</button>
                </div>
                <div class="book-card">
                    <div class="book-cover-wrap"><img src="images/ex5.webp" alt=""></div>
                    <h4>The Witch</h4>
                    <p class="author">Salem Author</p>
                    <div class="rating-price-row">
                        <span class="book-price">$33.00</span>
                        <span class="book-rating"><i class="fa-solid fa-star"></i> 4.1</span>
                    </div>
                    <button class="btn-bag">View Details</button>
                </div>
            </div>
        </section>

         <section class="competition-section">
    <div class="section-container">
        <h2 class="section-title">Ongoing Competitions</h2>
        <p class="section-subtitle">Join our exciting writing contests!</p>
        
        <div class="comp-grid">
            <?php
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
                <p class="section-subtitle">Pre-register today and prep your drafts! Submit your custom creative story
                    documents online once the portal officially unlocks next week.</p>
                <div class="winners-grid">
                    <div class="winner-card card-tilt-left">
                        <div class="winner-img-container">
                            <img src="images/pr2.webp" alt="Current Competition Winner" class="winner-img">
                        </div>
                        <div class="winner-info">
                            <span class="winner-tag tag-current"><i class="fa-solid fa-crown"></i> Current Winner</span>
                            <h4>Ayan Ahmed</h4>
                            <p class="winner-achievement">1st Prize - Short Story Contest</p>
                            <div class="winner-reward-box">
                                <p class="winner-reward"><strong>Reward:</strong> Featured in Journal Vol. 12 + Cash
                                    Reward</p>
                            </div>
                        </div>
                    </div>

                    <div class="winner-card card-tilt-right">
                        <div class="winner-img-container">
                            <img src="images/pr1.webp" alt="Previous Competition Winner" class="winner-img">
                        </div>
                        <div class="winner-info">
                            <span class="winner-tag tag-previous"><i class="fa-solid fa-star"></i> Previous
                                Winner</span>
                            <h4>Sara Khan</h4>
                            <p class="winner-achievement">Gold Medal - 3hr Essay Writing</p>
                            <div class="winner-reward-box">
                                <p class="winner-reward"><strong>Reward:</strong> Famous Literature Book Set +
                                    Certificate</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="competition-section upcoming-section" style="padding-top: 0;">
    <div class="section-container">
        <h2 class="section-title">Upcoming Competitions</h2>
        <p class="section-subtitle">Prepare your drafts early, sharpen your skills, and get ready to compete with the finest writers for massive prizes and global recognition!</p>
        
        <div class="comp-grid">

            <div class="comp-card dynamic-tilt-left">
                <div class="comp-badge" style="background-color: #f77f00; color: #ffffff;">Starting on June 05, 2026</div>
                <div class="comp-content">
                    <h3>G.K &amp; Literature Mega Quiz</h3>
                    <p class="comp-desc">Test your rapid-fire skills! A fast-paced online quiz covering global literature, famous authors, and core grammar concepts. Accuracy and speed will decide the winner.</p>
                    
                    <div class="comp-meta">
                        <span><i class="fa-regular fa-calendar"></i> <strong>Launch Date:</strong> June 05, 2026</span>
                        <span><i class="fa-solid fa-trophy"></i> <strong>Prize:</strong> Premium E-Reader &amp; Certificate</span>
                    </div>

                    <a href="javascript:void(0)" class="comp-btn btn-secondary" style="cursor: not-allowed; background-color: #6c757d; color: #ffffff;">
                        <i class="fa-solid fa-lock"></i> Registration Opening Soon
                    </a>
                </div>
            </div>

            <div class="comp-card dynamic-tilt-right">
                <div class="comp-badge" style="background-color: #f77f00; color: #ffffff;">Starting on June 15, 2026</div>
                <div class="comp-content">
                    <h3>Novella Chapter Showcase</h3>
                    <p class="comp-desc">An elite platform for budding authors. Submit the opening chapter of your unpublished book/novella. Top entries get expert mentoring from premium publishers.</p>
                    
                    <div class="comp-meta">
                        <span><i class="fa-regular fa-calendar"></i> <strong>Launch Date:</strong> June 15, 2026</span>
                        <span><i class="fa-solid fa-award"></i> <strong>Prize:</strong> Official Contract &amp; Hard Copy Publication</span>
                    </div>

                    <a href="javascript:void(0)" class="comp-btn btn-secondary" style="cursor: not-allowed; background-color: #6c757d; color: #ffffff;">
                        <i class="fa-solid fa-lock"></i> Registration Opening Soon
                    </a>
                </div>
            </div>

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
                <p class="section-subtitle">Want to skip the shipping wait? Easily connect with our certified
                    neighborhood hubs to grab official hard copies or audio CDs instantly.</p>
                <br>

                <div class="dealers-grid">
                    <div class="dealer-card">
                        <div class="dealer-header">
                            <div class="dealer-icon"><i class="fa-solid fa-location-dot"></i></div>
                            <h4>Downtown Book Center</h4>
                        </div>
                        <div class="dealer-body">
                            <p class="dealer-address">Shop #45, Main Commercial Avenue, Block B, Saddar</p>
                            <div class="dealer-meta-wrapper">
                                <p class="dealer-contact"><i class="fa-solid fa-phone"></i> <span>+92-21-35551234</span>
                                </p>
                                <p class="dealer-timing"><i class="fa-regular fa-clock"></i> <span>11:00 AM - 09:00
                                        PM</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="dealer-card">
                        <div class="dealer-header">
                            <div class="dealer-icon"><i class="fa-solid fa-location-dot"></i></div>
                            <h4>Universal Publications</h4>
                        </div>
                        <div class="dealer-body">
                            <p class="dealer-address">Plot 12-C, Lane 4, Phase 5, D.H.A.</p>
                            <div class="dealer-meta-wrapper">
                                <p class="dealer-contact"><i class="fa-solid fa-phone"></i> <span>+92-21-34445678</span>
                                </p>
                                <p class="dealer-timing"><i class="fa-regular fa-clock"></i> <span>10:00 AM - 08:00
                                        PM</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="dealer-card">
                        <div class="dealer-header">
                            <div class="dealer-icon"><i class="fa-solid fa-location-dot"></i></div>
                            <h4>Apex Book Stall</h4>
                        </div>
                        <div class="dealer-body">
                            <p class="dealer-address">G-9, Civic Center, Gulshan-e-Iqbal</p>
                            <div class="dealer-meta-wrapper">
                                <p class="dealer-contact"><i class="fa-solid fa-phone"></i> <span>+92-21-36669012</span>
                                </p>
                                <p class="dealer-timing"><i class="fa-regular fa-clock"></i> <span>12:00 PM - 10:00
                                        PM</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
    <footer>
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-logo-side">
                    <div class="footer-brand">

                        <a href="#" class="brand-logo"><i class="fa-solid fa-book-open logo-icon"></i>Bookish.</a>

                    </div>
                    <p>Karachi's premier node for digital and physical hard-copy book tracking systems.</p>
                    <div class="social-icons">
                        <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" aria-label="Linkedin"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="footer-col">
                    <h4>Get in Touch</h4>
                    <ul>
                        <li><a href="#"><i class="fa-regular fa-envelope"></i> Contact Support</a></li>
                        <li><a href="#"><i class="fa-solid fa-map-location-dot"></i> Dealer Hubs</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Company</h4>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Community</h4>
                    <ul>
                        <li><a href="#">Competitions</a></li>
                        <li><a href="#">Forum</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms & Conditions</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2026 Bookish Systems. All rights reserved. | Crafted with passion for Aptech Terminal Project.
                </p>
            </div>
        </div>
    </footer>

</body>

</html>