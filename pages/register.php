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
      
      <h2 class="w3-center">Customer Registration</h2>
      <p class="w3-center">Please fill out all required information to register.</p>
      
      <?php
      // Display any error messages from previous submission
      if (isset($_SESSION['registration_errors'])) {
          echo '<div class="w3-panel w3-red w3-padding w3-center">';
          foreach ($_SESSION['registration_errors'] as $error) {
              echo '<p class="w3-center">' . htmlspecialchars($error) . '</p>';
          }
          echo '</div>';
          unset($_SESSION['registration_errors']);
      }
      
      // Get previously entered data to retain values
      $old = $_SESSION['old_input'] ?? [];
      unset($_SESSION['old_input']);
      ?>
      
      <!-- Centered form container -->
      <div style="display: flex; justify-content: center; width: 100%;">
        <div style="width: 700px;">
          <form id="registrationForm" action="../scripts/process_registration.php" 
                method="POST" class="w3-container">
            
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
                <label for="first_name">First Name:</label>
              </div>
              <div class="w3-col" style="width:300px">
                <input type="text" id="first_name" name="first_name" 
                       class="w3-input w3-border slim-input" style="width:100%"
                       pattern="[A-Za-z\s\-']{2,50}"
                       title="First name should contain only letters, spaces, hyphens, or apostrophes (2-50 characters)"
                       placeholder="Enter your first name"
                       value="<?php echo htmlspecialchars($old['first_name'] ?? ''); ?>"
                       required>
              </div>
              <div class="w3-rest w3-padding-left">
                <span class="w3-text-red">*</span>
              </div>
            </div>
            
            <!-- Middle Initial -->
            <div class="w3-row w3-section">
              <div class="w3-col" style="width:150px">
                <label for="middle_initial">Middle Initial:</label>
              </div>
              <div class="w3-col" style="width:300px">
                <input type="text" id="middle_initial" name="middle_initial" 
                       class="w3-input w3-border slim-input" style="width:100%"
                       pattern="[A-Za-z]?"
                       title="Single letter if provided"
                       placeholder="Optional"
                       value="<?php echo htmlspecialchars($old['middle_initial'] ?? ''); ?>"
                       maxlength="1">
              </div>
            </div>
            
            <!-- Last Name -->
            <div class="w3-row w3-section">
              <div class="w3-col" style="width:150px">
                <label for="last_name">Last Name:</label>
              </div>
              <div class="w3-col" style="width:300px">
                <input type="text" id="last_name" name="last_name" 
                       class="w3-input w3-border slim-input" style="width:100%"
                       pattern="[A-Za-z\s\-']{2,50}"
                       title="Last name should contain only letters, spaces, hyphens, or apostrophes (2-50 characters)"
                       placeholder="Enter your last name"
                       value="<?php echo htmlspecialchars($old['last_name'] ?? ''); ?>"
                       required>
              </div>
              <div class="w3-rest w3-padding-left">
                <span class="w3-text-red">*</span>
              </div>
            </div>
            
            <!-- Gender -->
            <div class="w3-row w3-section">
              <div class="w3-col" style="width:150px">
                <label>Gender:</label>
              </div>
              <div class="w3-col" style="width:300px;">
                <span style="margin-right: 15px;">
                  <input type="radio" name="gender" value="Male" 
                         <?php echo ($old['gender'] ?? '') == 'Male' ? 'checked' : ''; ?> required> Male
                </span>
                <span style="margin-right: 15px;">
                  <input type="radio" name="gender" value="Female" 
                         <?php echo ($old['gender'] ?? '') == 'Female' ? 'checked' : ''; ?>> Female
                </span>
                <span>
                  <input type="radio" name="gender" value="Other" 
                         <?php echo ($old['gender'] ?? '') == 'Other' ? 'checked' : ''; ?>> Other
                  <span class="w3-text-red" style="margin-left: 3px;">*</span>
                </span>
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
                       title="Please enter a valid email address"
                       placeholder="your@email.com"
                       value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>"
                       required>
              </div>
              <div class="w3-rest w3-padding-left">
                <span class="w3-text-red">*</span>
              </div>
            </div>
            
            <!-- Phone -->
            <div class="w3-row w3-section">
              <div class="w3-col" style="width:150px">
                <label for="phone">Phone:</label>
              </div>
              <div class="w3-col" style="width:300px">
                <input type="tel" id="phone" name="phone" 
                       class="w3-input w3-border slim-input" style="width:100%"
                       pattern="[0-9\-\(\)\s\+]{10,20}"
                       title="Please enter a valid phone number"
                       placeholder="(902) 555-0123"
                       value="<?php echo htmlspecialchars($old['phone'] ?? ''); ?>">
              </div>
            </div>
            
            <!-- Street Address -->
            <div class="w3-row w3-section">
              <div class="w3-col" style="width:150px">
                <label for="street_address">Street Address:</label>
              </div>
              <div class="w3-col" style="width:300px">
                <input type="text" id="street_address" name="street_address" 
                       class="w3-input w3-border slim-input" style="width:100%"
                       placeholder="123 Main Street"
                       value="<?php echo htmlspecialchars($old['street_address'] ?? ''); ?>"
                       required>
              </div>
              <div class="w3-rest w3-padding-left">
                <span class="w3-text-red">*</span>
              </div>
            </div>
            
            <!-- City -->
            <div class="w3-row w3-section">
              <div class="w3-col" style="width:150px">
                <label for="city">City:</label>
              </div>
              <div class="w3-col" style="width:300px">
                <input type="text" id="city" name="city" 
                       class="w3-input w3-border slim-input" style="width:100%"
                       placeholder="Halifax"
                       value="<?php echo htmlspecialchars($old['city'] ?? ''); ?>"
                       required>
              </div>
              <div class="w3-rest w3-padding-left">
                <span class="w3-text-red">*</span>
              </div>
            </div>
            
            <!-- Region/Province -->
            <div class="w3-row w3-section">
              <div class="w3-col" style="width:150px">
                <label for="region">Province:</label>
              </div>
              <div class="w3-col" style="width:300px">
                <select id="region" name="region" 
                        class="w3-select w3-border slim-select" style="width:100%" required>
                  <option value="" disabled <?php echo !isset($old['region']) ? 'selected' : ''; ?>>-- Select --</option>
                  <option value="AB" <?php echo ($old['region'] ?? '') == 'AB' ? 'selected' : ''; ?>>Alberta</option>
                  <option value="BC" <?php echo ($old['region'] ?? '') == 'BC' ? 'selected' : ''; ?>>British Columbia</option>
                  <option value="MB" <?php echo ($old['region'] ?? '') == 'MB' ? 'selected' : ''; ?>>Manitoba</option>
                  <option value="NB" <?php echo ($old['region'] ?? '') == 'NB' ? 'selected' : ''; ?>>New Brunswick</option>
                  <option value="NL" <?php echo ($old['region'] ?? '') == 'NL' ? 'selected' : ''; ?>>Newfoundland and Labrador</option>
                  <option value="NS" <?php echo ($old['region'] ?? '') == 'NS' ? 'selected' : ''; ?>>Nova Scotia</option>
                  <option value="NT" <?php echo ($old['region'] ?? '') == 'NT' ? 'selected' : ''; ?>>Northwest Territories</option>
                  <option value="NU" <?php echo ($old['region'] ?? '') == 'NU' ? 'selected' : ''; ?>>Nunavut</option>
                  <option value="ON" <?php echo ($old['region'] ?? '') == 'ON' ? 'selected' : ''; ?>>Ontario</option>
                  <option value="PE" <?php echo ($old['region'] ?? '') == 'PE' ? 'selected' : ''; ?>>Prince Edward Island</option>
                  <option value="QC" <?php echo ($old['region'] ?? '') == 'QC' ? 'selected' : ''; ?>>Quebec</option>
                  <option value="SK" <?php echo ($old['region'] ?? '') == 'SK' ? 'selected' : ''; ?>>Saskatchewan</option>
                  <option value="YT" <?php echo ($old['region'] ?? '') == 'YT' ? 'selected' : ''; ?>>Yukon</option>
                </select>
              </div>
              <div class="w3-rest w3-padding-left">
                <span class="w3-text-red">*</span>
              </div>
            </div>
            
            <!-- Postal Code -->
            <div class="w3-row w3-section">
              <div class="w3-col" style="width:150px">
                <label for="postal_code">Postal Code:</label>
              </div>
              <div class="w3-col" style="width:300px">
                <input type="text" id="postal_code" name="postal_code" 
                       class="w3-input w3-border slim-input" style="width:100%"
                       pattern="[A-Za-z][0-9][A-Za-z] ?[0-9][A-Za-z][0-9]"
                       title="Please enter a valid Canadian postal code (e.g., B3J 2K9)"
                       placeholder="B3J 2K9"
                       value="<?php echo htmlspecialchars($old['postal_code'] ?? ''); ?>"
                       required>
              </div>
              <div class="w3-rest w3-padding-left">
                <span class="w3-text-red">*</span>
              </div>
            </div>
            
            <!-- Desired Login Name -->
            <div class="w3-row w3-section">
              <div class="w3-col" style="width:150px">
                <label for="login_name">Desired Login:</label>
              </div>
              <div class="w3-col" style="width:300px">
                <input type="text" id="login_name" name="login_name" 
                       class="w3-input w3-border slim-input" style="width:100%"
                       pattern="[A-Za-z][A-Za-z0-9_]{3,20}"
                       title="Login name must start with a letter and contain only letters, numbers, and underscores (4-20 characters)"
                       placeholder="john_doe"
                       value="<?php echo htmlspecialchars($old['login_name'] ?? ''); ?>"
                       required>
              </div>
              <div class="w3-rest w3-padding-left">
                <span class="w3-text-red">*</span>
              </div>
            </div>
            
            <!-- Password -->
            <div class="w3-row w3-section">
              <div class="w3-col" style="width:150px">
                <label for="password">Password:</label>
              </div>
              <div class="w3-col" style="width:300px">
                <input type="password" id="password" name="password" 
                       class="w3-input w3-border slim-input" style="width:100%"
                       pattern=".{8,}"
                       title="Password must be at least 8 characters"
                       placeholder="********"
                       required>
              </div>
              <div class="w3-rest w3-padding-left">
                <span class="w3-text-red">*</span>
              </div>
            </div>
            
            <!-- Confirm Password -->
            <div class="w3-row w3-section">
              <div class="w3-col" style="width:150px">
                <label for="confirm_password">Confirm Password:</label>
              </div>
              <div class="w3-col" style="width:300px">
                <input type="password" id="confirm_password" name="confirm_password" 
                       class="w3-input w3-border slim-input" style="width:100%"
                       placeholder="********"
                       required>
              </div>
              <div class="w3-rest w3-padding-left">
                <span class="w3-text-red">*</span>
              </div>
            </div>
            
            <!-- Submit Button -->
            <div class="w3-row w3-section">
              <div class="w3-col" style="width:150px">
                &nbsp;
              </div>
              <div class="w3-col" style="width:300px" class="w3-center">
                <button type="submit" class="w3-button w3-blue w3-round">Register</button>
                <button type="reset" class="w3-button w3-gray w3-round">Reset</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      
      <p class="w3-center">Already registered? <a href="login.php">Login here</a>.</p>
    </div>
  </main>
  
  <?php include '../common/footer.php'; ?>
  
  <script>
  // Client-side validation
  document.getElementById('registrationForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('confirm_password').value;
    
    if (password !== confirm) {
      e.preventDefault();
      alert('Passwords do not match!');
    }
  });
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
