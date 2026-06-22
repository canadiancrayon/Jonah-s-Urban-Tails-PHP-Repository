<?php
session_start();
require_once '../scripts/mysql/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'checkout.php';
    header('Location: login.php');
    exit;
}

$customer_id = $_SESSION['user_id'];
$pdo = getDBConnection();

if (!$pdo) {
    die("Database connection failed");
}

// Get cart items
$stmt = $pdo->prepare("
    SELECT c.*, p.product_name, p.unit_price
    FROM my_ShoppingCart c
    JOIN my_Products p ON c.product_id = p.product_id
    WHERE c.customer_id = ?
");
$stmt->execute([$customer_id]);
$cart_items = $stmt->fetchAll();

if (empty($cart_items)) {
    header('Location: shopping_cart.php');
    exit;
}

// Calculate grand total
$grand_total = 0;
foreach ($cart_items as $item) {
    $grand_total += $item['quantity'] * $item['unit_price'];
}

// Process checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    try {
        $pdo->beginTransaction();
        
        // Create order
        $stmt = $pdo->prepare("INSERT INTO my_Orders (customer_id, order_date, total_amount, order_status) VALUES (?, NOW(), ?, 'completed')");
        $stmt->execute([$customer_id, $grand_total]);
        $order_id = $pdo->lastInsertId();
        
        // Create order items
        foreach ($cart_items as $item) {
            $subtotal = $item['quantity'] * $item['unit_price'];
            $stmt = $pdo->prepare("INSERT INTO my_OrderItems (order_id, product_id, quantity, price_at_time, subtotal) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['unit_price'], $subtotal]);
        }
        
        // ==============================================
        // EMAIL FUNCTION CALLS GO HERE
        // ==============================================
        
        // Get customer info for email
        $stmt = $pdo->prepare("SELECT first_name, last_name, email FROM my_Customers WHERE customer_id = ?");
        $stmt->execute([$customer_id]);
        $customer = $stmt->fetch();
        
        // Build order summary for email
        $order_summary = "";
        foreach ($cart_items as $item) {
            $order_summary .= $item['quantity'] . " x " . $item['product_name'] . " - $" . number_format($item['quantity'] * $item['unit_price'], 2) . "\n";
        }
        
        // 1. Send confirmation email to the customer
        $to_user = $customer['email'];
        $user_subject = "Order Confirmation #" . $order_id . " - Jonah's Urban Tails";
        $user_message = "Dear " . $customer['first_name'] . ",\n\n";
        $user_message .= "Thank you for your order! Here is your confirmation:\n\n";
        $user_message .= "Order #: " . $order_id . "\n";
        $user_message .= "Order Date: " . date('Y-m-d H:i:s') . "\n\n";
        $user_message .= "Order Summary:\n";
        $user_message .= "----------------------------------------\n";
        $user_message .= $order_summary;
        $user_message .= "----------------------------------------\n";
        $user_message .= "Grand Total: $" . number_format($grand_total, 2) . "\n\n";
        $user_message .= "Thank you for choosing Jonah's Urban Tails!\n";
        $user_message .= "We will contact you shortly to confirm your booking.\n\n";
        $user_message .= "Best regards,\n";
        $user_message .= "Jonah's Urban Tails Team";
        
        $user_headers = "From: u07@mail.cs-smu.ca\r\n";
        $user_headers .= "Reply-To: u07@mail.cs-smu.ca\r\n";
        $user_headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        
        // 2. Send notification email to business (marker)
        $to_business = "u50@mail.cs-smu.ca";  // Marker's email for marking
        $business_subject = "New Order #" . $order_id . " - Jonah's Urban Tails";
        $business_message = "A new order has been placed!\n\n";
        $business_message .= "Order #: " . $order_id . "\n";
        $business_message .= "Customer: " . $customer['first_name'] . " " . $customer['last_name'] . "\n";
        $business_message .= "Email: " . $customer['email'] . "\n";
        $business_message .= "Order Date: " . date('Y-m-d H:i:s') . "\n\n";
        $business_message .= "Order Summary:\n";
        $business_message .= "----------------------------------------\n";
        $business_message .= $order_summary;
        $business_message .= "----------------------------------------\n";
        $business_message .= "Grand Total: $" . number_format($grand_total, 2) . "\n";
        
        $business_headers = "From: " . $customer['email'] . "\r\n";
        $business_headers .= "Reply-To: " . $customer['email'] . "\r\n";
        
        // Add the -f parameter for better deliverability
        $additional_params = "-fu07@mail.cs-smu.ca";
        
        // Send the emails
        $user_mail_sent = mail($to_user, $user_subject, $user_message, $user_headers, $additional_params);
        $business_mail_sent = mail($to_business, $business_subject, $business_message, $business_headers, $additional_params);
        
        // Optional: Log email results for debugging
        error_log("Checkout - Order #$order_id - User email sent: " . ($user_mail_sent ? 'Yes' : 'No'));
        error_log("Checkout - Order #$order_id - Business email sent: " . ($business_mail_sent ? 'Yes' : 'No'));
        
        // ==============================================
        // END OF EMAIL FUNCTIONS
        // ==============================================
        
        // Clear shopping cart
        $stmt = $pdo->prepare("DELETE FROM my_ShoppingCart WHERE customer_id = ?");
        $stmt->execute([$customer_id]);
        
        $pdo->commit();
        
        // Store receipt info in session
        $_SESSION['receipt'] = [
            'order_id' => $order_id,
            'items' => $cart_items,
            'grand_total' => $grand_total,
            'order_date' => date('Y-m-d H:i:s')
        ];
        
        header('Location: receipt.php');
        exit;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $error_message = $e->getMessage();
    }
}

// Get customer info for display
$stmt = $pdo->prepare("SELECT first_name, last_name, email FROM my_Customers WHERE customer_id = ?");
$stmt->execute([$customer_id]);
$customer = $stmt->fetch();
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
      
      <h2 class="w3-center">Checkout</h2>
      
      <?php if (isset($error_message)): ?>
        <div class="w3-panel w3-red w3-padding w3-center">
          <?php echo htmlspecialchars($error_message); ?>
        </div>
      <?php endif; ?>
      
      <div class="w3-card-4 w3-padding w3-margin-bottom">
        <h3>Shipping Information</h3>
        <p><strong><?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?></strong></p>
        <p><?php echo htmlspecialchars($customer['email']); ?></p>
      </div>
      
      <h3>Order Summary</h3>
      
      <?php foreach ($cart_items as $item): ?>
      <div class="w3-card-4 w3-margin-bottom" style="display: flex; justify-content: space-between; align-items: center; padding: 10px;">
        <div>
          <strong><?php echo htmlspecialchars($item['product_name']); ?></strong>
          <span style="color: #666;">x <?php echo $item['quantity']; ?></span>
        </div>
        <div>
          $<?php echo number_format($item['quantity'] * $item['unit_price'], 2); ?>
        </div>
      </div>
      <?php endforeach; ?>
      
      <div class="w3-card-4 w3-padding w3-right-align w3-margin-bottom">
        <h3>Grand Total: $<?php echo number_format($grand_total, 2); ?></h3>
      </div>
      
      <div class="w3-center w3-margin-top">
        <form method="POST" action="" style="display: inline;">
          <button type="submit" name="confirm_order" class="w3-button w3-green w3-round" 
                  onclick="return confirm('Confirm your order?')">Confirm Order</button>
        </form>
        <a href="shopping_cart.php" class="w3-button w3-gray w3-round">Back to Cart</a>
      </div>
      
    </div>
  </main>
  
  <?php include '../common/footer.php'; ?>
</body>
</html>
