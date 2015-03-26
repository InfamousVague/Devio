<?php
    require 'init.php';
    
?>
<!DOCTYPE html>
<html>
<head>
    <title>Devio.tv</title>
    <?php
        include('includes/global/meta.php');
        include('includes/global/styles.php');
    ?>
</head>
<body>
<?php 
    ini_set('display_startup_errors',1);
    ini_set('display_errors',1);
    error_reporting(-1);

    ?>
    <?php
        include('includes/global/header.php');
    ?>
    <div class="container">
    <div class="row">
    <div class="page-header">
      <h1>Our Mission</h1>
    </div>
    <p>
    We are sick and tired of entertainment being 40% uploaded content and 60% advertisements. As it stands Devio.tv is ad free, if at some point we see the need to add advertisements to our website we will make sure they are 100% non intrusive and do not hinder you from seeing your content when you want it, un-interrupted. 
<br><br>
That's great and all.... But making my content isn't free.. how the heck am I going to get paid? Well good sir, you, as a content creator will likely have supporters who want to back you along your journey. So what if you could give those people a way to support you AND you can give back to them in a way! We've got that covered. You will have the option to delay your uploads release to the general public, you backers however will be granted instant access to your videos! You can even set goals to reach financially before the video is released. There will be plenty of ways for your viewers and yourself to feel right at home on Devio.tv. 
<br><br>
So... That's how Devio.tv is going to take my money huh? I bet I only see a fraction of each backers monthly payment. NOPE! We only take out for taxes and transaction fees, the rest of your backers contributions go directly to you-- we believe that's the way it should be. 
<br><br>
Devio.tv is currently under very active development so some features may not be implemented and content on this site may be placeholder content. Currently all content uploaded is property of the respective content author unless otherwise specified.
<br><br>
As far as features are concerned we will do our best to implement everything the big name cooperate guys offer, but the full functionality they offer may lack while we go though the stages of improvement. Unfortunately we do not have billions of dollars to spend on fool proof functionality. But you better believe we will be doing our best to get as close as we can!
<br><br>
If you'd like to get in on the action you can sign up above. If you'd like more information or want to get in on this sweet Devio goodness feel free to <a href="mailto:contact.mattdylan@gmail.com">email me.</a></p>
    </div>
    <div class="row">

    <div class="page-header">
      <h1>All Devio Uploads <small>Videos property of their authors, not Devio.tv</small></h1>
    </div>
    <?php
        $dir = 'videovat';
        $directories = scandir($dir);
        $total_usage = 0;
        $total_videos = 0;
        foreach ($directories as $video) {
            if (strlen($video) === 10){
                $total_videos +=1;
                echo '<div class="col-xs-6 col-md-3">';
                $filesize = filesize($dir . '/' . $video . '/' . $video . '.mp4');
                $total_usage += $filesize;
                $kb = $filesize / 1024;
                $mb = $kb / 1024;
                echo '<h3>' . $video . '&nbsp;&nbsp;<small>'. round($mb, 2) . ' MB' . '</small></h4>';
                echo '<a href="http://devio.tv/play?v=' . $video . '" class="thumbnail">';
                echo '<img src="' . $dir . '/' . $video . '/' . $video . '.png"/>';
                echo '</a>';
                echo '</div>';
                
            }else{
                // Not a valid video
            }
        }
    ?>
    </div>
    <div class="row" style="background-image:url('assets/img/backgrounds/heart.png');">
        <div class="page-header">
            <h1>System Usage <small>Temporary alpha feature</small></h1>
        </div>
        <h4>Total Videos Uploaded: 
        <?php
            echo '<span class="label label-primary">' . $total_videos . '</span>';
        ?>
        </h4>
        <h4>Total video disk usage in MB: 
        <?php
            echo '<span class="label label-primary">' . round($total_usage / 1024 / 1024, 2) . ' MB </span>';
        ?>
        </h4>
        <h4>Load Average: 
        <?php
            $load = sys_getloadavg();
            foreach ($load as $cpu){
                echo '<span class="label label-info">' .  $cpu . '</span> &nbsp;';
            }
        ?>
        </h4>
        <h4>Free Disk Space: 
        <?php
            echo '<span class="label label-info">' . round(disk_free_space("/") / 1024 / 1024 / 1024) . ' GB</span>';
        ?>
        </h4>
        <br><br><br><br><br><br>

        </div>
    </div>

    <?php
        include('includes/global/footer.php');
        include('includes/global/scripts.php');
    ?>
</body>
</html>