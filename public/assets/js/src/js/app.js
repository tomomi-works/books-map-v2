var $ = require("jquery");


//*** *** *** *** *** *** *** *** *** ***//
//ヘッダーの高さ分だけコンテンツを下げる
$(function() {
    var height = $("#header").height();
    $("body").css("margin-top", height + 10);//10pxだけ余裕をもたせる
});

//*** *** *** *** *** *** *** *** *** ***//

//-- -- -- -- -- -- -- -- -- -- -//
//ページを読み込んだ後、一時的にメッセージを取得・表示。

//-- -- -- -- -- -- -- -- -- -- -//
  var $onceMsg = $('.js-top-msg-once');

  if( $onceMsg.length ){
      $onceMsg.slideDown();
      setTimeout(function(){
        $onceMsg.slideUp();
      }, 3000 );
  }

//*** *** *** *** *** *** *** *** *** ***//

//-- -- -- -- -- -- -- -- -- -- -//
//スクロール時のイベント

//-- -- -- -- -- -- -- -- -- -- -//
$(window).on('scroll',function(){
  //-- -- -- -- -- -- -- -- -- -- -//
  //ヘッダーの表示・非表示
  if( $(this).scrollTop() > 100){
    $('.js-header').slideUp('slow');
  }else{
    $('.js-header').slideDown();
  }
  //-- -- -- -- -- -- -- -- -- -- -//
  //topへ戻る
  if( $(this).scrollTop() > 200){
    $('.js-return-top-btn').fadeIn('slow');
  }else{
    $('.js-return-top-btn').fadeOut('slow');
  }
});

//*** *** *** *** *** *** *** *** *** ***//

//-- -- -- -- -- -- -- -- -- -- -//
//上部へ移動するボタン

//-- -- -- -- -- -- -- -- -- -- -//
$('.js-return-top-btn').on('click',function(){
  $('body,html').animate({
      scrollTop: 0
    }, 500);
    return false;
});
//*** *** *** *** *** *** *** *** *** ***//

//-- -- -- -- -- -- -- -- -- -- -//
//検索ボタンを押した時のアニメーション

$('.js-animation-active').on('click',function(){
  $(this).find('.js-animation-target').toggleClass('u-animation');
});

//-- -- -- -- -- -- -- -- -- -- -//


//-- -- -- -- -- -- -- -- -- -- -//
//フォームのバリデーションチェック
//-- -- -- -- -- -- -- -- -- -- -//
//user名前
$('.js-form-user-name').on('keyup',function(){
  if( $(this).val().match(/^[a-zA-Z0-9]+$/) && $(this).val().length === 6 ){
    $(this).addClass('border-success').removeClass('border-danger').siblings('.js-err-msg').addClass('d-none').removeClass('d-inline');
  }else{
    $(this).addClass('border-danger').siblings('.js-err-msg').text('半角英数字6文字で入力してください').addClass('d-inline').removeClass('d-none');
  }
});
//userニックネーム
$('.js-form-user-nick').on('keyup',function(){
  if( $(this).val().length >= 21 ){
    $(this).addClass('border-danger').siblings('.js-err-msg').text('20文字以内で入力してください').addClass('d-inline').removeClass('d-none');
  }else{
    $(this).addClass('border-success').removeClass('border-danger').siblings('.js-err-msg').addClass('d-none').removeClass('d-inline');
  }
});
//email
$('.js-form-email').on('keyup',function(){
  if( $(this).val().length > 0 ){
    $(this).addClass('border-success').removeClass('border-danger').siblings('.js-err-msg').addClass('d-none').removeClass('d-inline');
  }else{
    $(this).addClass('border-danger').siblings('.js-err-msg').text('半角英数字6文字で入力してください').addClass('d-inline').removeClass('d-none');
  }
});
//pass
$('.js-form-user-pass').on('keyup',function(){
  if( $(this).val().match(/^[a-zA-Z0-9]+$/) && $(this).val().length === 6 ){
    $(this).addClass('border-success').removeClass('border-danger').siblings('.js-err-msg').addClass('d-none').removeClass('d-inline');
  }else{
    $(this).addClass('border-danger').siblings('.js-err-msg').text('半角英数字6文字で入力してください').addClass('d-inline').removeClass('d-none');
  }
});

//-- -- -- -- -- -- -- -- -- -- -//
//コンタクトフォーム

//-- -- -- -- -- -- -- -- -- -- -//
//name
$('.js-contact-name').on('keyup',function(){
  if( $(this).val().length > 30 || $(this).val().length === 0 ){
    $(this).addClass('border-danger').siblings('.js-err-msg').text('30文字以内でご入力ください').addClass('d-inline').removeClass('d-none');
  }else{
    $(this).addClass('border-success').removeClass('border-danger').siblings('.js-err-msg').addClass('d-none').removeClass('d-inline');
  }
});
//email
$('.js-contact-email').on('keyup',function(){

  if( $(this).val().length === 0 ){
    $(this).addClass('border-danger').siblings('.js-err-msg').text('入力必須項目です').addClass('d-inline').removeClass('d-none');
  }else if( !$(this).val().match(/^[A-Za-z0-9]{1}[A-Za-z0-9_.-]*@{1}[A-Za-z0-9_.-]{1,}\.[A-Za-z0-9]{1,}$/) ){
    $(this).addClass('border-danger').siblings('.js-err-msg').text('Emailの形式でご入力ください').addClass('d-inline').removeClass('d-none');
  }else{
    $(this).addClass('border-success').removeClass('border-danger').siblings('.js-err-msg').addClass('d-none').removeClass('d-inline');
  }
});
//内容
$('.js-contact-matter').on('keyup',function(){
  if( $(this).val().length > 1000 || $(this).val().length === 0 ){
    $(this).addClass('border-danger').siblings('.js-err-msg').text('1000文字以内でご入力ください').addClass('d-inline').removeClass('d-none');
  }else{
    $(this).addClass('border-success').removeClass('border-danger').siblings('.js-err-msg').addClass('d-none').removeClass('d-inline');
  }
  //文字数カウント
  $(this).closest('.js-contact-content').siblings('.js-counter').find('.js-show-count').text( $(this).val().length );
});


