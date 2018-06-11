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
      <img class="footer-logo" src="../resources/images/logo-white.png"/>

      <ul class="link-list">
        <li class="header">Länkar</li>
        <li><a class="link" href="../index.php">Hem</a></li>
        <li><a class="link" href="../search/main.php">Sök</a></li>
        <li><a class="link" href="../index.php">Om oss</a></li>
        <li><a class="link" href="../index.php">Kontakt</a></li>
        <?php if (!isLoggedIn()): ?>
          <li><a class="link" href="../account/login.php">Logga in</a></li>
        <?php elseif($onProfile): ?>
          <li><a class="link" onclick="logOut()">Logga ut</a></li>
        <?php else: ?>
          <li><a class="link" href="../account/profile.php">Min profil</a></li>
        <?php endif; ?>
      </ul>
    </div>
    <div class="copyright">
      <p>Copyright &copy; .needI <?php echo date("Y"); ?></p>
    </div>
  </div>
</div>
