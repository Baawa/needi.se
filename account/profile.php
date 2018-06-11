<?php
header('Content-Type: text/html; charset=UTF-8');

require_once "../classes/include.php";

if (!isLoggedIn()) {
  redirect("login.php");
}

$onProfile = TRUE;

 ?>
<html>
<head>
  <title>Min profil | .needI</title>
  <meta name="viewport" content="width=device-width">
  <script src="../js/main.js"></script>
  <link rel="stylesheet" type="text/css" href="../css/main.css">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css?family=Dosis:400,500,600,700" rel="stylesheet">
</head>
<body>
  <?php include_once "../templates/header_main.php"; ?>

  <?php

  $noAction = TRUE;

  require_once "../classes/account/items.php";

  $user_id = getClientId();

  $requests = getRequests($user_id);

  $items = getItems($user_id);
  ?>

  <div class="myItems">
    <div class="header-action-label">
      <h4>Mina förfrågningar</h4>
    </div>
    <?php if(count($requests) == 0): ?>
      <p>Du har inga förfrågningar just nu.</p>
    <?php else: ?>
      <ul class="result-list">
        <?php foreach ($requests as $request): ?>
          <?php
          $rItem = getItem($request->item_id);
          $from_user = getUser($request->from_user_id);
          $to_user = getUser($request->to_user_id);

          $curr_user;
          if($request->from_user_id == $user_id){
            $curr_user = $from_user;
          } else{
            $curr_user = $to_user;
          }

           ?>
          <li class="result-item request">
            <div class="item-icon">
              <img src="../uploads/images/<?php echo $rItem->image_url; ?>" \>
            </div>
            <div class="item-stats">
              <div class="item-text">
                <h5><?php echo $rItem->name; ?></h5>

                <p class="sm bold">
                  <?php echo $request->start_date;?> - <?php echo $request->end_date;?>
                </p>

                <p class="sm">
                  <?php if($request->from_user_id == $user_id): ?>
                    Till<?php else: ?>Av<?php endif; ?> <?php echo $curr_user->name;?>
                </p>

                <p class="sm">
                  <a href="mailto:<?php echo $curr_user->email;?>"><?php echo $curr_user->email;?></a>
                </p>

                <p class="sm">
                  Tel: <?php echo $curr_user->phone_number;?>
                </p>

              </div>
              <a class="btn sm" href="../search/item.php?id=<?php echo $rItem->id; ?>">Visa mer</a>
              <?php if ($from_user->id == $user_id && $request->approved_date == ""): ?>
                <a class="btn sm green-bg" onclick="approveRequest('<?php echo $request->id; ?>')">Godkänn förfrågan</a>
              <?php elseif($to_user->id == $user_id && $request->approved_date != "" && $request->retrieved_date == ""): ?>
                <a class="btn sm green-bg" onclick="retrieveRequest('<?php echo $request->id; ?>')">Markera hämtat</a>
              <?php elseif($from_user->id == $user_id && $request->approved_date != "" && $request->retrieved_date != "" && $request->returned_date == ""): ?>
                <a class="btn sm green-bg" onclick="returnRequest('<?php echo $request->id; ?>')">Markera återlämnat</a>
              <?php endif; ?>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>

  <div class="myItems">
    <div class="header-action-label">
      <h4>Mina annonser</h4>
      <a class="btn green-bg" href="newitem.php">Skapa annons</a>
    </div>
    <?php if(count($items) == 0): ?>
      <p>Du har inga annonser än. <a class="link" href="newitem.php">Skapa en annons</a>.</p>
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
                <p class="sm"><?php echo $item->description; ?></p>
              </div>
              <a class="btn sm" href="../search/item.php?id=<?php echo $item->id; ?>">Visa mer</a>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>

<?php include_once "../templates/footer_main.php"; ?>

<script>
function approveRequest(req_id){
  if (req_id != "") {
    approveItemRequest(req_id, approveCallback);
  }
}

function retrieveRequest(req_id){
  if (req_id != "") {
    retrieveItemRequest(req_id, approveCallback);
  }
}

function returnRequest(req_id){
  if (req_id != "") {
    returnItemRequest(req_id, approveCallback);
  }
}

function approveCallback(xml){
  var message = xml.getElementsByTagName("message")[0];

  if (message == null){
    var success = xml.getElementsByTagName("success");

    if (success.length == 1) {
      window.location.replace('profile.php');
    }
  } else{
    //failure
  }
}
</script>
</body>
</html>
