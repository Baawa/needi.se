<?php
header('Content-Type: text/html; charset=UTF-8');

require_once "../classes/include.php";

if (!isLoggedIn()) {
  redirect("login.php");
}

$user_id = getClientId();

 ?>
<html>
<head>
  <title>Ny annons | needI</title>
  <meta name="viewport" content="width=device-width">
  <script src="../js/main.js"></script>
  <link rel="stylesheet" type="text/css" href="../css/main.css">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Dosis:400,500,600,700" rel="stylesheet">
</head>
<body>
  <?php include_once "../templates/header_main.php"; ?>

  <form class="login-form" id="newItem" name="newItem" action="javascript:void(0);">

    <h4>Ny annons</h4>

    <div class="input-group">
      <p class="label">Namn</p>
      <input class="input-text" type="text" name="name" placeholder="Namn..">
      <p class="error" id="error_name"></p>
    </div>

    <div class="input-group">
      <p class="label">Beskrivning</p>
      <input class="input-text" type="text" name="description" placeholder="Beskrivning..">
      <p class="error" id="error_description"></p>
    </div>

    <div class="input-group">
      <p class="label">Pris (SEK)</p>
      <input class="input-text" type="text" name="price" placeholder="Pris..">
      <p class="error" id="error_price"></p>
    </div>

    <div class="input-group">
      <p class="label">Annonsbild</p>
      <input class="upload-input" type="file" name="picture" placeholder="Annonsbild" onchange="checkFile()">
      <p class="error" id="error_picture"></p>
    </div>

    <div class="input-group">
      <p class="label">Välj dagar för uthyrning</p>
      <div class="checkbox-container">
        <div class="checkbox-group">
          <div class="checkbox-pair">
            <p class="label">Måndag</p>
            <input type="checkbox" name="mon" value="mon">
          </div>

          <div class="checkbox-pair">
            <p class="label">Tisdag</p>
            <input type="checkbox" name="tue" value="tue">
          </div>

          <div class="checkbox-pair">
            <p class="label">Onsdag</p>
            <input type="checkbox" name="wed" value="wed">
          </div>

          <div class="checkbox-pair">
            <p class="label">Torsdag</p>
            <input type="checkbox" name="thur" value="thur">
          </div>

          <div class="checkbox-pair">
            <p class="label">Fredag</p>
            <input type="checkbox" name="fri" value="fri">
          </div>

          <div class="checkbox-pair">
            <p class="label">Lördag</p>
            <input type="checkbox" name="sat" value="sat">
          </div>

          <div class="checkbox-pair">
            <p class="label">Söndag</p>
            <input type="checkbox" name="sun" value="sun">
          </div>
        </div>
      </div>
      <p class="error" id="error_checkboxes"></p>
    </div>

    <div class="input-group">
      <p class="label">Hämtas/lämnas från</p>
      <input class="input-text" type="text" name="start_time" placeholder="t.ex. 18.00">
      <p class="error" id="error_start_time"></p>
    </div>

    <div class="input-group">
      <p class="label">Till</p>
      <input class="input-text" type="text" name="end_time" placeholder="t.ex. 20.00">
      <p class="error" id="error_end_time"></p>
    </div>

    <div class="bottom-actions">
      <button class="btn" onclick="create()">Skapa Annons</button>
    </div>
  </form>

  <?php include_once "../templates/footer_main.php"; ?>

  <script>

  function create(){
    if (checkFields()) {

      var days = getValuesFromCheckboxes();
      if (days == "") {
        toggleElement("error_checkboxes", "Du måste välja minst en dag.", true);
        return;
      } else{
        toggleElement("error_checkboxes", "", false);
      }

      var formData = new FormData();

      formData.append("name", document.forms["newItem"]["name"].value);

      formData.append("description", document.forms["newItem"]["description"].value);

      formData.append("price", document.forms["newItem"]["price"].value);

      var file = checkFile();
      if (file){
        formData.append("image", file, file.name);
      }

      formData.append("days_available", days);

      formData.append("time_available", "" + document.forms["newItem"]["start_time"].value + "-" + document.forms["newItem"]["end_time"].value);

      formData.append("user_id", "<?php echo getClientId(); ?>");

      createItem(formData, createCallback);
    }
  }

  function checkFile(){
    var file = document.forms["newItem"]["picture"].files[0];

    if (!file.type.match('image.*')) {
      toggleElement("error_picture", "Ogiltigt format.", true);
      return null;
    }

    if (file.size >= 200000000 ) {
      toggleElement("error_picture", "Filen är större än 200mb.", true);

      return null;
    }
    toggleElement("error_picture", "", false);

    return file;
  }

  function checkFields(){
    var flag = true;

    var name = document.forms["newItem"]["name"];
    if (name.value == "") {
      toggleElement("error_name", "Fyll i ett namn.", true);
      flag = false;
    } else{
      toggleElement("error_name", "", false);
    }

    var description = document.forms["newItem"]["description"];
    if (description.value == "") {
      toggleElement("error_description", "Fyll i en beskrivning", true);
      flag = false;
    } else{
      toggleElement("error_description", "", false);
    }

    var price = document.forms["newItem"]["price"];
    if (price.value == "") {
      price.value = "0";
    } else{
      toggleElement("error_price", "", false);
    }

    var start_time = document.forms["newItem"]["start_time"];
    if (start_time.value == "") {
      toggleElement("error_start_time", "Fyll i en starttid.", true);
      flag = false;
    } else{
      toggleElement("error_start_time", "", false);
    }

    var end_time = document.forms["newItem"]["end_time"];
    if (end_time.value == "") {
      toggleElement("error_end_time", "Fyll i sluttid", true);
      flag = false;
    } else{
      toggleElement("error_end_time", "", false);
    }

    return flag;
  }

  function getValuesFromCheckboxes(){
    var form = document.getElementById("newItem");
    var inputs = form.getElementsByTagName("input");

    var values = "";

    for (var i = 0, max = inputs.length; i < max; i += 1) {
      if (inputs[i].type === "checkbox" && inputs[i].checked) {
        values += inputs[i].value;
      }
    }

    return values;
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

  function createCallback(xml){
    var message = xml.getElementsByTagName("message")[0];
    console.log(xml);
    if (message == null){
      var items = xml.getElementsByTagName("item");

      if (items.length == 1) {
        window.location.replace('profile.php');
      }
    } else{
      toggleElement("error_end_time", "Något gick fel. Försök igen.", true);
    }
  }

  </script>
</body>
</html>
