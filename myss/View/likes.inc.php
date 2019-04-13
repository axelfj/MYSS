<?php

require_once "../Controller/connection.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/CollectionHandler.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Cursor.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/DocumentHandler.php";
require_once "../Controller/createEdges.php";
require_once "../Controller/readCollection.php";

session_start();

use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use function ArangoDBClient\readCollection;
use ArangoDBClient\Statement as ArangoStatement;


$url = $_SERVER['REQUEST_URI'];
$posStart = strpos($url, '@') + 1;
$posEnd = strlen($url);
$postKey = substr($url, $posStart, $posEnd - $posStart);
$pos = strpos($url, 'profile.php');
if ($pos == false) {
    header('Location: index.php');
} else {
    header('Location: profile.php');
}
try {
    $database = connect();
    $document = new ArangoCollectionHandler(connect());

    $cursor = $document->byExample('post', ['visibility' => "Public"], ['visibility' => "Private"]);

    if ($cursor->getCount() != 0) {
        $statements = [
            'FOR u in liked 
            FILTER u._to == @postKey && u._from == @userKey 
            RETURN u'
            => ['postKey' => $postKey, 'userKey' => $_SESSION['userKey']]];
        $liked = readCollection($statements);
        var_dump($liked);

        if ($liked->getCount() == 0) {

            $statements = [$query = 'FOR x IN post FILTER x._key == @postKey UPDATE { _key: x._key, likes: x.likes+1} IN post' => ['postKey' => $postKey]];
            readCollection($statements);

            createEdge('user', $_SESSION['userKey'], 'post', $postKey, 'liked');
        }
        else{
            // delete the edge //
        }
    }

} catch (Exception $e) {
    echo $e->getMessage();
}
