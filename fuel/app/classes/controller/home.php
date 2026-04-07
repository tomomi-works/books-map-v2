<?php
//------------action
//index
//contact
//bookDetail
//------------------

use \Model\Post;

//定数
const USER_NAME_LEN = 6;//usernameの長さ
const PASS_LEN = 6; //パスワードの長さ
const CONTACT_NAME_LEN = 29;
const CONTACT_TEXT_LEN = 1000;

class Controller_Home extends Controller_Template
{

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

       }else{
         $loginuser = false;

         ///////////////////////
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
         if(Input::method() === 'POST' && Input::post('login') ){
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
               //ユーザーが居た以前のページか、
               //以前のページが検出できない場合はbook/booklistsへ
               \Response::redirect_back('book/booklists');

             }


           }else{
             // 失敗
             //エラーを配列形式で格納
             $errors = $login_form->error();
             //erorr message
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

       //テンプレ(beforeなどでまとめて読み込み)
       $this->template->head = View::forge('template/head');
       $this->template->footer = View::forge('template/footer');
       $this->template->header = View::forge('template/header');
       $this->template->loginuser = View::set_global('loginuser' ,$loginuser);
   }

  //home画面から誰でもアクセスできる
    //index
    public function action_index($link ='#', $bookTitle = 'title', $bookImg = 'dist/no_image.png', $summaryShort = null )
    {

        $data = \Model\Books::find(array(
                'select' => array('id', 'title', 'user_id', 'img', 'short','updated_at' ),
                'where' => array(
                    'delete_flg' => '0',
                ),
                'order_by' => array(
                    'updated_at' => 'desc'
                ),
                'limit' => 6,
                'offset' => 0,
            )
          );

        // ビューを返す
        $this->template->data = View::set_global('data', $data);

        $this->template->content = View::forge('pages/index');
        $this->template->link = View::set_global('link',$link);
        $this->template->bookTitle = View::set_global('bookTitle',$bookTitle);
        $this->template->bookImg = View::set_global('bookImg',$bookImg);
        $this->template->summaryShort = View::set_global('summaryShort',$summaryShort);

    }

    //contact
    public function action_contact()
    {
      $form = array();
      $errors_contact = '';
      //コンタクトフォームを作成
      $contact_form = Fieldset::forge('contact',
        array(
         'form_attributes' => array(
             'class' => 'container-fluid'
             )
         )
       );

      //name
      $contact_form->add(
          'name', 'お名前',
          array('type' => 'text', 'class' => 'form-control js-contact-name'),
          array(array('required'))
      )
      ->add_rule('max_length', CONTACT_NAME_LEN);

      //email
      $contact_form->add(
          'email', 'E-mail',
          array('type' => 'email', 'class' => 'form-control js-contact-email'),
          array('required', 'valid_email')
      );

      //text
      $contact_form->add(
          'content', 'お問い合わせ内容',
          array('type' => 'textarea', 'class' => 'form-control js-contact-matter', 'rows' => '10'),
          array(array('required'))
      )
      ->add_rule('max_length', CONTACT_TEXT_LEN)
      ->set_template('<div class=\"{error_class}\">{label}{required}</div><div class="field-fieldname form-group js-contact-content">{field} {description} {error_msg}</div><p class="text-right js-counter"><span class="js-show-count">0</span>/1000文字</p>');

      //送信ボタン
      $contact_form->add(
          'submit', '',
          array('type' => 'submit', 'class' => 'btn btn-primary col-sm-3', 'value' => '送信')
      )
      ->set_template('<div class=\"{error_class}\">{label}{required}</div><div class="field-fieldname form-group text-center">{field} {description} {error_msg}</div>');


      // error
      $errors_contact = array();

      if(Input::method() === 'POST'){
        //送信された場合
        // 入力を検証
        $contact_form->validation()->run();

        // 検証済みの場合、管理者と問合せした人にメールを送信
        if(!$contact_form->validation()->error()){

          \Package::load('email');
            // インスタンスを生成する
            $email = Email::forge();
            // 送信者のアドレスを指定する
            $email->from(Input::post('email'), Input::post('name'));
            // 受信者のアドレスを指定する(自分と相手にも届くようにする　)
            $email->to(array(
                'booksmap@tomomi-s.xyz' => 'booksmap管理者',
                Input::post('email') => Input::post('name'),
            ));

            // 表題を指定する
            $email->subject('BooksMAPへのお問い合わせの件');

            // 本文を指定する。
            $form = array();
            $form['email'] = Input::post('email');
            $form['name'] = Input::post('name');
            $form['content'] = Input::post('content');

            $email->body(\View::forge('pages/mail'), View::set_global('form',$form) );


            //メール送信
            try
            {
                $email->send();
                Session::set_flash('sucMsg','送信できました');
                \Response::redirect_back('book/bookLists');

            }
            catch(\EmailValidationFailedException $e)
            {
                // バリデーションが失敗したとき
                Session::set_flash('errMsg','送信できませんでした。メールアドレスをご確認ください');
            }
            catch(\EmailSendingFailedException $e)
            {
                // ドライバがメールを送信できなかったとき
                Session::set_flash('errMsg','送信できませんでした。');
            }


        }else{
          // 失敗
          //エラーを配列形式で格納
          $errors_contact = $contact_form->error();
          //erorr message
          Session::set_flash('errMsg','メール送信に失敗しました');
          $contact_form->repopulate();

        }

      }else{
        //送信失敗
        Session::set_flash('err','送信できませんでした');

      }

        $this->template->contact_form = View::set_global('contact_form',$contact_form->build(),false);

        $this->template->content = View::forge('pages/contact');
        $this->template->btnContainer = View::set_global('btnContainer',View::forge('common/btnContainer'));
        $this->template->errors_contact = View::set_global('errors_contact', $errors_contact);


    }

    //bookDetail
    public function action_bookDetail($editbook = false, $bookTitle = '', $category = '', $price = '¥ -',$bookstatus = '', $bookImg = 'dist/no_image.png', $summaryShort = 'まだ書かれていません！', $summary = null, $username = '')
    {
      // お気に入りと気になるの変数を初期化（ログインしていない場合のエラー表示回避）
      $is_favorite = false;
      $is_interest = false;

      // getパラメーターから、books_idを取得
      $books_id = Input::get('book');

      // books_idと合致する本の登録情報
      $book = \Model\Books::find_one_by('id', $books_id);
      if($book){
        //本があれば
        $this->template->book = View::set_global('book',$book);
      }else{
        //本がなければ
        Session::set_flash('err','本が見つかりませんでした');
        \Response::redirect_back('book/bookLists');
      }

      //ログイン中なら
      if(\Auth::check()){
        //ログイン中のユーザーID
        $user_id = Auth::get_user_id()[1];

        //お気に入り登録済みかチェック ---
        $is_favorite = \Model\Favorite::find_one_by(array(
          'book_id' => $books_id,
          'user_id' => $user_id,
        ));
        // Viewに結果を渡す
        View::set_global('is_favorite', !empty($is_favorite));

        //気になる登録済みかチェック ---
        $is_interest = \Model\Interest::find_one_by(array(
            'book_id' => $books_id,
            'user_id' => $user_id,
        ));
        // Viewに結果を渡す
        View::set_global('is_interest', !empty($is_interest));

        //本の登録情報にあるuser_idと合致する場合
        if($book['user_id'] == $user_id ){
          // 編集ボタンを表示
          $editbook = true;
        }else{
          $editbook = false;
        }
      }

      //この本を書いたユーザー
      $userid = $book['user_id'];
      $user = \Model\Users::find_by_pk($userid);
      $username = $user->username;


        $this->template->content = View::forge('pages/bookDetail');
        $this->template->btnContainer = View::set_global('btnContainer',View::forge('common/btnContainer'));
        $this->template->bookTitle = View::set_global('bookTitle',$bookTitle);
        $this->template->category = View::set_global('category',$category);
        $this->template->bookstatus = View::set_global('bookstatus',$bookstatus);
        $this->template->price = View::set_global('price',$price);
        $this->template->bookImg = View::set_global('bookImg',$bookImg);
        $this->template->summaryShort = View::set_global('summaryShort',$summaryShort);
        $this->template->summary = View::set_global('summary',$summary);
        $this->template->editbook = View::set_global('editbook', $editbook);
        $this->template->username = View::set_global('username', $username);

    }





}
 ?>
