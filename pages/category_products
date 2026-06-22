<?php
session_start();
require_once '../scripts/mysql/db_connect.php';
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
if ($category_id <= 0) { header('Location: product_catalog.php'); exit; }
$pdo = getDBConnection();
if (!$pdo) die("Database connection failed");
$stmt = $pdo->prepare("SELECT * FROM my_ProductCategories WHERE category_id = ?");
$stmt->execute([$category_id]);
$category = $stmt->fetch();
if (!$category) { header('Location: product_catalog.php'); exit; }
$stmt = $pdo->prepare("SELECT * FROM my_Products WHERE category_id = ? ORDER BY product_name");
$stmt->execute([$category_id]);
$products = $stmt->fetchAll();
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../common/head.php'; ?>
<body class="my_max_site_width w3-auto">
  <?php include '../common/banner.php'; ?>
  <?php include '../common/menus.php'; ?>
  <main class="w3-container">
    <div class="w3-container w3-border-left w3-border-right w3-border-black w3-light-grey w3-padding">
      <h2 class="w3-center"><?php echo htmlspecialchars($category['category_name']); ?></h2>
      <div class="w3-row-padding">
        <?php foreach ($products as $product): ?>
        <div class="w3-half w3-margin-bottom">
          <div class="w3-card-4" style="display: flex; flex-direction: row; height: 200px;">
            <div style="width: 200px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
              <span style="font-size: 60px;">🐾</span>
            </div>
            <div style="flex: 1; padding: 15px; display: flex; flex-direction: column;">
              <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
              <p style="font-size: 0.9em;"><?php echo htmlspecialchars(substr($product['product_description'], 0, 100)); ?>...</p>
              <div style="margin-top: auto; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-weight: bold; color: #2196F3;">$<?php echo number_format($product['unit_price'], 2); ?></span>
                <?php if ($is_logged_in): ?>
                <form action="../scripts/add_to_cart.php" method="POST">
                  <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                  <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
                  <button type="submit" class="w3-button w3-blue w3-round">Add to Cart</button>
                </form>
                <?php else: ?>
                <a href="login.php" class="w3-button w3-gray w3-round">Login to Buy</a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="w3-center"><a href="product_catalog.php" class="w3-button w3-blue w3-round">Return to Catalog</a></div>
    </div>
  </main>
  <?php include '../common/footer.php'; ?>
</body>
</html>
