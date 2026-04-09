
<!--  *****  *****  *****  *****  *****  -->
<!-- main -->

<section id="main">

  <h2 class="page-title text-center mt-2">マイページ</h2>
  <!--  *****  *****  *****  *****  *****  -->
  <!-- mypage contents -->
  <section id="mypage-panels" class="d-flex justify-content-center my-5 mx-0 row">
    <div class="btn-group btn-group-lg col-sm-9 row" role="group" aria-label="Basic example">
      <?php echo Html::anchor('members/mypage/favorite', 'お気に入り', array('class' => 'btn btn-outline-dark col-sm-3 mx-auto mb-2 rounded-sm') ); ?>
      <?php echo Html::anchor('members/mypage/interest', '気になる', array('class' => 'btn btn-outline-dark col-sm-3 mx-auto mb-2 rounded-sm') ); ?>
      <?php echo Html::anchor('members/mypage/submitBookList', '投稿一覧', array('class' => 'btn btn-outline-dark col-sm-3 mx-auto mb-2 rounded-sm') ); ?>
    </div>
  </section>

  <section id="mypage-panels-small" class="d-flex justify-content-center my-5 mx-0 row">
    <div class="btn-group-sm col-sm-9 my-5 d-flex justify-content-end" role="group" aria-label="Basic example">
      <?php echo Html::anchor('members/mypage/userInfoEdit', 'メール情報の編集', array('class' => 'btn btn-link rounded-sm') ); ?>
      <?php echo Html::anchor('members/mypage/userPassEdit', 'パスワードの編集', array('class' => 'btn btn-link rounded-sm') ); ?>
      <?php //echo Html::anchor('members/mypage/userNameEdit', 'ユーザー名の編集', array('class' => 'btn btn-link rounded-sm') ); ?>
      <?php echo Html::anchor('members/mypage/withdraw', '退会', array('class' => 'btn btn-link rounded-sm') ); ?>
    </div>
  </section>
    <!--  *****  *****  *****  *****  *****  -->
  <!--  *****  *****  *****  *****  *****  -->


</section>

<?php echo $btnContainer; ?>
