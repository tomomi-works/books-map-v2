
<!--  *****  *****  *****  *****  *****  -->
<!-- main -->

<section id="main">

  <h2 class="page-title text-center mt-2">パスワード変更</h2>

  <!--  *****  *****  *****  *****  *****  -->
  <!-- main contents -->

  <section id="mypage-panels" class="d-flex justify-content-center my-5 mx-0 row">
    <div class="btn-group btn-group-lg col-sm-6 row" role="group" aria-label="Basic example">
      <p>
        パスワード変更用の認証URLをご登録済みのメールアドレスへお送りします。</br>
        ご登録済みのメールアドレスをご入力ください。
      </p>
      <!-- error message -->
      <?php
      if(!empty($errors)){
        foreach($errors as $key => $val){
          ?>
          <li><?php echo $val; ?></li>
          <?php
        }
      }
      ?>

      <?php echo $resist_form; ?>
    </div>
  </section>


  <!--  *****  *****  *****  *****  *****  -->


</section>

<?php echo $btnContainer; ?>

<!-- *****  *****  *****  *****  ***** -->
