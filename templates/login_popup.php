<?php
require_once "../classes/include.php";
?>

<link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
<div class="popup-container" id="login-container">
  <script>

   function loginWithFB(fb_id){
     var key = "<?php echo $fb_key['key']; ?>";
     var dk = "<?php echo $fb_key['date']; ?>";

     loginFB(fb_id, key, dk, loginFBCallback);
   }

   function loginFBCallback(xml){
     console.log(xml);
     var message = xml.getElementsByTagName("message")[0];
     if (message == null){
       /*
         Om inloggning lyckas. Gå till profile.php
       */
       var users = xml.getElementsByTagName("user");
       if (users.length == 1) {
         window.location.replace('/account/index.php');
       }
     } else{
       /*
         Om inloggning misslyckas.
       */
       toggleElement("error_password", "Lyckades inte logga in.", true);
     }
   }

   function login(){
     if (checkFields()) {
       var email = document.forms["userLogin"]["email"].value;
       var pw = document.forms["userLogin"]["password"].value;

       loginUser(email, pw, loginCallback);
     }
   }

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

     var pw = document.forms["userLogin"]["password"];
     if (pw.value == "") {
       toggleElement("error_password", "Fyll i ditt lösenord.", true);
       flag = false;
     } else{
       toggleElement("error_password", "", false);
     }

     return flag;
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

   function loginCallback(xml){
     var message = xml.getElementsByTagName("message")[0];
     if (message == null){
       var users = xml.getElementsByTagName("user");
       for (i = 0; i < users.length; i++){
         var id = users[i].getElementsByTagName("id")[0].childNodes[0].nodeValue;
         toggleElement("error_password", "Inloggning lyckades! Du kommer nu att omdirigeras.", true);
         window.location.replace('/account/index.php');
       }
     } else{
       toggleElement("error_password", "Fel email eller lösenord!", true);
     }
   }
  </script>

  <script>
     window.fbAsyncInit = function() {
       FB.init({
         appId            : '<ID>',
         autoLogAppEvents : true,
         xfbml            : true,
         version          : 'v2.10'
       });
       FB.AppEvents.logPageView();
     };

     (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));

      function checkLoginState() {
        FB.getLoginStatus(function(response) {
          statusChangeCallback(response);
        });
      }

      function statusChangeCallback(response) {
        //console.log('statusChangeCallback');
        //console.log(response);
        if (response.status === 'connected') {
          //console.log(response.authResponse.accessToken);
          var uid = response.authResponse.userID;
          loginWithFB(uid);
        } else {
          //document.getElementById("fb-update-access-token").style.display = "flex";
        }
      }

      function fbLogin(){
        FB.login(function(response) {
          statusChangeCallback(response);
         }, {scope: 'public_profile,email'});
      }

      $("#login-container").click(function() {
        $("#login-container").remove();
        gtag('event', 'hide_login', { 'method' : 'needI' });
      });

      $('#login-form').click(function(event){
          event.stopPropagation();
      });

      function showRegister(){
        $("#popup-holder").load("/templates/register_popup.php");
        $("#login_container").remove();
        gtag('event', 'show_register', { 'method' : 'needI' });
      }
  </script>


  <form class="login-form" id="login-form" name="userLogin" action="javascript:void(0);">

    <h4>Logga in</h4>

    <div class="input-group">
      <p class="label">Email</p>
      <input class="input-text" type="email" name="email" placeholder="Email.."/>
      <p class="error" id="error_email"></p>
    </div>

    <div class="input-group">
      <p class="label">Lösenord</p>
      <input class="input-text" type="password" name="password" placeholder="Lösenord.."/>
      <p class="error" id="error_password"></p>
    </div>

    <div class="bottom-actions">
      <a class="link" onclick="showRegister();">Skapa nytt konto</a>
      <button class="btn" onclick="login()">Logga in</button>
    </div>

    <button class="btn fb" onclick="fbLogin();"><i class="fa fa-facebook-square" aria-hidden="true"></i>Logga in med Facebook</button>

  </form>
</div>

 <?php
 $fb_key = getKey();
  ?>
