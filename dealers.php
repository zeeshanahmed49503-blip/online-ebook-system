<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Dealers</title>
     <link rel="stylesheet" href="css/style.css">
     <style>
        
        .btn-icon {
            position: relative;
            font-size: 1.2rem;
            color: var(--primary-dark);
            text-decoration: none;
        }

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
        /* --- Premium Dealer Hero Core Layout --- */
.premium-hero.dealer-hero {
    background-color: white;
    padding: 40px 20px;
    border-radius: 20px;
    margin-top: 20px;
    position: relative;
}

.dealer-hero .hero-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 40px;
    align-items: center;
}

.dealer-hero .hero-wrapper {
    display: flex;
    flex-direction: column;
}

/* Badge Styling */
.dealer-hero .badge-container {
    margin-bottom: 20px;
}

.dealer-hero .hero-badge {
    padding: 6px 16px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    border-radius: 30px;
    color: var(--bg-card);
    background-color: var(--retro-purple); /* Standout contrast for dealers */
    border: 2px solid var(--primary-dark);
    box-shadow: 3px 3px 0px var(--primary-dark);
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

/* Typography matching your exact hierarchy */
.dealer-hero h1 {
    font-family: 'normal', sans-serif;
    font-size: 65px;
    font-weight: lighter;
    color: var(--primary-dark);
    line-height: 1.2;
    margin-bottom: 15px;
}

.dealer-hero .gradient-text {
    font-family: 'italic', serif;
    font-weight: lighter;
    color: var(--retro-orange);
}

.dealer-hero .hero-subtitle {
    font-size: 15px;
    color: var(--text-muted);
    line-height: 1.6;
    margin-bottom: 35px;
    max-width: 700px;
}


/* --- Neo-Brutalist Comic Search Bar Engine --- */
.dealer-search-box {
    background: var(--bg-card, #f9f9f9);
    padding: 15px;
    border-radius: 14px;
    border: 3px solid var(--primary-dark);
    box-shadow: 8px 8px 0px var(--primary-dark);
    max-width: 100%;
}

.search-form-wrap {
    display: flex;
    gap: 15px;
    align-items: center;
    flex-wrap: wrap;
}

.input-group-custom {
    position: relative;
    flex: 1;
    min-width: 200px;
    display: flex;
    align-items: center;
}

.input-group-custom .search-icon {
    position: absolute;
    left: 15px;
    color: var(--primary-dark);
    font-size: 16px;
    pointer-events: none;
}

.input-group-custom input,
.input-group-custom select {
    width: 100%;
    padding: 14px 14px 14px 45px;
    font-size: 14px;
    font-weight: 600;
    color: var(--primary-dark);
    background-color: white;
    border: 2px solid var(--primary-dark);
    border-radius: 8px;
    outline: none;
    transition: all 0.2s ease;
}

.input-group-custom input:focus,
.input-group-custom select:focus {
    background-color: var(--retro-yellow, #fff9e6);
    box-shadow: 0 0 0 1px var(--primary-dark);
}

/* Custom dropdown arrow tweak */
.select-custom select {
    appearance: none;
    cursor: pointer;
}

/* Reusing your signature button setup */
.dealer-hero .search-btn {
    cursor: pointer;
    white-space: nowrap;
    border: 2px solid var(--primary-dark);
    box-shadow: 4px 4px 0px var(--primary-dark);
    background-color: var(--cta-orange);
    color: white;
    padding: 14px 28px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.2s ease;
}

.dealer-hero .search-btn:hover {
    background-color: var(--retro-orange);
    transform: translate(2px, 2px);
    box-shadow: 2px 2px 0px var(--primary-dark);
}

/* --- Responsive Layout Breaks --- */
@media (max-width: 992px) {
    .dealer-hero .hero-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    .dealer-hero .hero-img-wrap {
        order: -1; /* Image moves to top on mobile layouts */
        max-width: 350px;
        margin: 0 auto;
    }
    .dealer-hero h1 {
        font-size: 32px;
    }
    .search-form-wrap {
        flex-direction: column;
        align-items: stretch;
    }
    .input-group-custom {
        width: 100%;
    }
    .dealer-hero .search-btn {
        justify-content: center;
    }
}

     </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="app-container">

    
    <section class="premium-hero dealer-hero">
    <div class="hero-grid">
        <div class="hero-wrapper">
            <div class="badge-container">
                <span class="hero-badge">
                    <i class="fa-solid fa-map-location-dot"></i> Official Network
                </span>
            </div>
            <h1>Skip the Shipping.<br><span class="gradient-text">Find Us Locally.</span></h1>
            <p class="hero-subtitle">
                Can't wait for delivery? Step into our publisher's extensive offline network. Locate an authorized book dealer right in your neighborhood to grab your comic, novel, or CD instantly.
            </p>
            
            <div class="dealer-search-box">
                <form action="" method="GET" class="search-form-wrap">
                    <div class="input-group-custom">
                        <i class="fa-solid fa-magnifying-glass search-icon"></i>
                        <input type="text" name="search_dealer" placeholder="Search shop name or area..." aria-label="Search Dealer">
                    </div>
                    
                    <div class="input-group-custom select-custom">
                        <i class="fa-solid fa-city search-icon"></i>
                        <select name="city" aria-label="Select City">
                            <option value="">Select City</option>
                            <option value="Karachi">Karachi</option>
                            <option value="Lahore">Lahore</option>
                            <option value="Islamabad">Islamabad</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="comp-btn btn-primary search-btn">
                        <i class="fa-solid fa-location-arrow"></i> Find Dealer
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
<section class="dealers-section">
            <div class="section-container">

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
                    <div class="dealer-card">
    <div class="dealer-header">
        <div class="dealer-icon"><i class="fa-solid fa-location-dot"></i></div>
        <h4>Liberty Books Emporium</h4>
    </div>
    <div class="dealer-body">
        <p class="dealer-address">Shop #2, Next to BBQ Tonight, Block 5, Clifton, Karachi</p>
        <div class="dealer-meta-wrapper">
            <p class="dealer-contact"><i class="fa-solid fa-phone"></i> <span>+92-21-35374111</span></p>
            <p class="dealer-timing"><i class="fa-regular fa-clock"></i> <span>10:00 AM - 11:00 PM</span></p>
        </div>
    </div>
</div>

<div class="dealer-card">
    <div class="dealer-header">
        <div class="dealer-icon"><i class="fa-solid fa-location-dot"></i></div>
        <h4>Readings Main Branch</h4>
    </div>
    <div class="dealer-body">
        <p class="dealer-address">12K, Main Boulevard, Gulberg II, Lahore</p>
        <div class="dealer-meta-wrapper">
            <p class="dealer-contact"><i class="fa-solid fa-phone"></i> <span>+92-42-111126657</span></p>
            <p class="dealer-timing"><i class="fa-regular fa-clock"></i> <span>09:00 AM - 12:00 AM</span></p>
        </div>
    </div>
</div>

<div class="dealer-card">
    <div class="dealer-header">
        <div class="dealer-icon"><i class="fa-solid fa-location-dot"></i></div>
        <h4>Variety Book Zone</h4>
    </div>
    <div class="dealer-body">
        <p class="dealer-address">Commercial Zone, 15-CCA, Phase 5, D.H.A, Lahore</p>
        <div class="dealer-meta-wrapper">
            <p class="dealer-contact"><i class="fa-solid fa-phone"></i> <span>+92-42-35692345</span></p>
            <p class="dealer-timing"><i class="fa-regular fa-clock"></i> <span>11:00 AM - 10:00 PM</span></p>
        </div>
    </div>
</div>

<div class="dealer-card">
    <div class="dealer-header">
        <div class="dealer-icon"><i class="fa-solid fa-location-dot"></i></div>
        <h4>Saeed Book Bank</h4>
    </div>
    <div class="dealer-body">
        <p class="dealer-address">Plot 12, F-7 Markaz, Jinnah Avenue, Islamabad</p>
        <div class="dealer-meta-wrapper">
            <p class="dealer-contact"><i class="fa-solid fa-phone"></i> <span>+92-51-2651656</span></p>
            <p class="dealer-timing"><i class="fa-regular fa-clock"></i> <span>10:00 AM - 10:30 PM</span></p>
        </div>
    </div>
</div>

<div class="dealer-card">
    <div class="dealer-header">
        <div class="dealer-icon"><i class="fa-solid fa-location-dot"></i></div>
        <h4>Paramount Book Stall</h4>
    </div>
    <div class="dealer-body">
        <p class="dealer-address">Main PECHS, Block 2, Shahrah-e-Faisal, Karachi</p>
        <div class="dealer-meta-wrapper">
            <p class="dealer-contact"><i class="fa-solid fa-phone"></i> <span>+92-21-34310030</span></p>
            <p class="dealer-timing"><i class="fa-regular fa-clock"></i> <span>11:00 AM - 08:30 PM</span></p>
        </div>
    </div>
</div>

<div class="dealer-card">
    <div class="dealer-header">
        <div class="dealer-icon"><i class="fa-solid fa-location-dot"></i></div>
        <h4>Mr. Books International</h4>
    </div>
    <div class="dealer-body">
        <p class="dealer-address">10-D, Super Market, F-6 Markaz, Islamabad</p>
        <div class="dealer-meta-wrapper">
            <p class="dealer-contact"><i class="fa-solid fa-phone"></i> <span>+92-51-2278845</span></p>
            <p class="dealer-timing"><i class="fa-regular fa-clock"></i> <span>09:30 AM - 09:30 PM</span></p>
        </div>
    </div>
</div>

<div class="dealer-card">
    <div class="dealer-header">
        <div class="dealer-icon"><i class="fa-solid fa-location-dot"></i></div>
        <h4>London Book House</h4>
    </div>
    <div class="dealer-body">
        <p class="dealer-address">Shop #5, Boat Basin Market, Block 5, Clifton, Karachi</p>
        <div class="dealer-meta-wrapper">
            <p class="dealer-contact"><i class="fa-solid fa-phone"></i> <span>+92-21-35874321</span></p>
            <p class="dealer-timing"><i class="fa-regular fa-clock"></i> <span>12:00 PM - 10:00 PM</span></p>
        </div>
    </div>
</div>

<div class="dealer-card">
    <div class="dealer-header">
        <div class="dealer-icon"><i class="fa-solid fa-location-dot"></i></div>
        <h4>Ferozsons Publishers</h4>
    </div>
    <div class="dealer-body">
        <p class="dealer-address">60, Shahrah-e-Quaid-e-Azam, Mall Road, Lahore</p>
        <div class="dealer-meta-wrapper">
            <p class="dealer-contact"><i class="fa-solid fa-phone"></i> <span>+92-42-36302274</span></p>
            <p class="dealer-timing"><i class="fa-regular fa-clock"></i> <span>10:00 AM - 09:00 PM</span></p>
        </div>
    </div>
</div>

<div class="dealer-card">
    <div class="dealer-header">
        <div class="dealer-icon"><i class="fa-solid fa-location-dot"></i></div>
        <h4>The Book Gallery</h4>
    </div>
    <div class="dealer-body">
        <p class="dealer-address">Shop #14, Mini Market, Sector G-11/3, Islamabad</p>
        <div class="dealer-meta-wrapper">
            <p class="dealer-contact"><i class="fa-solid fa-phone"></i> <span>+92-51-2831900</span></p>
            <p class="dealer-timing"><i class="fa-regular fa-clock"></i> <span>11:00 AM - 09:30 PM</span></p>
        </div>
    </div>
</div>
                </div>
            </div>
            
        </section>
</div>
</body>
</html>