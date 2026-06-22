<!DOCTYPE html>
<html lang="en">
<?php include '../common/head.php'; ?>

<body class="my_max_site_width w3-auto">
  <?php include '../common/banner.php'; ?>
  <?php include '../common/menus.php'; ?>
  
  <main class="w3-container">
    <div class="w3-container w3-border-left w3-border-right
                 w3-border-black w3-light-grey">
      
      <h3 class="w3-padding">Welcome to our booking page!</h3>
      
      <div class="w3-padding">
        <p>
          We do a variety of pet-sitting services. For your browsing convenience, please select one of the following options:
        </p>
        
        <ul class="w3-ul">
          <li>
            <strong>Browse Services:</strong> To browse our current booking options, 
            <a href="product_catalog.php">click here</a>.
          </li>
          <li>
            <strong>Ready to Book:</strong> Already have a username and password? 
            <br>To log in to our online booking portal, 
            <a href="login.php">click here</a>.
          </li>
          <li>
            <strong>New Customer:</strong> Need to register? 
            <br>To register (you only need to do it once), 
            <a href="register.php">click here</a>.
          </li>
          <li>
            <strong>View Cart:</strong> Check your current shopping cart, 
            <a href="shopping_cart.php">click here</a>.
          </li>
          <li>
            <strong>Checkout:</strong> Complete your order, 
            <a href="checkout.php">click here</a>.
          </li>
        </ul>
        
        <div class="w3-panel w3-light-blue w3-padding">
          <p><strong>Note:</strong> Some features may not be active yet. We're constantly improving our services!</p>
        </div>
      </div>
      
    </div>
  </main>
  
  <?php include '../common/footer.php'; ?>
</body>
</html>
