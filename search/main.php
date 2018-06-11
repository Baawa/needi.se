<?php
header('Content-Type: text/html; charset=UTF-8');

require_once "../classes/include.php";

$user_id;

if (isLoggedIn()) {
  $user_id = getClientId();
}

$search_term = htmlspecialchars($_POST["term"]);

$noAction = TRUE;
require_once "../classes/account/items.php";

if ($search_term != ""){
  $items = search($search_term);
} else{
  $items = getRandomItems(6);
}


 ?>
<html>
<head>
  <title><?php echo $search_term; ?> - sök | .needI</title>
  <meta name="viewport" content="width=device-width">
  <script src="../js/main.js"></script>
  <link rel="stylesheet" type="text/css" href="../css/main.css">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Dosis:400,500,600,700" rel="stylesheet">

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

  <?php include_once "../templates/header_main.php"; ?>

  <div class="top-search-bar">
    <form class="search-form" method="post" action="main.php">
      <input class="input-text btn-addition" type="text" placeholder="Sök föremål.." value="<?php echo $search_term; ?>" name="term"/>
      <button class="btn input-btn" type="submit">Sök</button>
    </form>
  </div>

  <div class="search-result-container">
    <?php if ($items == NULL): ?>
      <?php if ($search_term != ""): ?>
        <p class="no-result-text">Det finns inget sådant föremål just nu. <a class="link" href="../site/contact.php">Skicka gärna in förslag på föremål som du tycker borde finnas med</a>.</p>
      <?php else: ?>
        <p class="no-result-text">Fyll i sökrutan ovanför för att hitta det föremål du letar efter.</p>
      <?php endif; ?>
    <?php else: ?>
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
              <a class="btn sm" href="item.php?id=<?php echo $item->id; ?>">Visa mer/Boka</a>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>

  <?php include_once "../templates/footer_main.php"; ?>
</body>
</html>
