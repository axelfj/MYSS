<?php
include_once "header.php";
include_once "navbar.php";

require_once "../Controller/connection.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/CollectionHandler.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Cursor.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/DocumentHandler.php";

use ArangoDBCLient\DocumentHandler as ArangoDocumentHandler;
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\Document as ArangoDocument;

$database = connect();
$document = new ArangoCollectionHandler(connect());
$controller = new Controller();
?>

<!--================ Start Blog Post Area =================-->
<section class="blog-post-area">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        if (!isset($_POST['search'])) {
                            include_once "post.inc.php";
                        } else{
                            echo '<h5>Users</h5><br>';
                            if (!empty($dtoUser)){
                                include_once "user-found.inc.php";
                            } else {
                                echo '<div class="alert alert-danger" role="alert">No users found.</div>';
                            }
                            echo '<h5>By tag "'. $_POST['search'] . '"</h5><br>';
                            if (!empty($dtoPost_Comment_Tag)){
                                include "post.inc.php";
                            } else {
                                echo '<div class="alert alert-danger" role="alert">No posts found by tag.</div>';
                            }
                            echo '<h5>By text "'. $_POST['search'] . '"</h5><br>';
                            if (!empty($dtoText)){
                                $dtoPost_Comment_Tag = $dtoText;
                                include "post.inc.php";
                            } else {
                                echo '<div class="alert alert-danger" role="alert">No posts found by text.</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Start Blog Post Sidebar -->
            <div class="col-md-4 sidebar-widgets">
                <div class="widget-wrap">
                    <div class="single-sidebar-widget post-category-widget" style="background: white">
                        <h4 class="ti-tag"> Popular post tags</h4>

                        <ul class="cat-list mt-20">
                            <?php
                            $tags = $controller->getTags();

                            for($i = 0; $i < sizeof($tags) && $i < 5; $i++){?>
                            <li>
                                <a href="#" class="d-flex justify-content-between">
                                    <p><i class="fas fa-tags"></i><?php echo ' ' . $tags[$i];?></p>
                                </a>
                            </li>
                            <?php }?>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
        <!-- End Blog Post Siddebar -->
    </div>

</section>
<!--================ End Blog Post Area =================-->

<?php
include_once "footer.php";
?>
