<?php
    session_start();
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
    <header>
        <div class="nav-wrapper">
            <a href="index.php" class="brand-logo"><i class="fa-solid fa-book-open logo-icon"></i>Bookish.</a>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#">Books</a></li>
                    <li><a href="competitions.php">competitions</a></li>
                    <li><a href="#">dealers</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </nav>
  <div class="header-actions">
    <?php
    // Check karo kya session me user_id set hai (yaani user logged in hai)
    if (isset($_SESSION['name'])) {
    ?>
        <a href="cart.php" class="btn btn-icon" title="Cart">
            <i class="fa-solid fa-cart-shopping"></i>
            <span class="cart-badge">0</span>
        </a>

        <a href="account.php" class="btn btn-account" title="My Account">
            <i class="fa-solid fa-circle-user"></i> 
            <span><?php echo htmlspecialchars($_SESSION['name']); ?></span>
        </a>
    <?php
    } else {
    ?>
        <a href="login.php" class="btn btn-login">
            <i class="fa-solid fa-right-to-bracket"></i> Login
        </a>
        <a href="register.php" class="btn btn-register">
            <i class="fa-solid fa-user-plus"></i> Register
        </a>
    <?php
    }
    ?>
</div>
        </div>
    </header>
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
                    <h4>Fantasy</h4>
                </div>
                <div class="cat-card p2"><i class="fa-solid fa-heart"></i>
                    <h4>Romance</h4>
                </div>
                <div class="cat-card p3"><i class="fa-solid fa-mask"></i>
                    <h4>Mystery</h4>
                </div>
                <div class="cat-card p4"><i class="fa-solid fa-brain"></i>
                    <h4>Personal Growth</h4>
                </div>
                <div class="cat-card p5"><i class="fa-solid fa-user-shield"></i>
                    <h4>History</h4>
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
                <h2 class="section-title">Ongoing & Competition</h2>
                <p class="section-subtitle">Unleash your inner writer! Participate in our exciting writing contests to
                    showcase your creative skills, compete with the finest minds ...</p>
                <div class="comp-grid">
                    <div class="comp-card dynamic-tilt-left">
                        <div class="comp-badge status-active">Active Now</div>
                        <div class="comp-content">
                            <h3>Annual Essay Writing</h3>
                            <p class="comp-desc">Access the separate window topic, write, and submit your essay within a
                                strict 3-hour window. Show your skills in grammar, structure, and general knowledge!</p>
                            <div class="comp-meta">
                                <span><i class="fa-regular fa-clock"></i> <strong>Time Limit:</strong> 3 Hours</span>
                                <span><i class="fa-solid fa-trophy"></i> <strong>Prize:</strong> Famous G.K. & Grammar
                                    Book Bundle</span>
                            </div>
                            <a href="competitions.php" class="comp-btn btn-primary">Participate Now <i
                                    class="fa-solid fa-arrow-right-long"></i></a>
                        </div>
                    </div>

                    <div class="comp-card dynamic-tilt-right">
                        <div class="comp-badge status-upcoming">Commencing Soon</div>

                        <div class="comp-content">
                            <h3>Short Story Writing</h3>
                            <p class="comp-desc">Submit your best creative stories online in document format. The best
                                selected story will be officially published in our upcoming monthly journal!</p>
                            <div class="comp-meta">
                                <span><i class="fa-regular fa-calendar"></i> <strong>Commencing:</strong> Coming Next
                                    Week</span>
                                <span><i class="fa-solid fa-award"></i> <strong>Prize:</strong> Cash Prize & Journal
                                    Feature</span>
                            </div>
                            <a href="competitions.php" class="comp-btn btn-secondary">View Rules</a>
                        </div>
                    </div>
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


        <section class="quote-banner">
            <h3>"I do believe something very magical can happen <br> when you read a good book."</h3>
            <p>( J.K - Rowling )</p>
        </section>



        <section class="highlight-banners-grid">
            <div class="hl-card c1">
                <div class="hl-left">
                    <h3>New Release</h3><br><a href="#" class="hl-btn">Read Now</a>
                </div>
                <img src="images/ex3.webp" alt="">
            </div>
            <div class="hl-card c2">
                <div class="hl-left">
                    <h3>Sale on History Books</h3><br><a href="#" class="hl-btn">Shop Sale</a>
                </div>
                <img src="images/ex2.webp" alt="">
            </div>
            <div class="hl-card c3">
                <div class="hl-left">
                    <h3>Top Rated Hub</h3><br><a href="#" class="hl-btn">Explore</a>
                </div>
                <img src="images/ex4.webp" alt="">
            </div>
        </section>

        <section class="upcoming-block">
            <div class="up-left">
                <h2>Upcoming Book Alert</h2>
                <p>Get 35% off for fast pre-booking of any local Est, soluta provident explicabo iusto itaque nemo ipsam
                    odit eos ipsa accusantium? or global author releases coming next week.</p>
                <a href="#" class="btn-prime" style="width:fit-content;">Notify me <i class="fa-solid fa-bell"></i></a>
            </div>
            <div class="up-right-grid">
                <div class="grid-mini-cover"><img src="images/ex1.webp" alt=""></div>
                <div class="grid-mini-cover"><img src="images/ex3.webp" alt=""></div>
                <div class="grid-mini-cover"><img src="images/ex2.webp" alt=""></div>
                <div class="grid-mini-cover"><img src="images/ex4.webp" alt=""></div>
            </div>
        </section>

        <section class="testimonials">
            <div class="section-header">
                <h2>Customer Feedback</h2>
                <div class="slider-arrows">
                    <div class="arrow-btn"><i class="fa-solid fa-chevron-left"></i></div>
                    <div class="arrow-btn"><i class="fa-solid fa-chevron-right"></i></div>
                </div>
            </div>
            <div class="reviews-grid">
                <div class="review-card">
                    <p class="review-text">"Bookish is a breath of fresh air in today's digital media arena. The digital
                        asset catalog format options (PDF, Hard Copy, CD) makes it extremely easy to adapt according to
                        my mood."</p>
                    <div class="reviewer-profile">
                        <div class="rev-info">
                            <div class="rev-avatar"><img src="images/pr2.webp" alt=""></div>
                            <div class="rev-name">
                                <h4>Rylars Fixas</h4>
                                <p>Publishing Consultant</p>
                            </div>
                        </div>
                        <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i></div>
                    </div>
                </div>
                <div class="review-card">
                    <p class="review-text">"I am especially in love with the competitive execution layout module.
                        Authentic timers make the contest interface highly trustworthy and premium."</p>
                    <div class="reviewer-profile">
                        <div class="rev-info">
                            <div class="rev-avatar"><img src="images/pr3.webp" alt=""></div>
                            <div class="rev-name">
                                <h4>Tyntia Ravling</h4>
                                <p>Kids Father, Pakistan</p>
                            </div>
                        </div>
                        <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i></div>
                    </div>
                </div>
            </div>
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
        <section class="newsletter-section">
            <div class="nl-left">
                <h2>Subscribe to our Newsletter</h2>
                <p>Subscribe to our newsletter to receive early updates, <br> coupon discounts, and weekly logs.</p>
            </div>
            <form class="nl-form" onsubmit="event.preventDefault();">
                <input type="email" placeholder="Enter your email">
                <button type="submit">Subscribe</button>
            </form>
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