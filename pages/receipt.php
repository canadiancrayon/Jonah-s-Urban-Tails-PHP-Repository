<?php
session_start();
require_once '../scripts/mysql/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if receipt data exists
if (!isset($_SESSION['receipt'])) {
    header('Location: shopping_cart.php');
    exit;
}

$receipt = $_SESSION['receipt'];
$customer_id = $_SESSION['user_id'];

$pdo = getDBConnection();
if ($pdo) {
    $stmt = $pdo->prepare("SELECT first_name, last_name, email FROM my_Customers WHERE customer_id = ?");
    $stmt->execute([$customer_id]);
    $customer = $stmt->fetch();
}

// Clear receipt from session after display
unset($_SESSION['receipt']);
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
      
      <!-- Success Header -->
      <div class="w3-panel w3-green w3-padding w3-center">
        <h2>✓ Thank You for Your Order!</h2>
        <p>Order #<?php echo $receipt['order_id']; ?></p>
        <p>Order Date: <?php echo date('F j, Y g:i A', strtotime($receipt['order_date'])); ?></p>
      </div>
      
      <!-- Order Summary -->
      <div class="w3-card-4 w3-padding w3-margin-bottom">
        <h3>Order Summary</h3>
        <table class="w3-table w3-striped">
          <thead class="w3-blue">
            <tr>
              <th>Product</th>
              <th class="w3-center">Quantity</th>
              <th class="w3-right-align">Price</th>
              <th class="w3-right-align">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($receipt['items'] as $item): ?>
            <tr>
              <td><?php echo htmlspecialchars($item['product_name']); ?></td>
              <td class="w3-center"><?php echo $item['quantity']; ?></td>
              <td class="w3-right-align">$<?php echo number_format($item['unit_price'], 2); ?></td>
              <td class="w3-right-align">$<?php echo number_format($item['quantity'] * $item['unit_price'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr class="w3-light-gray">
              <td colspan="3" class="w3-right-align"><strong>Grand Total:</strong></td>
              <td class="w3-right-align"><strong>$<?php echo number_format($receipt['grand_total'], 2); ?></strong></td>
            </tr>
          </tfoot>
        </table>
      </div>
      
      <!-- Customer Information -->
      <?php if (isset($customer)): ?>
      <div class="w3-card-4 w3-padding w3-margin-bottom">
        <h3>Order Details</h3>
        <p><strong>Customer:</strong> <?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($customer['email']); ?></p>
        <p><strong>Order #:</strong> <?php echo $receipt['order_id']; ?></p>
        <p><strong>Date:</strong> <?php echo date('F j, Y g:i A', strtotime($receipt['order_date'])); ?></p>
        <div class="w3-panel w3-light-blue w3-padding">
          <p><strong>Confirmation Sent To:</strong> <?php echo htmlspecialchars($customer['email']); ?></p>
          <p>We've sent a confirmation email to this address.</p>
        </div>
      </div>
      <?php endif; ?>
      
      <!-- Next Steps -->
      <div class="w3-card-4 w3-padding w3-margin-bottom w3-light-gray">
        <h3>What's Next?</h3>
        <ul class="w3-ul">
          <li>✓ You will receive a confirmation email shortly</li>
          <li>✓ Our team will contact you within 24 hours to confirm your booking</li>
          <li>✓ You can view your order history in your account</li>
          <li>✓ For urgent questions, contact us directly</li>
        </ul>
      </div>
      
      <!-- Navigation Buttons -->
      <div class="w3-center w3-margin-top">
        <a href="product_catalog.php" class="w3-button w3-blue w3-round">Continue Shopping</a>
        <a href="../my_business.php" class="w3-button w3-gray w3-round">Return to Home</a>
      </div>
      
    </div>
  </main>
  
  <?php include '../common/footer.php'; ?>
</body>
</html>
