<?php
session_start();
require_once '../scripts/mysql/db_connect.php';
$pdo = getDBConnection();
if (!$pdo) die("Database connection failed");
$stmt = $pdo->query("SELECT * FROM my_ProductCategories ORDER BY category_name");
$categories = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../common/head.php'; ?>
<body class="my_max_site_width w3-auto">
  <?php include '../common/banner.php'; ?>
  <?php include '../common/menus.php'; ?>
  <main class="w3-container">
    <div class="w3-container w3-border-left w3-border-right w3-border-black w3-light-grey w3-padding">
      <h2 class="w3-center">Product Catalog</h2>
      <div class="w3-row-padding w3-margin-top">
        <?php foreach ($categories as $category): ?>
        <div class="w3-third w3-margin-bottom">
          <div class="w3-card-4 w3-hover-shadow">
            <a href="category_products.php?category_id=<?php echo $category['category_id']; ?>" style="text-decoration: none;">
              <div style="height: 150px; background: #2196F3; display: flex; align-items: center; justify-content: center;">
                <span style="font-size: 60px; color: white;">🐾</span>
              </div>
              <div class="w3-container w3-center">
                <h3><?php echo htmlspecialchars($category['category_name']); ?></h3>
                <p><?php echo htmlspecialchars($category['category_description']); ?></p>
              </div>
            </a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </main>
  <?php include '../common/footer.php'; ?>
</body>
</html>
