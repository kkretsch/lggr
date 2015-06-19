    <div class="container">
      <hr>
      <footer>
        <p class="debugfooter"><?= $aPerf['count'] ?> queries in <?= $aPerf['time'] ?> seconds. Session: <?= $_COOKIE['PHPSESSID'] ?> by <?= htmlentities($_SERVER['REMOTE_USER']) ?></p>
        <p>&copy; <a href="http://lggr.io" target="_blank">lggr.io</a> 2015</p>
      </footer>
    </div> <!-- /container -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<?php
if('stats'==basename($_SERVER['SCRIPT_NAME'], '.php')) {
?>
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
    <script src="js/lggr_stats.js"></script>
<?php
} // if
?>
    <script src="js/lggr.js"></script>
  </body>
</html>
