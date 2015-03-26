<?php
    $video = htmlspecialchars($_GET["v"]);
    $thumbnail = htmlspecialchars($_GET["t"]);
    ini_set('display_startup_errors',1);
    ini_set('display_errors',1);
    error_reporting(-1);
    
    
?>

	<div class="vp_wrapper">
	    <!--<div id="debugging"></div>-->
	    <div class="vp_video_wrap">
	        <div id="currentTime">01:02 / 01:20</div>
	        <a href="http://devio.tv"><img src="http://devio.tv/tools/embed_img/devio_watermark.png" id="devio_watermark" alt="Devio Watermark"></a>
	        <div class="vp_motif" style="display: block; bottom: 0px;"></div>
    	    <span id="bigplay" class="glyphicon glyphicon-play" style="display: block;"></span>
        	<video  id="vp_video" poster="<?php echo $thumbnail; ?>" style="-webkit-filter: blur(1px);">
              <source src="<?php echo $video; ?>" type="video/mp4">
            Your browser does not support the video tag.
            </video>
        </div>
        <div class="vp_scrubber" style="height: 0.3em; margin-top: 0px;">
            <div class="vp_loader" style="width: 0%;"></div>
            <div class="vp_scrubber_bar" style="height: 0.3em; width: 0%;"></div>
            <div class="vp_scrubber_handle ui-draggable" style="left: 536px;"></div>
        </div>
        <div class="vp_controlgroup">
            <button class="vp_button" id="vp_replay"><span class="glyphicon glyphicon-repeat"></span></button>
            <!--<button class="vp_button" id="vp_skip_back"><span class="glyphicon glyphicon-backward"></span></button>-->
            <button class="vp_button" id="vp_play"><span class="glyphicon glyphicon-play"></span></button>
            <!--<button class="vp_button" id="vp_skip_forward"><span class="glyphicon glyphicon-forward"></span></button>-->
            <!--
            <button class="vp_button" id="vp_playback_slower"><span class="glyphicon glyphicon-minus"></span></button>
            <button class="vp_button" id="vp_playback_faster"><span class="glyphicon glyphicon-plus"></span></button> 
            -->
            
            <button class="vp_button btn_right" id="vp_fullscreen"><span class="glyphicon glyphicon-fullscreen"></span></button>
            
            <button class="vp_button btn_right" id="vp_share"><span class="glyphicon glyphicon-send"></span></button>
            <button class="vp_button btn_right" id="vp_love"><span class="glyphicon glyphicon-heart"></span></button>
            <button class="vp_button btn_right" id="vp_watch_later"><span class="glyphicon glyphicon-time"></span></button>

            <span class="vp_controlgroup_button vp_volume"><button class="vp_reset" id="vp_volume"><span class="glyphicon glyphicon-volume-down"></span></button><div class="vp_volume_rocker"><div class="vp_volume_meter" style="width: 14.390625%;"><div class="vp_volume_handle ui-draggable" style="left: 3.390625px;"></div></div></div></span>

        </div>
    </div>
    <link rel="stylesheet" href="http://devio.tv/assets/twitter_bootstrap/css/bootstrap.min.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    
    <script type="text/javascript">
    $(document).ready(function(){
        var vp_video = document.getElementById("vp_video"),
            $vp_wrapper = $('#vp_wrapper'),
            $vp_play = $('#vp_play'),
            $vp_replay = $('#vp_replay'),
            $vp_skip_back = $('#vp_skip_back'),
            $vp_skip_forward = $('#vp_skip_forward'),
            $currentTime = $('#currentTime'),
            $faster = $('#vp_playback_faster'),
            $slower = $('#vp_playback_slower'),
            $vp_volume = $('#vp_volume'),
            vp_duration,
            scrubbing,
            debugging = true,
            hideCursor,
            hideTime,
            debug = $('#debugging'),
            scrubbing = false;
            

        $('#vp_fullscreen').click(function(){
            var videoElement = document.getElementById('vp_video');    
            videoElement.webkitRequestFullScreen();
        });
        
        $('.vp_wrapper').mousemove(function( event ) {
            $('#currentTime').show();
            clearTimeout(hideTime);
            hideTime = setTimeout(function(){
                $('#currentTime').fadeOut();
            }, 500);
        });

        $('.vp_video_wrap').mousemove(function( event ) {
            $(this).css('cursor', 'auto');
            clearTimeout(hideCursor);
            hideCursor = setTimeout(function(){
                $('.vp_video_wrap').css('cursor', 'none');
            }, 1000);
        });

        vp_video.volume = .5;
        
        $vp_play.click(function(){
            if ( vp_video.paused ) {
                vp_video.play();
                $(this).html('<span class="glyphicon glyphicon-pause"></span>');
            }else{
                vp_video.pause();
                $(this).html('<span class="glyphicon glyphicon-play"></span>');
            }
        });
        
        $('.vp_volume_rocker').click(function(e){
            vp_video.volume = Math.floor(e.pageX - $(this).offset().left) / 100;
            $('.vp_volume_meter').css('width', Math.floor(e.pageX - $(this).offset().left) + '%');
            $('.vp_volume_handle').css('left', (e.pageX - $(this).offset().left - 10)  + 'px');
        });
        
        /*$('.vp_scrubber').click(function(e){
            var current_pos = (e.pageX - $(this).offset().left) / 10;
            vp_video.currentTime = (vp_video.duration / 100) * current_pos;
        });*/
        
        var cached_volume = 0;
        $vp_volume.click(function(){
            if (vp_video.volume > 0){
                cached_volume = vp_video.volume;
                vp_video.volume = 0;
                $(this).html('<span class="glyphicon glyphicon-volume-off"></span>');
            }else{
                vp_video.volume = cached_volume;
                $(this).html('<span class="glyphicon glyphicon-volume-up"></span>');
            }
        });
        
        $('.vp_video_wrap').click(function(){
            if ( vp_video.paused ) {
                vp_video.play();
                $('#vp_play').html('<span class="glyphicon glyphicon-pause"></span>');
            }else{
                vp_video.pause();
                $('#vp_play').html('<span class="glyphicon glyphicon-play"></span>');
            }
        });
        
        $('.vp_video_wrap, .vp_scrubber').mouseover(function(){
            $('.vp_scrubber').css({'height' : '.7em', 'margin-top' : '-.4em'});
            $('.vp_scrubber_bar').css({'height' : '.7em'});
            $('.vp_motif').css({'bottom': '.4em'});
        });
        
        $('.vp_video_wrap, .vp_scrubber').mouseout(function(){
            $('.vp_scrubber').css({'height' : '.3em', 'margin-top' : '0'});
            $('.vp_scrubber_bar').css({'height' : '.3em'});
            $('.vp_motif').css({'bottom': '0'});
        });
        
        $vp_replay.click(function(){
            vp_video.currentTime = 0;
        });
        
        $faster.click(function(){
            vp_video.playbackRate += .25;
        });
        $slower.click(function(){
            vp_video.playbackRate -= .25;
        });
        
        $vp_skip_back.mousedown(function(){
            scrubbing = setInterval(function(){
                if (vp_video.currentTime > .5){
                    vp_video.currentTime -= .5;
                }
            },100);
        });
        
        $vp_skip_back.mouseup(function(){
            clearInterval(scrubbing);
        });
        
        $vp_skip_forward.mousedown(function(){
            scrubbing = setInterval(function(){
                vp_video.currentTime += .5;
            },100);
        });
        
        $vp_skip_forward.mouseup(function(){
            clearInterval(scrubbing);
        });
        
        $( ".vp_volume_handle" ).draggable({ 
            containment: ".vp_volume_rocker", 
            axis: "x", 
            drag: function( event, ui ) {
                var position = $('.vp_volume_handle').position();
                $('.vp_volume_meter').css('width', (position.left +10) + '%');
                vp_video.volume = (position.left+5) / 100;
                if (position.left < 50){
                    $('.vp_reset').html('<span class="glyphicon glyphicon-volume-down"></span>');
                }else{
                    $('.vp_reset').html('<span class="glyphicon glyphicon-volume-up"></span>');
                }
            }
        });
        $( ".vp_scrubber_handle" ).draggable({ 
            containment: ".vp_scrubber", 
            axis: "x", 
            drag: function( event, ui ) {
                var position = $('.vp_scrubber_handle').position();
                var scrubber_percent = ((position.left + $('.vp_scrubber_handle').width()) / $('.vp_scrubber').width());
                var video_time = vp_video.duration * scrubber_percent;
                vp_video.currentTime = video_time;
            }
        });
        $('#vp_video').bind('progress', function() {
             $('.vp_loader').css('width', (($('#vp_video').get(0).buffered.end(0) / $('#vp_video').get(0).duration)*100) + '%');
        });
        setInterval(function(){
                vp_duration = vp_video.duration;
                var vp_video_percent = ((vp_video.currentTime / vp_duration) * 100) + '%';
                $('.vp_scrubber_bar').css('width',vp_video_percent);
                $('.vp_scrubber_handle').css('left', ($('.vp_scrubber_bar').width()-10));
                $('#currentTime').css('left', ($('.vp_scrubber_bar').width()) - ($('#currentTime').width()/2) - 5);
                if (!vp_video.paused){
                    $('#vp_video').css('-webkit-filter', 'grayscale(0)');
                    $('#bigplay, .vp_motif').fadeOut();
                    function secondstotime(secs){
                        var t = new Date(1970,0,1);
                        t.setSeconds(secs);
                        var s = t.toTimeString().substr(0,8);
                        if(secs > 86399)
                        	s = Math.floor((t - Date.parse("1/1/70")) / 360000) + s.substr(2);
                        return s.substring(3);
                    }
                    $currentTime.html(secondstotime(Math.floor(vp_video.currentTime)) + " / " + secondstotime(Math.floor(vp_video.duration)));
                    if (debugging){
                        debug.html(' ');
                        debug.append('Video Length: ' + vp_duration +'s <br>');
                        debug.append('Video Current Time: ' + vp_video.currentTime +'s <br>');
                        debug.append('Video Volume: ' + vp_video.volume +'<br>');
                        debug.append('Video Playing: True<br>');
                    }
                }else{
                    $('#bigplay, .vp_motif').show();
                    $('#vp_video').css('-webkit-filter', 'grayscale(0.6)');
                    if (debugging){
                        debug.html(' ');
                        debug.append('Video Length: ' + vp_duration +'s <br>');
                        debug.append('Video Current Time: ' + vp_video.currentTime +'s <br>');
                        debug.append('Video Volume: ' + vp_video.volume +'<br>');
                        debug.append('Video Playing: False<br>');
                    }
                }
        }, 50);
    });
    </script>
    
    <style>
        .btn_right{
            float:right;
            margin-left:.5em;
        }
        #devio_watermark{
            position:absolute;
            bottom:.5em;
            right:.5em;
            z-index:40;
            width:100px;
        }
        .vp_scrubber{
            border-top:1px solid #333;
            border-bottom:1px solid #000;
            width:100%;
            height:.3em;
            background:url('http://devio.tv/tools/embed_img/stripe.png');
            overflow:hidden;
            position:relative;
        }
        .vp_loader{
            position:absolute;
            height:.7em;
            background:rgba(255,255,255,.2);
            right:100%;
            left:0;
            z-index:28;
        }
        .vp_video_wrap{
            position:relative;
        }
        #vp_video{
            transition: -webkit-filter 1s;
            width:100%;
            max-height:50em;
        }
        .vp_scrubber_bar{
            width:50%;
            height:.3em;
            z-index:30;
            position:relative;
            background:url('http://devio.tv/tools/embed_img/striped.png');
            border-right:1px solid #000;
            box-shadow:inset 0 1px 2px rgba(255,255,255,.5), inset 0 -1px 2px rgba(0,0,0,.5),5px 0px 5px rgba(0,204,255,.5);
        }
        .vp_scrubber, .vp_scrubber_bar{
            transition:height .5s, margin-top .5s;
        }
        .vp_controlgroup{
            padding:.3em;
            width:100%;
            background: #222;
        }
        .vp_button{
            border:1px solid #000;
            color:#fff;
            outline: none;
            border-radius:3px;
            background:none;
            font-size:9pt;
            padding:.6em;
            padding-bottom:.6em;
            width:3em;
        }
        .vp_scrubber_handle{
            position:absolute;
            left:-10px;
            height:1em;
            top:0;
            background:#ddd;
            width:10px;
            border-left:1px solid #333;
            z-index:30;
        }
        .vp_scrubber_handle:hover{
            cursor:pointer;
        }
        .vp_volume{
            padding-top:.5em;
            padding-bottom:0;
            height:1.6em;
            float:right;
            width:9.5em;
            margin-right:.3em;
            color:#fff;
        }
        .vp_volume_handle{
            width:10px;
            height:.5em;
            position:absolute;
            right:0;
            top:0;
            left:50%;
            background:#fff;
            border-left:1px solid #333;
            z-index:20;
        }
        .vp_volume_handle:hover{
            cursor:pointer;
        }
        .vp_volume_rocker{
            float:right;
            background:url('http://devio.tv/tools/embed_img/stripe.png');
            border-radius:2px;
            border:1px solid #333;
            width:7em;
            margin:0;
            height:.6em;
            margin-right:.4em;
            margin-top:.35em;
            overflow:hidden;
        }
        #vp_fullscreen{
            float:right;
        }
        .vp_volume_meter{
            width:90%;
            background:url('http://devio.tv/tools/embed_img/striped.png');
            height:.5em;
            box-shadow:inset 0 1px 2px rgba(255,255,255,.5), inset 0 -1px 2px rgba(0,0,0,.5),5px 0px 5px rgba(0,204,255,.5);
            position:relative;
            border-radius:2px 0 0 2px;
            border-right:1px solid #000;
        }

        .vp_controlgroup button:hover{
            background:#111;
            color:rgb(0,204,255);
        }
        .vp_reset{
            background:none;
            border:none;
            outline: none;
        }
        .vp_reset:hover{
            background:transparent;
            color:rgb(0,204,255);
        }
        .vp_wrapper{
            width:100%;
            height:auto;
            margin:0 auto;
            background:#000;
            overflow:hidden;
            border: 2px solid #222;
            position:relative;
            
        }
        #currentTime{
            color:#fff;
            padding:.2em;
            font-size:8pt;
            display:inline;
            position:absolute;
            bottom:.5em;
            z-index:40;
            background:rgba(0,0,0,.5);
        }
        #bigplay{
            position:absolute;
            color:#fff;
            font-size:30pt;
            top:50%;
            left:50%;
            z-index:30;
            margin-left:-55px;
            margin-top:-50px;
            padding:.7em;
            background:rgba(0,0,0,.5);
            border-radius:6px;
        }
        .vp_volume button{
            float:left;
            margin-top:-.15em;
        }
        .vp_volume button:hover{
            background:none;
        }
        .vp_motif{
            position:absolute;
            top:0;
            right:0;
            bottom:0;
            left:0;
            background:url('http://devio.tv/tools/embed_img/motif.png');
            z-index:20;
            opacity:.3;
            transition:bottom .5s;
        }
        #debugging{
            position:absolute;
            top:.5em;
            left:.5em;
            color:#fff;
            font-size:10pt;
            z-index:31;
        }
    </style>