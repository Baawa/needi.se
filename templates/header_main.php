<?php
$onProfile;

$cookie_consent = getCookieConsent();
 ?>

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 <div id="popup-holder"></div>

 <?php if ($cookie_consent === FALSE):?>
   <div class="banner" id="cookie-banner">
     <p>Vi använder cookies för att leverera en så bra upplevelse som möjligt till dig. Genom att använda vår webbplats samtycker du till vår användning av cookies. <a href="/resources/legal/cookies.pdf" class="link white">Läs mer</a>.</p>
     <button onclick="setCookieConsent();" class="btn white-bg">Jag förstår</button>
   </div>
 <?php endif;?>

<div class="header-main">
  <div class="header-inner">
    <img class="header-logo" src="../resources/images/logo-black.png"/>

    <ul class="link-list">
      <li><a class="link" href="../index.php">Hem</a></li>
      <li><a class="link" href="../search/main.php">Sök</a></li>
      <li><a class="link" href="../site/about.php">Om oss</a></li>
      <li><a class="link" href="../site/contact.php">Kontakt</a></li>
      <?php if (!isLoggedIn()): ?>
        <li><a class="btn long" onclick="showLogin();">Logga in</a></li>
      <?php elseif($onProfile): ?>
        <li><a class="btn long red-bg" onclick="logOut()">Logga ut</a></li>
      <?php else: ?>
        <li><a class="btn long" href="../account/profile.php">Min profil</a></li>
      <?php endif; ?>
    </ul>
  </div>
</div>
