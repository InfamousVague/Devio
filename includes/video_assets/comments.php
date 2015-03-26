<div class="page-header">
  <h1 style="color:#445566;">Comments</h1>
</div>
<div class="row">
    <div class="write_comment" style="padding:1em;">
        <p><strong>
            <?php 
                if($user->rank_id > 1){
                    echo $user->firstname . ' ' . $user->lastname . ' says...';   
                }
            ?>
        </strong></p>   
        <?php 
            if($user->rank_id < 1){
                echo '<textarea class="form-control" rows="5"  disabled="disabled" placeholder="Please login to comment..."></textarea>';
            }else if($user->rank_id < 2){
                echo '<textarea class="form-control" rows="5"  disabled="disabled" placeholder="You must verify your email to comment..."></textarea>';
            }else{
                echo '<textarea class="form-control" rows="5"  placeholder="Say something about this video..."></textarea>';
            }
        ?>
        <button class="_action right" style="margin:1em 0 1em 1em">Say It Man!</button>
    </div>
</div>
<div class="row">
    <div class="comments">
        <div class="comment">
            <div class="col-md-2">
                <img class="presenter" src="http://placehold.it/70x70.png" alt="Comment by USERNAME"/>
            </div>
            <div class="col-md-10">
                <div class="comment_content">
                    <div class="arrow-right"></div><div class="arrow-right_2"></div>
                    <p><strong>Commenter Name</strong> <span class="timestamp">timestamp</span><hr>
                        <span class="comment_body">
                        <br />
                        sdkjhflkasdjlkfjhaskjdfh jkhdsafjkl hasdfkljasdh lkfhiuasdhfiou hiaudsnf iouasdbiouf basduf ioausdbfyu absduybf lkjbnqwe iqwue nfbioquweb oqiuwbel kjbfiuasdbf uyasdfb qweukf.
                        <br /><br />
                        </span>
                    <hr>
                    <a href="#" class="comment_action"><span class="glyphicon glyphicon-flag"></span> Flag</a> &nbsp;&nbsp;&nbsp;
                    <a href="#" class="comment_action"><span class="glyphicon glyphicon-thumbs-up"></span> Like</a> &nbsp;&nbsp;&nbsp;
                    <a href="#" class="comment_action"><span class="glyphicon glyphicon-thumbs-down"></span> Meh</a> &nbsp;&nbsp;&nbsp;
                    <a href="#" class="comment_action"><span class="glyphicon glyphicon-share-alt"></span> Reply</a>
                    </p>
    
                </div>
            </div>
        </div>
    </div>
</div>