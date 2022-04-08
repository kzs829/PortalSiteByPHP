    <?php
    //アクセスカウンター
    if(!(isset($_SESSION['access']))){
      $counter_file = './count.txt';
      $counter_lenght = 8;
      $fp = fopen($counter_file, 'r+');
      if ($fp) {
          if (flock($fp, LOCK_EX)) {
            $counter = fgets($fp, $counter_lenght);
            $counter++;
            rewind($fp);
            if (fwrite($fp,  $counter) === FALSE) {
                  echo ('<p>'.'ファイル書き込みに失敗しました'.'</p>');
            }
            flock ($fp, LOCK_UN);
          }
      }
      fclose ($fp);
      $_SESSION['access'] = 1;
    }

    //アクセス数取得
    $counter = get_access_num();
    ?>
    <footer>
      <div class = "top">
        <ul class = "sitemap" style = "margin-right:20px;">
          <li><span style = "font-size:16px"><b>ツールをダウンロードする</b><span></li>
          <li><a href = "grouping_download.php">公開中システムA</a></li>
          <li><a href = "fromto_download.php">公開中ツールB</a></li>
          <li><a href="kurorom_download.php">公開中ツールC</a></li>
        </ul>
        <ul class = "sitemap" style = "margin-right:60px;">
          <li><span style = "font-size:16px"><b>ツールに関する申込をする</b><span></li>
          <li><a href="application.php">各種申込画面</a></li>
          <li><a href = "kurorom_apply_form.php">　ツールAマスタ登録申込</a></li>
          <li><a href="redmine_apply_form.php">　RedmineID登録申込</a></li>
          <li><a href="apply_list.php">　申込一覧</a></li>
        </ul>
        <ul class = "sitemap">
          <li><span style = "font-size:16px"><b>その他</b><span></li>
          <li><a href="inquiry.php">お問合せ</a></li>
          <li><a href = "public_inquiry.php">みんなのQ&A</a></li>
          <li><a href = "new_info.php">News</a></li>
          <li><a href="blog_list.php">Column</a></li>
          <li><a href="#">大阪事業所関連</a></li>
        </ul>
        <ul class = "sitemap" style = "padding-left:50px; margin-left:40px; border-left:1px solid lightgray;">
          <li><span style = "font-size:16px"><b>関連部署</b><span></li>
          <li><a href="#">生産本部<i class="material-icons" style = "font-size:14px; margin-left:3px; top:2px;">&#xE3E0;</i></a></li>
          <li><a href = "#">総務部<i class="material-icons" style = "font-size:14px; margin-left:3px; top:2px;">&#xE3E0;</i></a></li>
          <li><a href = "#">生産統括部 テクノロジーグループ<i class="material-icons" style = "font-size:14px; margin-left:3px; top:2px;">&#xE3E0;</i></a></li>
          <li><a href="#">生産統括部 ものづくりグループ<i class="material-icons" style = "font-size:14px; margin-left:3px; top:2px;">&#xE3E0;</i></a></li>
        </ul>
      </div>
      <div class = "bottom">
        <p style = "margin-top:10px;">TECH 生産開発部<br>
          メールアドレス：<a style="color:white; border-bottom:1px solid white;" href="mailto:<?php echo kanri_mail; ?>?subject=%E3%80%90%E3%81%8A%E5%95%8F%E5%90%88%E3%81%9B%E3%80%91%E4%BB%B6%E5%90%8D%E3%82%92%E8%A8%98%E5%85%A5&amp;body=%E4%BA%8B%E6%A5%AD%E6%89%80%EF%BC%9A%0A%E9%83%A8%E7%BD%B2%EF%BC%9A%0A%E5%90%8D%E5%89%8D%EF%BC%9A%0A%E3%83%A1%E3%83%BC%E3%83%AB%EF%BC%9A%0A%E9%9B%BB%E8%A9%B1%EF%BC%9A%0A%E5%95%8F%E3%81%84%E5%90%88%E3%82%8F%E3%81%9B%E5%86%85%E5%AE%B9%EF%BC%9A">sctool@sample.com</a>
        </p>
        <p style= "float:right;">
          <span style = "display:block; margin-top:10px;">
            最終更新日時:
            <script type="text/javascript">
            <!--
            document.write(document.lastModified.substr(0,2));
            document.write("/");
            document.write(document.lastModified.substr(3,2));
            document.write(" ");
            document.write(document.lastModified.substr(11,2));
            document.write(":");
            document.write(document.lastModified.substr(14,2));
            //-->
            </script>
          </span>
          <span style = "display:block;">アクセス数:<?php echo $counter; ?></span>
        </p>
      </div>
    </footer>
  </body>
</html>