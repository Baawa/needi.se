<?php
require_once "../classes/include.php";
 ?>
<div id="register-container" class="popup-container">
  <script>

  function register(fb_id, email, name){
    if (checkFields()) {
      var adress = document.getElementById("adress").value;
      var phone_number = document.getElementById("phone_number").value;
      registerFB(fb_id, email, name, adress, phone_number, registerCallback);
    }
  }

  function checkFields(){
    return true;
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

  function registerCallback(xml){
    var message = xml.getElementsByTagName("message")[0];
    if (message == null){
      /*
        Om inloggning lyckas. Gå till profile.php
      */
      var users = xml.getElementsByTagName("user");
      if (users.length == 1) {
        window.location.replace('/account/profile.php');
      }
    } else{
      /*
        Om inloggning misslyckas.
      */
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
         var email = "";
         var name = "";
         FB.api("/" + uid + "?fields=email,name", function (resp) {
           if (resp && !resp.error) {
             email = resp.email;
             name = resp.name;
             register(uid, email, name);
           }
         });
       } else {
         //
       }
     }

     function fbLogin(){
       FB.login(function(response) {
         statusChangeCallback(response);
        }, {scope: 'public_profile,email'});
     }

     $("#register-container").click(function() {
       $("#register-container").remove();
       gtag('event', 'hide_register', { 'method' : 'needI' });
     });

     $('#register-form').click(function(event){
         event.stopPropagation();
     });
  </script>
  <form class="login-form" id="register-form" name="userRegister" action="javascript:void(0);">
    <h4>Registrera</h4>

    <div class="input-group">
      <p class="label">Postnummer</p>
      <input id="adress" class="input-text" type="text" name="adress" placeholder="t.ex. 413 26..">
      <p class="error" id="error_adress"></p>
    </div>

    <div class="input-group">
      <p class="label">Telefonnummer</p>
      <input id="phone_number" class="input-text" type="text" name="phone_number" placeholder="Telefonnummer..">
      <p class="error" id="error_phone_number"></p>
    </div>

    <div class="input-group">
      <p class="label">Genom att registrera dig godkänner du våra<a class="link" href="../resources/legal/anvandarvillkor.pdf"> Användarvillkor</a>.</p>
    </div>

    <div class="bottom-actions">
      <button class="btn fb" onclick="fbLogin();"><i class="fa fa-facebook-square" aria-hidden="true"></i>Skapa konto med facebook</button>
    </div>
  </form>
</div>
