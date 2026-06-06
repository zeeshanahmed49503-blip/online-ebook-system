<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact us</title>
       <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
    /* --- Contact Section Layout --- */
.contact-section {
    background-color: white;
    padding: 50px 20px;
    border-radius: 20px;
    margin-top: 50px;
    position: relative;
}

.contact-section .section-title {
    font-family: 'normal', serif;
    font-size: 65px;
    font-weight: lighter;
    color: var(--primary-dark);
    margin-bottom: 12px;
    line-height: 1.1;
}

.contact-section .section-title span {
    font-family: 'italic', serif;
    font-weight: lighter;
    color: var(--retro-orange);
}

.contact-section .section-subtitle {
    font-size: 14px;
    color: var(--text-muted);
    line-height: 1.6;
    margin-bottom: 40px;
    max-width: 500px;
}

.contact-grid {
    display: grid;
    grid-template-columns: 0.8fr 1.2fr;
    gap: 40px;
    margin-top: 20px;
}

/* --- Left Side: Coupon Info Cutouts --- */
.contact-info-wrap {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.info-coupon {
    background: var(--bg-card, #f9f9f9);
    padding: 20px;
    border-radius: 12px;
    border: 2px dashed rgba(26, 26, 26, 0.2); /* Retro coupon dashed line */
    display: flex;
    align-items: center;
    gap: 15px;
    transition: transform 0.2s ease;
}

.info-coupon:hover {
    transform: scale(1.02);
}

.coupon-icon {
    background-color: var(--retro-purple, #e6e0f8);
    color: var(--primary-dark);
    width: 45px;
    height: 45px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    border: 2px solid var(--primary-dark);
    box-shadow: 2px 2px 0px var(--primary-dark);
}

.coupon-details h5 {
    font-size: 15px;
    font-weight: 700;
    color: var(--primary-dark);
    margin: 0 0 4px 0;
}

.coupon-details p {
    font-size: 13px;
    color: var(--text-muted);
    margin: 0;
    word-break: break-word;
}

/* --- Right Side: Neo-Brutalist Form Block --- */
.contact-form-box {
    background: linear-gradient(135deg, #ffffff 70%, var(--pastel-orange, #fff0e6) 100%); 
    border-radius: 16px;
    padding: 35px;
    border: 3px solid var(--primary-dark); /* Bold vintage solid border */
    box-shadow: 10px 10px 0px var(--primary-dark); /* Playful raw drop shadow */
}

.form-row-custom {
    display: flex;
    gap: 20px;
}

.form-group-custom {
    margin-bottom: 22px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.form-group-custom label {
    font-size: 13px;
    font-weight: 700;
    color: var(--primary-dark);
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.input-wrapper-custom {
    position: relative;
    display: flex;
    align-items: center;
}

.input-wrapper-custom .input-icon {
    position: absolute;
    left: 15px;
    color: var(--primary-dark);
    font-size: 15px;
    pointer-events: none;
}

.input-wrapper-custom .textarea-icon {
    top: 18px; /* Icon alignment for textarea */
}

.input-wrapper-custom input,
.input-wrapper-custom select,
.input-wrapper-custom textarea {
    width: 100%;
    padding: 12px 15px 12px 42px;
    font-size: 14px;
    font-weight: 600;
    color: var(--primary-dark);
    background-color: white;
    border: 2px solid var(--primary-dark);
    border-radius: 8px;
    outline: none;
    transition: all 0.2s ease;
}

.input-wrapper-custom textarea {
    resize: none;
}

.input-wrapper-custom input:focus,
.input-wrapper-custom select:focus,
.input-wrapper-custom textarea:focus {
    background-color: var(--retro-yellow, #fff9e6);
    box-shadow: 0 0 0 1px var(--primary-dark);
}

/* Custom select arrow behavior */
.select-wrapper select {
    appearance: none;
    cursor: pointer;
}

/* Button Submit override matching project buttons */
.contact-form-box .submit-btn {
    width: 100%;
    cursor: pointer;
    padding: 14px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 14px;
    border: 2px solid var(--primary-dark);
    box-shadow: 4px 4px 0px var(--primary-dark);
    background-color: var(--cta-orange);
    color: white;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.2s ease;
}

.contact-form-box .submit-btn:hover {
    background-color: var(--retro-orange);
    transform: translate(2px, 2px);
    box-shadow: 2px 2px 0px var(--primary-dark);
}

/* --- Responsive Layout Breaks --- */
@media (max-width: 992px) {
    .contact-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    .form-row-custom {
        flex-direction: column;
        gap: 0;
    }
    .contact-form-box {
        padding: 20px;
        box-shadow: 6px 6px 0px var(--primary-dark);
    }
}
</style>
</head>
<body>
    <?php include 'navbar.php'; ?>
<div class="app-container">


    <section class="contact-section">
    <div class="section-container">
        <h2 class="section-title">Get In Touch <br> <span> With Us ...</span></h2>
        <p class="section-subtitle">Facing an issue with your E-Book download? Want to report a bug or just say hello to the publisher? Drop us a line below!</p>
        
        <div class="contact-grid">
            <div class="contact-info-wrap">
                <div class="info-coupon">
                    <div class="coupon-icon"><i class="fa-solid fa-envelope"></i></div>
                    <div class="coupon-details">
                        <h5>Official Support</h5>
                        <p>support@ebookpublisher.com</p>
                    </div>
                </div>
                
                <div class="info-coupon">
                    <div class="coupon-icon"><i class="fa-solid fa-headset"></i></div>
                    <div class="coupon-details">
                        <h5>Helpline Number</h5>
                        <p>+92-21-111-BOOKS (26657)</p>
                    </div>
                </div>

                <div class="info-coupon">
                    <div class="coupon-icon"><i class="fa-solid fa-building"></i></div>
                    <div class="coupon-details">
                        <h5>Main Publication Office</h5>
                        <p>Plot 45-C, Sector 15, Industrial Area, Karachi, Pakistan.</p>
                    </div>
                </div>
            </div>

            <div class="contact-form-box">
                <form action="process_contact.php" method="POST">
                    <div class="form-row-custom">
                        <div class="form-group-custom">
                            <label for="user_name">Your Name</label>
                            <div class="input-wrapper-custom">
                                <i class="fa-solid fa-user input-icon"></i>
                                <input type="text" id="user_name" name="name" placeholder="John Doe" required>
                            </div>
                        </div>

                        <div class="form-group-custom">
                            <label for="user_email">Email Address</label>
                            <div class="input-wrapper-custom">
                                <i class="fa-solid fa-envelope input-icon"></i>
                                <input type="email" id="user_email" name="email" placeholder="john@example.com" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label for="msg_subject">Subject / Inquiry Type</label>
                        <div class="input-wrapper-custom select-wrapper">
                            <i class="fa-solid fa-circle-info input-icon"></i>
                            <select id="msg_subject" name="subject" required>
                                <option value="">Select what's wrong...</option>
                                <option value="Payment Issue">Payment Verification Problem</option>
                                <option value="PDF Access">Cannot Access Purchased PDF</option>
                                <option value="CD/HardCopy Shipping">Delayed CD or Hard Copy Delivery</option>
                                <option value="Competition Query">Essay Competition Issue</option>
                                <option value="General Feedback">General Feedback & Suggestions</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label for="user_message">Describe Your Issue</label>
                        <div class="input-wrapper-custom">
                            <i class="fa-solid fa-pen-to-square input-icon textarea-icon"></i>
                            <textarea id="user_message" name="message" rows="5" placeholder="Type your message or requirement in detail here..." required></textarea>
                        </div>
                    </div>

                    <button type="submit" class="comp-btn btn-primary submit-btn">
                        <i class="fa-solid fa-paper-plane"></i> Send Message To Admin
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
</div>
</body>
</html>