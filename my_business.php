<?php
// Start session at the VERY TOP
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'common/head.php'; ?>

<body class="my_max_site_width w3-auto">
  <?php include 'common/banner.php'; ?>
  <?php include 'common/menus.php'; ?>
  
  <main class="w3-container">
    <?php
    // Display welcome message after login/registration
    if (isset($_SESSION['welcome_message'])) {
        echo '<div class="w3-panel w3-green w3-padding w3-center">';
        echo '<p>' . htmlspecialchars($_SESSION['welcome_message']) . '</p>';
        echo '</div>';
        unset($_SESSION['welcome_message']);
    }
    
    // Display info messages (logout, login name changed, etc.)
    if (isset($_SESSION['info_message'])) {
        echo '<div class="w3-panel w3-blue w3-padding w3-center">';
        echo '<p>' . htmlspecialchars($_SESSION['info_message']) . '</p>';
        echo '</div>';
        unset($_SESSION['info_message']);
    }
    ?>
    
    <div class="w3-container w3-border-left w3-border-right
                 w3-border-black w3-light-grey" style="padding-right:0">
      
      <!-- Left Column: About Text -->
      <article class="w3-half">
        <h3>
          Welcome to the website for Jonah's Urban Tails!
        </h3>
        <p>
          Founded back in 2018 by longtime animal lover Jonah, our services include at-home petsitting, both short and long term!
          Instead of taking your furry friend to a kennel where they may feel stressed and uncomfortable, Jonah comes right to your
          home to care for them in their familiar environment. Whether it's a quick visit during the day or an extended stay while you're away,
          Jonah ensures your pet receives the love and attention they deserve.
        </p>
        <p>
          Jonah has taken care of a wide variety of animals over the years, from common pets like dogs, cats, and hamsters, to more 
          exotic companions such as reptiles and fish. No matter the species, Jonah approaches each pet with the same level of dedication and care.
          Starting in Summer 2024, overnight stays will now be available for certain pets, allowing them to stay in the comfort of their own home 
          while you're away.
        </p>
      </article>
      
      <!-- Right Column: Slideshow -->
      <div class="w3-half w3-padding w3-center">
        <!-- Slideshow container -->
        <div class="w3-card-4 my_relative">
          <?php include 'common/carousel.php'; ?>
        </div>
      </div>
    </div>
  </main>
  
  <?php include 'common/footer.php'; ?>
  
  <!-- AJAX for time updates -->
  <script>
  var request = null;
  function getCurrentTime()
  {
    request = new XMLHttpRequest();
    var url = "scripts/time.php";
    request.open("GET", url, true);
    request.onreadystatechange = updatePage;
    request.send(null);
  }
  function updatePage()
  {
    if (request.readyState == 4)
    {
      var dateDisplay = document.getElementById("datetime");
      if (dateDisplay) {
        dateDisplay.innerHTML = request.responseText;
      }
    }
  }
  getCurrentTime();
  setInterval('getCurrentTime()', 60000);
  </script>
</body>
</html>
