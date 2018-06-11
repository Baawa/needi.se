<?php
header('Content-Type: text/html; charset=UTF-8');

require_once "classes/include.php";

$user_id;

if (isLoggedIn()) {
  $user_id = getClientId();
}

$noAction = true;

require_once "classes/account/items.php";

$items = getRandomItems(6);

$cookie_consent = getCookieConsent();

 ?>
<html>
<head>
  <title>.needI | Hyr det du saknar</title>

  <!-- SEO, FAVION, etc. -->
  <meta name="description" content="Genom .needI kan du hyra eller låna ut alla de saker som du har stoppat undan i källaren, på vinden, eller i garderoben för att glömmas bort.">

  <link rel="apple-touch-icon" sizes="180x180" href="/resources/images/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/resources/images/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/resources/images/favicon/favicon-16x16.png">
  <link rel="manifest" href="/resources/images/favicon/site.webmanifest">
  <link rel="mask-icon" href="/resources/images/favicon/safari-pinned-tab.svg" color="#0071ce">
  <link rel="shortcut icon" href="/resources/images/favicon/favicon.ico">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-config" content="/resources/images/favicon/browserconfig.xml">
  <meta name="theme-color" content="#ffffff">

  <meta name="viewport" content="width=device-width">
  <script src="js/main.js"></script>
  <link rel="stylesheet" type="text/css" href="css/main.css">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Dosis:400,500,600,700" rel="stylesheet">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

  <!-- FB META -->
  <meta property="og:url" content="http://needi.se">
  <meta property="og:title" content=".needI">
  <meta property="og:description" content="Genom .needI kan du hyra eller låna ut alla de saker som du har stoppat undan i källaren, på vinden, eller i garderoben för att glömmas bort.">
  <meta property="og:image" content="http://needi.se/resources/images/fb-bg.png">
  <meta property"fb:app_id" content="<APPID>">

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=<ID>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '<ID>');
  </script>
</head>
<body>
  <?php if ($cookie_consent === FALSE):?>
    <div class="banner" id="cookie-banner">
      <p>Vi använder cookies för att leverera en så bra upplevelse som möjligt till dig. Genom att använda vår webbplats samtycker du till vår användning av cookies. <a href="/resources/legal/cookies.pdf" class="link white">Läs mer</a>.</p>
      <button onclick="setCookieConsent();" class="btn white-bg">Jag förstår</button>
    </div>
  <?php endif;?>

  <div id="popup-holder"></div>

  <div class="start-top-container">
    <div class="header-main header-start">
      <div class="header-inner">
        <img class="header-logo" src="resources/images/logo-black.png"/>

        <ul class="link-list">
          <li><a class="link" href="index.php">Hem</a></li>
          <li><a class="link" href="search/main.php">Sök</a></li>
          <li><a class="link" href="site/about.php">Om oss</a></li>
          <li><a class="link" href="site/contact.php">Kontakt</a></li>
          <?php if (!isLoggedIn()): ?>
            <li><a class="btn long" onclick="showLogin();">Logga in</a></li>
          <?php else: ?>
            <li><a href="account/profile.php" class="btn long">Min profil</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>

    <div class="content">
      <img class="half-page sm" src="resources/images/man-walking.png" />
      <div class="text">
        <h2 class="light">Hyr ut det du har,</h2>
        <h2 class="normal">Hyr det du saknar.</h2>
        <!--<p>Genom .needI kan du hyra eller låna ut alla de saker som du har stoppat undan i källaren, på vinden, eller i garderoben för att glömmas bort. Och genom att hyra eller låna samma saker av andra behöver du själv inte ha en massa saker som tar onödig plats hemma.</p>-->
        <form class="search-form" method="post" action="search/main.php">
          <input class="input-text btn-addition" type="text" placeholder="Sök föremål.." value="<?php echo $search_term; ?>" name="term"/>
          <button class="btn input-btn" type="submit">Sök</button>
        </form>
      </div>
    </div>
  </div>

  <?php if ($items != NULL): ?>
    <div class="search-result-container start-page">
      <div class="center-inside-h margin-bot-40">
        <h4>Populära objekt</h4>
      </div>
      <ul class="result-list">
        <?php foreach ($items as $item): ?>
          <li class="result-item">
            <div class="item-icon">
              <img src="../uploads/images/<?php echo $item->image_url; ?>" \>
            </div>
            <div class="item-stats">
              <div class="item-text">
                <h5><?php echo $item->name; ?></h5>
                <p class="sm"><?php echo $item->price; ?> SEK / Dygn</p>
              </div>
              <a class="btn sm" href="/search/item.php?id=<?php echo $item->id; ?>">Visa mer/Boka</a>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
      <div class="center-inside-h margin-top-20">
        <a href="/search/main.php" class="btn lg green-bg">Visa fler</a>
      </div>
    </div>
  <?php endif; ?>

  <div class="second-container">
    <div class="content">
      <div class="text">
        <h3>Lätt att hyra ut,</h3>
        <h3>Ännu lättare att hyra.</h3>
        <p>För att hyra och hyra ut dina saker genom .needI krävs det att du skapar ett konto hos oss. Genom att vara inloggad kan du se dina nuvarande bokningar, lägga upp dina saker för uthyrning och hyra saker av andra.</p>
        <a href="account/login.php" class="btn">Skapa ett konto</a>
      </div>
      <img class="half-page" src="resources/images/website-on-mac.png" />
    </div>
  </div>

  <div class="third-container">
    <div class="content">
      <img class="half-page" src="resources/images/judge.png" />
      <div class="text">
        <h3>Tryggt,</h3>
        <h3>För båda parter.</h3>
        <p>När du hyr eller hyr ut dina saker till andra rekommenderar vi att ni använder er av vårt uthyrningsavtal. Avtalet gör det säkert för både hyrestagaren och uthyraren. Vilket leder till en trevlig och enkel affär.</p>
        <a href="account/login.php" class="btn">Kom igång</a>
      </div>
    </div>
  </div>

  <!-- Start of Async Drift Code -->
