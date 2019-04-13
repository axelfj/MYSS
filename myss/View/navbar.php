<?php session_start();

// The connection to the database and dependencies.
require_once "../Controller/connection.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Statement.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Cursor.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/DocumentHandler.php";

// Calls the functions of Arango to manage Collections, Documents, Etc.
use ArangoDBClient\Statement as ArangoStatement;

// Creates a connection to the database.
$database = connect();

try{

    // Var that saves the input.
    $search = '';
    if ($_POST['search'] != ''){
        $search = $_POST['search'] . '%';
    }

    // Makes an AQL query to look for the posts with the tags.
    $query = 'FOR x IN post FILTER x.tagsPost LIKE @tags RETURN x._id';

    // Creates an Statement so we can bind the vars.
    // He will look for the tags in the collection user with the tags that we put in the input.
    $statement = new ArangoStatement(
        $database,
        array(
            "query" => $query,
            "count" => true,
            "batchSize" => 1,   // It is suppose to only bring one.
            "sanitize" => true,
            "bindVars" => array("tags" => $search)
        )
    );

    // Executes the query.
    $cursor = $statement->execute();

    // And saves the result in an array.
    $resultingDocuments = array();

    var_dump($cursor->getCount());
    var_dump($search);



} catch (Exception $e){
    echo 'Algo falló.';
}
?>

<body style="background-color: #E7E7E9;" class="profile-page">
<!--================ Start Header Area =================-->
<header class="">
    <nav class="navbar navbar-expand-lg navbar-light bg-light static-navbar justify-content-end">
        <a class="navbar-brand" style="color:rgb(56, 164, 255);padding-left:400px;" href="index.php"
           onmouseover="this.style.color='rgb(0, 138, 255)';" onmouseout="this.style.color='rgb(56, 164, 255)'">MYSS</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse flex-grow-0 ml-auto" id="navbarSupportedContent">
            <ul class="navbar-nav text-left">
                <li class="nav-item">
                    <div class="input-group md-form form-sm form-2 pl-0">
                        <form class="search-form" action="#">
                            <input class="form-control my-0 py-1 blue-border" type="text" placeholder="Search"
                                   aria-label="Search" name="search" id="search">
                        </form>
                        <div class="input-group-append">
                            <i class="fas fa-search" aria-hidden="true" style="padding-top: 15px; padding-left: 5px;"></i>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <div class="collapse navbar-collapse flex-grow-0 ml-auto" id="navbarSupportedContent">
            <ul class="navbar-nav text-right" style="padding-right:400px;">
                <li class="nav-item">
                    <a class="nav-link" href="profile.php" onmouseover="this.style.color='rgb(56, 164, 255)'"
                       onmouseout="this.style.color='';">
                        <i class="fas fa-user-alt"></i>
                        <span class="badge badge-notify"></span> Profile</a>
                </li>
            </ul>
        </div>
    </nav>

</header>
<br><br><br><br>
<!--================ End Header Area =================-->

<!--================ Pop-Up Post Area =================-->
<div class="modal fade" id="postModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>


