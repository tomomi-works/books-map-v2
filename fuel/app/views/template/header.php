<body id="body">
  <div class="d-flex flex-column">
    <div class="d-flex flex-column min-vh-100">


  <!-- *****  *****  *****  *****  ***** -->
  <!-- header -->

<header id="header" class="fixed-top bg-light">

  <!-- alert -->
  <?php if( !empty( Session::get_flash('sucMsg') ) ){ ?>
  <div class="alert alert-success fixed-top rounded-0 js-top-msg-once" role="alert">
      <?php echo Session::get_flash('sucMsg'); ?>
  </div>
<?php }elseif( !empty( Session::get_flash('errMsg') ) ){ ?>

  <div class="alert alert-danger fixed-top rounded-0 js-top-msg-once" role="alert">
      <?php echo Session::get_flash('errMsg'); ?>
  </div>

  <?php } ?>

  <!-- top-menu -->

      <!-- icon-menu humberger -->
      <div class="pos-f-t p-humberger_container container-fluid p-0">
        <nav class="navbar navbar-dark bg-dark justify-content-end">
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        </nav>
        <div class="collapse container-fluid p-0" id="navbarToggleExternalContent">
          <div class="bg-dark p-4">

            <ul class="nav d-flex flex-column">
              <li class="nav-item">
                <?php echo Html::anchor('members/mypage/index','<i class="fas fa-user h3 my-0 mr-3 text-white"></i><p class="my-0 text-white">mypage</p>',array('class'=>'nav-link btn font-ss container-fluid d-block d-flex align-items-center') ); ?>
              </li>
              <?php if( $loginuser ){
                ?>
                <li class="nav-item">
                  <?php echo Html::anchor('/auth/logout','<i class="fas fa-door-closed h3 my-0 mr-3 text-white"></i><p class="my-0 text-white">logout</p>',array('class'=>'nav-link btn font-ss container-fluid d-block d-flex align-items-center') ); ?>
                </li>
                <?php
             }else{
                ?>
                <li class="nav-item">
                  <a class="nav-link btn font-ss container-fluid d-block d-flex align-items-center" data-toggle="modal" data-target="#login">
                    <i class="fas fa-door-open h3 my-0 mr-3 text-white"></i><p class="my-0 text-white">login</p>
                  </a>
                </li>
                <?php
             } ?>

              <li class="nav-item">
                <?php echo Html::anchor('members/books/bookEdit','<i class="fas fa-plus-circle h3 my-0 mr-3 text-white"></i><p class="my-0 text-white">post</p>',array('class' => 'nav-link btn font-ss container-fluid d-block d-flex align-items-center' )); ?>
              </li>
            </ul>

          </div>
        </div>

      </div>

    <!-- main-menu -->

      <!-- icon-menu -->

        <div class="p-iconmenu_container">

          <ul class="nav mt-4 position-absolute right0">
            <li class="nav-item d-flex flex-column justify-content-end text-center">
              <?php echo Html::anchor('members/mypage/index','<i class="fas fa-user h3 my-0"></i>',array('class'=>'nav-link btn font-ss') ); ?>
              <p class="font-ss my-0">mypage</p>
            </li>
            <?php if( $loginuser ){
              ?>
              <li class="nav-item d-flex flex-column justify-content-end text-center">
                <?php echo Html::anchor('/auth/logout','<i class="fas fa-door-closed h3 my-0"></i>',array('class'=>'nav-link btn font-ss') ); ?>
                <p class="font-ss my-0">logout</p>
              </li>
              <?php
           }else{
              ?>
              <li class="nav-item d-flex flex-column justify-content-end text-center">
                <a class="nav-link btn font-ss" data-toggle="modal" data-target="#login">
                  <i class="fas fa-door-open h3 my-0"></i>
                </a>
                <p class="font-ss my-0">login</p>
              </li>
              <?php
           } ?>

            <li class="nav-item d-flex flex-column text-center">
              <?php echo Html::anchor('members/books/bookEdit','<i class="fas fa-plus-circle h3 my-0"></i>',array('class' => 'nav-link text-dark' )); ?>
              <p class="font-ss my-0">post</p>
            </li>
          </ul>

        </div>


      <div class="js-header">
        <!-- title & menu-->
        <div class="d-flex justify-content-center flex-column container-fluid row mx-0">
          <!-- title -->
          <?php echo Html::anchor('book/booklists','BooksMαp',array('class'=>'p-2 btn btn-lg mx-auto h1') ); ?>
            <ul class="nav justify-content-center">
              <li class="nav-item">
                <?php echo Html::anchor('book/booklists','Home',array('class'=>'nav-link') ); ?>
              </li>
              <li class="nav-item">
                <?php echo Html::anchor('members/mypage/favorite','favorite',array('class'=>'nav-link') ); ?>
              </li>
              <li class="nav-item">
                <?php echo Html::anchor('members/mypage/interest','interest',array('class'=>'nav-link') ); ?>
              </li>
            </ul>
        </div>
      </div>

</header>

<!--　top-return btn  -->
<div class="position-fixed right0 bottom0 z-index-navmenu">
  <div class="container-fluid h3 text-secondary">
    <a class="text-secondary js-return-top-btn" href="#">
       <i class="fas fa-chevron-up p-2 bg-light rounded-sm"></i>
    </a>
  </div>
</div>



    <!-- Modal -->

    <div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="loginTitle" aria-hidden="true">

      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="loginTitle">ログイン</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
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
            <!-- login form -->
              <?php
              if(!empty($login_form) ){
                echo $login_form;
              }
              ?>
            <!-- link -->
              <div class="d-flex flex-column">
                <?php echo Html::anchor('signup/index', '新規登録', array('class' => 'btn btn-sm btn-link' ) ); ?>
                <?php echo Html::anchor('password/registPass', 'パスワードを忘れた方', array('class' => 'btn btn-sm btn-link' ) ); ?>
              </div>
          </div>

        </div>
      </div>

    </div>
