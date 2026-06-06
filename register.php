<?php
include("auth.php");
$error = '';
if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass = trim($_POST['password']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    $check_email_query = "SELECT * FROM users WHERE email = '$email'";
$query_run = mysqli_query($conn, $check_email_query);

    // NAME VALIDATION
    if (empty($name)) {
        $error = "Name required";
    } elseif (!preg_match("/^[a-zA-Z ]+$/", $name)) {
        $error = "Only letters allowed in name";
    }

    // EMAIL VALIDATION
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email";
    }

    // PASSWORD VALIDATION
    elseif (strlen($pass) < 8) {
        $error = "Password must be 8 characters";
    }

    // PHONE VALIDATION
    elseif (!preg_match("/^[0-9]{11}$/", $phone)) {
        $error = "Phone must be 11 digits";
    }

    // ADDRESS VALIDATION
    elseif (strlen($address) < 5) {
        $error = "Address too short";
    }elseif (mysqli_num_rows($query_run) > 0) {
    $error = "Email already exists!";
}else {
        $hash_pass = password_hash($pass, PASSWORD_DEFAULT);
        $query = "insert into users(full_name, email, password, phone, address)
                values('$name','$email','$hash_pass','$phone','$address')";
        mysqli_query($conn, $query);
        header("location: login.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .err {
            color: red;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'normal', sans-serif;
        }

        :root {
            /* Main Brand Palette */
            --primary-dark: #1a1a1a;
            /* Clean Dark Charcoal (Headings & Primary Text) */
            --brand-accent: #5b5500;
            /* Cool Sage Green (Links, Highlights, Accents) */
            --cta-orange: #551700;
            /* Terracotta/Orange (Primary Buttons, Action items) */
            --bg-main: #fff7ec;
            /* Soft Book-Paper Cream (Website background) */
            --bg-card: #ffffff;
            /* Pure White for clean card structures */

            /* Text Varieties */
            --text-muted: #6b7280;
            /* Slate Gray for secondary details/author names */

            /* Pastel Categories Grid Colors */
            --pastel-purple: #e1d1fd;
            --pastel-orange: #ffe5c1;
            --pastel-blue: #cbe5ff;
            --pastel-green: #c2f9f1;
            --pastel-pink: #ffcdd6;

            /* Retro Graphics Colors */
            --retro-yellow: #fcbf1e;
            --retro-orange: #f26419;
            --retro-red: #e63946;
            --retro-purple: #7209b7;
        }

        /* Master Landscape Container */
        .split-screen-container {
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            /* 55% left banner, 45% form */
            min-height: 100vh;
            background-color: #ffffff;
        }

        /* LEFT PANEL - The Aesthetic Banner */
        .left-panel {
            position: relative;
            background: linear-gradient(to left, var(--retro-orange));
            /* Premium Rich Blue Gradient */
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            object-fit: cover;
            /* padding: 60px; */
        }

        .left-panel img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }


        /* RIGHT PANEL - The Form Area */
        .right-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
            background-color: #fdfdfd;
        }

        .form-wrapper {
            width: 100%;
            max-width: 480px;
        }

        .form-wrapper h2 {
            font-size: 2rem;
            color: #222;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .form-wrapper .subtitle {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 35px;
        }

        /* Inputs Layout inside form (2-column layout for fields) */
        .input-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            /* Name-Email side by side, Pass-Phone side by side */
            gap: 15px;
        }

        .input-group {
            position: relative;
            margin-bottom: 18px;
        }

        .textarea-group {
            margin-top: 3px;
        }

        /* Icons styling */
        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            transition: color 0.3s ease;
            font-size: 0.95rem;
        }

        .textarea-group .input-icon {
            top: 20px;
            transform: none;
        }

        /* Input boxes & Textarea formatting */
        .input-group input,
        .input-group textarea {
            width: 100%;
            padding: 14px 16px 14px 44px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #2d3748;
            background-color: #f7fafc;
            outline: none;
            transition: all 0.3s ease;
        }

        .input-group textarea {
            resize: none;
        }

        /* Focus States */
        .input-group input:focus,
        .input-group textarea:focus {
            border-color: var(--retro-orange);
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(42, 82, 152, 0.1);
        }

        .input-group input:focus~.input-icon,
        .input-group textarea:focus~.input-icon {
            color: var(--retro-orange);
        }

        /* Premium Submit Button */
        .btn-submit {
            width: 100%;
            padding: 15px;
            background-color: var(--retro-orange);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: 0 4px 12px rgba(42, 82, 152, 0.2);
        }

        .btn-submit:hover {
            background-color: black;
            box-shadow: 0 6px 18px rgba(30, 60, 114, 0.3);
        }

        .btn-submit:active {
            transform: scale(0.99);
        }

        /* Footer Section */
        .form-footer {
            margin-top: 30px;
            text-align: center;
            font-size: 0.9rem;
            color: #718096;
        }

        .form-footer a {
            color: blue;
            text-decoration: none;
            font-weight: 100;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        /* RESPONSIVE DESIGN (Mobile Friendly) */
        @media (max-width: 900px) {
            .split-screen-container {
                grid-template-columns: 1fr;
                /* Mobile par single column ho jayega automatically */
            }

            .left-panel {
                display: none;
                /* Mobile screen choti hoti hai toh banner hide ho jayega */
            }

            .right-panel {
                padding: 40px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="split-screen-container">
        <!-- Left Side: Cool Visual Section -->
        <div class="left-panel">
            <img src="images/register.webp" alt="">
        </div>

        <!-- Right Side: The Form Section -->
        <div class="right-panel">
            <div class="form-wrapper">
                <h2>Create Account</h2>
                <p class="subtitle">Please enter your details to register.</p>

                <form action="register.php" method="POST">
                    <div class="input-grid">
                        <!-- Full Name -->
                        <div class="input-group">
                            <i class="fa-solid fa-user input-icon"></i>
                            <input type="text" id="name" value="<?php echo $_POST['name'] ?? ''; ?>" name="name" placeholder="Full Name">
                        </div>

                        <!-- Email Address -->
                        <div class="input-group">
                            <i class="fa-solid fa-envelope input-icon"></i>
                            <input type="email" id="email" value="<?php echo $_POST['email'] ?? ''; ?>" name="email" placeholder="Email Address">
                        </div>

                        <!-- Password -->
                        <div class="input-group">
                            <i class="fa-solid fa-lock input-icon"></i>
                            <input type="password" id="password" value="<?php echo $_POST['password'] ?? ''; ?>" name="password" placeholder="Create Password">
                        </div>

                        <!-- Phone Number -->
                        <div class="input-group">
                            <i class="fa-solid fa-phone input-icon"></i>
                            <input type="tel" id="phone" value="<?php echo $_POST['phone'] ?? ''; ?>" name="phone" placeholder="Phone Number">
                        </div>
                    </div>

                    <!-- Address (Full Width) -->
                    <div class="input-group textarea-group">
                        <i class="fa-solid fa-location-dot input-icon"></i>
                        <textarea id="address" placeholder="Enter Your Address" name="address" rows="2"><?php echo $_POST['address'] ?? ''; ?></textarea>
                    </div>

                    <p class="err"><?php echo $error; ?></p>

                    <!-- Register Button -->
                    <button type="submit" name="submit" class="btn-submit">
                        <i class="fa-solid fa-user-plus"></i> Register Now
                    </button>
                </form>

                <div class="form-footer">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </div>
        </div>

    </div>
</body>

</html>