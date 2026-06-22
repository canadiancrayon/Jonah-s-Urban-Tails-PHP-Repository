<?php
session_start();
require_once '../scripts/mysql/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'shopping_cart.php';
    header('Location: login.php');
    exit;
}

$customer_id = $_SESSION['user_id'];
$pdo = getDBConnection();

if (!$pdo) {
    die("Database connection failed");
}

// ==============================================
// POST HANDLING (Update Cart or Empty Cart)
// ==============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Handle Empty Cart
    if (isset($_POST['empty_cart'])) {
        $stmt = $pdo->prepare("DELETE FROM my_ShoppingCart WHERE customer_id = ?");
        $stmt->execute([$customer_id]);
        header('Location: shopping_cart.php');
        exit;
    }
    
    // Handle Update Cart (update quantities)
    if (isset($_POST['update_cart'])) {
        $quantities = $_POST['quantity'] ?? [];
        $errors = [];
        
        foreach ($quantities as $product_id => $quantity) {
            $product_id = (int)$product_id;
            $quantity = trim($quantity);
            
            // Skip if quantity is empty
            if ($quantity === '' || $quantity === null) {
                continue;
            }
            
            // Validate it's a number
            if (!is_numeric($quantity)) {
                $errors[] = "Quantity must be a number.";
                continue;
            }
            
            $quantity = (int)$quantity;
            
            // Validate not negative
            if ($quantity < 0) {
                $errors[] = "Quantity cannot be negative.";
                continue;
            }
            
            if ($quantity == 0) {
                // Delete item if quantity is 0
                $stmt = $pdo->prepare("DELETE FROM my_ShoppingCart WHERE customer_id = ? AND product_id = ?");
                $stmt->execute([$customer_id, $product_id]);
            } else {
                // Update quantity
                $stmt = $pdo->prepare("UPDATE my_ShoppingCart SET quantity = ? WHERE customer_id = ? AND product_id = ?");
                $stmt->execute([$quantity, $customer_id, $product_id]);
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['cart_errors'] = $errors;
        }
        header('Location: shopping_cart.php');
        exit;
    }
}

// ==============================================
// GET HANDLING (Delete individual item)
// ==============================================
if (isset($_GET['delete'])) {
    $product_id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM my_ShoppingCart WHERE customer_id = ? AND product_id = ?");
    $stmt->execute([$customer_id, $product_id]);
    header('Location: shopping_cart.php');
    exit;
}

// ==============================================
// GET CART ITEMS
// ==============================================
$stmt = $pdo->prepare("
    SELECT c.*, p.product_name, p.product_description, p.unit_price
    FROM my_ShoppingCart c
    JOIN my_Products p ON c.product_id = p.product_id
    WHERE c.customer_id = ?
    ORDER BY c.added_date DESC
");
$stmt->execute([$customer_id]);
$cart_items = $stmt->fetchAll();

// Calculate grand total
$grand_total = 0;
foreach ($cart_items as $item) {
    $grand_total += $item['quantity'] * $item['unit_price'];
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
      
      <h2 class="w3-center">Shopping Cart</h2>
      
      <!-- Display success messages -->
      <?php if (isset($_SESSION['cart_message'])): ?>
        <div class="w3-panel w3-green w3-padding w3-center">
          <?php echo htmlspecialchars($_SESSION['cart_message']); ?>
        </div>
        <?php unset($_SESSION['cart_message']); ?>
      <?php endif; ?>
      
      <!-- Display error messages -->
      <?php if (isset($_SESSION['cart_errors'])): ?>
        <div class="w3-panel w3-red w3-padding">
          <?php foreach ($_SESSION['cart_errors'] as $error): ?>
            <p class="w3-center"><?php echo htmlspecialchars($error); ?></p>
          <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['cart_errors']); ?>
      <?php endif; ?>
      
      <?php if (empty($cart_items)): ?>
        <!-- Empty Cart Message -->
        <div class="w3-panel w3-light-gray w3-padding w3-center">
          <p>Your shopping cart is empty.</p>
          <p><a href="product_catalog.php" class="w3-button w3-blue w3-round">Return to Catalog</a></p>
        </div>
      <?php else: ?>
        <!-- Cart Items Form -->
        <form method="POST" action="">
          <?php foreach ($cart_items as $item): ?>
          <div class="w3-card-4 w3-margin-bottom" style="display: flex; flex-direction: row; align-items: center;">
            <!-- Product Icon -->
            <div style="width: 80px; height: 80px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center;">
              <span style="font-size: 40px;">🐾</span>
            </div>
            
            <!-- Product Info -->
            <div style="flex: 2; padding: 10px;">
              <h4 style="margin: 0 0 5px 0;"><?php echo htmlspecialchars($item['product_name']); ?></h4>
              <p style="font-size: 0.8em; margin: 0; color: #666;">
                <?php echo htmlspecialchars(substr($item['product_description'], 0, 80)); ?>
              </p>
            </div>
            
            <!-- Price -->
            <div style="width: 100px; padding: 10px; text-align: center;">
              <p style="margin: 0;"><strong>$<?php echo number_format($item['unit_price'], 2); ?></strong></p>
            </div>
            
            <!-- Quantity -->
            <div style="width: 120px; padding: 10px; text-align: center;">
              <label>Qty:</label>
              <input type="number" name="quantity[<?php echo $item['product_id']; ?>]" 
                     value="<?php echo $item['quantity']; ?>" 
                     min="0"
                     style="width: 60px; margin: 5px;">
            </div>
            
            <!-- Subtotal -->
            <div style="width: 100px; padding: 10px; text-align: center;">
              <p style="margin: 0;"><strong>$<?php echo number_format($item['quantity'] * $item['unit_price'], 2); ?></strong></p>
            </div>
            
            <!-- Delete Button -->
            <div style="width: 50px; padding: 10px; text-align: center;">
              <a href="?delete=<?php echo $item['product_id']; ?>" 
                 class="w3-button w3-red w3-round-small"
                 onclick="return confirm('Remove this item from cart?')">✗</a>
            </div>
          </div>
          <?php endforeach; ?>
          
          <!-- Grand Total -->
          <div class="w3-card-4 w3-padding w3-right-align w3-margin-top">
            <h3>Grand Total: $<?php echo number_format($grand_total, 2); ?></h3>
          </div>
          
          <!-- Buttons Section -->
          <div class="w3-center w3-margin-top">
            <!-- Update Cart Button -->
            <button type="submit" name="update_cart" class="w3-button w3-green w3-round">Update Cart</button>
          </div>
        </form>
        
        <!-- Empty Cart Button (OUTSIDE the main form) -->
        <div class="w3-center w3-margin-top">
          <form method="POST" action="" style="display: inline;">
            <input type="hidden" name="empty_cart" value="1">
            <button type="submit" class="w3-button w3-red w3-round" 
                    onclick="return confirm('Empty your entire cart? This cannot be undone.')">Empty Cart</button>
          </form>
          
          <!-- Navigation Buttons -->
          <a href="product_catalog.php" class="w3-button w3-blue w3-round">Return to Catalog</a>
          <a href="checkout.php" class="w3-button w3-blue w3-round">Proceed to Checkout</a>
        </div>
        
      <?php endif; ?>
      
    </div>
  </main>
  
  <?php include '../common/footer.php'; ?>
</body>
</html>
