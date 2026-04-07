<?php

//------------action
//index
//favorite
//interest
//submitBookList
//userInfoEdit
//userNameEdit
//withdraw
//logicaldelete
//------------------

const EMAIL_MIN_LEN = 1;
const EMAIL_MAX_LEN =255;
const USER_NAME_LEN = 6;
const PASS_LEN = 6;

class Controller_Members_Mypage extends Controller_Template{

  public function before()
   {
     //ログイン認証
       parent::before(); // この行がないと、テンプレートが動作しません!
       //auth check

       $groups = \Auth::get_groups();
       $group = $groups[0][1];
       //auth check
       if( \Auth::check() && $group == 1 )
       {
         //ログインチェックok
         $loginuser = true;
         // 必要があれば画面遷移

       }else{
         $loginuser = false;
         Session::set_flash('errMsg','ログインしていません');
         \Response::redirect_back('book/booklists');
       }


       //テンプレ
       $this->template->head = View::forge('template/head');
       $this->template->footer = View::forge('template/footer');
       $this->template->header = View::forge('template/header');
       $this->template->loginuser = View::set_global('loginuser' ,$loginuser);


   }


   //mypage
  public function action_index()
   {
       //contentは可変
       $this->template->content = View::forge('pages/mypage');
       //ほか
       $this->template->btnContainer = View::set_global('btnContainer',View::forge('common/btnContainer'));

   }


