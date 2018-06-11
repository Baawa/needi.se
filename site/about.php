<?php
header('Content-Type: text/html; charset=UTF-8');

require_once "../classes/include.php";

$user_id;

if (isLoggedIn()) {
  $user_id = getClientId();
}

 ?>
<html>
<head>
  <title>Om oss | .needI</title>

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
  <script src="../js/main.js"></script>
  <link rel="stylesheet" type="text/css" href="../css/main.css">
  <link href="https://fonts.googleapis.com/css?family=Dosis:400,500,600,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

  <!-- FB META -->
  <meta property="og:url" content="http://needi.se">
  <meta property="og:title" content=".needI">
  <meta property="og:description" content="Genom .needI kan du hyra eller låna ut alla de saker som du har stoppat undan i källaren, på vinden, eller i garderoben för att glömmas bort.">
  <meta property="og:image" content="http://needi.se/resources/images/fb-bg.png">
  <meta property"fb:app_id" content="<ID>">

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=<ID>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-113619014-1');
  </script>
</head>
<body>
  <?php include_once "../templates/header_main.php"; ?>

  <div class="about-top">
    <h2>Om oss</h2>
    <p class="about-text">Vi vill hjälpa människor i vår omgivning genom att frigöra dem från begränsningar kopplade till ägarskap. Vi kommer att uppnå detta genom att bygga en världsomspännande gemenskap där vem som helst, var som helst, kan med trygghet och enkelhet hyra eller låna vardagliga föremål från en person i sin närhet.</p>
  </div>

  <div class="about-panel">
    <ul class="about-list">
      <li>
        <div class="text">
          <h4>Bakgrund</h4>
          <p>Historien bakom .needI handlar om två studenter som skulle sätta upp en tavla i lägenhet. Något som studenterna inte tänkte på var att lägenhetens väggar var gjorda av betong. Som de flesta vet räcker det inte med en vanlig borrmaskin för att sätta en skruv i en betongvägg, istället behöver man använda en s.k. slagborr. Problemet med en slagborr är att det är en kostsam investering, och därför knappast något du hittar hemma hos en student. Med den insikten verkade det för en stund hopplöst för de två studenterna. Men en av studenterna fick då en idé: "Kan man inte låna det här av grannen?" brast han ut. Den andra studenten som var nyinflyttad i huset svarade då "Jo.. Men jag känner ju ingen här.. Och hur ska jag veta vem som har en slagborr?.. Vi kan ju knappast gå och knacka på varenda dörr.", åter tillbaka till hopplösheten började en tanke gro hos de två studenterna. Och även om tavlan inte blev uppsatt den dagen har den tanken växt upp till att bli .needI. </p>
        </div>
        <div class="image">
          <img src="../resources/images/couch-AandP.jpg"/>
        </div>
      </li>
    </ul>

    <ul class="about-list">
      <li class="mobile-reverse">
        <div class="image">
          <img src="../resources/images/aboutimage2.jpeg"/>
        </div>
        <div class="text right">
          <h4>Hur .needI fungerar</h4>
          <p>.needI vill göra det enklare för gemene man att hyra och låna ut saker som man sällan använder. .needI bidrar därför med en öppen plattform där du och andra kan lägga upp de föremål som ni vill hyra ut, och hitta de saker som ni kan tänkas behöva just nu. Vi på .needI tror på närhet och grannsamverkan, därför finns vi för tillfället bara i Johannebergs-området i Göteborg, så att du som bor på Johanneberg aldrig har långt att gå och att du vet vem du lånar av.</p>
        </div>
      </li>
    </ul>

    <ul class="about-list">
      <li>
        <div class="text">
          <h4>Kom i kontakt</h4>
          <p>Tycker du att det vi jobbar med verkar intressant och vill ta reda på mer om .needI? Har du hittat ett fel på hemsidan? eller behöver du något som du inte kan hitta genom sök-funktionen? Tveka inte på att hör av dig! Enklast når du oss på <a href="mailto:info@needi.se" class="link">info@needi.se</a> eller genom <a href="contact.php" class="link">kontaktformuläret</a>.
          <br><br>Du kan också maila direkt till någon av oss!
          <br><b>Albin Bååw</b> albin[at]needi.se
          <br><b>Pedram Shirmohammad</b> pedram[at]needi.se</p>
        </div>
        <div class="image">
          <img src="../resources/images/aboutimage3.jpeg"/>
        </div>
      </li>
    </ul>
  </div>

  <?php include_once "../templates/footer_main.php"; ?>
</body>
</html>
