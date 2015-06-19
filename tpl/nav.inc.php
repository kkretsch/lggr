    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="./do.php?a=reset"><img src="/img/logo.png" alt="Lggr.io" /></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="stats.php">Statistic</a></li>
            <li><a href="https://lggr.io" target="_blank">Project</a></li>
          </ul>
          <form method="post" action="./do.php?a=search" class="navbar-form navbar-right">
            <div class="form-group">
              <input name="q" placeholder="Search in messages" class="form-control" type="text" value="<?= $searchvalue ?>">
            </div>
            <button type="submit" class="btn btn-success">Search</button>
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

