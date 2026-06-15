<?php
include 'auth.php'; // Database connection ($conn) iske andar hona chahiye

// 1. Search aur Filter ki logic handle karna
$where_clauses = [];

// Agar user text search kare
if (isset($_GET['search_dealer']) && !empty(trim($_GET['search_dealer']))) {
    $search = mysqli_real_escape_string($conn, trim($_GET['search_dealer']));
    $where_clauses[] = "(name LIKE '%$search%' OR address LIKE '%$search%')";
}

// Agar user city select kare
if (isset($_GET['city']) && !empty(trim($_GET['city']))) {
    $city = mysqli_real_escape_string($conn, trim($_GET['city']));
    $where_clauses[] = "address LIKE '%$city%'";
}

// Base Query
$query = "SELECT * FROM dealers";

// Agar filters lagaye hain toh WHERE clause add karo
if (count($where_clauses) > 0) {
    $query .= " WHERE " . implode(' AND ', $where_clauses);
}

// New entries sabse pehle dikhane ke liye
$query .= " ORDER BY id DESC";
$result = mysqli_query($conn, $query);
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
            background-color: var(--retro-purple); 
            border: 2px solid var(--primary-dark);
            box-shadow: 3px 3px 0px var(--primary-dark);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        /* Typography */
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

        .select-custom select {
            appearance: none;
            cursor: pointer;
        }

        .dealer-hero .search-btn {
            cursor: pointer;
            white-space: nowrap;
            border: 2px solid var(--primary-dark);
            box-shadow: 4px 4px 0px var(--primary-dark);
            background-color: var(--cta-orange, #f26419);
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
                                <input type="text" name="search_dealer" value="<?php echo isset($_GET['search_dealer']) ? htmlspecialchars($_GET['search_dealer']) : ''; ?>" placeholder="Search shop name or area..." aria-label="Search Dealer">
                            </div>
                            
                            <div class="input-group-custom select-custom">
                                <i class="fa-solid fa-city search-icon"></i>
                                <select name="city" aria-label="Select City">
                                    <option value="">Select City</option>
                                    <option value="Karachi" <?php if(isset($_GET['city']) && $_GET['city'] == 'Karachi') echo 'selected'; ?>>Karachi</option>
                                    <option value="Lahore" <?php if(isset($_GET['city']) && $_GET['city'] == 'Lahore') echo 'selected'; ?>>Lahore</option>
                                    <option value="Islamabad" <?php if(isset($_GET['city']) && $_GET['city'] == 'Islamabad') echo 'selected'; ?>>Islamabad</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="comp-btn btn-primary search-btn">
                                <i class="fa-solid fa-location-arrow"></i> Find Dealer
                            </button>
                            
                            <?php if(isset($_GET['search_dealer']) || isset($_GET['city'])): ?>
                                <a href="dealers.php" class="search-btn" style="background-color: #6c757d; text-decoration: none;">Clear Filters</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <section class="dealers-section">
            <div class="section-container">
                <div class="dealers-grid">
                    
                    <?php 
                    // 2. Database Loop lagana
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <div class="dealer-card">
                                <div class="dealer-header">
                                    <div class="dealer-icon"><i class="fa-solid fa-location-dot"></i></div>
                                    <h4><?php echo htmlspecialchars($row['name']); ?></h4>
                                </div>
                                <div class="dealer-body">
                                    <p class="dealer-address"><?php echo htmlspecialchars($row['address']); ?></p>
                                    <div class="dealer-meta-wrapper">
                                        <p class="dealer-contact">
                                            <i class="fa-solid fa-phone"></i> <span><?php echo htmlspecialchars($row['phone']); ?></span>
                                        </p>
                                        <p class="dealer-timing">
                                            <i class="fa-regular fa-clock"></i> <span><?php echo htmlspecialchars($row['timing']); ?></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php 
                        }
                    } else {
                        // Agar koi result na mile
                        echo "<div class='col-12 text-center py-5'><h3 class='text-muted'>Koi dealer nahi mila aapki search ke mutabiq!</h3></div>";
                    }
                    ?>

                </div>
            </div>
        </section>
    </div>
      <?php include 'footer.php'?>

</body>
</html>