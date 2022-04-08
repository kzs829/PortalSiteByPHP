<?php
//SQLServerとの接続
function connection_db_2(){
  $err_message = null;
  $pdo = null;
  try {
    $dsn = 'sqlsrv:server = localhost; database = sctool_hp';
    $pdo = new PDO($dsn, null, null);
  } catch (Exception $e) {
    $err_message = '[ERR-01]DBとの接続に失敗しました。';
    return [$pdo, $err_message, FALSE];
  }
  return [$pdo, $err_message, TRUE];
}

//MySQLとの接続
function connection_db(){
  $err_message = null;
  $pdo = null;
  try {
    $dsn = 'mysql:dbname=sctool_hp; host=127.0.0.1';
    $pdo = new PDO($dsn, "root", null);
  } catch (Exception $e) {
    $err_message = '[ERR-01]DBとの接続に失敗しました。';
    return [$pdo, $err_message, FALSE];
  }
  return [$pdo, $err_message, TRUE];
}

//メールの送信
function send_mail($to, $subject, $message){
  $err_message = null;
  mb_language( 'Japanese' );
  mb_internal_encoding( 'UTF-8' );
  try {
    $headers = "From: postmaster@localhost";
    
    $result = mb_send_mail($to, $subject, $message, $headers);
    
    if($result == FALSE){
      throw new Exception();
    }
  } catch (Exception $e) {
    $err_message = '[ERR-08]メールの送信に失敗しました。';
    return [$err_message, FALSE];
  }
  return [$err_message, TRUE];
}

//ユーザー情報の取得
function get_user_info($pdo, $user_no){
  $result = $pdo -> prepare('SELECT * FROM user_info WHERE user_no = ?');
  $params = array($user_no);
  $result -> execute($params);
  $user_info = $result -> fetchAll(PDO::FETCH_ASSOC);

  return $user_info;
}

//アクセス数の取得
function get_access_num(){
  $counter_file = './count.txt';
  $counter_lenght = 8;
  $fp = fopen($counter_file, 'r+');
  if ($fp) {
     if (flock($fp, LOCK_EX)) {
        $counter = fgets($fp, $counter_lenght);
     }
  }else{
    $counter = 'アクセス数を取得できませんでした。';
  }
  fclose ($fp);
  return $counter;
}

?>