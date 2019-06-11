<?php

use ArangoDBCLient\DocumentHandler as ArangoDocumentHandler;
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\Document as ArangoDocument;
use function ArangoDBClient\readCollection;


require_once "../Model/executeQuery.php";
require_once "../Model/createEdges.php";
require_once "../Controller/connection.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/CollectionHandler.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Cursor.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/DocumentHandler.php";


class UserQuery
{
    public static function register($username, $email, $password, $name, $birthday, $userImage)
    {
        $database = new ArangoDocumentHandler(connect());
        $user = new ArangoDocument();
        $user->set("username", $username);
        $user->set("email", $email);
        $user->set("password", $password);
        $user->set("name", $name);
        $user->set("birthday", $birthday);
        if ($userImage == '') {
            $userImage = "img/user.jpg";
        }
        $user->set("userImage", $userImage);

        $database->save("user", $user);
        return 'You have been successfully registered';
    }

    public static function isUsernameTaken($username)
    {
        $document = new ArangoCollectionHandler(connect());
        $cursorUser = $document->byExample('user', ['username' => $username]);
        if ($cursorUser->getCount() == 0) {
            return false;
        }
        return true;
    }

    public static function isEmailTaken($email)
    {
        $document = new ArangoCollectionHandler(connect());
        $cursorEmail = $document->byExample('user', ['email' => $email]);
        if ($cursorEmail->getCount() == 0) {
            return false;
        }
        return true;
    }

    public static function getInformation($email)
    {
        $query = ['
        FOR x IN user 
        FILTER x.email == @email 
        RETURN {password: x.password, key: x._key, username: x.username, name: x.name, email: x.email, userImage: x.userImage}' => ['email' => $email]];
        $cursor = readCollection($query);
        return $cursor;
    }

    public static function getProfile($username)
    {
        $query = ['
        FOR x IN user 
        FILTER x.username == @username
        RETURN {key: x._key, username: x.username, name: x.name, email: x.email, birthday: x.birthday, 
                userImage: x.userImage}' => ['username' => $username]];

        $cursor = readCollection($query);
        $resultingDocuments = array();

        if ($cursor->getCount() > 0) {
            $profile = array();

            foreach ($cursor as $key => $value) {
                $resultingDocuments[$key] = $value;
                $profile['username'] = $resultingDocuments[$key]->get('username');
                $profile['name'] = $resultingDocuments[$key]->get('name');
                $profile['email'] = $resultingDocuments[$key]->get('email');
                $profile['birthday'] = $resultingDocuments[$key]->get('birthday');
                $profile['key'] = $resultingDocuments[$key]->get('key');
                $profile['userImage'] = $resultingDocuments[$key]->get('userImage');
            }
            return $profile;
        }
        return null;
    }

    public static function getUsernameAndImage($userKey){
        try {
            $statements = [
                'FOR u in user 
                FILTER u._key == @userKey 
                RETURN {username: u.username, userImage: u.userImage}' => ['userKey' => $userKey]];

            $cursor = readCollection($statements);
            $resultingDocuments = array();
            $usernameAndPicture = array();

            if ($cursor->getCount() > 0) {
                foreach ($cursor as $key => $value) {
                    $resultingDocuments[$key] = $value;
                    $usernameAndPicture['username'] = $resultingDocuments[$key]->get('username');
                    $usernameAndPicture['userImage'] = $resultingDocuments[$key]->get('userImage');
                }
            }
            return $usernameAndPicture;
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    // We must send the keys.
    public static function followUser($fromUser, $toUser)
    {
        return userFollow($fromUser, $toUser);
    }

    // Checks if an user is following another one.
    // We must send the keys.
    public static function ifFollowing($fromUser, $toUser)
    {
        $query = ['
        FOR x IN follows 
        FILTER x._from == @fromUser && x._to == @toUser
        RETURN x._key' => ['fromUser' => 'user/' . $fromUser, 'toUser' => 'user/' . $toUser]];
        $cursor = readCollection($query);

        // Checks if we got the graph. If the graph exists, he will return true.
        $dataFound = $cursor->getCount();
        if ($dataFound > 0) {
            return true;
        }
        return false;
    }
    // Gets all my friends usernames.
    public static function getAllMyFriends($userId)
    {
        // In the Follows collection we must ask for the user/$userid.
        $userIdComplete = 'user/' . $userId;
        $statements = [
            'FOR x in follows
            FILTER x._from == @fromUser 
            RETURN x' => ['fromUser' => $userIdComplete]];
        return readCollection($statements);
    }


    public static function changeUsername($currentUsername, $newUsername)
    {
        $statements = [
            'FOR u IN user
            FILTER u.username == @current
            UPDATE u WITH { username: @new } IN user' => ['current' => $currentUsername, 'new' => $newUsername]];
        readCollection($statements);
    }

    public static function changeEmail($currentEmail, $newEmail)
    {
        $statements = [
            'FOR u IN user
            FILTER u.email == @current
            UPDATE u WITH { email: @new } IN user' => ['current' => $currentEmail, 'new' => $newEmail]];
        readCollection($statements);
    }

    public static function changeName($username, $newName)
    {
        $statements = [
            'FOR u IN user
            FILTER u.username == @username
            UPDATE u WITH { name: @name } IN user' => ['username' => $username, 'name' => $newName]];
        readCollection($statements);
    }

    public static function changeBirthday($username, $newBirthday)
    {
        $statements = [
            'FOR u IN user
            FILTER u.username == @username
            UPDATE u WITH { birthday: @birthday } IN user' => ['username' => $username, 'birthday' => $newBirthday]];
        readCollection($statements);
    }

    public static function changePassword($username, $newPassword)
    {
        $statements = [
            'FOR u IN user
            FILTER u.username == @username
            UPDATE u WITH { password: @password } IN user' => ['username' => $username, 'password' => $newPassword]];
        readCollection($statements);
    }

    public static function changePicture($username, $newPicture)
    {
        $statements = [
            'FOR u IN user
            FILTER u.username == @username
            UPDATE u WITH { userImage: @userImage } IN user' => ['username' => $username, 'userImage' => $newPicture]];
        readCollection($statements);
    }
    public static function getAllMyFriendsKeys($userId){
        $cursor = UserQuery::getAllMyFriends($userId);
        $userKeys = array();
        foreach ($cursor as $key => $value){
            $resultingDocuments[$key] = $value;
            $userkey = $resultingDocuments[$key]->get('_to');

            array_push($userKeys, $userkey);
        }
        return $userKeys;

    }

    public static function searchFriends($username, $userId){
        $userkeys = UserQuery::getUsersStartingWith($username);
        $friends = self::getAllMyFriendsKeys($userId);

        $result = array();

        foreach ($userkeys as $ukey => $uvalue){
            foreach ($friends as $fkey => $fvalue){
                if($uvalue["key"] == $fvalue){
                    array_push($result, $uvalue);
                }
            }
        }
        return $result;

    }

    public static function getUsersStartingWith($username){
        $username .= "%";
        $statement = [
            'FOR u IN user 
            FILTER u.username LIKE @username
            RETURN {key: u._id, username: u.username}' => ['username' => $username]
        ];

        $cursor = readCollection($statement);
        $userKeys = array();
        foreach ($cursor as $key => $value){
            $resultingDocuments[$key] = $value;
            $userkey['key'] = $resultingDocuments[$key]->get('key');
            $userkey['username'] = $resultingDocuments[$key]->get('username');
            array_push($userKeys, $userkey);
        }
        return $userKeys;
    }
}