<?php
//------------action
//bookLists
//------------------



use \Model\Category;

const EMAIL_MIN_LEN = 1;
const EMAIL_MAX_LEN =255;
const USER_NAME_LEN = 6;
const PASS_LEN = 6;

class Controller_Book extends Controller_Template
{

  public function before()
   {
     //ログイン認証
       parent::before(); // この行がないと、テンプレートが動作しません!

       $groups = \Auth::get_groups();
       $group = $groups[0][1];
       //auth check
       if( \Auth::check() && $group == 1 )
       {
         //ログインチェックok
         $loginuser = true;

       }else{
         $loginuser = false;

         ////////////////////////////////////
         //ログインフォームの生成
         $login_form = Fieldset::forge('loginform');

         $login_form
         ->add('username','ユーザー名',
               array(
                 'type' => 'text',
                 'class' => 'form-control js-form-user-name',
                 'id' => 'login-user',
               )
         )
         ->add_rule('required')
         ->add_rule('exact_length', USER_NAME_LEN);

         $login_form
         ->add('password','パスワード',
               array(
                 'type' => 'password',
                 'class' => 'form-control js-form-user-pass',
                 'id' => 'login-user',
               )
         )
         ->add_rule('required')
         ->add_rule('exact_length', PASS_LEN);

         $login_form
         ->add('login', '',
               array('type' => 'submit',
                 'class' => 'btn btn-primary',
                 'value '=> 'ログイン'
               )
         )
         ->set_template(
           '<div class="modal-footer d-flex flex-column">{label}{required}{field} <span>{description}</span> {error_msg}</div>'
         );

         // error
         $errors = array();

         //ログインフォームが投稿されたら？
         if(Input::method() === 'POST' && Input::post('login')){
           // 現在の Fieldset の Validation インスタンスを取得
           $val = $login_form->validation();
           //バリデーションチェック
           if( $val->run() ){
             // バリデーションに成功した場合の処理
             // 資格情報のチェック
             if( \Auth::instance()->login(\Input::param('username'), \Input::param('password')) )
             {
               // ユーザーを覚えてほしい？
               if (\Input::param('remember', false))
               {
                   // remember-me クッキーを作成
                   \Auth::remember_me();
               }
               else
               {
                   // 存在する場合、 remember-me クッキーを削除
                   \Auth::dont_remember_me();
               }

               // ログイン
               Session::set_flash('sucMsg','ログインしました');
               \Response::redirect('members/mypage/index');

             }else{
               //ユーザー情報が見つかりません
               $errors = $login_form->error();
               Session::set_flash('errMsg','ユーザー情報が見つかりません');
             }


           }else{

             // 失敗 エラーを配列形式で格納
             $errors = $login_form->error();
             Session::set_flash('errMsg','ログインに失敗しました');

           }

           // フォーム送信からの入力値をフィールドに設定する。
           $login_form->repopulate();
         }

         //変数としてビューを割り当てる
         //login_formをviewへ渡す
         $this->template->login_form = View::set_global('login_form', $login_form->build(), false);
         $this->template->errors = View::set_global('errors', $errors);

         /////////////////////////////////////

       }
       //テンプレ
       $this->template->head = View::forge('template/head');
       $this->template->footer = View::forge('template/footer');
       $this->template->header = View::forge('template/header');
       $this->template->loginuser = View::set_global('loginuser' ,$loginuser);
   }

  //bookLists
  public function action_bookLists($bookTitle = 'title', $bookImg = 'dist/no_image.png', $bookStatus = '読んでる',  $summaryShort = null)
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
    $categories = Category::get_category();
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

    // $radios[3] = '高い';

    $radios = array('新しい', '古い', '安い', '高い' );

    $search_form
    ->add(
        'order', '',
        array('options' => $radios, 'type' => 'radio' )
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
          $order = array('updated_at' => 'desc');
        }elseif( $ord == 1){
          $order = array('updated_at' => 'asc');
        }elseif( $ord == 2){
          $order = array('price' => 'asc');
        }elseif( $ord == 3){
          $order = array('price' => 'desc');
        }else{
          $order = array('updated_at' => 'desc');
        }

      }else{
        $order = array('updated_at' => 'desc');
      }

    }else{

      //pagenation
      $count = \Model\Books::count('id', true, array('delete_flg' => 0) );
      $order = array('updated_at' => 'desc');
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


    $countset['delete_flg'] = 0;
    //pagenation
    $count = \Model\Books::count('id', true, $countset );

    $config = array(
        // 'pagination_url' => \Uri::base() . 'book/booklists' . '?cateid=' . 6,
        'pagination_url' => \Uri::base() . 'book/booklists?' . $cateparam . $statparam . $ordparam,
        'total_items'    => $count,
        'per_page'       => 12,
        'uri_segment'    => 3,
        // もしくは、クエリ文字列によるページネーションがよいのであれば
        'uri_segment'    => 'page',
    );


    // 'mypagination' という名前の pagination インスタンスを作る
    $pagination = Pagination::forge('mypagination', $config);


    //削除されていないモノを表示
    $where['delete_flg'] = '0';
    // $this->template->ok = View::set_global('ok',$where);


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
    $this->template->content = View::set_global('book/booklists', $data);
    $this->template->data = View::set_global('data', $data);
    $this->template->count = View::set_global('count', $count);


    /////////////////////////////////////////

      $this->template->content = View::forge('pages/bookLists');
      $this->template->btnContainer = View::set_global('searchHead',View::forge('common/searchHead'));
      // $categories = Category::get_category();
      $this->template->categories = View::set_global( 'categories', $categories);
      // $bookstatuses = \Model\Bookstatus::get_all();
      $this->template->bookstatuses = View::set_global('bookstatuses',$bookstatuses);
      $this->template->btnContainer = View::set_global('searchFoot',View::forge('common/searchFoot'));
      $this->template->bookTitle = View::set_global('bookTitle',$bookTitle);

      $this->template->bookStatus = View::set_global('bookStatus',$bookStatus);

      $this->template->bookImg = View::set_global('bookImg',$bookImg);
      $this->template->summaryShort = View::set_global('summaryShort',$summaryShort);

  }



}



 ?>
