<?php

namespace ArangoDBClient;

require_once("../Controller/connection.php");

/* set up some example statements */
$statements = [
    //'FOR u IN user FILTER u.username == @username return u.username' => ['username'=>'azzefj'],
    //'FOR u IN post FILTER u.tagsPost == @tagsPost RETURN u._key' => ["tagsPost" => "Estoesuntag,tag,esto,es"]
    // here goes the query//
];

function readCollection($statements)
{
    try {
        $connection = connect();
        foreach ($statements as $query => $bindVars) {
            $statement = new Statement($connection, [
                    'query' => $query,
                    'count' => true,
                    'batchSize' => 1000,
                    'bindVars' => $bindVars,
                    'sanitize' => true,
                ]
            );

            $cursor = $statement->execute();
        }
        return $cursor;
    } catch (ConnectException $e) {
        print $e . PHP_EOL;
    } catch (ServerException $e) {
        print $e . PHP_EOL;
    } catch (ClientException $e) {
        print $e . PHP_EOL;
    }
}

// In case, we need to execute a query without binding anything.
function readCollectionWithoutBinding($query){
    try {
        $connection = connect();
            $statement = new Statement($connection, [
                    'query' => $query,
                    'count' => true,
                    'batchSize' => 1000,
                    'sanitize' => true,
                ]
            );

            $cursor = $statement->execute();

        return $cursor;
    } catch (ConnectException $e) {
        print $e . PHP_EOL;
    } catch (ServerException $e) {
        print $e . PHP_EOL;
    } catch (ClientException $e) {
        print $e . PHP_EOL;
    }
}