//-- -- -- -- -- -- -- -- -- -- -//
//bookEdit
//bookTitle
$('.js-book-title').on('keyup',function(){
  if( $(this).val().length > 100 || $(this).val().length === 0 ){
    $(this).addClass('border-danger').siblings('.js-err-msg').text('100文字以内でご入力ください').addClass('d-inline').removeClass('d-none');
  }else{
    $(this).addClass('border-success').removeClass('border-danger').siblings('.js-err-msg').addClass('d-none').removeClass('d-inline');
  }
});
//価格
$('.js-book-price').on('keyup',function(){
  if( !$(this).val().match(/^\d+$/) ){
    $(this).addClass('border-danger').siblings('.js-err-msg').text('半角数字のみ入力できます').addClass('d-inline').removeClass('d-none');
  }else{
    $(this).addClass('border-success').removeClass('border-danger').siblings('.js-err-msg').addClass('d-none').removeClass('d-inline');
  }
});
//あらすじ
$('.js-book-short').on('keyup',function(){
  if( $(this).val().length > 300 ){
    $(this).addClass('border-danger').siblings('.js-err-msg').text('300文字以内でご入力ください').addClass('d-inline').removeClass('d-none');
  }else{
    $(this).addClass('border-success').removeClass('border-danger').siblings('.js-err-msg').addClass('d-none').removeClass('d-inline');
  }
  //文字数カウント
  $(this).siblings('.js-counter').find('.js-show-count').text( $(this).val().length );
});
//本文
$('.js-book-summary').on('keyup',function(){
  if( $(this).val().length > 1000 ){
    $(this).addClass('border-danger').siblings('.js-err-msg').text('1000文字以内でご入力ください').addClass('d-inline').removeClass('d-none');
  }else{
    $(this).addClass('border-success').removeClass('border-danger').siblings('.js-err-msg').addClass('d-none').removeClass('d-inline');
  }
  //文字数カウント
  $(this).siblings('.js-counter').find('.js-show-count').text( $(this).val().length );
});



//画像アップロード
$('.js-upload-img').change(function(e){

    var reader = new FileReader();
    reader.onload = function (e) {
        $(".js-view-img").attr('src', e.target.result);
    }
    reader.readAsDataURL(e.target.files[0]);

});

//お気に入り登録
$('.ajax-fav-in').on('click',function(){

  $this = $(this);

    $.ajax({
      type: 'post',
      //アクセス先のパス
      url: '/booksmap/public/regist/fav.json',
      data: {
        bookid: $('.js-bookid').text()
      }
    }).done(function(data){
          console.log('fav');
          $this.toggleClass('d-none').siblings('.ajax-fav-remove').toggleClass('d-none');
          // console.log(data);
      }).fail(function(data){
          alert('Error!');
          if(data){
            console.log(data);
          }
      });

});

//お気に入りから削除
$('.ajax-fav-remove').on('click',function(){

    $this = $(this);

    $.ajax({
      type: 'post',
      //アクセス先のパス
      url: "/booksmap/public/regist/favnon.json",
      //相対パスだと画面によってパスが違ってしまうので、Uri::create()を使う
      data: {
        bookid: $('.js-bookid').text()
      }
    }).done(function(json){
          console.log('favnon');
          $this.toggleClass('d-none').siblings('.ajax-fav-in').toggleClass('d-none');

      }).fail(function(jqXHR, textStatus){
          alert('Error!');
      });


});




//気になる登録
$('.ajax-inte-in').on('click',function(){

  $this = $(this);

    $.ajax({
      type: 'post',
      //アクセス先のパス
      url: '/booksmap/public/regist/interest.json',
      data: {
        bookid: $('.js-bookid').text()
      }
    }).done(function(data){
          console.log('inte');
          $this.toggleClass('d-none').siblings('.ajax-inte-remove').toggleClass('d-none');
          // console.log(data);
      }).fail(function(data){
          alert('Error!');
          if(data){
            console.log(data);
          }
      });

});

//気になるから削除
$('.ajax-inte-remove').on('click',function(){

    $this = $(this);

    $.ajax({
      type: 'post',
      //アクセス先のパス
      url: "/booksmap/public/regist/interestnon.json",
      //相対パスだと画面によってパスが違ってしまうので、Uri::create()を使う
      data: {
        bookid: $('.js-bookid').text()
      }
    }).done(function(data){
          console.log('intenon');
          $this.toggleClass('d-none').siblings('.ajax-inte-in').toggleClass('d-none');

      }).fail(function(data){
          alert('Error!');
      });


});
