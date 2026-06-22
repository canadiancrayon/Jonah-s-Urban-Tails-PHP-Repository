<!DOCTYPE html>
<html lang="en">
<?php include '../common/head.php'; ?>

<body class="my_max_site_width w3-auto">
  <?php include '../common/banner.php'; ?>
  <?php include '../common/menus.php'; ?>
  
  <main class="w3-container">
    <div class="w3-container w3-border-left w3-border-right
                 w3-border-black w3-light-grey w3-padding w3-center">
      
      <div class="w3-panel w3-green w3-round w3-padding w3-margin-top">
        <h1 class="w3-jumbo">✓</h1>
        <h2>Thank You for Your Feedback!</h2>
      </div>
      
      <div class="w3-card-4 w3-white w3-padding w3-margin">
        <?php
        // Start session to get the user's name and email
        session_start();
        
        // Get data from session (set in process_feedback.php)
        $name = $_SESSION['feedback_name'] ?? 'Valued Customer';
        $email = $_SESSION['feedback_email'] ?? '';
        
        // Clear the session data so it doesn't show again on refresh
        unset($_SESSION['feedback_name']);
        unset($_SESSION['feedback_email']);
        ?>
        
        <p class="w3-large">Dear <?php echo htmlspecialchars($name); ?>,</p>
        
        <p>We have successfully received your feedback. Thank you for taking the time to share your thoughts with us!</p>
        
        <div class="w3-panel w3-light-blue w3-left-align w3-padding">
          <p><strong>What happens next?</strong></p>
          <ul class="w3-ul">
            <li>✓ A confirmation email has been sent to: <strong><?php echo htmlspecialchars($email); ?></strong></li>
            <li>✓ Our team (u50@mail.cs-smu.ca) has been notified of your feedback</li>
            <li>✓ Your feedback has been saved to our data directory</li>
            <li>✓ We'll follow up with you if you requested it</li>
          </ul>
        </div>
        
        <p>If you have any urgent questions, please contact us directly at <strong>jonahsurbantails@gmail.com</strong> or call <strong>(902) 441-0813</strong>.</p>
        
        <div class="w3-margin-top">
          <a href="../my_business.php" class="w3-button w3-blue w3-round">Return to Home</a>
          <a href="feedback.php" class="w3-button w3-gray w3-round">Submit Another Feedback</a>
        </div>
      </div>
      
    </div>
  </main>
  
  <?php include '../common/footer.php'; ?>
  
  <style>
  .w3-jumbo {
    font-size: 64px !important;
    margin: 0;
    line-height: 1.2;
  }
  </style>
</body>
</html>
