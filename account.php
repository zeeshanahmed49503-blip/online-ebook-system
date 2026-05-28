<?php
session_start();
include("auth.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';
$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($query);

if (isset($_POST['update_profile'])) {
    $new_name    = mysqli_real_escape_string($conn, trim($_POST['name']));
    $new_phone   = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $new_address = mysqli_real_escape_string($conn, trim($_POST['address']));

    if (empty($new_name)) {
        $error = "Name cannot be empty!";
    } 
    elseif (!preg_match("/^[a-zA-Z\s]*$/", $new_name)) {
        $error = "Name can only contain letters and spaces!";
    }
    elseif (!empty($new_phone)) {
        if (!preg_match("/^[0-9]+$/", $new_phone)) {
            $error = "Phone number must contain numbers only!";
        } 
        elseif (strlen($new_phone) < 10 || strlen($new_phone) > 13) {
            $error = "Please enter a valid phone number (10 to 13 digits)!";
        }
    }

    if (empty($error)) {
        $update_sql = "UPDATE users SET full_name = '$new_name', phone = '$new_phone', address = '$new_address' WHERE id = '$user_id'";
        
        if (mysqli_query($conn, $update_sql)) {
            $_SESSION['name'] = $new_name;
            header("Location: account.php?status=success");
            exit();
        } else {
            $error = "Failed to update profile. Database error.";
        }
    }
}

if (isset($_GET['status']) && $_GET['status'] == 'success') {
    $success = "Profile updated successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Bookish.</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        .btn-sidebar-logout {
    margin-top: 30px;
    background-color: rgba(230, 57, 70, 0.2); /* Soft Red Translucent */
    color: #ffb3b7;
    border: 1px solid rgba(230, 57, 70, 0.4);
    padding: 10px 20px;
    font-size: 0.9rem;
    font-weight: 500;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-sidebar-logout:hover {
    background-color: #e63946; /* Solid Red on Hover */
    color: #ffffff;
    box-shadow: 0 4px 15px rgba(230, 57, 70, 0.4);
    transform: translateY(-2px);
}
        body {
            background-color: #fff7ec;
            color: #1a1a1a;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .account-container {
            display: grid;
            grid-template-columns: 1fr 1.8fr;
            width: 100%;
            max-width: 950px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .profile-sidebar {
            background: linear-gradient(135deg, #f26419, #551700);
            color: #ffffff;
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
.avatar-letter-box {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3.5rem;
    font-weight: 700;
    color: #ffffff; /* Letter ka color white rahega */
    text-shadow: 0 2px 4px rgba(0,0,0,0.15);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    margin-bottom: 20px;
    border: 3px solid rgba(255,255,255,0.4);
    text-transform: uppercase;
}
        .profile-sidebar h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .profile-sidebar .badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            margin-bottom: 30px;
        }

        .quick-info {
            width: 100%;
            text-align: left;
            margin-top: auto;
            background: rgba(0,0,0,0.15);
            padding: 20px;
            border-radius: 10px;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            font-size: 0.9rem;
        }

        .info-row:last-child { margin-bottom: 0; }
        .info-row i { color: #fcbf1e; font-size: 1rem; width: 20px; }

        .back-home {
            margin-top: 25px;
            color: #ffffff;
            text-decoration: none;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            opacity: 0.8;
            transition: opacity 0.3s;
        }
        .back-home:hover { opacity: 1; text-decoration: underline;}

        .settings-content {
            padding: 50px 40px;
        }

        .settings-content h3 {
            font-size: 1.6rem;
            color: #222;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .settings-content .subtitle {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 35px;
        }

        .msg {
            padding: 12px;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .msg.err { background-color: #ffe5e7; color: #e63946; }
        .msg.succ { background-color: #e2f9f3; color: #0fa983; }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .full-width {
            grid-column: span 2;
        }

        .input-box {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .input-box label {
            font-size: 0.85rem;
            color: #4a5568;
            font-weight: 500;
        }

        .input-field {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background-color: #f7fafc;
            color: #2d3748;
            font-size: 0.9rem;
            outline: none;
            transition: all 0.3s;
        }

        .input-field:focus {
            border-color: #f26419;
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(242, 100, 25, 0.1);
        }

        .input-field:disabled {
            background-color: #edf2f7;
            color: #a0aec0;
            cursor: not-allowed;
        }

        .btn-save {
            background-color: #f26419;
            color: white;
            border: none;
            padding: 14px 28px;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-top: 25px;
        }

        .btn-save:hover { background-color: #1a1a1a; }

        @media (max-width: 768px) {
            .account-container { grid-template-columns: 1fr; }
            .form-grid { grid-template-columns: 1fr; }
            .full-width { grid-column: span 1; }
            .settings-content { padding: 30px 20px; }
        }
    </style>
</head>
<body>

    <div class="account-container">
        
       <div class="profile-sidebar">
    <?php 
        $first_letter = strtoupper(substr($user['full_name'], 0, 1));
        
        $colors = ['#f26419', '#3366ff', '#0fa983', '#8338ec', '#ff006e', '#fb5607', '#3a86ff'];
        $color_index = $user['id'] % count($colors);
        $bg_color = $colors[$color_index];
    ?>
    
    <div class="avatar-letter-box" style="background-color: <?php echo $bg_color; ?>;">
        <?php echo $first_letter; ?>
    </div>
    
    <h2 style="text-transform: capitalize;"><?php echo htmlspecialchars($user['full_name']); ?></h2>
    <span class="badge">PRO READER</span>

    <div class="quick-info">
        <div class="info-row">
            <i class="fa-solid fa-envelope"></i>
            <span><?php echo htmlspecialchars($user['email']); ?></span>
        </div>
        <div class="info-row">
            <i class="fa-solid fa-phone"></i>
            <span><?php echo htmlspecialchars($user['phone'] ?: 'No Phone Added'); ?></span>
        </div>
    </div>

    <a href="logout.php" class="btn-sidebar-logout">
        <i class="fa-solid fa-right-from-bracket"></i> Log Out
    </a>

    <a href="index.php" class="back-home">
        <i class="fa-solid fa-arrow-left"></i> Back to Bookstore
    </a>
</div>
        <div class="settings-content">
            <h3>Account Settings</h3>
            <p class="subtitle">Update your personal details and manage your profile.</p>

            <?php if(!empty($error)): ?>
                <div class="msg err"><i class="fa-solid fa-circle-exclamation"></i> <?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if(!empty($success)): ?>
                <div class="msg succ"><i class="fa-solid fa-circle-check"></i> <?php echo $success; ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-grid">
                    
                    <div class="input-box">
                        <label>Full Name</label>
                        <input type="text" name="name" class="input-field" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                    </div>

                    <div class="input-box">
                        <label>Email Address (Locked)</label>
                        <input type="email" class="input-field" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                    </div>

                    <div class="input-box">
                        <label>Phone Number</label>
                        <input type="tel" name="phone" class="input-field" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="Enter phone number">
                    </div>

                    <div class="input-box">
                        <label>Password (Encrypted)</label>
                        <input type="password" class="input-field" value="********" disabled>
                    </div>

                    <div class="input-box full-width">
                        <label>Shipping Address</label>
                        <textarea name="address" class="input-field" rows="3" style="resize: none;" placeholder="Enter your delivery address..."><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                    </div>
                </div>

                <button type="submit" name="update_profile" class="btn-save">
                    <i class="fa-solid fa-floppy-disk"></i> Save Changes
                </button>
            </form>
        </div>
        
    </div>

</body>
</html>