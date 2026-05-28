<?php
session_start();

include('auth.php');

$error = '';

$user_name = $_SESSION['name'] ?? '';
$already_submitted = false;

// ✅ FIX 1: Ek hi jagah, sahi column se check karo
if (!empty($user_name)) {
    $safe_user_name = mysqli_real_escape_string($conn, $user_name);
    $user_id = $_SESSION['user_id'];
    $check_query = "SELECT id FROM submissions WHERE user_id = '$user_id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $already_submitted = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title'])) {

    $user_name = $_SESSION['name'];

    $competition_type = mysqli_real_escape_string($conn, $_POST['competition']);
    $title            = mysqli_real_escape_string($conn, $_POST['title']);
    $content          = mysqli_real_escape_string($conn, $_POST['draft_content']);

    $file_path = "";

    $rules_accepted = isset($_POST['rules_agreement']) ? true : false;

    if (!$rules_accepted) {
        $error = "You must accept the official arena rules and plagiarism terms.";
    } elseif (strlen(trim($title)) < 5) {
        $error = "Title is too short! Minimum 5 characters required.";
    } else {
        $has_text = !empty(trim($content));
        $has_file = (isset($_FILES['submission_file']) && $_FILES['submission_file']['error'] == 0);

        if (!$has_text && !$has_file) {
            $error = "Please provide content: either write in the composition deck OR upload a file.";
        } elseif ($has_text && $has_file) {
            $error = "Conflict detected! Please use only one method: either write in the box OR upload a file, not both.";
        } elseif ($has_text) {
            $word_count = str_word_count(trim($content));
            if ($word_count < 100) {
                $error = "Your manuscript is too short ($word_count words). Minimum 100 words required.";
            }
        }
    }

    if (empty($error)) {

        if (isset($_FILES['submission_file']) && $_FILES['submission_file']['error'] == 0) {
            $target_dir = "uploads/";

            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_name   = time() . "_" . basename($_FILES["submission_file"]["name"]);
            $target_file = $target_dir . $file_name;

            if (move_uploaded_file($_FILES["submission_file"]["tmp_name"], $target_file)) {
                $file_path = $target_file;
            }
        }

        $safe_user_name = mysqli_real_escape_string($conn, $user_name);
       
$insert_query = "INSERT INTO submissions (user_id, user_name, competition_type, title, content, file_path) 
                 VALUES ('$user_id', '$safe_user_name', '$competition_type', '$title', '$content', '$file_path')";

        if (mysqli_query($conn, $insert_query)) {
            // ✅ FIX 4: redirect se pehle JS me localStorage clear karo — exit() se pehle
            echo "<script>
                localStorage.removeItem('competition_remaining_time');
                alert('Masterpiece Dispatched Successfully!');
                window.location.href='competitions.php';
            </script>";
            exit();
        } else {
            $error = "Database Error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creative Competitions - Bookish</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .err {
            color: red;
            font-size: 22px;
        }

        :root {
            --primary-orange: #ff6b00;
            --accent-orange: #e05e00;
            --pure-black: #000000;
            --dark-gray: #1f2833;
            --text-light: #c5c6c7;
            --text-muted: #868e96;
        }

        .premium-hero {
            position: relative;
            background: #ffffff;
            padding: 100px 20px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            border-radius: 20px;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 50px;
            max-width: 1300px;
            width: 100%;
            margin: auto;
            align-items: center;
        }

        .hero-wrapper {
            text-align: left;
            z-index: 2;
        }

        .badge-container {
            margin-bottom: 25px;
        }

        .hero-badge {
            background: rgba(255, 107, 0, 0.05);
            border: 1px solid rgba(255, 107, 0, 0.2);
            color: var(--primary-orange);
            padding: 8px 18px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: normal;
            font-style: normal;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(5px);
        }

        .premium-hero h1 {
            font-size: 4rem;
            font-weight: lighter;
            font-style: normal;
            line-height: 1.2;
            color: var(--pure-black);
            letter-spacing: -1px;
            margin-bottom: 25px;
        }

        .gradient-text {
            color: var(--primary-orange);
            font-style: italic;
            font-weight: lighter;
        }

        .hero-subtitle {
            font-size: 1.15rem;
            color: grey;
            max-width: 750px;
            margin: 0 0 45px 0;
            line-height: 1.75;
            font-style: normal;
        }

        .hero-img-wrap {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hero-img-wrap img {
            max-width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .hero-stats {
            display: flex;
            gap: 20px;
            margin-bottom: 45px;
            width: 100%;
            max-width: 750px;
        }

        .stat-item {
            flex: 1;
            background: #ffffff;
            border: 1px solid #e1e8ed;
            padding: 20px 24px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            gap: 16px;
            text-align: left;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .stat-item:hover {
            transform: translateY(-4px);
            border-color: var(--primary-orange);
            box-shadow: 0 12px 24px rgba(255, 107, 0, 0.08);
        }

        .stat-icon-wrap {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(255, 107, 0, 0.08);
            color: var(--primary-orange);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
            transition: background 0.3s ease;
        }

        .stat-item:hover .stat-icon-wrap {
            background: var(--primary-orange);
            color: #ffffff;
        }

        .stat-info-wrap {
            display: flex;
            flex-direction: column;
        }

        .stat-num {
            font-size: 1rem;
            font-weight: 700;
            color: var(--pure-black);
            line-height: 1.2;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 4px;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .hero-stats { flex-direction: column; gap: 15px; }
        }

        @media (max-width: 992px) {
            .hero-grid { grid-template-columns: 1fr; text-align: center; gap: 40px; }
            .hero-wrapper { text-align: center; }
            .hero-subtitle { margin: 0 auto 45px auto; }
        }

        @media (max-width: 768px) {
            .premium-hero h1 { font-size: 2.8rem; }
            .hero-stats { flex-direction: column; padding: 25px; width: 100%; }
            .divider { display: none; }
        }

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

        /* ===== SUBMISSION SECTION ===== */
        .submission-section {
            width: 100%;
            background: #ffffff;
            margin-top: 50px;
            border-radius: 20px;
        }

        .submission-box {
            border-radius: 24px;
            padding: 20px;
            max-width: 1300px;
            margin: 0 auto;
        }

        .submission-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 30px;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 30px;
            margin-bottom: 35px;
        }

        .desk-badge {
            color: var(--primary-orange);
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 10px;
        }

        .desk-title {
            font-size: 2.2rem;
            font-weight: lighter;
            color: var(--pure-black);
            margin-bottom: 8px;
        }

        .desk-subtitle {
            color: grey;
            font-size: 1rem;
            max-width: 600px;
        }

        .timer-container {
            display: flex;
            align-items: center;
            background: var(--pure-black);
            color: #ffffff;
            padding: 15px 25px;
            border-radius: 16px;
            gap: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 107, 0, 0.2);
        }

        .timer-icon { font-size: 1.8rem; color: var(--primary-orange); }

        .timer-digits { display: flex; flex-direction: column; }

        #countdown-timer {
            font-size: 1.6rem;
            font-weight: 700;
            font-family: monospace;
            letter-spacing: 1px;
            color: #ffffff;
        }

        .timer-digits small {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .desk-form { display: flex; flex-direction: column; gap: 25px; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }

        .form-group { display: flex; flex-direction: column; gap: 8px; }

        .form-group label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--pure-black);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group input[type="text"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #dcdcdc;
            border-radius: 10px;
            font-size: 1rem;
            background: #ffffff;
            color: var(--pure-black);
            transition: all 0.2s ease;
        }

        .form-group input[type="text"]:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 4px rgba(255, 107, 0, 0.08);
        }

        .form-group textarea { resize: vertical; font-family: inherit; line-height: 1.6; }

        .word-counter { text-align: right; font-size: 0.85rem; color: var(--text-muted); margin-top: 2px; }

        .file-drop-zone {
            border: 2px dashed #dcdcdc;
            background: #fafafa;
            border-radius: 12px;
            padding: 30px 20px;
            text-align: center;
            position: relative;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .file-drop-zone:hover { border-color: var(--primary-orange); background: rgba(255, 107, 0, 0.01); }

        .file-zone-icon {
            font-size: 2.2rem;
            color: var(--text-muted);
            margin-bottom: 10px;
            transition: color 0.2s ease;
        }

        .file-drop-zone:hover .file-zone-icon { color: var(--primary-orange); }

        .file-drop-zone input[type="file"] {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-drop-zone p { font-size: 0.95rem; color: var(--pure-black); margin-bottom: 4px; }
        .file-drop-zone p span { color: var(--primary-orange); font-weight: 600; }
        .file-drop-zone small { font-size: 0.8rem; color: var(--text-muted); }

        .checkbox-group { margin-top: 5px; }

        .custom-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            cursor: pointer;
            position: relative;
            user-select: none;
        }

        .custom-checkbox input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0; width: 0;
        }

        .checkmark {
            width: 20px; height: 20px;
            background-color: #ffffff;
            border: 2px solid #dcdcdc;
            border-radius: 6px;
            flex-shrink: 0;
            position: relative;
            transition: all 0.2s ease;
            margin-top: 2px;
        }

        .custom-checkbox:hover input ~ .checkmark { border-color: var(--primary-orange); }

        .custom-checkbox input:checked ~ .checkmark {
            background-color: var(--primary-orange);
            border-color: var(--primary-orange);
        }

        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
            left: 6px; top: 2px;
            width: 5px; height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .custom-checkbox input:checked ~ .checkmark:after { display: block; }

        .checkbox-text { font-size: 0.9rem; color: grey; line-height: 1.5; }

        .form-actions { display: flex; justify-content: flex-end; margin-top: 10px; }

        .btn-submit {
            background: var(--primary-orange);
            color: #ffffff;
            border: none;
            padding: 16px 40px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            background: var(--accent-orange);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 107, 0, 0.2);
        }

        @media (max-width: 768px) {
            .submission-box { padding: 25px; }
            .submission-header { flex-direction: column; align-items: flex-start; gap: 20px; }
            .timer-container { width: 100%; justify-content: center; }
            .form-row { grid-template-columns: 1fr; gap: 20px; }
            .btn-submit { width: 100%; justify-content: center; }
        }

        .workspace-hidden { display: none !important; }
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
                <?php if (isset($_SESSION['name'])): ?>
                    <a href="cart.php" class="btn btn-icon" title="Cart">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span class="cart-badge">0</span>
                    </a>
                    <a href="account.php" class="btn btn-account" title="My Account">
                        <i class="fa-solid fa-circle-user"></i>
                        <span><?php echo htmlspecialchars($_SESSION['name']); ?></span>
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-login">
                        <i class="fa-solid fa-right-to-bracket"></i> Login
                    </a>
                    <a href="register.php" class="btn btn-register">
                        <i class="fa-solid fa-user-plus"></i> Register
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="app-container">

        <!-- ===== HERO ===== -->
        <section class="premium-hero">
            <div class="hero-grid">
                <div class="hero-wrapper">
                    <div class="badge-container">
                        <span class="hero-badge">
                            <i class="fa-solid fa-trophy"></i> Live Writing Arena
                        </span>
                    </div>
                    <h1>Unleash Your Words.<br><span class="gradient-text">Capture The Mind.</span></h1>
                    <p class="hero-subtitle">
                        Step into our publisher's official creative hub. Whether you are a young mind stepping into the <strong>3-Hour Essay Challenge</strong> or an author submitting a masterpiece for the <strong>Monthly Journal</strong>, your journey to becoming a published writer starts here.
                    </p>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-icon-wrap"><i class="fa-solid fa-stopwatch-20"></i></div>
                            <div class="stat-info-wrap">
                                <span class="stat-num">03 Hrs</span>
                                <span class="stat-label">Strict Essay Timer</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon-wrap"><i class="fa-solid fa-gift"></i></div>
                            <div class="stat-info-wrap">
                                <span class="stat-num">Cash & Books</span>
                                <span class="stat-label">Exciting Rewards</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon-wrap"><i class="fa-solid fa-feather-pointed"></i></div>
                            <div class="stat-info-wrap">
                                <span class="stat-num">Official</span>
                                <span class="stat-label">Journal Feature</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hero-img-wrap">
                    <img src="images/comp.webp" alt="Creative Arena Illustration">
                </div>
            </div>
        </section>

        <!-- ===== COMPETITIONS GRID ===== -->
        <section class="competition-section">
            <div class="section-container">
                <h2 class="section-title">Ongoing &amp; Competition</h2>
                <p class="section-subtitle">Unleash your inner writer! Participate in our exciting writing contests...</p>
                <div class="comp-grid">

                    <!-- Essay Card -->
                    <div class="comp-card dynamic-tilt-left">
                        <div class="comp-badge status-active">Active Now</div>
                        <div class="comp-content">
                            <h3>Annual Essay Writing</h3>
                            <p class="comp-desc">Access the separate window topic, write, and submit your essay within a strict 3-hour window.</p>
                            <div class="comp-meta">
                                <span><i class="fa-regular fa-clock"></i> <strong>Time Limit:</strong> 3 Hours</span>
                                <span><i class="fa-solid fa-trophy"></i> <strong>Prize:</strong> Famous G.K. &amp; Grammar Book Bundle</span>
                            </div>

                            <?php if (isset($_SESSION['name'])): ?>
                                <?php if ($already_submitted): ?>
                                    <!-- ✅ FIX 1: Sahi already_submitted variable use ho rahi hai -->
                                    <a href="javascript:void(0)" class="comp-btn"
                                       style="background-color:#2b9348; color:#ffffff; cursor:not-allowed;">
                                        Submitted Successfully!
                                    </a>
                                <?php else: ?>
                                    <!-- ✅ FIX 3: id="participate-btn" sirf ek bar, tab hi jab user logged in ho -->
                                    <a href="javascript:void(0)" id="participate-btn" class="comp-btn btn-primary">
                                        Participate Now <i class="fa-solid fa-arrow-right-long"></i>
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="login.php" class="comp-btn btn-primary">
                                    Login to Participate <i class="fa-solid fa-arrow-right-long"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Story Card -->
                    <div class="comp-card dynamic-tilt-right">
                        <div class="comp-badge status-upcoming">Commencing Soon</div>
                        <div class="comp-content">
                            <h3>Short Story Writing</h3>
                            <p class="comp-desc">Submit your best creative stories online. The best selected story will be officially published in our upcoming monthly journal!</p>
                            <div class="comp-meta">
                                <span><i class="fa-regular fa-calendar"></i> <strong>Commencing:</strong> Coming Next Week</span>
                                <span><i class="fa-solid fa-award"></i> <strong>Prize:</strong> Cash Prize &amp; Journal Feature</span>
                            </div>
                            <a href="competitions.html" class="comp-btn btn-secondary">View Rules</a>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ===== SUBMISSION WORKSPACE ===== -->
        <section
            class="submission-section <?php echo (isset($_POST['title']) && !empty($error)) ? '' : 'workspace-hidden'; ?>"
            id="workspace">
            <div class="section-container">
                <div class="submission-box">

                    <div class="submission-header">
                        <div class="header-text-side">
                            <span class="desk-badge"><i class="fa-solid fa-feather"></i> Writing Desk</span>
                            <h2 class="desk-title">Submit Your Masterpiece</h2>
                            <p class="desk-subtitle">Fill in the details below and unleash your creativity.</p>
                        </div>
                        <div class="timer-container">
                            <div class="timer-icon"><i class="fa-solid fa-hourglass-start"></i></div>
                            <div class="timer-digits">
                                <span id="countdown-timer">03:00:00</span>
                                <small>Remaining Time</small>
                            </div>
                        </div>
                    </div>

                    <form action="competitions.php" method="POST" enctype="multipart/form-data" class="desk-form">

                        <div class="form-row">
                            <div class="form-group">
                                <label for="comp-select"><i class="fa-solid fa-trophy"></i> Select Competition</label>
                                <select id="comp-select" name="competition" required>
                                    <option value="" disabled <?php echo (!isset($_POST['competition'])) ? 'selected' : ''; ?>>
                                        Choose a challenge...
                                    </option>
                                    <option value="essay" <?php echo (isset($_POST['competition']) && $_POST['competition'] == 'essay') ? 'selected' : ''; ?>>
                                        Annual Essay Writing (3-Hour Challenge)
                                    </option>
                                    <option value="story" <?php echo (isset($_POST['competition']) && $_POST['competition'] == 'story') ? 'selected' : ''; ?>>
                                        Short Story Writing Contest
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="comp-title"><i class="fa-solid fa-heading"></i> Topic / Title</label>
                                <input type="text" id="comp-title" name="title"
                                    value="<?php echo isset($_POST['title']) ? htmlspecialchars(trim($_POST['title'])) : ''; ?>"
                                    placeholder="Enter the title of your work" required>
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label for="comp-text"><i class="fa-solid fa-pen-nib"></i> Write / Paste Your Draft</label>
                            <textarea id="comp-text" name="draft_content"
                                placeholder="Type your essay or story here directly..." rows="12"><?php echo isset($_POST['draft_content']) ? htmlspecialchars($_POST['draft_content']) : ''; ?></textarea>
                            <div class="word-counter">
                                <span>Characters: <strong id="char-count">0</strong></span>
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label><i class="fa-solid fa-file-arrow-up"></i> Or Upload Document File (Optional)</label>
                            <div class="file-drop-zone">
                                <i class="fa-regular fa-file-word file-zone-icon"></i>
                                <input type="file" id="file-upload" name="submission_file" accept=".pdf,.doc,.docx">
                                <p>Drag and drop your file here, or <span>Browse</span></p>
                                <small>Supported formats: PDF, DOC, DOCX (Max 10MB)</small>
                                <p id="file-name-display" style="color:#2b9348; font-weight:600; margin-top:8px; font-size:0.85rem; display:none;"></p>
                            </div>
                        </div>

                        <div class="form-group full-width checkbox-group">
                            <?php if (!empty($error)): ?>
                                <p class="err"><?php echo htmlspecialchars($error); ?></p>
                            <?php endif; ?>
                            <label class="custom-checkbox">
                                <input type="checkbox" name="rules_agreement">
                                <span class="checkmark"></span>
                                <p class="checkbox-text">I confirm this is 100% my original work, free from AI plagiarism, and I agree to the official arena rules.</p>
                            </label>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-submit">
                                Submit Entry <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </section>

        <!-- ===== WINNERS ===== -->
        <section class="winners-section">
            <div class="section-container">
                <h2 class="section-title">Our Proud Winners</h2>
                <p class="section-subtitle">Pre-register today and prep your drafts!</p>
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
                                <p class="winner-reward"><strong>Reward:</strong> Featured in Journal Vol. 12 + Cash Reward</p>
                            </div>
                        </div>
                    </div>
                    <div class="winner-card card-tilt-right">
                        <div class="winner-img-container">
                            <img src="images/pr1.webp" alt="Previous Competition Winner" class="winner-img">
                        </div>
                        <div class="winner-info">
                            <span class="winner-tag tag-previous"><i class="fa-solid fa-star"></i> Previous Winner</span>
                            <h4>Sara Khan</h4>
                            <p class="winner-achievement">Gold Medal - 3hr Essay Writing</p>
                            <div class="winner-reward-box">
                                <p class="winner-reward"><strong>Reward:</strong> Famous Literature Book Set + Certificate</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div><!-- end app-container -->

    <!-- ✅ FIX 2 & 3: Sirf EK script block, null-safe listener -->
    <script>
        // ── Participate Button (null-safe) ──────────────────────────────────
        var participateBtn = document.getElementById('participate-btn');
        if (participateBtn) {
            participateBtn.addEventListener('click', function () {
                openWorkspace();
            });
        }

        // ── Current logged-in user (PHP se inject) ─────────────────────────
        var userLoggedIn  = <?php echo isset($_SESSION['name']) ? 'true' : 'false'; ?>;
        var alreadyDone   = <?php echo $already_submitted ? 'true' : 'false'; ?>;
        var currentUser   = <?php echo isset($_SESSION['name']) ? json_encode($_SESSION['name']) : 'null'; ?>;

        // ── User mismatch check: doosre user ka timer clear karo ────────────
        var savedTimerUser = localStorage.getItem('competition_timer_user');
        if (savedTimerUser !== null && savedTimerUser !== currentUser) {
            localStorage.removeItem('competition_remaining_time');
            localStorage.removeItem('competition_timer_user');
        }

        // ── Workspace open helper ───────────────────────────────────────────
        function openWorkspace() {
            var workspace = document.getElementById('workspace');
            if (!workspace) return;

            workspace.classList.remove('workspace-hidden');
            workspace.scrollIntoView({ behavior: 'smooth' });

            if (!localStorage.getItem('competition_remaining_time')) {
                localStorage.setItem('competition_remaining_time', 10800);
                localStorage.setItem('competition_timer_user', currentUser);
            }

            startCountdown();
        }

        // ── Auto-resume: sirf same user ka timer resume ho ─────────────────
        var hasSavedTimer    = localStorage.getItem('competition_remaining_time') !== null;
        var timerBelongsToMe = localStorage.getItem('competition_timer_user') === currentUser;

        if (userLoggedIn && !alreadyDone && hasSavedTimer && timerBelongsToMe) {
            openWorkspace();
        }

        // ── Countdown Timer ─────────────────────────────────────────────────
        var timerInterval = null;

        // Helper: seconds ko HH:MM:SS string mein convert karo
        function formatTime(s) {
            var hrs  = Math.floor(s / 3600);
            var mins = Math.floor((s % 3600) / 60);
            var secs = s % 60;
            return (hrs  < 10 ? "0" + hrs  : hrs)  + ":" +
                   (mins < 10 ? "0" + mins : mins) + ":" +
                   (secs < 10 ? "0" + secs : secs);
        }

        function startCountdown() {
            // Agar timer pehle se chal raha ho toh duplicate mat chalao
            if (timerInterval) return;

            var timerDisplay = document.getElementById('countdown-timer');
            var totalSeconds = parseInt(localStorage.getItem('competition_remaining_time')) || 10800;

            // ✅ KEY FIX: setInterval se pehle TURANT sahi time display karo
            // Isse page refresh par "03:00:00" flash nahi hoga
            timerDisplay.innerHTML = formatTime(totalSeconds);

            timerInterval = setInterval(function () {
                if (totalSeconds <= 0) {
                    clearInterval(timerInterval);
                    timerInterval = null;
                    localStorage.removeItem('competition_remaining_time');

                    timerDisplay.innerHTML   = "00:00:00 (Time's Up!)";
                    timerDisplay.style.color = "red";

                    var textarea  = document.querySelector('textarea[name="draft_content"]');
                    var fileInput = document.getElementById('file-upload');
                    if (textarea)  textarea.disabled  = true;
                    if (fileInput) fileInput.disabled = true;

                    alert("Time Over! Workspace locked.");
                    return;
                }

                totalSeconds--;
                localStorage.setItem('competition_remaining_time', totalSeconds);
                timerDisplay.innerHTML = formatTime(totalSeconds);

            }, 1000);
        }

        // ── File name display ───────────────────────────────────────────────
        var fileUpload = document.getElementById('file-upload');
        if (fileUpload) {
            fileUpload.addEventListener('change', function () {
                var fileDisplay = document.getElementById('file-name-display');
                if (this.files && this.files.length > 0) {
                    fileDisplay.innerHTML    = '<i class="fa-solid fa-paperclip"></i> Attached: ' + this.files[0].name;
                    fileDisplay.style.display = 'block';
                } else {
                    fileDisplay.style.display = 'none';
                }
            });
        }

        // ── Scroll to error on validation fail ─────────────────────────────
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($error)): ?>
        setTimeout(function () {
            var errorParagraph = document.querySelector('.err');
            if (errorParagraph) {
                errorParagraph.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }, 100);
        <?php endif; ?>
    </script>

</body>
</html>