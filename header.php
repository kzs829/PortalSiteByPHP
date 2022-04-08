<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-UA-Compatible" content="IE=11">
    <title>TECH</title>
    <link rel="icon" type="image/x-icon" href="./image/favicon.ico">
    <link href="css/common.css" rel="stylesheet" type="text/css">
    <link href="css/index.css" rel="stylesheet" type="text/css">
    <link href="css/slick.css" rel="stylesheet" type="text/css">
    <link href="css/slick-theme.css" rel="stylesheet" type="text/css">
    <link href="css/scrolldown.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/slick.min.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <!-- 日付選択ダイアログ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="js/datepicker-ja.js"></script>
    <script>
      $(function() {
        $(".datepicker").datepicker({
        });
      });
    </script> 
  </head>
  <body onLoad="document.post.submit();" oncontextmenu="return false;">
    <?php include("config.php"); ?>
    <?php
    session_start();

    if(isset($_SESSION['MAIL'])){
      $mail = $_SESSION['MAIL'];
    }
    if(isset($_SESSION['ADMIN_FLG'])){
      $admin_flg = $_SESSION['ADMIN_FLG'];
    }

    require "common_func.php";
    ?>
    <header>
      <div class = "top">
        <a href = "index.php"><img class = "logo" src="./image/logo.png"></a>
        <ul class = "mini-menu">
          <?php if(isset($mail)){ ?>
            <li class = "mini-menu-item"><i class="material-icons">&#xE566;</i><a href = "logout.php">ログアウト</a></li>
          <?php } else{?>
            <li class = "mini-menu-item"><i class="material-icons">&#xE890;</i><a href="login.php">ログイン</a></li>
          <?php } ?>
          <li class = "mini-menu-item"><i class="material-icons">&#xE8AF;</i><a href="inquiry.php">お問合せ</a></li>
          <li class = "mini-menu-item"><i class="material-icons">&#xE420;</i><a href="public_inquiry.php">みんなのQ&A</a></li>
          <?php if(isset($_SESSION['NAME'])){ ?>
            <li class = "mini-menu-item">
              <a href="mypage.php"><span style = "font-size:10px;">ようこそ</span><?php echo $_SESSION['NAME']; ?><span style = "font-size:10px;">さん</span></a>
            </li>
          <?php } ?>
        </ul>
      </div>
      <div class = "under">
        <ul class = "menu">
          <li class = "menu-item"><a class = "menu-item-text" href="application.php">各種申込</a></li>
          <li class = "menu-item">
            <a href="#" class = "menu-item-text">ツールダウンロード</a>
            <ul>
              <li><a href = "grouping_download.php">公開中システムA</a></li>
              <li><a href = "fromto_download.php">公開中システムB</a></li>
              <li><a href="kurorom_download.php">公開中システムC</a></li>
            </ul>
          </li>
          <?php if(isset($mail)){ ?>
            <li class = "menu-item"><a class = "menu-item-text" href="mypage.php">マイページ</a></li>
          <?php } ?>
          <?php if(isset($admin_flg) && $admin_flg == 1){ ?>
            <li class = "menu-item"><a class = "menu-item-text" href="inquiry_management.php">管理画面</a></li>
          <?php } ?>
        </ul> 
      </div>
    </header>

    <script>
    $(window).on("scroll", function(){
      $("header").css("left", -$(window).scrollLeft());
    });
    </script>