
/*
  url => where to fetch the data.
  callback => a function taking the responseXML as it's parameter
*/
function makeAJAXCall(url, callback){
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      callback(this.responseXML);
    }
  };
  xhttp.open("GET", url, true);
  xhttp.send();
}

function makeAJAXPOSTCall(url, formData, callback){
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      callback(this.responseXML);
    }
  };
  xhttp.open('POST', url, true);
  xhttp.send(formData);
}

function loadToElement(url, id){
  var elem = document.getElementById(id);

  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      elem.innerHTML = this.responseText;
    }
  }
  xhttp.open("GET", url, true);
  xhttp.send();
}

function loginUser(email, pw, callback){
  var url = "../classes/account/login.php?a=login&email=" + encodeURIComponent(email) + "&password=" + encodeURIComponent(pw);

  makeAJAXCall(url, callback);
}

function loginFB(fb_id, key, dk, callback){
  var url = "../classes/account/login.php?a=loginFB&fb_id=" + encodeURIComponent(fb_id) + "&key=" + encodeURIComponent(key) + "&dk=" + encodeURIComponent(dk);

  makeAJAXCall(url, callback);
}

function registerFB(fb_id, email, name, adress, phone_number, callback){

  var url = "../classes/account/login.php?a=registerFB&fb_id=" + encodeURIComponent(fb_id) + "&email=" + encodeURIComponent(email) + "&name=" + encodeURIComponent(name) + "&adress=" + encodeURIComponent(adress) + "&phone_number=" + encodeURIComponent(phone_number);

  makeAJAXCall(url, callback);
}

function logOut(){
  var url = "../classes/account/login.php?a=logout";

  makeAJAXCall(url, callBackLogOut);
}

function callBackLogOut(xml){
  window.location.replace("../index.php");
}

function getItems(user_id, callback){
  var url = "../classes/account/items.php?a=get&user_id=" + encodeURIComponent(user_id);

  makeAJAXCall(url, callback);
}

function getItem(item_id, callback){
  var url = "../classes/account/items.php?a=get-single&item_id=" + encodeURIComponent(item_id);

  makeAJAXCall(url, callback);
}

function createItem(formData, callback){
  var url = "../classes/account/items.php?a=create";

  makeAJAXPOSTCall(url, formData, callback);
}

function updateItem(item_id, name, description, price, time_available, days_available, callback){
  var url = "../classes/account/items.php?a=update&item_id=" + encodeURIComponent(item_id) + "&name=" + encodeURIComponent(name) + "&description=" + encodeURIComponent(description) + "&price=" + encodeURIComponent(price) + "&time_available=" + encodeURIComponent(time_available) + "&days_available=" + encodeURIComponent(days_available);

  makeAJAXCall(url, callback);
}

function searchItem(name, callback){
  var url = "..classes/account/items.php?a=search&name=" + encodeURIComponent(name);

  makeAJAXCall(url, callback);
}

function bookItem(item_id, user_id, start_date, end_date, callback){
  var url = "../classes/account/items.php?a=book&item_id=" + encodeURIComponent(item_id) + "&user_id=" + encodeURIComponent(user_id) + "&start_date=" + encodeURIComponent(start_date) + "&end_date=" + encodeURIComponent(end_date);

  makeAJAXCall(url, callback);
}

function approveItemRequest(req_id, callback){
  var url = "../classes/account/items.php?a=approve&request_id=" + encodeURIComponent(req_id);

  makeAJAXCall(url, callback);
}

function retrieveItemRequest(req_id, callback){
  var url = "../classes/account/items.php?a=retrieve&request_id=" + encodeURIComponent(req_id);

  makeAJAXCall(url, callback);
}

function returnItemRequest(req_id, callback){
  var url = "../classes/account/items.php?a=return&request_id=" + encodeURIComponent(req_id);

  makeAJAXCall(url, callback);
}

function setCookieConsent(){
  var url = "../classes/account/login.php?a=cookieConsent";

  makeAJAXCall(url, hideCookieBanner);
}

function hideCookieBanner(){
  document.getElementById('cookie-banner').style.display = "none";
}

function showLogin(){
  $("#popup-holder").load("/templates/login_popup.php");

  gtag('event', 'login', { 'method' : 'needI' });
}
