<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<style>
    .header-actions { display: flex; align-items: center; gap: 15px; }
    .fr{ color: green; text-transform: uppercase; }
    .btn-icon { position: relative; font-size: 1.2rem; color: var(--primary-dark); text-decoration: none; }
    .cart-badge { position: absolute; top: -4px; right: -5px; background-color: var(--retro-orange, #f26419); color: white; font-size: 0.7rem; padding: 2px 6px; border-radius: 50%; font-weight: 600; }
    .btn-account { display: flex; align-items: center; gap: 8px; text-decoration: none; color: var(--primary-dark); font-weight: 500; }
    .btn-logout { color: #e63946; font-size: 1.1rem; transition: transform 0.2s ease; }
    .btn-logout:hover { transform: scale(1.1); }
</style>

<header>
    <div class="nav-wrapper">
        <a href="index.php" class="brand-logo"><i class="fa-solid fa-book-open logo-icon"></i>Bookish.</a>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="books.html">Books</a></li>
                <li><a href="competitions.php">Competitions</a></li>
                <li><a href="dealers.php">Dealers</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
        <div class="header-actions">
            <?php
            if (isset($_SESSION['name'])) {
                if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                    ?>
                    <a href="admin/dashboard.php" class="btn btn-admin" title="Admin Dashboard" style="background-color: #d9534f; color: white;">
                        <i class="fa-solid fa-user-shield"></i> Admin Panel
                    </a>
                    <?php
                } else {
                    ?>
                    <a href="cart.php" class="btn btn-icon" title="Cart">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span class="cart-badge">0</span>
                    </a>
                    <?php
                }
                ?>

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
