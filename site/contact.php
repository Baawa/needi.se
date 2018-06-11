<?php
header('Content-Type: text/html; charset=UTF-8');

require_once "../classes/include.php";

$user_id;

if (isLoggedIn()) {
  $user_id = getClientId();
}

$send = htmlspecialchars($_GET["send"]);
if ($send != NULL) {
  if ($send == "send") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);

    if ($name != "" && $email != "" && $message != "") {
      contactMail($name, $email, $message);
    }
  }
}

 ?>
<html>
<head>
  <title>Kontakt | .needI</title>
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

    gtag('config', 'UA-113619014-1');
  </script>
</head>
<body>
  <?php include_once "../templates/header_main.php"; ?>

  <div class="about-top">
    <h2>Kontakta oss</h2>
    <p class="about-text">Vi på needI vill hjälpa människor i vår omgivning genom att frigöra dem från begränsningar kopplade till ägarskap. För att kunna uppnå detta är vi ständigt i kontakt med er, våra användare, för att kunna se till att ni har bästa möjliga upplevelse. Om du har några frågor, förslag på hur vi kan bli bättre, eller vill komma i kontakt med oss av någon annan anledning kan du använda formuläret nedan eller skicka ett mail direkt till oss på info[at]needi.se</p>
  </div>

  <div class="contact-panel">
    <form class="login-form" id="userLogin" name="userLogin" method="post" action="contact.php?a=send">
      <h3>Kontaktformuläret</h3>
      <p class="intro-text">Har du några frågor? Eller saknar du ett föremål? <br>Kontakta oss på .needI via formuläret nedan så hjälper vi dig.</p>

      <div class="input-group">
        <p class="label">Namn</p>
        <input class="input-text" type="text" name="name" placeholder="Namn.." onchange="checkFields();" oninput="checkFields();"/>
        <p class="error" id="error_name"></p>
      </div>
      <div class="input-group">
        <p class="label">Email</p>
        <input class="input-text" type="text" name="email" placeholder="Email.." onchange="checkFields();" oninput="checkFields();"/>
        <p class="error" id="error_email"></p>
      </div>
      <div class="input-group">
        <p class="label">Meddelande</p>
        <textarea class="input-text" name="message" placeholder="Meddelande.." id="message-input" onchange="checkFields();" oninput="checkFields();"></textarea>
        <p class="error" id="error_message"></p>
      </div>

      <div class="bottom-actions">
        <button class="btn" id="submitBtn" disabled>Skicka</button>
      </div>
    </form>
  </div>

  <?php include_once "../templates/footer_main.php"; ?>

  <script>
  function checkFields(){
    var flag = true;

    var email = document.forms["userLogin"]["email"];
    if (email.value == "") {
      toggleElement("error_email", "Fyll i din email-adress.", true);
      flag = false;
    } else if (!email.value.includes("@")){
      toggleElement("error_email", "Fyll i en giltig email-adress.", true);
      flag = false;
    } else{
      toggleElement("error_email", "", false);
    }

    var pw = document.forms["userLogin"]["name"];
    if (pw.value == "") {
      toggleElement("error_name", "Fyll i ditt namn.", true);
      flag = false;
    } else{
      toggleElement("error_name", "", false);
    }

    var ms = document.getElementById("message-input");
    if (ms.value == "") {
      toggleElement("error_message", "Fyll i ett meddelande.", true);
      flag = false;
    } else{
      toggleElement("error_message", "", false);
    }

    if (!flag) {
      document.getElementById("submitBtn").disabled = true;
    } else{
      document.getElementById("submitBtn").disabled = false;
    }
  }

  function toggleElement(id, value, flag){
    var e = document.getElementById(id);
    if (!flag) {
      e.style.display = "none";
    } else{
      e.style.display = "block";
      e.innerHTML = value;
    }
  }
  </script>
</body>
</html>
