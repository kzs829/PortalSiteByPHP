//UUID生成
function generateUuid() {
    // https://github.com/GoogleChrome/chrome-platform-analytics/blob/master/src/internal/identifier.js
    // const FORMAT: string = "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx";
    let chars = "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".split("");
    for (let i = 0, len = chars.length; i < len; i++) {
        switch (chars[i]) {
            case "x":
                chars[i] = Math.floor(Math.random() * 16).toString(16);
                break;
            case "y":
                chars[i] = (Math.floor(Math.random() * 4) + 8).toString(16);
                break;
        }
    }
    return chars.join("");
}

//未入力、文字種チェック
function validate(attr,message,check){
  var required_message = "※必須入力です";
  if(!attr.val()){
    attr.css('border', '1px solid red');
    attr.next('p').text(required_message);
    return false;
  } else if (attr.val().match(check)) {
    attr.css('border', '1px solid red');
    attr.next('p').text(message);
    return false;
  } else {
    attr.css('border', '1px solid #ccc');
    attr.next('p').text('');
    return true;
  }
}

//未入力チェック
function validation(obj){
  var required_message = "※必須入力です";
  if(!$(obj).val()){
    $(obj).css('border', '1px solid red');
    $(obj).next('p').text(required_message);

    return false;
  } else {
    $(obj).css('border', '1px solid #ccc');
    $(obj).next('p').text('');

    return true;
  }
}

//メールアドレス＠チェック
function vali_mail_atmark(attr){
  var message = '※正しいメールアドレスの形式で入力してください';
  var result = attr.val().match("[@]");
  if(result){
    if(result['index'] == 0 || result['index'] == attr.val().length - 1){
      attr.css('border', '1px solid red');
      attr.next('p').text(message);  
      return false;
    }
    attr.css('border', '1px solid #ccc');
    attr.next('p').text('');
    return true;
  }else{
    attr.css('border', '1px solid red');
    attr.next('p').text(message);
    return false;
  }
}

//バリデーションエラー表示
function vali_err(id, message){
  $('#' + id).css('border', '1px solid red');
  $('#' + id).next("p").text(message);
}

//バリデーションエラー非表示
function vali_no_err(id){
  $('#' + id).css('border', '1px solid #ccc');
  $('#' + id).next("p").text('');
}

//ツールダウンロード時のログイン確認
function jump_login(){
  if(window.confirm('ツール本体のダウンロードにはログインが必要です\n※資料のダウンロードは実施いただけます')){
    var f = document.forms["to_login_form"]
    f.submit();
  }
}

//パラメータを設定したURLを返す
function setParameter( paramsArray ) {
  var resurl = location.href.replace(/\?.*$/,"");
  for ( key in paramsArray ) {
      resurl += (resurl.indexOf('?') == -1) ? '?':'&';
      resurl += key + '=' + paramsArray[key];
  }
  return resurl;
}

//パラメータを取得する
function getParameter(){
  var paramsArray = [];
  var url = location.href; 
  parameters = url.split("#");
  if( parameters.length > 1 ) {
      url = parameters[0];
  }
  parameters = url.split("?");
  if( parameters.length > 1 ) {
      var params   = parameters[1].split("&");
      for ( i = 0; i < params.length; i++ ) {
         var paramItem = params[i].split("=");
         paramsArray[paramItem[0]] = paramItem[1];
      }
  }
  return paramsArray;
};

//モーダルウィンドウを表示する
function showModal(){
  var popup = document.getElementById('modal-window');
  popup.classList.add('is-show');

  var blackBg = document.getElementById('black-background');
  var closeBtn = document.getElementById('close-modal');

  closePopUp(blackBg);
  closePopUp(closeBtn);
  function closePopUp(elem) {
    elem.addEventListener('click', function(){
      if(popup.classList.contains('is-show')){
        popup.classList.remove('is-show');
      }
    });
  }
}