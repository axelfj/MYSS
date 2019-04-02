<div class="panel container" style="background-color: white;">
    <div class="btn-group pull-right postbtn">
        <button type="button" class="dotbtn dropdown-toggle" data-toggle="dropdown" aria-expanded="false"
                style="padding-top: 10px;">
            <span class="dots"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li><a href="javascript:void(0)">Hide this</a></li>
            <li><a href="javascript:void(0)">Report</a></li>
        </ul>
    </div>
    <div class="col-md-12 container" style="background-color: white;">
        <div class="media">
            <div class="media-left"><a href="javascript:void(0)"><img
                            src="https://www.kwsme.com/wp-content/uploads/2016/06/login-user-icon.png" alt=""
                            class="media-object"> </a></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <div class="media-body">
                <h4 class="media-heading">Lucky Sans<br>
                    <small><i class="fa fa-clock-o"></i> Yesterday, 2:00 am</small>
                </h4>
                <hr>
                <p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante
                    sollicitudin commodo. Cras purus odio. </p>

                <ul class="nav nav-pills pull-left ">
                    <li><a href="" title=""><i class="far fa-thumbs-up"></i> 2015</a></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <li><a href="" title=""><i class="far fa-comment-alt"></i> 25</a></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <li><a href="" title=""><i class="fas fa-tags"></i> jis, juas</a></li>
                </ul>
            </div>
        </div>

        <?php include "comment.inc.php";?>
        
        <hr>
        <textarea id="comment" name="comment" type="text" class="form-control"
                  placeholder="Type a new comment..." style="resize: none;"></textarea><br>
        <button id="commentbtn" name="commentbtn" class="btn btn-primary pull-right" disabled><!--<i class="fas fa-cog"></i>-->Comment</button><br><br><br>
    </div>
</div>