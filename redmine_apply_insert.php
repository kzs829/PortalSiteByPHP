<?php include('header.php');?>
<?php
//DBとの接続
list($pdo, $err_message, $bool) = connection_db_2();

$json_app_details = $_POST['json_app_details'];
$json_applicant_info = $_POST['json_applicant_info'];
$uuid = $_POST['uuid'];

$app_details = json_decode( $json_app_details , true );
$applicant_info = json_decode( $json_applicant_info , true );

//申込内容をDBに格納
foreach($app_details as $row_data){
  list($err_message, $bool) = redmine_apply_insert($pdo, $row_data, $uuid);
}

//申込管理情報をDBに格納
list($err_message, $bool) = applicant_info_insert($pdo, $applicant_info, $uuid);

if($bool){
  //メール送信
  $to = kanri_mail;
  $subject = subject_accept_apply;
  $message = "※このメールはシステムからの自動配信です。\n"
             ."※本メールアドレス(postmaster@sample.co.jp)は送信専用のため返信はできません。\n\n"
             ."管理者様\n\n"
             ."申込ID【".$uuid."】の申込をシステム受付しました。\n"
             ."申込内容は申込一覧画面よりご参照ください。\n"
             ."申込一覧画面：".url."apply_list.php\n\n"
             ."―――――――――\n"
             ."SC戦略プロセス改善G：sctool@sample.com\n"
             ."HP：".url."index.php\n\n";
  list($err_message, $bool) = send_mail($to, $subject, $message);  

  $to = $_SESSION['MAIL'];
  $subject = subject_complete_apply;
  $message = "※このメールはシステムからの自動配信です。\n"
             ."※本メールアドレス(postmaster@sample.co.jp)は送信専用のため返信はできません。\n\n"
             .$_SESSION['NAME']."様\n\n"
             ."申込ID【".$uuid."】の申込が完了いたしました。\n"
             ."申込が受付された際に再度ご連絡いたしますので、今しばらくお待ちくださいませ。\n\n"
             ."申込内容、対応状況などは申込一覧画面よりご覧いただけます。\n"
             ."申込一覧画面：".url."apply_list.php\n\n"
             ."―――――――――\n"
             ."SC戦略プロセス改善G：sctool@sample.com\n"
             ."HP：".url."index.php\n\n";
  list($err_message, $bool) = send_mail($to, $subject, $message);  
  
  $alert = "<script type='text/javascript'>alert('申込が完了しました。');</script>";
  echo $alert;
}else{
  $alert = "<script type='text/javascript'>alert('申込が失敗しました。');</script>";
  echo $alert;
}
?>
<form action = "application.php" method = "post" name = "post"></form>  
<?php
function redmine_apply_insert($pdo, $row_data, $uuid){
  $err_message = null;
  try{
    $tsql = "INSERT INTO redmine_apply (apply_id, category, family_name, first_name, id, note) VALUES (?, ?, ?, ?, ?, ?);";
    $stmt = $pdo->prepare($tsql);
    $params = array($uuid, $row_data[0], $row_data[1], $row_data[2], $row_data[3], $row_data[4]);
    $stmt->execute($params);

    return [$err_message, TRUE];
  } catch (\Exception $e){
    $err_message = '[ERR-11]申込情報の登録に失敗しました。';
    return [$err_message, FALSE];
  }
}

function applicant_info_insert($pdo, $applicant_info, $uuid){
  $err_message = null;

  $apply_date = new DateTime();
  $apply_date -> setTimeZone( new DateTimeZone('Asia/Tokyo'));
  $apply_date = $apply_date -> format('Y-m-d H:i');

  try{
    $tsql = "INSERT INTO application (apply_id, type, department, name, mail, apply_date, delivery_date, reseption_department) VALUES (?, 'RedmineID登録依頼', ?, ?, ?, ?, ?, 'SC戦略生産革新部');";
    $stmt = $pdo->prepare($tsql);
    $params = array($uuid, $applicant_info[0], $applicant_info[1], $applicant_info[2], $apply_date, $applicant_info[3]);
    $stmt->execute($params);
 
    return [$err_message, TRUE];
  } catch (\Exception $e){
    $err_message = '[ERR-11]申込情報の登録に失敗しました。';
    return [$err_message, FALSE];
  }
}
?>
<?php include('footer.php');?>
