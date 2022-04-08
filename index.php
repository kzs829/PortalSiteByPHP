<?php include('header.php'); ?>
<?php
//DBの接続
list($pdo, $err_message, $bool) = connection_db_2();

if($bool){
  //新着情報の取得
  $new_info = get_newinfo($pdo);
  //コラムの取得
  $blog = get_blog($pdo);
}
?>
<main style = "margin: 0; width: 100%;">
<div style = "position: fixed; bottom:40px; right:-120px; width: 300px;">
  <ul>
    <li>
      <a href = "#second">ツールラインナップ</a>
    </li>
    <li>
      <a href = "#newinfo">News/Column</a>
    </li>
  </ul>
</div>
<p id="page-top"><a href="#top">PAGE TOP</a></p>
<div class = "top-bg" id = "top">
  <h2 style = "letter-spacing:2px; text-shadow:2px 2px 7px white;">わたしたち生産開発部は<br><span style = "color:#475995; font-size:34px;">生産領域のシステム、ツールを開発するグループ</span>です</h2>
  <button class = "button" onclick="location.href='mailto:<?php echo kanri_mail; ?>?subject=%E3%80%90%E3%81%8A%E5%95%8F%E5%90%88%E3%81%9B%E3%80%91%E4%BB%B6%E5%90%8D%E3%82%92%E8%A8%98%E5%85%A5&amp;body=%E4%BA%8B%E6%A5%AD%E6%89%80%EF%BC%9A%0A%E9%83%A8%E7%BD%B2%EF%BC%9A%0A%E5%90%8D%E5%89%8D%EF%BC%9A%0A%E3%83%A1%E3%83%BC%E3%83%AB%EF%BC%9A%0A%E9%9B%BB%E8%A9%B1%EF%BC%9A%0A%E5%95%8F%E3%81%84%E5%90%88%E3%82%8F%E3%81%9B%E5%86%85%E5%AE%B9%EF%BC%9A'" style = "background-color:rgba(71, 87, 149, .8); color:white; text-align:left; padding:20px 30px;"><i class="material-icons" style = "color:white; font-size:50px;">&#xE0BE;</i><p style = "display:inline-block;">お困りごとなどの<br><span style = "font-size:22px;">ご要望、ご相談はこちら</span></p></button>
  <a href = "#second" style = "text-decoration:underline;"><b>どんなツールを開発しているの？</b><i class="material-icons">&#xE039;</i></a>
  <a class = "scrolldown" href="#second"><span></span>Scroll</a>
</div>
<div class = "second-bg" id = "second">
  <h2 style = "font-size:35px;">ツールラインナップ</h2>
  <p style = "font-size:20px; margin-top:10px; margin-bottom:40px;">既存のツールをご紹介します</p>
  <div class = "tool_lineup_box">
  	<div class="cp_ribbon08"><span>公開中</span></div>
    <p>       
      <span style = "font-size:18px;">生産ツール<br></span>
      <i class="material-icons">&#xE86C;</i><a href = "grouping_download.php">公開中システムA</a><br>
      　　- 公開中ツールA-a<br>
      <i class="material-icons">&#xE86C;</i><a href = "fromto_download.php">公開中ツールB</a><br>
      <i class="material-icons">&#xE86C;</i><a href="kurorom_download.php">公開中ツールC</a><br>
      ----------------------------------------------<br>
      <span style = "font-size:18px;">高度化ツール<br></span>
      <i class="material-icons">&#xE86C;</i>テキスト解析ツール</span>
    </p>
  </div>
  <div class = "tool_lineup_box">
    <div class="cp_ribbon08"><span>未公開</span></div>
    <p>
      <span style = "font-size:18px;">生産ツール<br></span>
      <i class="material-icons">&#xE86C;</i>未公開ツールA<br>
      <i class="material-icons">&#xE86C;</i>未公開ツールB<br>
      <i class="material-icons">&#xE86C;</i>未公開ツールC<br>
      <i class="material-icons">&#xE86C;</i>未公開ツールD<br>
      ----------------------------------------------<br>
      <span style = "font-size:18px;">高度化ツール<br></span>
      <i class="material-icons">&#xE86C;</i>チャットボット<br>
    </p>
  </div>
  <p style = "text-align:left; display:inline-block; margin-top:20px;">
    ツールのファイルや資料のダウンロードはヘッダーメニューのツールダウンロードから<br>
    ツールに関するマスタ登録などの追加申請などはヘッダーメニューの<a href="application.php" style = "text-decoration:underline;">各種申込</a>から<br>
    ※各種申込、ツール本体のダウンロードにはユーザ登録が必要です<br> 
    <a href = "pre_register.php" style = "text-decoration:underline;"><b>新規ユーザ登録はこちら</b></a><br>
  </p>
