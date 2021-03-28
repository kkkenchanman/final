<?php

ini_set( 'display_errors', 1 );
ini_set( 'error_reporting', E_ALL );

// ----------------------------------------------------
//  データベース接続
//-----------------------------------------------------

function dbConnect(){
  $dsn = 'mysql:host=localhost;dbname=yukigassen_APP;charset=utf8';
  $user = 'yukigassen_APP_user';
  $pass = 'yukigassenAPP';

  try{
    $dbh = new PDO($dsn, $user, $pass, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
  }catch(PDOException $e){
    echo 'error'.$e->getMessage();
    exit();
  }
  return $dbh;
}
$dbh = dbConnect();

// ----------------------------------------------------
//  コメント登録
//-----------------------------------------------------
$comment = $_POST['comment'];
$id = $_POST['id'];

// if(empty($comment)){
//   echo 'コメントが未入力です';
// };

$sql="INSERT INTO
        comments (
          comment,
          gameId
        )
      VALUES(
          :comment,
          :gameId
        )";

$dbh->beginTransaction();
try{
  $stmt=$dbh->prepare($sql);
  $stmt -> bindValue(':comment',$comment, PDO::PARAM_STR);
  $stmt -> bindValue(':gameId',$id, PDO::PARAM_INT);
  $stmt->execute();
  $dbh->commit();

  exit;
}catch(PDOException $e){
  $dbh->rollback();
  exit($e);
}

?>