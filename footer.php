 <style>
    
footer {
    background-color: #0b0c10; /* Ultra-slick deep premium black background */
    width: 100%;
    padding: 80px 0 40px 0; /* Padding inside top & bottom, sides handled by container */
    color: #ffffff;
    margin-top: 80px;
    border-top: 1px solid rgba(255, 255, 255, 0.08); /* Minimalist upper separation line */
}

/* 1300px Perfect Fluid Container */
.footer-container {
    width: 100%;
    max-width: 1300px;
    margin: 0 auto;
    padding: 0 24px;
    box-sizing: border-box;
}

.footer-grid {
    display: grid;
    grid-template-columns: 2fr repeat(4, 1fr);
    gap: 50px;
    margin-bottom: 60px;
}

/* Logo Alignment & Brand CSS */
.footer-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.footer-svg {
    width: 30px;
    height: 30px;
}

.footer-logo-side h3 {
    font-family: 'normal', serif;
    font-size: 26px;
    font-weight: 700;
    letter-spacing: -0.5px;
    margin: 0;
}

.footer-logo-side h3 span {
    font-family: 'italic', serif;
    font-weight: 300;
    color: #ff7a00; /* Custom neon/retro accent orange */
}

.footer-logo-side p {
    font-size: 14px;
    color: #a0a5b5; /* Smooth muted text contrast on black */
    max-width: 320px;
    line-height: 1.8;
    margin: 0 0 25px 0;
}

/* Social Media Handles */
.social-icons {
    display: flex;
    gap: 12px;
}

.social-icons a {
    width: 38px;
    height: 38px;
    background-color: rgba(255, 255, 255, 0.04);
    color: #ffffff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    text-decoration: none;
    border: 1px solid rgba(255, 255, 255, 0.08);
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}

.social-icons a:hover {
    background-color: #ff7a00;
    color: #0b0c10;
    border-color: #ff7a00;
    transform: translateY(-4px);
    box-shadow: 0 6px 20px rgba(255, 122, 0, 0.3);
}

/* Columns & Headings Styling */
.footer-col h4 {
    font-size: 15px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin: 0 0 25px 0;
    color: #ffffff;
    position: relative;
}

footer .brand-logo{
    color: white;
}
.footer-col ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-col ul li {
    margin-bottom: 14px;
}
footer .fa-book-open {
    color: var(--retro-orange);
}

.footer-col ul li a {
    text-decoration: none;
    color: #a0a5b5;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
}

.footer-col ul li a i {
    font-size: 13px;
    color: rgba(255, 122, 0, 0.7);
}

.footer-col ul li a:hover {
    color: #ff7a00;
    transform: translateX(4px);
}

/* Footer Bottom Strip Styling */
.footer-bottom {
    text-align: center;
    font-size: 13px;
    color: #686d7a;
    padding-top: 30px;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
}

.footer-bottom p {
    margin: 0;
}

/* RESPONSIVE LAYOUT RESPONSES */
@media (max-width: 1024px) {
    .footer-grid {
        grid-template-columns: 1.5fr repeat(2, 1fr);
        gap: 40px;
    }
}

@media (max-width: 768px) {
    footer {
        padding: 60px 0 30px 0;
    }
    .footer-grid {
        grid-template-columns: 1fr;
        gap: 35px;
        text-align: center;
    }
    .footer-brand {
        justify-content: center;
    }
    .footer-logo-side p {
        margin-left: auto;
        margin-right: auto;
    }
    .social-icons {
        justify-content: center;
    }
    .footer-col h4::after {
        left: 50%;
        transform: translateX(-50%);
    }
    .footer-col ul li a {
        justify-content: center;
    }
    .footer-col ul li a:hover {
        transform: translateY(-2px);
    }
}
 </style>
 
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
                <p>&copy; 2026 Bookish Systems. All rights reserved. | Crafted with passion for Aptech Terminal Project.</p>
            </div>
        </div>
    </footer>
