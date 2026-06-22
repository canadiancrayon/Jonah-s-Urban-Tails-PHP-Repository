<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="w3-container">
    <div class="w3-border w3-border-black w3-light-grey">
        <div id="logo" class="w3-half">
            <img src="images/urban_tails_logo.png" alt="Urban Tails Logo" class="w3-image responsive-logo">
        </div>
        <div class="w3-half w3-right-align">
            <div class="w3-panel">
                <div class="w3-container">
                    <div class="w3-twothird w3-center">
                        <?php if (isset($_SESSION['user_name'])): ?>
                            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
                        <?php else: ?>
                            <h2>Welcome!</h2>
                        <?php endif; ?>
                        <?php include 'scripts/time.php'; ?>
                    </div>
                    <div class="w3-third w3-center w3-padding">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a class='w3-button w3-blue w3-round' href='scripts/logout.php'>Log out</a>
                        <?php else: ?>
                            <a class='w3-button w3-blue w3-round' href='pages/login.php'>Log in</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php include 'scripts/daily_quote.php'; ?>
            </div>
        </div>
    </div>
</header>
