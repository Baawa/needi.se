<?php
header('Content-Type: text/html; charset=UTF-8');

require_once "../classes/include.php";

$user_id;

if (isLoggedIn()) {
  $user_id = getClientId();
}

$noAction = TRUE;

require_once "../classes/account/items.php";

$item_id = htmlspecialchars($_GET["id"]);

$item = getItem($item_id);

 ?>
<html>
<head>
  <title><?php echo $item->name; ?> | .needI</title>
  <meta name="viewport" content="width=device-width">
  <script src="../js/main.js"></script>
  <link rel="stylesheet" type="text/css" href="../css/main.css">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Dosis:400,500,600,700" rel="stylesheet">

  <meta property="og:url" content="http://needi.se/search/item.php?id=<?php echo $item_id; ?>">
  <meta property="og:title" content=".needI - <?php echo $item->name; ?>">
  <meta property="og:description" content="<?php echo $item->description; ?>">
  <meta property="og:image" content="http://needi.se/uploads/images/<?php echo $item->image_url; ?>">
  <meta property"fb:app_id" content="<ID>">

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

  <?php if ($item == NULL): ?>
    <p>Föremålet finns inte.</p>
  <?php else: ?>
    <?php
    $days = getDaysArrayFromString($item->days_available);
    $times = getTimesFromString($item->time_available);
     ?>

    <div class="item-container <?php if ($item->user_id == $user_id) echo 'edit-mode'; ?>">
      <div class="item-image">
        <img src="../uploads/images/<?php echo $item->image_url; ?>"></img>
      </div>

      <div class="item-stats">
        <?php if ($item->user_id == $user_id): ?>
          <div class="form-container">
            <form class="edit-form" id="editItem" name="editItem" action="javascript:void(0);">

              <p class="label">Namn</p>
              <input class="input-text" type="text" name="name" placeholder="Namn.." value="<?php echo $item->name;?>">
              <p class="error" id="error_name"></p>

              <p class="label">Beskrivning</p>
              <input class="input-text" type="text" name="description" placeholder="Beskrivning.." value="<?php echo $item->description;?>">
              <p class="error" id="error_description"></p>

              <p class="label">Pris (SEK)</p>
              <input class="input-text" type="number" name="price" placeholder="Pris.." value="<?php echo $item->price;?>">
              <p class="error" id="error_price"></p>

              <p class="label">Välj dagar för uthyrning</p>
              <div class="checkbox-container">
                <div class="checkbox-group">
                  <div class="checkbox-pair">
                    <p class="label">Måndag</p>
                    <input type="checkbox" name="mon" value="mon" <?php if($days["mon"] == TRUE){echo "checked";} ?>>
                  </div>

                  <div class="checkbox-pair">
                    <p class="label">Tisdag</p>
                    <input type="checkbox" name="tue" value="tue" <?php if($days["tue"] == TRUE){echo "checked";} ?>>
                  </div>

                  <div class="checkbox-pair">
                    <p class="label">Onsdag</p>
                    <input type="checkbox" name="wed" value="wed" <?php if($days["wed"] == TRUE){echo "checked";} ?>>
                  </div>

                  <div class="checkbox-pair">
                    <p class="label">Torsdag</p>
                    <input type="checkbox" name="thur" value="thur" <?php if($days["thur"] == TRUE){echo "checked";} ?>>
                  </div>

                  <div class="checkbox-pair">
                    <p class="label">Fredag</p>
                    <input type="checkbox" name="fri" value="fri" <?php if($days["fri"] == TRUE){echo "checked";} ?>>
                  </div>

                  <div class="checkbox-pair">
                    <p class="label">Lördag</p>
                    <input type="checkbox" name="sat" value="sat" <?php if($days["sat"] == TRUE){echo "checked";} ?>>
                  </div>

                  <div class="checkbox-pair">
                    <p class="label">Söndag</p>
                    <input type="checkbox" name="sun" value="sun" <?php if($days["sun"] == TRUE){echo "checked";} ?>>
                  </div>
                </div>
              </div>

              <p class="error" id="error_checkboxes"></p>

              <p class="label">Hämtas/lämnas från</p>
              <input class="input-text" type="time" name="start_time" placeholder="t.ex. 18.00" value="<?php echo $times[0]; ?>">
              <p class="error" id="error_start_time"></p>

              <p class="label">Till</p>
              <input class="input-text" type="time" name="end_time" placeholder="t.ex. 20.00" value="<?php echo $times[1]; ?>">
              <p class="error" id="error_end_time"></p>

              <button class="btn" onclick="save()">Spara ändringar</button>
            </form>
          </div>
        <?php else: ?>
          <div class="item-text">
            <h4><?php echo $item->name;?></h4>
            <p class="price"><?php echo $item->price;?> SEK / Dygn</p>
            <p class="description"><?php echo $item->description;?></p>
          </div>

          <div class="day-indicator-group">
            <div class="day-indicator <?php if ($days["mon"] == TRUE){ echo "filled"; } ?>">Mån</div>
            <div class="day-indicator <?php if ($days["tue"] == TRUE){ echo "filled"; } ?>">Tis</div>
            <div class="day-indicator <?php if ($days["wed"] == TRUE){ echo "filled"; } ?>">Ons</div>
            <div class="day-indicator <?php if ($days["thur"] == TRUE){ echo "filled"; } ?>">Tors</div>
            <div class="day-indicator <?php if ($days["fri"] == TRUE){ echo "filled"; } ?>">Fre</div>
            <div class="day-indicator <?php if ($days["sat"] == TRUE){ echo "filled"; } ?>">Lör</div>
            <div class="day-indicator <?php if ($days["sun"] == TRUE){ echo "filled"; } ?>">Sön</div>
          </div>

          <p class="item-time">Hämtas/Lämnas <?php echo "" . $times[0] . " - " . $times[1];?></p>

          <form class="book-form" name="bookItem" action="javascript:void(0);">
            <div class="input-group">
              <p class="label">Boka från</p>
              <input class="input-text" type="text" name="start_date" placeholder="t.ex. 20-04-2018">
              <p class="error" id="error_start_date_book"></p>
            </div>

            <div class="input-group">
              <p class="label">Boka till</p>
              <input class="input-text" type="text" name="end_date" placeholder="t.ex. 21-04-2018">
              <p class="error" id="error_end_date_book"></p>
            </div>

            <button class="btn" onclick="<?php if($user_id != NULL){echo 'book();';} else{echo 'bookNoAccount();';} ?>">Skicka förfrågan</button>
            <p class="success" id="success_book"></p>
          </form>
        <?php endif; ?>
      </div>

    </div>
  <?php endif; ?>

  <script>

  function book(){
    if (checkFieldsBook()) {
      var start_date = document.forms["bookItem"]["start_date"].value;
      var end_date = document.forms["bookItem"]["end_date"].value;
      var item_id = "<?php echo $item->id; ?>";
      var user_id = "<?php echo $user_id; ?>";

      bookItem(item_id, user_id, start_date, end_date, bookCallback);
    }
  }

  function bookNoAccount(){
    window.location.replace('../account/index.php');
  }

  function save(){
    if (checkFields()) {
      var days = getValuesFromCheckboxes();
      if (days == "") {
        toggleElement("error_checkboxes", "Du måste välja minst en dag.", true);
        return;
      } else{
        toggleElement("error_checkboxes", "", false);
      }

      var name = document.forms["editItem"]["name"].value;
      var description = document.forms["editItem"]["description"].value;
      var price = document.forms["editItem"]["price"].value;
      var time_available = "" + document.forms["editItem"]["start_time"].value + "-" + document.forms["editItem"]["end_time"].value;
      var days_available = days;
      var item_id = "<?php echo $item->id; ?>";

      updateItem(item_id, name, description, price, time_available, days_available, saveCallback)
    }
  }

  function checkFieldsBook(){
    var flag = true;

    var start_time = document.forms["bookItem"]["start_date"];
    if (start_time.value == "") {
      toggleElement("error_start_date_book", "Fyll i en starttid.", true);
      flag = false;
    } else{
      toggleElement("error_start_date_book", "", false);
    }

    var end_time = document.forms["bookItem"]["end_date"];
    if (end_time.value == "") {
      toggleElement("error_end_date_book", "Fyll i sluttid", true);
      flag = false;
    } else{
      toggleElement("error_end_date_book", "", false);
    }

    return flag;
  }

  function checkFields(){
    var flag = true;

    var name = document.forms["editItem"]["name"];
    if (name.value == "") {
      toggleElement("error_name", "Fyll i ett namn.", true);
      flag = false;
    } else{
      toggleElement("error_name", "", false);
    }

    var description = document.forms["editItem"]["description"];
    if (description.value == "") {
      toggleElement("error_description", "Fyll i en beskrivning", true);
      flag = false;
    } else{
      toggleElement("error_description", "", false);
    }

    var price = document.forms["editItem"]["price"];
    if (price.value == "") {
      price.value = "0";
    } else{
      toggleElement("error_price", "", false);
    }

    var start_time = document.forms["editItem"]["start_time"];
    if (start_time.value == "") {
      toggleElement("error_start_time", "Fyll i en starttid.", true);
      flag = false;
    } else{
      toggleElement("error_start_time", "", false);
    }

    var end_time = document.forms["editItem"]["end_time"];
    if (end_time.value == "") {
      toggleElement("error_end_time", "Fyll i sluttid", true);
      flag = false;
    } else{
      toggleElement("error_end_time", "", false);
    }

    return flag;
  }

  function getValuesFromCheckboxes(){
    var form = document.getElementById("editItem");
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

  function bookCallback(xml){
    console.log(xml);

    var message = xml.getElementsByTagName("message")[0];

    if (message == null){
      /*
        Om vi lyckas skapa item.
      */
      var requests = xml.getElementsByTagName("request");

      if (requests.length == 1) {
        toggleElement("success_book", "Förfrågan skickad!", true);
        toggleElement("error_end_date_book", "", false);
      }
    } else{
      toggleElement("error_end_date_book", "Något gick fel. Försök igen.", true);
      toggleElement("success_book", "", false);
    }
  }

  function saveCallback(xml){
    var message = xml.getElementsByTagName("message")[0];

    if (message == null){
      /*
        Om vi lyckas skapa item.
      */
      var success = xml.getElementsByTagName("success");

      if (success.length == 1) {
        window.location.replace('item.php?id=<?php echo $item->id; ?>');
      }
    } else{
      toggleElement("error_end_time", "Något gick fel. Försök igen.", true);
    }
  }

  </script>

  <?php include_once "../templates/footer_main.php"; ?>
</body>
</html>
