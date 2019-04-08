<?php
require __DIR__ . '/arangodb-php/autoload.php';

// set up some aliases for less typing later
use ArangoDBClient\Collection as ArangoCollection;
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\Connection as ArangoConnection;
use ArangoDBClient\ConnectionOptions as ArangoConnectionOptions;
use ArangoDBClient\DocumentHandler as ArangoDocumentHandler;
use ArangoDBClient\Document as ArangoDocument;
use ArangoDBClient\Exception as ArangoException;
use ArangoDBClient\Export as ArangoExport;
use ArangoDBClient\ConnectException as ArangoConnectException;
use ArangoDBClient\ClientException as ArangoClientException;
use ArangoDBClient\ServerException as ArangoServerException;
use ArangoDBClient\Statement as ArangoStatement;
use ArangoDBClient\UpdatePolicy as ArangoUpdatePolicy;

ArangoException::enableLogging();

function connect(){
    try{
        $connectionOptions = [
            ArangoConnectionOptions::OPTION_DATABASE => 'myss', // database name
            ArangoConnectionOptions::OPTION_ENDPOINT => 'tcp://127.0.0.1:8529', // server endpoint to connect to
            ArangoConnectionOptions::OPTION_AUTH_TYPE => 'Basic', // authorization type to use (currently supported: 'Basic')
            ArangoConnectionOptions::OPTION_AUTH_USER => 'root', // user for basic authorization
            ArangoConnectionOptions::OPTION_AUTH_PASSWD => '1234', // password for basic authorization
            ArangoConnectionOptions::OPTION_CONNECTION => 'Keep-Alive', // connection persistence on server. can use either 'Close' (one-time connections) or 'Keep-Alive' (re-used connections)
            ArangoConnectionOptions::OPTION_TIMEOUT => 3, // connect timeout in seconds
            ArangoConnectionOptions::OPTION_RECONNECT => true, // whether or not to reconnect when a keep-alive connection has timed out on server
            ArangoConnectionOptions::OPTION_CREATE => true, // optionally create new collections when inserting documents
            ArangoConnectionOptions::OPTION_UPDATE_POLICY => ArangoUpdatePolicy::LAST, // optionally create new collections when inserting documents
        ];
        $connection = new ArangoConnection($connectionOptions);
    } catch (Exception $exception){
        echo "No conectó.";
    }
    return $connection;
}