</div>
<div class = "tokyo-label" onclick = "location.href = 'index.php'">
  <img src="./image/336705_m.jpg" style = "border-radius:10px 0 0 10px; display:inline-block; height:100%; width:450px; vertical-align:top; object-fit:cover;">
  <p style = "display:inline-block; margin-left:50px; margin-top:25px;">
    <span style = "font-size:26px;"><b>大阪事業所の方はこちらもチェック</b></span><br>
    <i class="material-icons">&#xE86C;</i>ツールD関連<br>
    <i class="material-icons">&#xE86C;</i>その他大阪事業所関連<br>
  </p>
</div>
<div class = "therd-column">
  <div id = "newinfo" class = "newinfo">
    <h2 class = "subheading">News<a href = "new_info.php" style = "float:right; font-size:16px; line-height:35px; font-weight:normal;">一覧 ＞</a></h2>
    <?php if(empty($new_info)){ ?>
      <p style = "padding:20px;">ニュースはまだありません。</p>
    <?php } ?>
    <ul>
      <?php foreach($new_info as $row){ ?>
      <li class = "newinfo-item" onclick = "location.href = 'newinfo_page.php?newinfo_no=<?php echo $row['newinfo_no']; ?>'">
          <span class = "datetime" style = "margin-right:5px;"><?php echo substr($row['post_time'], 0, 10); ?></span>
          <span class = "label"><?php echo $row['major_category']; ?></span>
          <span class = "limited_text" style = "margin-left:5px; width:60%; position:relative; top:5px;"><?php echo $row['subject']; ?></span>
      </li>
      <?php } ?>
    </ul>
    <div style = "clear:float;"></div>
  </div>
  <div class = "blog">
    <h2 class = "subheading">Column<a href = "blog_list.php" style = "float:right; font-size:16px; line-height:35px; font-weight:normal;">一覧 ＞</a></h2>
    <?php if(empty($blog)){ ?>
      <p style = "padding:20px;">コラムはまだありません。</p>
    <?php } ?>
    <ul>
      <?php foreach($blog as $row){ ?>
      <li class = "blog-item" onclick = "location.href = 'blog_page.php?blog_no=<?php echo $row['blog_no']; ?>'">
        <?php if($row['image']){ ?>
          <img src="<?php echo column_sumnail_path.$row['image']; ?>" width="100" height="60" style = "vertical-align:top; object-fit:cover;">
        <?php }else{ ?>
          <img src="./image/no-image.jpg" width="100" height="60" style = "vertical-align:top; object-fit:cover;">
        <?php } ?>
        <span class = "image-label" style = "margin-right:-94px; left:-104px; width:100px;"><b><?php echo $row['major_category']; ?></b></span>
        <div style = "display:inline-block; width:400px; position:relative; top:5px;">
          <span class = "datetime" style = "margin-left:5px;"><?php echo substr($row['post_time'], 0, 10); ?></span>
          <p class = "limited_text" style = "margin-left:5px; width:100%; display:block;"><?php echo $row['subject']; ?></p>
        </div>
      </li>
      <?php } ?>
    </ul>
    <div style = "clear:float;"></div>
  </div>
</div>
<div style = "clear:float;"></div>
</main>
<?php
//新着情報の取得
function get_newinfo($pdo){
  $result = $pdo -> query('SELECT * FROM new_info ORDER BY post_time DESC offset 0 rows fetch next 3 rows only;');
  $new_info = $result -> fetchAll(PDO::FETCH_ASSOC);

  return $new_info;
}

//コラムの取得
function get_blog($pdo){
  $result = $pdo -> query('SELECT * FROM blog ORDER BY post_time DESC offset 0 rows fetch next 3 rows only;');
  $blog = $result -> fetchAll(PDO::FETCH_ASSOC);

  return $blog;
}
?>
<script type="text/javascript">
$(function(){
  $('.scrolldown').click(function(){
    var speed = 500;
    var href= $(this).attr("href");
    var target = $(href == "#" || href == "" ? 'html' : href);
    var position = target.offset().top;
    $("html, body").animate({scrollTop:position}, speed, "swing");
    return false;
  });
});

//スムーススクロール
$(function(){
  // #で始まるリンクをクリックしたら実行されます
  $('a[href^="#"]').click(function() {
    // スクロールの速度
    var speed = 400; // ミリ秒で記述
    var href= $(this).attr("href");
    var target = $(href == "#" || href == "" ? 'html' : href);
    var position = target.offset().top;
    $('body,html').animate({scrollTop:position}, speed, 'swing');
    return false;
  });
});

//スクロールアップ
$(function() {
    var topBtn = $('#page-top');    
    topBtn.hide();
    //スクロールが100に達したらボタン表示
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            topBtn.fadeIn();
        } else {
            topBtn.fadeOut();
        }
    });
    //スクロールしてトップ
    topBtn.click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 500);
        return false;
    });
});
</script>
<?php include('footer.php'); ?>