<script>
!function() {
  var t;
  if (t = window.driftt = window.drift = window.driftt || [], !t.init) return t.invoked ? void (window.console && console.error && console.error("Drift snippet included twice.")) : (t.invoked = !0,
  t.methods = [ "identify", "config", "track", "reset", "debug", "show", "ping", "page", "hide", "off", "on" ],
  t.factory = function(e) {
    return function() {
      var n;
      return n = Array.prototype.slice.call(arguments), n.unshift(e), t.push(n), t;
    };
  }, t.methods.forEach(function(e) {
    t[e] = t.factory(e);
  }), t.load = function(t) {
    var e, n, o, i;
    e = 3e5, i = Math.ceil(new Date() / e) * e, o = document.createElement("script"),
    o.type = "text/javascript", o.async = !0, o.crossorigin = "anonymous", o.src = "https://js.driftt.com/include/" + i + "/" + t + ".js",
    n = document.getElementsByTagName("script")[0], n.parentNode.insertBefore(o, n);
  });
}();
drift.SNIPPET_VERSION = '0.3.1';
drift.load('<ID>');
</script>
<!-- End of Async Drift Code -->

  <div class="footer-container">
    <div class="footer">
      <div class="footer-inner">
        <img class="footer-logo" src="resources/images/logo-white.png"/>

        <ul class="link-list">
          <li class="header">Länkar</li>
          <li><a class="link" href="index.php">Hem</a></li>
          <li><a class="link" href="search/main.php">Sök</a></li>
          <li><a class="link" href="index.php">Om oss</a></li>
          <li><a class="link" href="index.php">Kontakt</a></li>
          <?php if (!isLoggedIn()): ?>
            <li><a class="link" href="account/login.php">Logga in</a></li>
          <?php elseif($onProfile): ?>
            <li><a class="link" onclick="logOut()">Logga ut</a></li>
          <?php else: ?>
            <li><a class="link" href="account/profile.php">Min profil</a></li>
          <?php endif; ?>
        </ul>
      </div>
      <div class="copyright">
        <p>Copyright &copy; .needI <?php echo date("Y"); ?></p>
      </div>
    </div>
  </div>
</body>
</html>
