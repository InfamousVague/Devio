<?php
    $video = htmlspecialchars($_GET['v']);
?>
<div class="video_info">
    <div class="actions">
       <span id="views"><span class="glyphicon glyphicon-eye-open"></span>&nbsp; 1,999</span> 
       <span id="views"><span class="glyphicon glyphicon-thumbs-up"></span>&nbsp; 34</span>
       <span id="views"><span class="glyphicon glyphicon-thumbs-down"></span>&nbsp; 12</span>
    </div>
    <div class="video_actions">
        <button class="_action"><span class="glyphicon glyphicon-thumbs-up"></span>&nbsp; Like</button>
        <button class="_action"><span class="glyphicon glyphicon-thumbs-down"></span>&nbsp; Meh</button>
        <button class="_action_ghost"><span class="glyphicon glyphicon-heart-empty"></span>&nbsp; Become a Backer</button>
        <a href="<?php echo 'http://devio.tv/videovat/' . $video . '/' . $video .'.mp4'; ?>" download="<?php echo $video . ".mp4"; ?>"><button class="_action_ghost"><span class="glyphicon glyphicon-download"></span>&nbsp; Download</button></a>
        
        <button class="_action_ghost right"><span class="glyphicon glyphicon-cog"></span></button>
    </div>
    <div class="innercontent">
        <div class="row">
            <div class="col-sm-3 col-md-2 col-lg-1">
                <img class="presenter" src="http://placehold.it/70x70/00EEFF/fff&text=User%20IMG" />
            </div>
            <div class="col-sm-9 col-md-10 col-lg-11">
                <h2>Video Title</h2>
                <p class="presenter_info">uploaded by <span>UserName</span> on timestamp</p>
                <p>
                    <span class="label label-tag" style="color:#fff;">Tag</span>
                    <span class="label label-tag" style="color:#fff;">Tag</span>
                    <span class="label label-tag" style="color:#fff;">Tag</span>
                    <span class="label label-tag" style="color:#fff;">Tag</span>
                    <span class="label label-tag" style="color:#fff;">Tag</span>
                    <span class="label label-tag" style="color:#fff;">Tag</span>
                    <span class="label label-tag" style="color:#fff;">Tag</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <script type="text/javascript" src="assets/scripts/info_block.js"></script>
                <div class="description_wrapper">
                    <p class="description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. <br /><br />
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                </div>
                <div class="description_toggle">More...</div>
            </div>
        </div>
    </div>
</div>