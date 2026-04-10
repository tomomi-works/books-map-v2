<!-- *****  *****  *****  *****  ***** -->
<!-- footer -->

    <footer id="footer" class="bg-dark text-white px-2 pt-2 mt-auto">
      <!-- footer-menu -->
      <div class="container-fluid ">
        <div class="row">
          <div class="col-6">

            <ul class="nav flex-column bg-dark">
              <li class="nav-item">
                <?php echo Html::anchor('members/mypage/index','マイページ',array('class'=>'nav-link text-white font-ss')); ?>
              </li>
              <li class="nav-item">
                <?php echo Html::anchor('home/contact', 'お問い合わせ', array('class'=>'nav-link text-white font-ss') ); ?>
              </li>
            </ul>

          </div>
          <div class="col-6">
            <h3 class="m-0 text-right">BooksMap</h3>
          </div>
        </div>
      </div>
      <p class="m-0 d-flex align-items-end justify-content-center height__80">Copyright © <a href="https://github.com/tomomi-works/">TOMOMI</a> All Rights Reserved.</p>
    </footer>
  </div>
</div>
<?php echo Asset::js('dist/js/build.js'); ?>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>
</html>
