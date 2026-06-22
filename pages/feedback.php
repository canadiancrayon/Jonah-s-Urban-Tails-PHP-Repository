<?php
// Start session at the VERY TOP
session_start();
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
      
      <h2 class="w3-center">Feedback Form</h2>
      <p class="w3-center">We'd love to hear from you! Please fill out this form.</p>
      
      <?php
      // Display any error messages from previous submission
      if (isset($_SESSION['feedback_errors'])) {
          echo '<div class="w3-panel w3-red w3-padding w3-center">';
          foreach ($_SESSION['feedback_errors'] as $error) {
              echo '<p class="w3-center">' . htmlspecialchars($error) . '</p>';
          }
          echo '</div>';
          unset($_SESSION['feedback_errors']);
      }
      
      // Get previously entered data to retain values
      $old = $_SESSION['feedback_data'] ?? [];
      unset($_SESSION['feedback_data']);
      ?>
      
      <form id="feedbackForm" action="../scripts/process_feedback.php" method="POST" 
            onsubmit="return validateForm()" class="w3-container">
        
        <!-- Salutation -->
        <div class="w3-row w3-section">
          <div class="w3-col" style="width:150px">
            <label for="salutation">Salutation:</label>
          </div>
          <div class="w3-col" style="width:300px">
            <select id="salutation" name="salutation" 
                    class="w3-select w3-border slim-select" style="width:100%" required>
              <option value="" disabled <?php echo !isset($old['salutation']) ? 'selected' : ''; ?>>-- Select --</option>
              <option value="Mr" <?php echo ($old['salutation'] ?? '') == 'Mr' ? 'selected' : ''; ?>>Mr</option>
              <option value="Mrs" <?php echo ($old['salutation'] ?? '') == 'Mrs' ? 'selected' : ''; ?>>Mrs</option>
              <option value="Ms" <?php echo ($old['salutation'] ?? '') == 'Ms' ? 'selected' : ''; ?>>Ms</option>
              <option value="Dr" <?php echo ($old['salutation'] ?? '') == 'Dr' ? 'selected' : ''; ?>>Dr</option>
              <option value="Prof" <?php echo ($old['salutation'] ?? '') == 'Prof' ? 'selected' : ''; ?>>Prof</option>
            </select>
          </div>
          <div class="w3-rest w3-padding-left">
            <span class="w3-text-red">*</span>
          </div>
        </div>
        
        <!-- First Name -->
        <div class="w3-row w3-section">
          <div class="w3-col" style="width:150px">
            <label for="firstName">First Name:</label>
          </div>
          <div class="w3-col" style="width:300px">
            <input type="text" id="firstName" name="firstName" 
                   class="w3-input w3-border slim-input" style="width:100%"
                   pattern="[A-Za-z\s\-']{2,50}" 
                   title="First name should contain only letters, spaces, hyphens, or apostrophes (2-50 characters)"
                   placeholder="Enter your first name"
                   value="<?php echo htmlspecialchars($old['firstName'] ?? ''); ?>"
                   required>
          </div>
          <div class="w3-rest w3-padding-left">
            <span class="w3-text-red">*</span>
          </div>
        </div>
        
        <!-- Last Name -->
        <div class="w3-row w3-section">
          <div class="w3-col" style="width:150px">
            <label for="lastName">Last Name:</label>
          </div>
          <div class="w3-col" style="width:300px">
            <input type="text" id="lastName" name="lastName" 
                   class="w3-input w3-border slim-input" style="width:100%"
                   pattern="[A-Za-z\s\-']{2,50}" 
                   title="Last name should contain only letters, spaces, hyphens, or apostrophes (2-50 characters)"
                   placeholder="Enter your last name"
                   value="<?php echo htmlspecialchars($old['lastName'] ?? ''); ?>"
                   required>
          </div>
          <div class="w3-rest w3-padding-left">
            <span class="w3-text-red">*</span>
          </div>
        </div>
        
        <!-- Email -->
        <div class="w3-row w3-section">
          <div class="w3-col" style="width:150px">
            <label for="email">Email:</label>
          </div>
          <div class="w3-col" style="width:300px">
            <input type="email" id="email" name="email" 
                   class="w3-input w3-border slim-input" style="width:100%"
                   pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" 
                   title="Please enter a valid email address (e.g., name@domain.com)"
                   placeholder="your@email.com"
                   value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>"
                   required>
          </div>
          <div class="w3-rest w3-padding-left">
            <span class="w3-text-red">*</span>
          </div>
        </div>
        
        <!-- Phone (optional) -->
        <div class="w3-row w3-section">
          <div class="w3-col" style="width:150px">
            <label for="phone">Phone:</label>
          </div>
          <div class="w3-col" style="width:300px">
            <input type="tel" id="phone" name="phone" 
                   class="w3-input w3-border slim-input" style="width:100%"
                   pattern="[0-9\-\(\)\s\+]{10,20}" 
                   title="Please enter a valid phone number (10-20 digits, may include spaces, hyphens, parentheses)"
                   placeholder="(902) 555-0123"
                   value="<?php echo htmlspecialchars($old['phone'] ?? ''); ?>">
          </div>
        </div>
        
        <!-- Subject -->
        <div class="w3-row w3-section">
          <div class="w3-col" style="width:150px">
            <label for="subject">Subject:</label>
          </div>
          <div class="w3-col" style="width:300px">
            <input type="text" id="subject" name="subject" 
                   class="w3-input w3-border slim-input" style="width:100%"
                   placeholder="Brief subject"
                   value="<?php echo htmlspecialchars($old['subject'] ?? ''); ?>"
                   required>
          </div>
          <div class="w3-rest w3-padding-left">
            <span class="w3-text-red">*</span>
          </div>
        </div>
        
        <!-- Comments -->
        <div class="w3-row w3-section">
          <div class="w3-col" style="width:150px">
            <label for="comments">Comments:</label>
          </div>
          <div class="w3-col" style="width:300px">
            <textarea id="comments" name="comments" rows="5" 
                      class="w3-input w3-border" style="width:100%; resize:vertical;"
                      placeholder="Your comments here..." required><?php echo htmlspecialchars($old['comments'] ?? ''); ?></textarea>
          </div>
          <div class="w3-rest w3-padding-left">
            <span class="w3-text-red">*</span>
          </div>
        </div>
        
        <!-- Follow-up Checkbox -->
        <div class="w3-row w3-section">
          <div class="w3-col" style="width:150px">
            &nbsp;
          </div>
          <div class="w3-col" style="width:300px">
            <input type="checkbox" id="followup" name="followup" 
                   <?php echo isset($old['followup']) ? 'checked' : ''; ?> class="w3-check">
            <label for="followup">Please follow up with me</label>
          </div>
        </div>
        
        <!-- Submit Button -->
        <div class="w3-row w3-section">
          <div class="w3-col" style="width:150px">
            &nbsp;
          </div>
          <div class="w3-col" style="width:300px">
            <button type="submit" class="w3-button w3-blue w3-round">Submit Feedback</button>
            <button type="reset" class="w3-button w3-gray w3-round">Reset</button>
          </div>
        </div>
      </form>
      
    </div>
  </main>
  
  <?php include '../common/footer.php'; ?>
  
  <script>
  function validateForm() {
    let isValid = true;
    
    // Clear previous errors
    document.querySelectorAll('.w3-text-red').forEach(el => el.innerHTML = '');
    
    // Salutation
    const salutation = document.getElementById('salutation');
    if (!salutation.value) {
      alert('Please select a salutation');
      isValid = false;
    }
    
    // First Name
    const firstName = document.getElementById('firstName');
    const firstNamePattern = /^[A-Za-z\s\-']{2,50}$/;
    if (!firstName.value) {
      alert('First name is required');
      isValid = false;
    } else if (!firstNamePattern.test(firstName.value)) {
      alert('Invalid first name format');
      isValid = false;
    }
    
    // Last Name
    const lastName = document.getElementById('lastName');
    const lastNamePattern = /^[A-Za-z\s\-']{2,50}$/;
    if (!lastName.value) {
      alert('Last name is required');
      isValid = false;
    } else if (!lastNamePattern.test(lastName.value)) {
      alert('Invalid last name format');
      isValid = false;
    }
    
    // Email
    const email = document.getElementById('email');
    const emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i;
    if (!email.value) {
      alert('Email is required');
      isValid = false;
    } else if (!emailPattern.test(email.value)) {
      alert('Invalid email format');
      isValid = false;
    }
    
    // Phone (optional, but validate if provided)
    const phone = document.getElementById('phone');
    if (phone.value) {
      const phonePattern = /^[0-9\-\(\)\s\+]{10,20}$/;
      if (!phonePattern.test(phone.value)) {
        alert('Invalid phone format');
        isValid = false;
      }
    }
    
    // Subject
    const subject = document.getElementById('subject');
    if (!subject.value.trim()) {
      alert('Subject is required');
      isValid = false;
    }
    
    // Comments
    const comments = document.getElementById('comments');
    if (!comments.value.trim()) {
      alert('Comments are required');
      isValid = false;
    }
    
    return isValid;
  }
  </script>
  
  <style>
  /* Slimmer input fields */
  .slim-input {
    padding-top: 4px !important;
    padding-bottom: 4px !important;
    line-height: 1.2 !important;
    height: auto !important;
    min-height: 30px !important;
  }
  
  .slim-select {
    padding-top: 4px !important;
    padding-bottom: 4px !important;
    line-height: 1.2 !important;
    height: 34px !important;
  }
  </style>
</body>
</html>
