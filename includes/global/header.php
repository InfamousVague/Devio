<div id="header">
    <nav class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container">
        <div class="container-fluid" style="padding:0;">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="http://devio.tv"><img id="logo" src="assets/img/devio_dark.png" alt="Devio logo"/></a>
            </div>
        
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav" style="margin-left:1em;">
                <li><a href="#">Play</a></li>
                <li><a href="/upload">Upload</a></li>
                <li><a href="/register">Sign Up</a></li>
              </ul>
            <div class="navbar-right" style="margin-top:.6em;margin-left:1em;">
                <?php if ($user->rank_id < 1){echo '<a href="login">';} ?>
                <button class="_action right" id="user_info">
                    <span class="glyphicon glyphicon-user"></span> &nbsp;&blacktriangledown;
                </button>
                <?php if ($user->rank_id < 1){echo '</a>';} ?>

            </div>
            <form class="navbar-form navbar-right" style="width:20em;" role="search">
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" autocomplete="off" class="typeahead">
                        <span class="input-group-btn">
                        <button class="btn btn-default" id="search_glass" type="button">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                    </span>
                </div><!-- /input-group -->
            </form>
            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
      </div>
    </nav>
</div>
<div class="container" style="position:relative;">
    <div id="user_info_dropdown" <?php if($user->rank_id > 0){echo 'status="logged_in"';} ?> >
        <div id="triangle_up"></div>
        <div id="user_info_header">
            <p>
            <?php 
                echo $user->firstname . ' ' . $user->lastname;   
            ?>
            </p>
        </div>
        <ul>
            <li><a href="/login?logout">Logout</a>
            <li><a>
            <?php 
                if($user->rank_id === 1){
                    echo 'Verify Your Email!';
                }else{
                    echo 'Verified!';
                }
            ?></a>
            </li>
        </ul>

    </div>
</div>