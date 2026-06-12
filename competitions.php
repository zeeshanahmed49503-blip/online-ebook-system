<?php
session_start();

include('auth.php');

$error = '';       // For Essay
$story_error = ''; // For Short Story

$user_name = $_SESSION['name'] ?? '';
$user_id = $_SESSION['user_id'] ?? '';

$already_submitted = false;        // Essay status
$already_submitted_story = false;  // Story status

// Database checks for both competitions
if (!empty($user_name) && !empty($user_id)) {
    $safe_user_id = mysqli_real_escape_string($conn, $user_id);

    // 1. Check Essay Submission
    $check_essay = "SELECT id FROM submissions WHERE user_id = '$safe_user_id' AND competition_type = 'essay'";
    $essay_result = mysqli_query($conn, $check_essay);
    if (mysqli_num_rows($essay_result) > 0) {
        $already_submitted = true;
    }
}

// ==========================================
// HANDLE ESSAY SUBMISSION (Purana Form)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['draft_content'])) {
    $competition_type = "essay";
    $title            = "Annual Essay Submission";
    $content          = mysqli_real_escape_string($conn, $_POST['draft_content']);
    $file_path        = "";
    $rules_accepted   = isset($_POST['rules_agreement']) ? true : false;

    if (!$rules_accepted) {
        $error = "You must accept the official arena rules and plagiarism terms.";
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
        $competition_id = mysqli_real_escape_string($conn, $_POST['competition_id']);

$insert_query = "INSERT INTO submissions (user_id, user_name, competition_type, competition_id, title, content, file_path) 
                 VALUES ('$user_id', '$safe_user_name', '$competition_type', '$competition_id', '$title', '$content', '$file_path')";
        if (mysqli_query($conn, $insert_query)) {
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

// ==========================================
// HANDLE SHORT STORY SUBMISSION (Naya Form)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['story_content'])) {
    $competition_type = "story";
    $title            = mysqli_real_escape_string($conn, $_POST['story_title']); // User inputs title
    $content          = mysqli_real_escape_string($conn, $_POST['story_content']);
    $file_path        = "";
    $rules_accepted   = isset($_POST['story_rules_agreement']) ? true : false;

    if (empty(trim($title))) {
        $story_error = "Please give your story a beautiful title.";
    } elseif (!$rules_accepted) {
        $story_error = "You must confirm that this is your original work.";
    } else {
        $has_text = !empty(trim($content));
        $has_file = (isset($_FILES['story_file']) && $_FILES['story_file']['error'] == 0);

        if (!$has_text && !$has_file) {
            $story_error = "Please write your story or upload a document file.";
        } elseif ($has_text && $has_file) {
            $story_error = "Please choose only one method: write directly OR upload a file.";
        }
    }

    if (empty($story_error)) {
        if (isset($_FILES['story_file']) && $_FILES['story_file']['error'] == 0) {
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $file_name   = time() . "_" . basename($_FILES["story_file"]["name"]);
            $target_file = $target_dir . $file_name;
            if (move_uploaded_file($_FILES["story_file"]["tmp_name"], $target_file)) {
                $file_path = $target_file;
            }
        }

        $safe_user_name = mysqli_real_escape_string($conn, $user_name);
        $competition_id = mysqli_real_escape_string($conn, $_POST['competition_id']);
       $insert_query = "INSERT INTO submissions (user_id, user_name, competition_type, competition_id, title, content, file_path) 
                 VALUES ('$user_id', '$safe_user_name', '$competition_type', '$competition_id', '$title', '$content', '$file_path')";

        if (mysqli_query($conn, $insert_query)) {
            echo "<script>
                alert('Your Story has been successfully submitted!');
                window.location.href='competitions.php';
            </script>";
            exit();
        } else {
            $story_error = "Database Error: " . mysqli_error($conn);
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
            font-family: 'italic';
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
            .hero-stats {
                flex-direction: column;
                gap: 15px;
            }
        }

        @media (max-width: 992px) {
            .hero-grid {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 40px;
            }

            .hero-wrapper {
                text-align: center;
            }

            .hero-subtitle {
                margin: 0 auto 45px auto;
            }
        }

        @media (max-width: 768px) {
            .premium-hero h1 {
                font-size: 2.8rem;
            }

            .hero-stats {
                flex-direction: column;
                padding: 25px;
                width: 100%;
            }

            .divider {
                display: none;
            }
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

        .timer-icon {
            font-size: 1.8rem;
            color: var(--primary-orange);
        }

        .timer-digits {
            display: flex;
            flex-direction: column;
        }

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

        .desk-form {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

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

        .form-group textarea {
            resize: vertical;
            font-family: inherit;
            line-height: 1.6;
        }

        .word-counter {
            text-align: right;
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

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

        .file-drop-zone:hover {
            border-color: var(--primary-orange);
            background: rgba(255, 107, 0, 0.01);
        }

        .file-zone-icon {
            font-size: 2.2rem;
            color: var(--text-muted);
            margin-bottom: 10px;
            transition: color 0.2s ease;
        }

        .file-drop-zone:hover .file-zone-icon {
            color: var(--primary-orange);
        }

        .file-drop-zone input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-drop-zone p {
            font-size: 0.95rem;
            color: var(--pure-black);
            margin-bottom: 4px;
        }

        .file-drop-zone p span {
            color: var(--primary-orange);
            font-weight: 600;
        }

        .file-drop-zone small {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .checkbox-group {
            margin-top: 5px;
        }

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
            height: 0;
            width: 0;
        }

        .checkmark {
            width: 20px;
            height: 20px;
            background-color: #ffffff;
            border: 2px solid #dcdcdc;
            border-radius: 6px;
            flex-shrink: 0;
            position: relative;
            transition: all 0.2s ease;
            margin-top: 2px;
        }

        .custom-checkbox:hover input~.checkmark {
            border-color: var(--primary-orange);
        }

        .custom-checkbox input:checked~.checkmark {
            background-color: var(--primary-orange);
            border-color: var(--primary-orange);
        }

        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
            left: 6px;
            top: 2px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .custom-checkbox input:checked~.checkmark:after {
            display: block;
        }

        .checkbox-text {
            font-size: 0.9rem;
            color: grey;
            line-height: 1.5;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }

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
            .submission-box {
                padding: 25px;
            }

            .submission-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }

            .timer-container {
                width: 100%;
                justify-content: center;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .btn-submit {
                width: 100%;
                justify-content: center;
            }
        }

        .workspace-hidden {
            display: none !important;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

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
                <p class="section-subtitle">Unleash your inner writer! Participate in our exciting writing contests to showcase your creative skills, compete with the finest minds ...

                </p>
                <div class="comp-grid">
                    <?php
                    $static_query = "SELECT * FROM competitions WHERE id = 1";
                    $static_result = mysqli_query($conn, $static_query);
                    $static_row = mysqli_fetch_assoc($static_result);
                    ?>
                    <!-- Essay Card -->
                    <div class="comp-card dynamic-tilt-left">
                        <div class="comp-badge status-active"><?php echo $static_row['status']; ?></div>
                        <div class="comp-content">
                            <h3>Annual Essay Writing</h3>
                            <p class="comp-desc">Ready for a surprise? Once you click the start button, our system will instantly assign you a random hidden topic.</p>

                            <div class="comp-meta">
                                <span><i class="fa-regular fa-clock"></i> <strong>Time Limit:</strong> 3 Hours (No submission after deadline)</span>
                                <span><i class="fa-solid fa-clock"></i> <strong>Deadline:</strong><?php echo $static_row['deadline']; ?></span>
                                <span><i class="fa-solid fa-trophy"></i> <strong>Prize:</strong><?php echo $static_row['reward']; ?></span>
                                <span><i class="fa-solid fa-circle-xmark"></i> <strong>AI Content:</strong> Strictly prohibited</span>
                                <span><i class="fa-solid fa-arrows-rotate"></i> <strong>Plagiarism:</strong> Reused or copied content not allowed</span>
                            </div>

                            <!-- ✅ New Rule Paragraph Added Here Perfectly -->
                            <div class="comp-instructions" style="background-color: #f8f9fa; border-left: 4px solid #ff5314; padding: 12px 15px; margin: 15px 0; border-radius: 4px; font-size: 14px; color: #333; line-height: 1.5;">
                                <p style="margin: 0;">
                                    <i class="fa-solid fa-circle-info" style="color: #ff651e; margin-right: 5px;"></i>
                                    <strong>Important Note:</strong> As soon as you click the "Participate" button, your 3-hour timer will start immediately, and your writing topic will be displayed at the top of the page.
                                </p>
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
                    <?php
                    $dynamic_query = "SELECT * FROM competitions WHERE status = 'active' AND id != 1 ORDER BY id DESC";
                    $dynamic_result = mysqli_query($conn, $dynamic_query);

                   while ($row = mysqli_fetch_assoc($dynamic_result)) {
    $comp_id = $row['id'];
    $already_submitted_story = false;

    if (!empty($user_id)) {
        $check_story = "SELECT id FROM submissions WHERE user_id = '$safe_user_id' AND competition_id = '$comp_id'";
        $story_result = mysqli_query($conn, $check_story);
        $already_submitted_story = (mysqli_num_rows($story_result) > 0);
    }
                    ?>
                        <div class="comp-card dynamic-tilt-right">

                            <div class="comp-badge status-active"><?php echo $row['status'] ?></div>
                            <div class="comp-content">
                                <h3><?php echo $row['title'] ?></h3>
                                <p class="comp-desc"><?php echo $row['description'] ?></p>

                                <div class="comp-meta">
                                    <span><i class="fa-solid fa-clock"></i> <strong>Deadline:</strong><?php echo $row['deadline'] ?></span>
                                    <span><i class="fa-solid fa-award"></i> <strong>Prize:</strong><?php echo $row['reward'] ?></span>
                                    <span><i class="fa-solid fa-circle-xmark"></i> <strong>AI Content:</strong> Strictly prohibited</span>
                                    <span><i class="fa-solid fa-arrows-rotate"></i> <strong>Original Work:</strong> No plagiarism allowed</span>
                                </div>

                                <div class="comp-instructions" style="background-color: #f8f9fa; border-left: 4px solid #007bff; padding: 12px 15px; margin: 15px 0; border-radius: 4px; font-size: 14px; color: #333; line-height: 1.5;">
                                    <p style="margin: 0;">
                                        <i class="fa-solid fa-circle-info" style="color: #007bff; margin-right: 5px;"></i>
                                        <strong>Important Note:</strong> As soon as you click the <strong>"Participate"</strong> button, your 1-week countdown will start, and your submission portal will open immediately.
                                    </p>
                                </div>

                                <?php if (isset($_SESSION['name'])): ?>
    <?php if ($already_submitted_story): ?>
        <a href="javascript:void(0)" class="comp-btn" 
           style="background-color:#2b9348; color:#ffffff; cursor:not-allowed;">
            Submitted Successfully!
        </a>
    <?php else: ?>
        <a href="javascript:void(0)" 
           class="comp-btn btn-primary participate-story-btn" 
           data-id="<?php echo $row['id']; ?>"
           data-title="<?php echo htmlspecialchars($row['title']); ?>">
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
                    <?php
                    }
                    ?>
                </div>
            </div>

        </section>

        <!-- ===== SUBMISSION WORKSPACE ===== -->
        <section class="submission-section <?php echo (isset($_POST['draft_content']) && !empty($error)) ? '' : 'workspace-hidden'; ?>" id="workspace">
            <div class="section-container">
                <div class="submission-box">

                    <div class="submission-header" style="background: linear-gradient(135deg, #111111 0%, #222222 100%); padding: 35px; border-radius: 20px; border: 1px solid rgba(255,107,0,0.2); display: flex; justify-content: space-between; align-items: center; gap: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.25);">
                        <div class="header-text-side">
                            <span class="desk-badge" style="background: rgba(255,107,0,0.08); padding: 6px 14px; border-radius: 50px; border: 1px solid rgba(255,107,0,0.25); color: var(--primary-orange); font-size: 0.75rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 14px;">
                                <i class="fa-solid fa-feather-pointed"></i> Creative Arena Workspace
                            </span>

                            <h2 class="desk-title" style="font-size: 2.4rem; font-weight: 800; color: #ffffff; margin: 0 0 12px 0; letter-spacing: -0.5px; line-height: 1.2;">
                                Topic: <span style="background: linear-gradient(to right, #ff6b00, #ff9f43); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Annual Essay Writing Challenge</span>
                            </h2>

                            <div class="prize-container" style="display: flex; align-items: center; gap: 10px; background: rgba(255, 215, 0, 0.06); border: 1px dashed rgba(255, 215, 0, 0.3); padding: 8px 16px; border-radius: 8px; width: fit-content; margin-bottom: 5px;">
                                <span style="color: #ffd700; font-size: 0.9rem; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; display: flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-trophy" style="animation: pulse 2s infinite;"></i> Prize:
                                </span>
                                <span style="color: #ffffff; font-weight: 600; font-size: 0.95rem; letter-spacing: 0.3px;">
                                    Exclusive Gold Medal + $500 Cash Reward
                                </span>
                            </div>
                        </div>

                        <div class="timer-container" style="background: rgba(255,255,255,0.03); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 15px 25px; min-width: 140px; text-align: center;">
                            <div class="timer-icon" style="margin-bottom: 4px;"><i class="fa-solid fa-hourglass-start fa-spin" style="--fa-animation-duration: 3s; color: #ff6b00; font-size: 1.2rem;"></i></div>
                            <div class="timer-digits">
                                <span id="countdown-timer" style="font-family: monospace; font-size: 1.6rem; font-weight: 700; color: #ffffff; display: block;">03:00:00</span>
                                <small style="color: #ff6b00; font-weight: 600; font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">Remaining Time</small>
                            </div>
                        </div>
                    </div>

                    <form action="competitions.php" method="POST" enctype="multipart/form-data" class="desk-form" style="margin-top: 35px;">
                    <input type="hidden" name="competition_id" value="1">   
                    <div class="form-group full-width">
                            <label for="comp-text" style="font-size: 0.95rem; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; color: #333;">
                                <i class="fa-solid fa-pen-nib" style="color: var(--primary-orange);"></i> <strong>Write / Paste Your Draft</strong>
                            </label>

                            <textarea id="comp-text" name="draft_content" placeholder="Type your essay or story here directly..." rows="14" style="border-radius: 12px; border: 1px solid #dcdcdc; padding: 15px; font-size: 1rem; line-height: 1.6; transition: all 0.3s ease; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02); width: 100%; box-sizing: border-box;"><?php echo isset($_POST['draft_content']) ? htmlspecialchars($_POST['draft_content']) : ''; ?></textarea>

                            <div class="word-counter" style="margin-top: 8px; font-size: 0.85rem; color: #666; text-align: right;">
                                <span>Characters: <strong id="char-count" style="color: var(--primary-orange); font-weight: 700;"><?php echo isset($_POST['draft_content']) ? mb_strlen($_POST['draft_content']) : '0'; ?></strong></span>
                            </div>
                        </div>

                        <div class="form-group full-width" style="margin-top: 20px;">
                            <label style="font-size: 0.95rem; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; color: #333;"><i class="fa-solid fa-file-arrow-up" style="color: var(--primary-orange);"></i> Or Upload Document File (Optional)</label>
                            <div class="file-drop-zone" style="border: 2px dashed #ff6b00; background: rgba(255,107,0,0.01); padding: 30px; border-radius: 12px; text-align: center; position: relative; transition: all 0.3s ease;">
                                <i class="fa-regular fa-file-word file-zone-icon" style="color: #ff6b00; font-size: 2.5rem; margin-bottom: 10px; display: block;"></i>
                                <input type="file" id="file-upload" name="submission_file" accept=".pdf,.doc,.docx" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                                <p style="margin: 5px 0; font-size: 1rem; color: #444; font-weight: 500;">Drag and drop your file here, or <span style="color: #ff6b00; text-decoration: underline;">Browse</span></p>
                                <small style="color: #777; display: block; margin-top: 4px;">Supported formats: PDF, DOC, DOCX (Max 10MB)</small>
                                <p id="file-name-display" style="color:#2b9348; font-weight:600; margin-top:12px; font-size:0.85rem; display:none;"></p>
                            </div>
                        </div>

                        <div class="form-group full-width checkbox-group" style="margin-top: 25px; margin-bottom: 25px; background: rgba(0,0,0,0.02); padding: 15px; border-radius: 10px; border: 1px solid #e0e0e0;">
                            <?php if (!empty($error)): ?>
                                <p class="err" style="color: #d90429; font-weight: 600; background: rgba(217,4,41,0.05); padding: 12px; border-left: 4px solid #d90429; border-radius: 6px; margin-bottom: 15px;"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error); ?></p>
                            <?php endif; ?>

                            <div style="display: flex; align-items: flex-start; gap: 12px;">
                                <input type="checkbox"
                                    name="rules_agreement"
                                    id="rules-check"
                                    required
                                    style="width: 20px; height: 20px; min-width: 20px; min-height: 20px; accent-color: #ff6b00; cursor: pointer; margin-top: 2px; display: inline-block !important; visibility: visible !important; opacity: 1 !important;">

                                <label for="rules-check" style="margin: 0; font-size: 0.95rem; color: #444; line-height: 1.5; font-weight: 500; cursor: pointer; user-select: none;">
                                    I confirm this is 100% my original work, free from AI plagiarism, and I agree to the official arena rules. <span style="color: red; font-weight: bold;">*</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-actions" style="text-align: left;">
                            <button type="submit" id="submit-btn" class="btn-submit" style="background: linear-gradient(90deg, #ff6b00 0%, #ff8800 100%); padding: 18px 45px; box-shadow: 0 6px 20px rgba(255, 107, 0, 0.25); border: none; border-radius: 10px; color: white; font-weight: 700; font-size: 1rem; cursor: pointer; display: inline-flex; align-items: center; gap: 10px; transition: all 0.3s ease;">
                                Submit Entry <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </section>

        <section class="submission-section <?php echo (isset($_POST['story_content']) && !empty($story_error)) ? '' : 'workspace-hidden'; ?>" id="story-workspace">
            <div class="section-container">
                <div class="submission-box">

                    <div class="submission-header" style="background: linear-gradient(135deg, #111111 0%, #1a1a1a 100%); padding: 35px; border-radius: 20px; border: 1px solid rgba(255, 107, 0, 0.3); display: flex; justify-content: space-between; align-items: center; gap: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.4);">
                        <div class="header-text-side">
                            <span class="desk-badge" style="background: rgba(255, 107, 0, 0.08); padding: 6px 14px; border-radius: 50px; border: 1px solid rgba(255, 107, 0, 0.3); color: #ff6b00; font-size: 0.75rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 14px;">
                                <i class="fa-solid fa-book-open"></i> Fiction Arena Workspace
                            </span>

                            <h2 class="desk-title" style="font-size: 2.4rem; font-weight: 800; color: #ffffff; margin: 0 0 12px 0; letter-spacing: -0.5px; line-height: 1.2;">
                                Topic: <span style="background: linear-gradient(to right, #ff6b00, #ffa502); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Your Own Choice (Creative Story)</span>
                            </h2>

                            <div class="prize-container" style="display: flex; align-items: center; gap: 10px; background: rgba(255, 107, 0, 0.04); border: 1px dashed rgba(255, 107, 0, 0.3); padding: 8px 16px; border-radius: 8px; width: fit-content; margin-bottom: 5px;">
                                <span style="color: #ff6b00; font-size: 0.9rem; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; display: flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-award"></i> Reward:
                                </span>
                                <span style="color: #ffffff; font-weight: 600; font-size: 0.95rem; letter-spacing: 0.3px;">
                                    Cash Prize &amp; Official Journal Feature
                                </span>
                            </div>
                        </div>

                        <div class="timer-container" style="background: rgba(255,255,255,0.02); backdrop-filter: blur(10px); border: 1px solid rgba(255,107,0,0.15); border-radius: 14px; padding: 15px 25px; min-width: 140px; text-align: center;">
                            <div class="timer-icon" style="margin-bottom: 4px;"><i class="fa-solid fa-calendar-day" style="color: #ff6b00; font-size: 1.2rem;"></i></div>
                            <div class="timer-digits">
                                <span style="font-family: sans-serif; font-size: 1.3rem; font-weight: 700; color: #ffffff; display: block;">7 Days Max</span>
                                <small style="color: #ff6b00; font-weight: 600; font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">Submission Window</small>
                            </div>
                        </div>
                    </div>

                    <form action="competitions.php" method="POST" enctype="multipart/form-data" class="desk-form" style="margin-top: 35px;">
<input type="hidden" name="competition_id" id="story-competition-id" value="">
                        <div class="form-group full-width" style="margin-bottom: 25px;">
                            <label for="story-title" style="font-size: 0.95rem; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; color: #111111;">
                                <i class="fa-solid fa-heading" style="color: #ff6b00;"></i> <strong>Enter Your Story Title / Name</strong> <span style="color: red;">*</span>
                            </label>
                            <input type="text" id="story-title" name="story_title"
                                value="<?php echo isset($_POST['story_title']) ? htmlspecialchars($_POST['story_title']) : ''; ?>"
                                placeholder="e.g., The Shadows of Tomorrow..."
                                style="border-radius: 12px; border: 1px solid #dcdcdc; padding: 15px; font-size: 1rem; color: #111111; width: 100%; box-sizing: border-box; transition: all 0.3s ease; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                        </div>

                        <div class="form-group full-width">
                            <label for="story-text" style="font-size: 0.95rem; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; color: #111111;">
                                <i class="fa-solid fa-feather" style="color: #ff6b00;"></i> <strong>Write Your Creative Story</strong> <span style="color: red;">*</span>
                            </label>

                            <textarea id="story-text" name="story_content" placeholder="Let your imagination flow! Type or paste your custom story here..." rows="16" style="border-radius: 12px; border: 1px solid #dcdcdc; padding: 15px; font-size: 1rem; line-height: 1.6; transition: all 0.3s ease; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02); width: 100%; box-sizing: border-box; color: #111111;"><?php echo isset($_POST['story_content']) ? htmlspecialchars($_POST['story_content']) : ''; ?></textarea>
                            <div class="word-counter" style="margin-top: 8px; font-size: 0.85rem; color: #666; text-align: right;">
                                <span>Characters: <strong id="story-char-count" style="color: #ff6b00; font-weight: 700;">0</strong></span>
                            </div>
                        </div>

                        <div class="form-group full-width" style="margin-top: 20px;">
                            <label style="font-size: 0.95rem; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; color: #111111;"><i class="fa-solid fa-file-arrow-up" style="color: #ff6b00;"></i> Or Upload Story Document (Optional)</label>
                            <div class="file-drop-zone" style="border: 2px dashed #ff6b00; background: rgba(255,107,0,0.01); padding: 30px; border-radius: 12px; text-align: center; position: relative; transition: all 0.3s ease;">
                                <i class="fa-regular fa-file-word file-zone-icon" style="color: #ff6b00; font-size: 2.5rem; margin-bottom: 10px; display: block;"></i>
                                <input type="file" id="story-file-upload" name="story_file" accept=".pdf,.doc,.docx" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                                <p style="margin: 5px 0; font-size: 1rem; color: #444; font-weight: 500;">Drag and drop your file here, or <span style="color: #ff6b00; text-decoration: underline;">Browse</span></p>
                                <small style="color: #777; display: block; margin-top: 4px;">Supported formats: PDF, DOC, DOCX (Max 10MB)</small>
                                <p id="story-file-name-display" style="color:#2b9348; font-weight:600; margin-top:12px; font-size:0.85rem; display:none;"></p>
                            </div>
                        </div>

                        <div class="form-group full-width checkbox-group" style="margin-top: 25px; margin-bottom: 25px; background: rgba(0,0,0,0.02); padding: 15px; border-radius: 10px; border: 1px solid #e0e0e0;">
                            <?php if (!empty($story_error)): ?>
                                <p class="err" style="color: #d90429; font-weight: 600; background: rgba(217,4,41,0.05); padding: 12px; border-left: 4px solid #d90429; border-radius: 6px; margin-bottom: 15px;"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($story_error); ?></p>
                            <?php endif; ?>

                            <div style="display: flex; align-items: flex-start; gap: 12px;">
                                <input type="checkbox"
                                    name="story_rules_agreement"
                                    id="story-rules-check"
                                    required
                                    style="width: 20px; height: 20px; min-width: 20px; min-height: 20px; accent-color: #ff6b00; cursor: pointer; margin-top: 2px; display: inline-block !important; visibility: visible !important; opacity: 1 !important;">

                                <label for="story-rules-check" style="margin: 0; font-size: 0.95rem; color: #444; line-height: 1.5; font-weight: 500; cursor: pointer; user-select: none;">
                                    I confirm this story is entirely written by me, contains no AI-generated elements, and matches my original creative thought. <span style="color: red; font-weight: bold;">*</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-actions" style="text-align: left;">
                            <button type="submit" id="story-submit-btn" class="btn-submit" style="background: linear-gradient(90deg, #ff6b00 0%, #ff8800 100%); padding: 18px 45px; box-shadow: 0 6px 20px rgba(255, 107, 0, 0.25); border: none; border-radius: 10px; color: white; font-weight: 700; font-size: 1rem; cursor: pointer; display: inline-flex; align-items: center; gap: 10px; transition: all 0.3s ease;">
                                Submit Story <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </section>

        <section class="competition-section upcoming-section" style="padding-top: 0;">
            <div class="section-container">
                <h2 class="section-title">Upcoming Competitions</h2>
                <p class="section-subtitle">Prepare your drafts early, sharpen your skills, and get ready to compete with the finest writers for massive prizes and global recognition!</p>

                <div class="comp-grid">
<?php
$upcoming_query = "SELECT * FROM competitions WHERE status = 'upcoming' ORDER BY id ASC";
$upcoming_result = mysqli_query($conn, $upcoming_query);

while ($urow = mysqli_fetch_assoc($upcoming_result)) {
?>

                    <div class="comp-card dynamic-tilt-left">
                        <div class="comp-badge" style="background-color: #f77f00; color: #ffffff;">Starting on <?php echo $urow['starting_at'] ?></div>
                        <div class="comp-content">
                            <h3><?php echo $urow['title'] ?></h3>
                            <p class="comp-desc"><?php echo $urow['description'] ?></p>

                            <div class="comp-meta">
                                <span><i class="fa-regular fa-calendar"></i> <strong>Deadline:</strong><?php echo $urow['deadline'] ?></span>
                                <span><i class="fa-solid fa-trophy"></i> <strong>Prize:</strong><?php echo $urow['reward'] ?></span>
                                <span><i class="fa-solid fa-circle-xmark"></i> <strong>AI Content:</strong> Strictly prohibited</span>
                                <span><i class="fa-solid fa-arrows-rotate"></i> <strong>Plagiarism:</strong> Reused or copied content not allowed</span>
                            </div>

                            <a href="javascript:void(0)" class="comp-btn btn-secondary" style="cursor: not-allowed; background-color: #6c757d; color: #ffffff;">
                                <i class="fa-solid fa-lock"></i> Registration Opening Soon
                            </a>
                        </div>
                    </div>
                    <?php } ?>

                </div>
            </div>
        </section>

        <!-- ===== WINNERS ===== -->
        <section class="winners-section">

            <div class="section-container">
                <h2 class="section-title">Our Proud Winners</h2>
                <p class="section-subtitle">Pre-register today and prep your drafts! Submit your custom creative story documents online once the portal officially unlocks next week.

                </p>
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

        document.querySelectorAll('.participate-story-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var title = this.getAttribute('data-title');
        var compId = this.getAttribute('data-id');
        document.querySelector('#story-workspace .desk-title span').innerText = title;
        document.getElementById('story-competition-id').value = compId;
        openStoryWorkspace();
    });
});
        // --- DYNAMIC CHARACTER COUNTER FOR ESSAY ---
        var essayTextarea = document.getElementById('comp-text');
        if (essayTextarea) {
            essayTextarea.addEventListener('input', function() {
                var charCount = this.value.length;
                document.getElementById('char-count').innerText = charCount;
            });
        }

        // New Story button handler
        var participateStoryBtn = document.getElementById('participate-story-btn');
        if (participateStoryBtn) {
            participateStoryBtn.addEventListener('click', function() {
                openStoryWorkspace();
            });
        }

        var userLoggedIn = <?php echo isset($_SESSION['name']) ? 'true' : 'false'; ?>;
        var alreadyDone = <?php echo $already_submitted ? 'true' : 'false'; ?>;
        var currentUser = <?php echo isset($_SESSION['name']) ? json_encode($_SESSION['name']) : 'null'; ?>;

        var savedTimerUser = localStorage.getItem('competition_timer_user');
        if (savedTimerUser !== null && savedTimerUser !== currentUser) {
            localStorage.removeItem('competition_remaining_time');
            localStorage.removeItem('competition_timer_user');
        }

        function lockAllInputs() {
            var timerDisplay = document.getElementById('countdown-timer');
            if (timerDisplay) {
                timerDisplay.innerHTML = "00:00:00 (Time's Up!)";
                timerDisplay.style.color = "red";
            }

            var textarea = document.getElementById('comp-text') || document.querySelector('textarea[name="draft_content"]');
            var fileInput = document.getElementById('file-upload');
            var checkbox = document.querySelector('#rules-check');
            var submitBtn = document.getElementById('submit-btn') || document.querySelector('.btn-submit');

            if (textarea) {
                textarea.disabled = true;
                textarea.style.background = "#eeeeee";
            }
            if (fileInput) {
                fileInput.disabled = true;
                var dropZone = document.querySelector('.file-drop-zone');
                if (dropZone) dropZone.style.pointerEvents = "none";
            }
            if (checkbox) {
                checkbox.disabled = true;
            }
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.style.opacity = "0.5";
                submitBtn.style.cursor = "not-allowed";
            }
        }

        // Opens Essay Arena
        function openWorkspace() {
            var workspace = document.getElementById('workspace');
            if (!workspace) return;

            workspace.classList.remove('workspace-hidden');
            workspace.scrollIntoView({
                behavior: 'smooth'
            });

            if (localStorage.getItem('competition_remaining_time') === 'expired') {
                lockAllInputs();
                return;
            }

            if (!localStorage.getItem('competition_remaining_time')) {
                localStorage.setItem('competition_remaining_time', 10800);
                localStorage.setItem('competition_timer_user', currentUser);
            }

            startCountdown();
        }

        // Opens Story Arena (No Timer required)
        function openStoryWorkspace() {
            var storyWorkspace = document.getElementById('story-workspace');
            if (!storyWorkspace) return;

            storyWorkspace.classList.remove('workspace-hidden');
            storyWorkspace.scrollIntoView({
                behavior: 'smooth'
            });
        }

        // Auto reopen setups on error or reload
        var hasSavedTimer = localStorage.getItem('competition_remaining_time') !== null;
        var timerBelongsToMe = localStorage.getItem('competition_timer_user') === currentUser;

        if (userLoggedIn && !alreadyDone && hasSavedTimer && timerBelongsToMe) {
            openWorkspace();
        }

        // If story post returns errors, keep story workspace open
        <?php if (isset($_POST['story_content']) && !empty($story_error)): ?>
            openStoryWorkspace();
        <?php endif; ?>

        // --- TIMING COUNTER LOGIC (For Essay Only) ---
        var timerInterval = null;

        function formatTime(s) {
            var hrs = Math.floor(s / 3600);
            var mins = Math.floor((s % 3600) / 60);
            var secs = s % 60;
            return (hrs < 10 ? "0" + hrs : hrs) + ":" +
                (mins < 10 ? "0" + mins : mins) + ":" +
                (secs < 10 ? "0" + secs : secs);
        }

        function startCountdown() {
            if (timerInterval) return;
            var timerDisplay = document.getElementById('countdown-timer');
            var savedTime = localStorage.getItem('competition_remaining_time');

            if (savedTime === 'expired') {
                lockAllInputs();
                return;
            }

            var totalSeconds = parseInt(savedTime);
            if (isNaN(totalSeconds) || totalSeconds <= 0) {
                totalSeconds = 10800;
            }

            timerDisplay.innerHTML = formatTime(totalSeconds);

            timerInterval = setInterval(function() {
                if (totalSeconds <= 0) {
                    clearInterval(timerInterval);
                    timerInterval = null;
                    if (localStorage.getItem('competition_remaining_time') === 'expired') return;
                    localStorage.setItem('competition_remaining_time', 'expired');
                    lockAllInputs();
                    setTimeout(function() {
                        alert("Time Over! Workspace locked.");
                    }, 50);
                    return;
                }
                totalSeconds--;
                localStorage.setItem('competition_remaining_time', totalSeconds);
                timerDisplay.innerHTML = formatTime(totalSeconds);
            }, 1000);
        }

        // --- FILE ATTACHMENT DISPLAYS ---
        var fileUpload = document.getElementById('file-upload');
        if (fileUpload) {
            fileUpload.addEventListener('change', function() {
                var fileDisplay = document.getElementById('file-name-display');
                if (this.files && this.files.length > 0) {
                    fileDisplay.innerHTML = '<i class="fa-solid fa-paperclip"></i> Attached: ' + this.files[0].name;
                    fileDisplay.style.display = 'block';
                }
            });
        }

        var storyFileUpload = document.getElementById('story-file-upload');
        if (storyFileUpload) {
            storyFileUpload.addEventListener('change', function() {
                var storyFileDisplay = document.getElementById('story-file-name-display');
                if (this.files && this.files.length > 0) {
                    storyFileDisplay.innerHTML = '<i class="fa-solid fa-paperclip"></i> Attached: ' + this.files[0].name;
                    storyFileDisplay.style.display = 'block';
                }
            });
        }

        // --- DYNAMIC CHARACTER COUNTER FOR STORY ---
        var storyTextarea = document.getElementById('story-text');
        if (storyTextarea) {
            storyTextarea.addEventListener('input', function() {
                var charCount = this.value.length;
                document.getElementById('story-char-count').innerText = charCount;
            });
        }

        // Smooth error auto scroll
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && (!empty($error) || !empty($story_error))): ?>
            setTimeout(function() {
                var errorParagraph = document.querySelector('.err');
                if (errorParagraph) {
                    errorParagraph.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }, 100);
        <?php endif; ?>

        var participateBtn = document.getElementById('participate-btn');
if (participateBtn) {
    participateBtn.addEventListener('click', function() {
        openWorkspace();
    });
}
    </script>
</body>

</html>