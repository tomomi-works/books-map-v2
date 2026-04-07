
<!--  *****  *****  *****  *****  *****  -->
<!-- main -->

<section id="main">
  <!--  *****  *****  *****  *****  *****  -->
  <!-- book overview -->

  <section id="book-overview" class="d-flex justify-content-center my-5">
    <!-- feature -->
    <div class="d-flex row mx-auto d-flex justify-content-center container-fluid">
      <div class="card col-sm-3 col-md-4 p-0">
        <?php
        if(!empty($book['img'])){
          Asset::add_path('assets/img/uploads', 'img');
          echo Asset::img( $book['img'], array('class' => 'bd-placeholder-img card-img-top w-100 max-height__250 fit-cover') );
        }else{
          echo Asset::img( $bookImg, array('class' => 'bd-placeholder-img card-img-top w-100 max-height__250 fit-cover') );
        }
        ?>
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><?php echo $book['title']; ?></li>
          <li class="list-group-item font-weight-light text-secondary py-0">
            カテゴリー：
            <?php
            echo \Model\Category::get_name($book['cate_id']);
            ?>
          </li>
          <li class="list-group-item font-weight-light text-secondary py-0">
            価格：
            <?php if(!empty($book['price'])){
              echo $book['price'].'円';
            }else{
              echo '¥-';
            }; ?>
          </li>
          <li class="list-group-item font-weight-light text-secondary py-0">
            投稿：
            <?php if(!empty($username)) echo $username;?>
            さん
          </li>
        </ul>
      </div>
      <!-- short -->
      <div class="card col-sm-6 col-md-7 p-0">
        <div class="card-body">
          <div class="row">
            <h5 class="col-8 col-sm-10 card-title mb-2 text-muted">overview</h5>
            <p class="col-4 col-sm-2 card-title mb-2 text-white bg-dark text-center rounded">
              <?php
              echo \Model\Bookstatus::get_name($book['stat_id']);
              ?>
            </p>
          </div>
          <p class="card-text white-space-pre-line">
            <?php
            if(!empty($book['short'])){
              echo $book['short'];
            }else{
              echo 'まだ書かれていません！';
            }
            ?>
          </p>
          <div class="row">

            <?php
            //ログインしていた場合、お気に入り・気になるを登録
            if($loginuser):
             ?>
            <div class="col-sm-6">
              <span class="js-bookid d-none"><?php echo Input::get('book'); ?></span>
              <div class="ajax-fav-in <?php echo ($is_favorite) ? 'd-none' : ''; ?>">
                <p class="btn btn-outline-info container-fluid" data-toggle="buttons">
                  <i class="fas fa-heart text-danger"></i>
                  お気に入り登録
                </p>
              </div>

              <div class="ajax-fav-remove <?php echo ($is_favorite) ? '' : 'd-none'; ?>">
                <p class="btn btn-outline-info container-fluid" data-toggle="buttons">
                  <i class="fas fa-heart text-danger"></i>
                  お気に入りから削除
                </p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="ajax-inte-in <?php echo ($is_interest) ? 'd-none' : ''; ?>">
                <p class="btn btn-outline-info container-fluid" data-toggle="buttons">
                  <i class="fas fa-book"></i>
                  気になるへ追加
                </p>
              </div>
              <div class="ajax-inte-remove <?php echo ($is_interest) ? '' : 'd-none'; ?>">
                <p class="btn btn-outline-info container-fluid" data-toggle="buttons">
                  <i class="fas fa-book"></i>
                  気になるから削除
                </p>
              </div>
            </div>
            <?php
            endif;
             ?>


            <?php if(!empty($editbook)):?>
              <div class="col-sm-6 mb-3">
              <?php
              echo Html::anchor( Uri::base().'members/books/bookEdit'.'?book='.$book['id'], '編集する',array('class' => 'btn btn-outline-info container-fluid'));
              ?>
              </div>
              <div class="col-sm-6">
                <!-- button modal -->
                <button type="button" class="btn btn-outline-info container-fluid" data-toggle="modal" data-target="#exampleModal">
                  削除する
                </button>
              </div>

            <?php endif;?>

          </div>

          <!-- Modal -->
          <div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">この本の内容を削除しますか？</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <?php
                  echo Html::anchor( Uri::base().'members/books/bookdelete'.'?book='.$book['id'], '削除する',array('class' => 'btn btn-outline-info container-fluid'));
                  ?>
                </div>
              </div>
            </div>
          </div>



        </div>
      </div>
    </div>
  </section>

  <!--  *****  *****  *****  *****  *****  -->
  <!-- summary -->
  <section id="summary" class="d-flex justify-content-center row mx-0">
    <div class="col-sm-9 col-md-11 mx-auto px-0 d-flex flex-column">
      <h1 class="text-muted">summary</h1>
      <div class="card p-0">
        <div class="card-body">
          <p class="card-text white-space-pre-line">
            <?php
            if(!empty($book['summary'])){
              echo $book['summary'];

            }else{
              echo 'まだ書かれていません！';
            }
            ?>
          </p>
        </div>
      </div>

    </div>
  </section>

  <!--  *****  *****  *****  *****  *****  -->
  <!-- book overview -->
  <section id="book-overview-short" class="d-flex justify-content-center my-5">
    <!-- feature -->
    <div class="d-flex row mx-auto d-flex justify-content-center container-fluid">
      <div class="card col-sm-3 col-md-4 p-0">
        <?php
        if(!empty($book['img'])){
          Asset::add_path('assets/img/uploads', 'img');
          echo Asset::img( $book['img'], array('class' => 'bd-placeholder-img card-img-top w-100 max-height__250 fit-cover') );
        }else{
          echo Asset::img( $bookImg, array('class' => 'bd-placeholder-img card-img-top w-100 max-height__250 fit-cover') );
        }
        ?>
      </div>
      <!-- short -->
      <div class="card col-sm-6 col-md-7 p-0">
        <div class="card-body">
          <ul class="list-group list-group-flush">
            <li class="list-group-item p-0"><?php echo $book['title']; ?></li>
            <li class="list-group-item font-weight-light text-secondary p-0">
              カテゴリー：
              <?php
              echo \Model\Category::get_name($book['cate_id']);
              ?>
            </li>
            <li class="list-group-item font-weight-light text-secondary p-0">
              <?php if(!empty($book['price'])){
                echo $book['price'].'円';
              }else{
                echo '¥-';
              }; ?>
            </li>
          </ul>

          <div class="row">


          </div>

        </div>
      </div>
    </div>
  </section>

  <!--  *****  *****  *****  *****  *****  -->
  <?php echo $btnContainer; ?>


</section>
