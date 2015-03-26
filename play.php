<?php
    require 'init.php';
    
    $vidid = htmlspecialchars($_GET["v"]);
    
    $video_title = "Pending Functionality";
    //Grab video title from database
    
    
?>
<!DOCTYPE html>
<html>
<head>
    <title>Devio.tv | <?php echo $video_title; ?></title>
</head>
<body>
<?php 
    ini_set('display_startup_errors',1);
    ini_set('display_errors',1);
    error_reporting(-1);

    ?>
    <?php
        include('includes/global/styles.php');
        include('includes/global/header.php');
    ?>
    <div class="container">    
        <?php
            print file_get_contents(WEBSITE_URL . '/tools/embed_player.php?v=http://devio.tv/videovat/' . $vidid . '/' . $vidid . '.mp4&t=http://devio.tv/videovat/' . $vidid . '/' . $vidid . '.png');
            print file_get_contents(WEBSITE_URL. '/includes/video_assets/info_block.php?v=' . $vidid);
        ?>
        <div class="row">
            <div class="col-sm-12 col-md-9 col-lg-9">
                <?php 
                    include('includes/video_assets/comments.php'); 
                ?>
            </div>
            <div class="col-sm-12 col-md-3 col-lg-3">
                <?php
                    print file_get_contents(WEBSITE_URL. '/includes/video_assets/related_videos.php?v=' . $vidid);
                ?>
            </div>
        </div>
    </div>
    <?php
        include('includes/global/footer.php');
        include('includes/global/scripts.php');
    ?>
</body>
</html>