  //favorite
  public function action_favorite($date = 'date', $link = '#', $bookTitle = 'title', $category = '小説', $bookStatus = '読んでる', $price = '¥ -', $bookImg = 'dist/no_image.png')
  {

    /////////////////////////////////////////
    //search post
    //検索フォームを生成
    $search_form = Fieldset::forge('search',
    array(
    'form_attributes' => array(
                        'class' => 'row mb-3',
                        'method' => 'get'
                        )
    )
    );

    $search_form
    ->add('カテゴリー', 'カテゴリー',
          array(
                'type' => 'text',
                )
      )
    ->set_template('<p class="container-fluid">
                    カテゴリー
                    </p>');

    //Fieldset_Field インスタンスを作成し、現在の Fieldset に追加する。
    // チェックボックス
    //cateの配列に入る。valueは1つずれている。
    $categories = \Model\Category::get_category();
    $catename = array();
    foreach($categories as $key => $val){
      $catename[] = $val['name'];
    }
    $search_form
    ->add(
        'cate', '',
        array(
          'options' => $catename,
              'type' => 'checkbox',

              )
    )
    ->set_template('
    <div class="p-fieldbox form-group container-fluid">{group_label}{required}{fields}{field} {label}<br />{fields}<span>{description}</span>{error_msg}</div>
    ')
    ;

    $search_form
    ->add('ステータス', 'ステータス',
          array(
                'type' => 'text',
                )
      )
    ->set_template('<p class="container-fluid">
                    ステータス
                    </p>');


    $bookstatuses = \Model\Bookstatus::get_all();
    $statname = array();
    foreach ( $bookstatuses as $key => $val){
      $statname[] = $val['name'];
    }
      $search_form
      ->add(
          'stat', '',
          array(
            'options' => $statname,
                'type' => 'checkbox',

                )
      )
      ->set_template('
      <div class="p-fieldbox form-group container-fluid">{group_label}{required}{fields}{field} {label}{fields}<span>{description}</span>{error_msg}</div>
      ')
      ;

    $search_form
    ->add('表示順', '表示順',
          array(
                'type' => 'text',
                )
      )
    ->set_template('<p class="container-fluid">
                    表示順
                    </p>');

    //ラジオ
    $ops = array('新しい', '古い', '安い', '高い');
    $search_form
    ->add(
        'order', '',
        array('options' => $ops, 'type' => 'radio', 'value' => 'true')
    )
    ->set_template('<div class="container-fluid">
                    {group_label}{required}{fields}
                    {field}
                    {label}
                    {fields}<span>{description}</span>{error_msg}
                    </div>');


    $search_form
    ->add('submit', '',
          array('type' => 'submit',
                'value' => '検索',
                'class' => 'btn btn-info col-sm-3')
    )
    ->set_template(
      '<div class="container-fluid d-flex justify-content-center">{label}{required}{field} <span>{description}</span> {error_msg}</div>'
    );

    $search_form->repopulate();

    $this->template->search_form = View::set_global('search_form',$search_form->build(),false );


    //searchでPOSTされた条件を取得
    if(Input::method() === 'GET'){
      //POSTされた条件を入れる
      $where = array();
      $order = array();
      $countset = array();

      //カテゴリーがポストされていた場合
      if(!empty( Input::get('cate') )){
        $cate = array();
        $cate = Input::get('cate');
        $cateid = array();
        foreach ($cate as $key => $val) {
          //選択したカテゴリーのID
          $cateid[] = $val + 1;
          $catep[] = $val;
        }
        $where[] = array('cate_id', 'in', $cateid);
        //pagenation表示するレコード件数を指定
        $countset[] = array('cate_id', 'in', $cateid);
      }

      //ステータスがポストされていた場合
      if(!empty( Input::get('stat') )){
        $stat = array();
        $stat = Input::get('stat');
        $statid = array();
        foreach ($stat as $key => $val) {
          //選択したカテゴリーのID
          $statid[] = $val + 1;
          $statp[] = $val;
        }
        $where[] = array('stat_id', 'in', $statid);
        //pagenation表示するレコード件数を指定
        $countset[] = array('stat_id', 'in', $statid);
      }

      //表示順
      if(!empty( Input::get('order'))){
        $ord = Input::get('order');
        if( $ord == 0){
          $order = array('id' => 'desc');
        }elseif( $ord == 1){
          $order = array('id' => 'asc');
        }elseif( $ord == 2){
          $order = array('price' => 'asc');
        }elseif( $ord == 3){
          $order = array('price' => 'desc');
        }else{
          $order = array('id' => 'desc');
        }

      }else{
        $order = array('id' => 'desc');
      }

    }else{

      //pagenation
      $count = \Model\Favorite::count('id', true, array('delete_flg' => 0) );

    }

    //parameter調整
    //cate
    if(!empty($catep)){
      $cateparam = '';
      foreach ($catep as $key => $val) {
        //cate[0]=0&cate[1]=1&...って形で表示させる
          $cateparam .= 'cate['.$val.']='.$val.'&';
      }
    }
    else{
      $cateparam = '';
    }
    //stat
    if(!empty($statp)){
      $statparam = '';
      foreach ($statp as $key => $val) {
        //cate[0]=0&cate[1]=1&...って形で表示させる
          $statparam .= 'stat['.$val.']='.$val.'&';
      }
    }
    else{
      $statparam = '';
    }
    //order
    if(!empty($ord)){
      $ordparam = '';
          $ordparam = 'order='.(int)$ord.'&';
    }
    else{
      $ordparam = '';
    }

    // 削除されていないモノを条件に追加 (インデックス配列形式で統一)
    $where[] = array('delete_flg', '=', 0);
    $countset[] = array('delete_flg', '=', 0);

    //user
    // すべてのログインID情報を取得
    $id_info = Auth::get_user_id();

    // ドライバおよび id 情報を印字
    if ($id_info)
    {
          $u_id = $id_info[1];
    }
    else
    {
        Session::set_flash('errMsg','ログインしてください');
        Response::redirect('login'); // ログインしてなければ飛ばす
    }
    //ユーザーIDを元に、favoriteテーブルから全てのレコードを取得

    $favs = \Model\Favorite::find_by('user_id', $u_id);

    $favbooksid = array();
    if (empty($favs))
      {
        //お気に入り登録がなかった場合
        Session::set_flash('errMsg','お気に入り登録はありません');
        // お気に入りがない時のための空データ
        $data['books_data'] = array();
        $count = 0;
      }
      else
      {
        //お気に入り登録があった場合
          foreach($favs as $fav)
          {
            //全ての['book_id']を配列へ入れる
            $favbooksid[] = $fav['book_id'];
          }
          //where句を追加
          //$where['id'] = favoriteしている本のid;
          $where[] = array('id', 'in', $favbooksid);
          //favoriteしているidの本だけを表示
          $countset[] = array('id', 'in', $favbooksid );

          //pagenation
          $count = \Model\Books::count('id', true, $countset );

          $config = array(
              'pagination_url' => \Uri::base() . 'members/mypage/favorite?' . $cateparam . $statparam . $ordparam,
              'total_items'    => $count,
              'per_page'       => 12,
              'uri_segment'    => 'page',
          );

          // 'mypagination' という名前の pagination インスタンスを作る
          $pagination = Pagination::forge('mypagination', $config);

          //booksテーブルから['book_id']と合致する本の情報を引き出して$data['books_data']へ入れる
          $data['books_data'] = \Model\Books::find(array(
                                  'select' => array(
                                    'id',
                                    'title',
                                    'user_id',
                                    'cate_id',
                                    'stat_id',
                                    'price',
                                    'img',
                                    'short',
                                    'summary',
                                    'delete_flg',
                                    'updated_at',
                                    'created_at'
                                ),
                                'where' =>$where,
                                'order_by' => $order,
                                'limit' => $pagination->per_page,
                                'offset' => $pagination->offset,
                              ));

          // オブジェクトを渡し、ビューの中に echo で出力される時に表示される
          $data['pagination'] = $pagination;
          // ビューを返す

          // すでに お気に入りがある($this->template->content がセットされている)場合は forge しない
          $this->template->content = View::forge('pages/favorite', $data);
          // globalセット
          View::set_global('data', $data);
          View::set_global('count', $count);

          /////////////////////////////////////////
      }

      if (!isset($this->template->content)) {
          $this->template->content = View::forge('pages/favorite', array('books_data' => array()));
      }

      $this->template->btnContainer = View::set_global('searchHead',View::forge('common/searchHead'));
      $this->template->btnContainer = View::set_global('searchFoot',View::forge('common/searchFoot'));
      $this->template->btnContainer = View::set_global('btnContainer',View::forge('common/btnContainer'));
      $this->template->bookTitle = View::set_global('bookTitle',$bookTitle);
      $this->template->category = View::set_global('category',$category);
      $categories = \Model\Category::get_category();
      $this->template->categories = View::set_global( 'categories', $categories);
      $bookstatuses = \Model\Bookstatus::get_all();
      $this->template->bookstatuses = View::set_global('bookstatuses',$bookstatuses);
      $this->template->bookImg = View::set_global('bookImg',$bookImg);
      $this->template->bookStatus = View::set_global('bookStatus',$bookStatus);
      $this->template->price = View::set_global('price',$price);
      $this->template->link = View::set_global('link',$link);
      $this->template->date = View::set_global('date',$date);
  }

  //interest
  public function action_interest($date = 'date', $link = '#', $bookTitle = 'title', $category = '小説', $bookStatus = '読んでる', $price = '¥ -', $bookImg = 'dist/no_image.png')
  {

    /////////////////////////////////////////
    //search post
    //検索フォームを生成
    $search_form = Fieldset::forge('search',
    array(
    'form_attributes' => array(
                        'class' => 'row mb-3',
                        'method' => 'get'
                        )
    )
    );

    $search_form
    ->add('カテゴリー', 'カテゴリー',
          array(
                'type' => 'text',
                )
      )
    ->set_template('<p class="container-fluid">
                    カテゴリー
                    </p>');

    //Fieldset_Field インスタンスを作成し、現在の Fieldset に追加する。
    // チェックボックス
    //cateの配列に入る。valueは1つずれている。
    $categories = \Model\Category::get_category();
    $catename = array();
    foreach($categories as $key => $val){
      $catename[] = $val['name'];
    }
    $search_form
    ->add(
        'cate', '',
        array(
          'options' => $catename,
              'type' => 'checkbox',

              )
    )
    ->set_template('
    <div class="p-fieldbox form-group container-fluid">{group_label}{required}{fields}{field} {label}<br />{fields}<span>{description}</span>{error_msg}</div>
    ')
    ;

    $search_form
    ->add('ステータス', 'ステータス',
          array(
                'type' => 'text',
                )
      )
    ->set_template('<p class="container-fluid">
                    ステータス
                    </p>');


    $bookstatuses = \Model\Bookstatus::get_all();
    $statname = array();
    foreach ( $bookstatuses as $key => $val){
      $statname[] = $val['name'];
    }
      $search_form
      ->add(
          'stat', '',
          array(
            'options' => $statname,
                'type' => 'checkbox',

                )
      )
      ->set_template('
      <div class="p-fieldbox form-group container-fluid">{group_label}{required}{fields}{field} {label}{fields}<span>{description}</span>{error_msg}</div>
      ')
      ;

    $search_form
    ->add('表示順', '表示順',
          array(
                'type' => 'text',
                )
      )
    ->set_template('<p class="container-fluid">
                    表示順
                    </p>');

    //ラジオ
    $ops = array('新しい', '古い', '安い', '高い');
    $search_form
    ->add(
        'order', '',
        array('options' => $ops, 'type' => 'radio', 'value' => 'true')
    )
    ->set_template('<div class="container-fluid">
                    {group_label}{required}{fields}
                    {field}
                    {label}
                    {fields}<span>{description}</span>{error_msg}
                    </div>');


    $search_form
    ->add('submit', '',
          array('type' => 'submit',
                'value' => '検索',
                'class' => 'btn btn-info col-sm-3')
    )
    ->set_template(
      '<div class="container-fluid d-flex justify-content-center">{label}{required}{field} <span>{description}</span> {error_msg}</div>'
    );

    $search_form->repopulate();

    $this->template->search_form = View::set_global('search_form',$search_form->build(),false );


    //searchでPOSTされた条件を取得
    if(Input::method() === 'GET'){
      //POSTされた条件を入れる
      $where = array();
      $order = array();
      $countset = array();

      //カテゴリーがポストされていた場合
      if(!empty( Input::get('cate') )){
        $cate = array();
        $cate = Input::get('cate');
        $cateid = array();
        foreach ($cate as $key => $val) {
          //選択したカテゴリーのID
          $cateid[] = $val + 1;
          $catep[] = $val;
        }
        $where[] = array('cate_id', 'in', $cateid);
        //pagenation表示するレコード件数を指定
        $countset[] = array('cate_id', 'in', $cateid);
      }

      //ステータスがポストされていた場合
      if(!empty( Input::get('stat') )){
        $stat = array();
        $stat = Input::get('stat');
        $statid = array();
        foreach ($stat as $key => $val) {
          //選択したカテゴリーのID
          $statid[] = $val + 1;
          $statp[] = $val;
        }
        $where[] = array('stat_id', 'in', $statid);
        //pagenation表示するレコード件数を指定
        $countset[] = array('stat_id', 'in', $statid);
      }

      //表示順
      if(!empty( Input::get('order'))){
        $ord = Input::get('order');
        if( $ord == 0){
          $order = array('id' => 'desc');
        }elseif( $ord == 1){
          $order = array('id' => 'asc');
        }elseif( $ord == 2){
          $order = array('price' => 'asc');
        }elseif( $ord == 3){
          $order = array('price' => 'desc');
        }else{
          $order = array('id' => 'desc');
        }

      }else{
        $order = array('id' => 'desc');
      }
      // $order = array('id' => 'desc');

    }else{

      //pagenation
      $count = \Model\Books::count('id', true, array('delete_flg' => 0) );

    }

    //parameter調整
    //cate
    if(!empty($catep)){
      $cateparam = '';
      foreach ($catep as $key => $val) {
        //cate[0]=0&cate[1]=1&...って形で表示させる
          $cateparam .= 'cate['.$val.']='.$val.'&';
      }
    }
    else{
      $cateparam = '';
    }
    //stat
    if(!empty($statp)){
      $statparam = '';
      foreach ($statp as $key => $val) {
        //cate[0]=0&cate[1]=1&...って形で表示させる
          $statparam .= 'stat['.$val.']='.$val.'&';
      }
    }
    else{
      $statparam = '';
    }
    //order
    if(!empty($ord)){
      $ordparam = '';
          $ordparam = 'order='.(int)$ord.'&';
    }
    else{
      $ordparam = '';
    }

    $where[] = array('delete_flg', '=', 0);
    $countset[] = array('delete_flg', '=', 0);

    //user
    // すべてのログインID情報を取得
    $id_info = Auth::get_user_id();

    // ドライバおよび id 情報を印字
    if ($id_info)
    {
          $u_id = $id_info[1];
    }
    else
    {
        Session::set_flash('errMsg','ログインしてください');
        Response::redirect('login');
    }


    //ユーザーIDを元に、favoriteテーブルから全てのレコードを取得
    $interests = \Model\Interest::find_by('user_id', $u_id);
    $intebooksid = array();
    if (empty($interests))
      {
        //お気に入り登録がなかった場合
        Session::set_flash('errMsg','気になる登録はありません');
        $data['books_data'] = array();
        $count = 0;
      }
      else
      {
        //お気に入り登録があった場合
          foreach($interests as $interest)
          {
            //全ての['book_id']を配列へ入れる
            $intebooksid[] = $interest['book_id'];
          }

          //where句を追加
          //$where['id'] = favoriteしている本のid;
          $where[] = array('id', 'in', $intebooksid);
          //interestしている本だけを表示
          $countset[] = array('id', 'in', $intebooksid );

          //pagenation
          $count = \Model\Books::count('id', true, $countset );

          $config = array(
              // 'pagination_url' => \Uri::base() . 'book/booklists' . '?cateid=' . 6,
              'pagination_url' => \Uri::base() . 'members/mypage/interest?' . $cateparam . $statparam . $ordparam,
              'total_items'    => $count,
              'per_page'       => 12,
              // 'uri_segment'    => 3,
              // もしくは、クエリ文字列によるページネーションがよいのであれば
              'uri_segment'    => 'page',
          );

          // 'mypagination' という名前の pagination インスタンスを作る
          $pagination = Pagination::forge('mypagination', $config);

          //booksテーブルから['book_id']と合致する本の情報を引き出して$data['books_data']へ入れる
          $data['books_data'] = \Model\Books::find(array(
                                  'select' => array(
                                    'id',
                                    'title',
                                    'user_id',
                                    'cate_id',
                                    'stat_id',
                                    'price',
                                    'img',
                                    'short',
                                    'summary',
                                    'delete_flg',
                                    'updated_at',
                                    'created_at'
                                ),
                                'where' =>$where,
                                'order_by' => $order,
                                'limit' => $pagination->per_page,
                                'offset' => $pagination->offset,
                              ));

          // オブジェクトを渡し、ビューの中に echo で出力される時に表示される
          $data['pagination'] = $pagination;
          // ビューを返す
          $this->template->content = View::forge('pages/interest', $data);
          // グローバル
          View::set_global('data', $data);
          View::set_global('count', $count);

          /////////////////////////////////////////

      }

      if (!isset($this->template->content)) {
          $this->template->content = View::forge('pages/interest', array('books_data' => array()));
      }


      // $this->template->content = View::forge('pages/interest');
      $this->template->btnContainer = View::set_global('searchHead',View::forge('common/searchHead'));
      $categories = \Model\Category::get_category();
      $this->template->categories = View::set_global( 'categories', $categories);

      $this->template->btnContainer = View::set_global('searchFoot',View::forge('common/searchFoot'));
      $this->template->btnContainer = View::set_global('btnContainer',View::forge('common/btnContainer'));
      $this->template->bookTitle = View::set_global('bookTitle',$bookTitle);
      $this->template->category = View::set_global('category',$category);
      $bookstatuses = \Model\Bookstatus::get_all();
      $this->template->bookstatuses = View::set_global('bookstatuses',$bookstatuses);
      $this->template->bookStatus = View::set_global('bookStatus',$bookStatus);
      $this->template->bookImg = View::set_global('bookImg',$bookImg);
      $this->template->price = View::set_global('price',$price);
      $this->template->link = View::set_global('link',$link);
      $this->template->date = View::set_global('date',$date);
  }

  //submitBookList
  public function action_submitBookList($date = 'date', $link = 'home/bookDetail', $bookTitle = 'title', $category = '小説', $bookStatus = '読んでる',  $price = '¥ -', $bookImg = 'dist/no_image.png')
  {

    /////////////////////////////////////////
    //search post
    //検索フォームを生成
    $search_form = Fieldset::forge('search',
    array(
    'form_attributes' => array(
                        'class' => 'row mb-3',
                        'method' => 'get'
                        )
    )
    );

    $search_form
    ->add('カテゴリー', 'カテゴリー',
          array(
                'type' => 'text',
                )
      )
    ->set_template('<p class="container-fluid">
                    カテゴリー
                    </p>');

    //Fieldset_Field インスタンスを作成し、現在の Fieldset に追加する。
    // チェックボックス
    //cateの配列に入る。valueは1つずれている。
    $categories = \Model\Category::get_category();
    $catename = array();
    foreach($categories as $key => $val){
      $catename[] = $val['name'];
    }
    $search_form
    ->add(
        'cate', '',
        array(
          'options' => $catename,
              'type' => 'checkbox',

              )
    )
    ->set_template('
    <div class="p-fieldbox form-group container-fluid">{group_label}{required}{fields}{field} {label}<br />{fields}<span>{description}</span>{error_msg}</div>
    ')
    ;

    $search_form
    ->add('ステータス', 'ステータス',
          array(
                'type' => 'text',
                )
      )
    ->set_template('<p class="container-fluid">
                    ステータス
                    </p>');


    $bookstatuses = \Model\Bookstatus::get_all();
    $statname = array();
    foreach ( $bookstatuses as $key => $val){
      $statname[] = $val['name'];
    }
      $search_form
      ->add(
          'stat', '',
          array(
            'options' => $statname,
                'type' => 'checkbox',

                )
      )
      ->set_template('
      <div class="p-fieldbox form-group container-fluid">{group_label}{required}{fields}{field} {label}{fields}<span>{description}</span>{error_msg}</div>
      ')
      ;

    $search_form
    ->add('表示順', '表示順',
          array(
                'type' => 'text',
                )
      )
    ->set_template('<p class="container-fluid">
                    表示順
                    </p>');

    //ラジオ
    $ops = array('新しい', '古い', '安い', '高い');
    $search_form
    ->add(
        'order', '',
        array('options' => $ops, 'type' => 'radio', 'value' => 'true')
    )
    ->set_template('<div class="container-fluid">
                    {group_label}{required}{fields}
                    {field}
                    {label}
                    {fields}<span>{description}</span>{error_msg}
                    </div>');


    $search_form
    ->add('submit', '',
          array('type' => 'submit',
                'value' => '検索',
                'class' => 'btn btn-info col-sm-3')
    )
    ->set_template(
      '<div class="container-fluid d-flex justify-content-center">{label}{required}{field} <span>{description}</span> {error_msg}</div>'
    );

    $search_form->repopulate();

    $this->template->search_form = View::set_global('search_form',$search_form->build(),false );


    //searchでPOSTされた条件を取得
    if(Input::method() === 'GET'){
      //POSTされた条件を入れる
      $where = array();
      $order = array();
      $countset = array();

      //カテゴリーがポストされていた場合
      if(!empty( Input::get('cate') )){
        $cate = array();
        $cate = Input::get('cate');
        $cateid = array();
        foreach ($cate as $key => $val) {
          //選択したカテゴリーのID
          $cateid[] = $val + 1;
          $catep[] = $val;
        }
        $where[] = array('cate_id', 'in', $cateid);
        //pagenation表示するレコード件数を指定
        $countset[] = array('cate_id', 'in', $cateid);
      }

      //ステータスがポストされていた場合
      if(!empty( Input::get('stat') )){
        $stat = array();
        $stat = Input::get('stat');
        $statid = array();
        foreach ($stat as $key => $val) {
          //選択したカテゴリーのID
          $statid[] = $val + 1;
          $statp[] = $val;
        }
        $where[] = array('stat_id', 'in', $statid);
        //pagenation表示するレコード件数を指定
        $countset[] = array('stat_id', 'in', $statid);
      }

      //表示順
      if(!empty( Input::get('order'))){
        $ord = Input::get('order');
        if( $ord == 0){
          $order = array('id' => 'desc');
        }elseif( $ord == 1){
          $order = array('id' => 'asc');
        }elseif( $ord == 2){
          $order = array('price' => 'asc');
        }elseif( $ord == 3){
          $order = array('price' => 'desc');
        }else{
          $order = array('id' => 'desc');
        }

      }else{
        $order = array('id' => 'desc');
      }

    }else{

      //pagenation
      $count = \Model\Books::count('id', true, array('delete_flg' => 0, $submitid) );

    }

    //parameter調整
    //cate
    if(!empty($catep)){
      $cateparam = '';
      foreach ($catep as $key => $val) {
        //cate[0]=0&cate[1]=1&...って形で表示させる
          $cateparam .= 'cate['.$val.']='.$val.'&';
      }
    }
    else{
      $cateparam = '';
    }
    //stat
    if(!empty($statp)){
      $statparam = '';
      foreach ($statp as $key => $val) {
        //cate[0]=0&cate[1]=1&...って形で表示させる
          $statparam .= 'stat['.$val.']='.$val.'&';
      }
    }
    else{
      $statparam = '';
    }
    //order
    if(!empty($ord)){
      $ordparam = '';
          $ordparam = 'order='.$ord.'&';
    }
    else{
      $ordparam = '';
    }


    //削除されていないモノを表示
    $where[] = array('delete_flg', '=', 0);

    //user
    // すべてのログインID情報を取得
    $id_info = Auth::get_user_id();

    // ドライバおよび id 情報を印字
    if ($id_info)
    {
          $u_id = $id_info[1];
    }
    else
    {
        Session::set_flash('errMsg','ログインしてください');
    }


    $where[] = array('user_id', '=', $u_id);

    //submitしている本のid全て
    $submit_all = \Model\Books::find_by('user_id', Auth::get_user_id()[1] );
    $submitid = array();

    if(empty($submit_all)){
      Session::set_flash('errMsg','まだ投稿していません');

    }else{

      foreach ($submit_all as $key => $val) {
        $submitid[] = $val['id'];
      }
      //submitしている本だけを表示
      $countset[] = array('id', 'in', $submitid );

      //削除されていないモノを表示
      $countset['delete_flg'] = 0;

      //pagenation
      $count = \Model\Books::count('id', true, $countset );

      $config = array(
          // 'pagination_url' => \Uri::base() . 'book/booklists' . '?cateid=' . 6,
          'pagination_url' => \Uri::base() . 'members/mypage/submitBookList?' . $cateparam . $statparam . $ordparam,
          'total_items'    => $count,
          'per_page'       => 12,
          'uri_segment'    => 3,
          // もしくは、クエリ文字列によるページネーションがよいのであれば
          'uri_segment'    => 'page',
      );



      // 'mypagination' という名前の pagination インスタンスを作る
      $pagination = Pagination::forge('mypagination', $config);

      //booksテーブルから['book_id']と合致する本の情報を引き出して$data['books_data']へ入れる
      $data['books_data'] = \Model\Books::find(array(
                              'select' => array(
                                'id',
                                'title',
                                'user_id',
                                'cate_id',
                                'stat_id',
                                'price',
                                'img',
                                'short',
                                'summary',
                                'delete_flg',
                                'updated_at',
                                'created_at'
                            ),
                            'where' =>$where,
                            'order_by' => $order,
                            'limit' => $pagination->per_page,
                            'offset' => $pagination->offset,
                          ));

      // オブジェクトを渡し、ビューの中に echo で出力される時に表示される
      $data['pagination'] = $pagination;
      // ビューを返す
      $this->template->content = View::forge('pages/submitBookList', $data);
      // グローバル
      View::set_global('data', $data);
      View::set_global('count', $count);

    }

    if (!isset($this->template->content)) {
      $this->template->content = View::forge('pages/submitBookList', array('books_data' => array()));
    }


    /////////////////////////////////////////

      $bookstatuses = \Model\Bookstatus::get_all();
      $this->template->bookstatuses = View::set_global('bookstatuses',$bookstatuses);
      $categories = \Model\Category::get_category();
      $this->template->categories = View::set_global( 'categories', $categories);

      $this->template->btnContainer = View::set_global('searchHead',View::forge('common/searchHead'));
      $this->template->btnContainer = View::set_global('searchFoot',View::forge('common/searchFoot'));
      $this->template->btnContainer = View::set_global('btnContainer',View::forge('common/btnContainer'));
      $this->template->bookTitle = View::set_global('bookTitle',$bookTitle);
      $this->template->category = View::set_global('category',$category);
      $this->template->bookStatus = View::set_global('bookStatus',$bookStatus);

      $this->template->bookImg = View::set_global('bookImg',$bookImg);
      $this->template->price = View::set_global('price',$price);
      $this->template->link = View::set_global('link',$link);
      $this->template->date = View::set_global('date',$date);
  }

  //userInfoEdit //email
  public function action_userInfoEdit($userId = 'userId', $nick = null, $pass = 'pass66' )
  {
    //ユーザーIDを取得
    $id_info = Auth::get_user_id();
    // var_dump($id_info[1]);
    //email
    $email_info = Auth::get_email();
    //name
    $name_info = Auth::get_screen_name();


    ////////////////////////////////////
      $error = '';
      $formData = '';
      //ユーザー情報編集用のフォーム作成
      $usereditform = Fieldset::forge('useredit',
      array(
          'form_attributes' => array(
          'class' => 'col-sm-6 mx-auto',
          )
      )
    );


      $usereditform
      ->add('email', 'email',
        array('type' => 'email', 'class' => 'form-control js-form-email','value' => $email_info , 'autocomplete' => 'off') )
      // ->add_rule('required')
      ->add_rule('valid_email')
      ->add_rule('min_length', EMAIL_MIN_LEN)
      ->add_rule('max_length', EMAIL_MAX_LEN);

      $usereditform
      ->add(
        'submit', '',
        array('type' => 'submit', 'class' => 'btn btn-outline-dark col-5 d-block mx-auto mt-4', 'value '=> '変更する' )
      );

      //submitされた時、
      //ポスト送信か確認(⇨validationクラスでバリデーションを実行するためにはPOST送信でなければならないため。)
      if(Input::method() === 'POST'){

        $val = $usereditform->validation();
        //入力を検証
        if( $val->run() ){
          //バリデーションに成功した場合の処理
          // バリデートに成功したフィールドと値の組を配列で取得する
            $formData = $val->validated(); //ok

          //編集フォームが空欄だった場合の処理
          //email
          if(!empty($formData['email']) && $formData['email'] !== $email_info ){
            $edit_email = $formData['email'];
          }else{
            $edit_email = $email_info;
          }

          // 現在のユーザーのデータを更新
          $update = Auth::update_user(
              array(
                  'email'        => $edit_email,  // 新しいメールアドレスを設定する

              )
          );
          //データベースを更新
          if( $update ){

          // 更新できたら、セッションに値をいれ、メッセージを出す
            Session::set_flash('sucMsg','変更しました！');
          // リダイレクト
            Response::redirect('book/booklists');
          }
          else
          {
            // セッションに値をいれ、メッセージを出す
            Session::set_flash('errMsg','変更できませんでした！');
          }


        }
        else {
          //失敗
          // エラー格納
          $error = $val->error();
          //セッションに値をいれ、メッセージを出す
          Session::set_flash('errMsg','登録できませんでした！');
        }
        // フォームにPOSTされた値をセット
        $usereditform->repopulate();

      }

        $this->template->usereditform = View::set_global('usereditform',$usereditform->build(), false);
        $this->template->error = View::set_global('error', $error);

    ///////////////////////////////////


      $this->template->content = View::forge('pages/userInfoEdit');
      $this->template->btnContainer = View::set_global('btnContainer',View::forge('common/btnContainer'));
      $this->template->userId = View::set_global('userId',$userId);
      $this->template->nick = View::set_global('nick',$nick);
      $this->template->pass = View::set_global('pass',$pass);
  }


  //userInfoEdit //nickname
  public function action_userNameEdit($userId = '', $nick = null, $pass = '' )
  {
    $name_info = Auth::get_profile_fields('nickname','ユーザーさん');

    ////////////////////////////////////
      $error = '';
      $formData = '';
      //ユーザー情報編集用のフォーム作成
      $usereditform = Fieldset::forge('nameedit',
      array(
          'form_attributes' => array(
          'class' => 'col-sm-6 mx-auto',
          )
      )
    );

      // 検証ルール付きFieldset Fieldを生成
      $usereditform
      ->add('nickname', 'ユーザー名',
        array('type' => 'text', 'class' => 'form-control js-form-user-name','value' => $name_info, 'placeholder'=>'半角英数字6文字', 'autocomplete' => 'off') )
      ->add_rule('required')
      ->add_rule('exact_length', USER_NAME_LEN);

      $usereditform
      ->add(
        'submit', '',
        array('type' => 'submit', 'class' => 'btn btn-outline-dark col-5 d-block mx-auto mt-4', 'value '=> '変更する' )
      );

      //submitされた時、
      //ポスト送信か確認(⇨validationクラスでバリデーションを実行するためにはPOST送信でなければならないため。)
      if(Input::method() === 'POST'){

        $val = $usereditform->validation();
        //入力を検証
        if( $val->run() ){
          //バリデーションに成功した場合の処理
          // バリデートに成功したフィールドと値の組を配列で取得する
            $formData = $val->validated(); //ok
          // 現在のユーザーのデータを更新
          $update = Auth::update_user(
              array(
                  'nickname'     => $formData['nickname'],
              )
          );
          //データベースを更新
          if( $update ){

          // 更新できたら、セッションに値をいれ、メッセージを出す
            Session::set_flash('sucMsg','変更しました！');

          }
          else
          {
            $error = $val->error();
            // セッションに値をいれ、メッセージを出す
            Session::set_flash('errMsg','登録できませんでした！');
          }

        }
        else {
          //失敗
          // エラー格納
          $error = $val->error();
          //セッションに値をいれ、メッセージを出す
          Session::set_flash('errMsg','登録できませんでした！');
        }

      }

        $this->template->usereditform = View::set_global('usereditform',$usereditform->build(), false);
        $this->template->error = View::set_global('error', $error);

    ///////////////////////////////////

      $this->template->content = View::forge('pages/userNameEdit');
      $this->template->btnContainer = View::set_global('btnContainer',View::forge('common/btnContainer'));
      $this->template->userId = View::set_global('userId',$userId);
      $this->template->nick = View::set_global('nick',$nick);
      $this->template->pass = View::set_global('pass',$pass);
  }




  //withdraw
  public function action_withdraw()
  {
    $withdraw_form = \Fieldset::forge('withdraw');

    $withdraw_form
    ->add('withdraw', '', array( 'type' => 'submit', 'class' => 'btn btn-outline-dark col-sm-1 col-5 mx-auto mt-4', 'value' => '退会する' ))
    ->set_template('<div class=\"{error_class}\">{label}{required}</div><div class="field-fieldname text-center">{field} {description} {error_msg}</div>');


    $this->template->withdraw_form =  View::set_global( 'withdraw_form', $withdraw_form->build( Uri::create('members/mypage/logicaldelete') ), false );
    $this->template->content = View::forge('pages/withdraw');
    $this->template->btnContainer = View::set_global('btnContainer',View::forge('common/btnContainer'));

  }

  //userデータを論理削除
  public function action_logicaldelete($withdraw_form = '')
  {
    //username取得
    $username = Auth::get_screen_name();
    //userを削除
    Auth::delete_user( $username );

    //退会完了画面へ
    $this->template->content = View::forge('pages/withdraw');

  }



}

 ?>
