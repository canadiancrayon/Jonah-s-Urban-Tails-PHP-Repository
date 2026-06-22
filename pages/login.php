<?php
// Start session at the VERY TOP
session_start();

// Store redirect URL if coming from cart or checkout
if (isset($_GET['redirect'])) {
    $_SESSION['redirect_after_login'] = $_GET['redirect'];
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../common/head.php'; ?>

<body class="my_max_site_width w3-auto">
  <?php include '../common/banner.php'; ?>
  <?php include '../common/menus.php'; ?>
  
  <main class="w3-container">
    <div class="w3-container w3-border-left w3-border-right
                 w3-border-black w3-light-grey w3-padding">
      
      <h2 class="w3-center">Customer Login</h2>
      
      <?php
      // Display any error messages from previous submission
      if (isset($_SESSION['login_errors'])) {
          echo '<div class="w3-panel w3-red w3-padding w3-center">';
          foreach ($_SESSION['login_errors'] as $error) {
              echo '<p class="w3-center">' . htmlspecialchars($error) . '</p>';
          }
          echo '</div>';
          unset($_SESSION['login_errors']);
      }
      
      // Display info messages (like logout confirmation)
      if (isset($_SESSION['info_message'])) {
          echo '<div class="w3-panel w3-blue w3-padding w3-center">';
          echo '<p class="w3-center">' . htmlspecialchars($_SESSION['info_message']) . '</p>';
          echo '</div>';
          unset($_SESSION['info_message']);
      }
      
      // Get previously entered data to retain values
      $old = $_SESSION['old_login'] ?? [];
      unset($_SESSION['old_login']);
      ?>
      
      <!-- Centered login form -->
      <div style="display: flex; justify-content: center; width: 100%;">
        <div style="width: 350px;">
          <form action="../scripts/process_login.php" method="POST" 
                class="w3-container w3-card-4 w3-white w3-padding">
            
            <!-- Login Name -->
            <div class="w3-row w3-section">
              <label for="login_name">Login Name:</label>
              <input type="text" id="login_name" name="login_name" 
                     class="w3-input w3-border slim-input" 
                     style="width: 100%; padding-top: 4px; padding-bottom: 4px; line-height: 1.2; height: 34px;"
                     value="<?php echo htmlspecialchars($old['login_name'] ?? ''); ?>"
                     placeholder="Enter your username"
                     required>
            </div>
            
            <!-- Password -->
            <div class="w3-row w3-section">
              <label for="password">Password:</label>
              <input type="password" id="password" name="password" 
                     class="w3-input w3-border slim-input" 
                     style="width: 100%; padding-top: 4px; padding-bottom: 4px; line-height: 1.2; height: 34px;"
                     placeholder="Enter your password"
                     required>
            </div>
            
            <!-- Submit Button -->
            <div class="w3-row w3-section w3-center">
              <button type="submit" class="w3-button w3-blue w3-round" 
                      style="width: 100%; padding: 8px 16px;">Login</button>
            </div>
            
            <div class="w3-row w3-section w3-center">
              <p>Not registered? <a href="register.php">Register here</a></p>
              <p><a href="product_catalog.php">Continue browsing as guest</a></p>
            </div>
            
          </form>
        </div>
      </div>
      
    </div>
  </main>
  
  <?php include '../common/footer.php'; ?>
  
  <style>
  /* Slimmer input fields */
  .slim-input {
    padding-top: 4px !important;
    padding-bottom: 4px !important;
    line-height: 1.2 !important;
    height: auto !important;
    min-height: 30px !important;
  }
  </style>
</body>
</html>
