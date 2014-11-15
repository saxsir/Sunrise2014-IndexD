<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/config.php';
use Silex\Application;

$app = new Application();

$app['db'] = function() use($app,$host,$mysqldConfig){
    try{
        $con = new PDO(sprintf('mysql:host=%s;dbname=%s;charset=utf8', $host, $mysqldConfig['database']), $mysqldConfig['user'], $mysqldConfig['password'], array(PDO::ATTR_EMULATE_PREPARES => false));
    }catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        die;
    }
    return $con;
};

$con = $app['db'];

define("MAX_USER", 10000);
define("MAX_TWEET", 10000);
define("MAX_FOLLOWER", 10000);

// ユーザーデータのinsert
echo "Insert user data..."
$range = range(1, MAX_USER);
foreach ($range as $i) {
  $name = "ユーザーネーム" . $i;
  $screen_name = "スクリーンネーム" . $i;
  $sql = "INSERT INTO `users` (name, screen_name, profile, created_at, updated_at) values ($name, $screen_name, 'hogehoge', now(), now())";
  $sth = $con->prepare($sql);
  $sth->execute();
}

// followerデータのinsert
echo "Insert follower data..."
$range = range(1, MAX_FOLLOWER);
foreach ($range as $i) {
  $sid = rand(1, MAX_USER);
  $did = rand(1, MAX_USER); //きっと一致することはない...

  $sql = "INSERT INTO `followers` (source_id, destination_id) values ($sid, $did)";
  $sth = $con->prepare($sql);
  $sth->execute();
}

// tweetデータのinsert
echo "Insert tweet data..."
$range = range(1, MAX_TWEET);
foreach ($range as $i) {
  $rand = rand(1, 111111);
  $user_id = rand(1, MAX_USER);
  $screen_name = "スクリーンネーム" . $user_id;
  $time = time();
  $body = "Body_".$screen_name . " " . $time;

  $sql = "INSERT INTO `tweets` (user_id, screen_name, body, created_at) values ($user_id, $screen_name, $body, now())";
  $sth = $con->prepare($sql);
  $sth->execute();
}

$message = 'finished';
echo  $message;
