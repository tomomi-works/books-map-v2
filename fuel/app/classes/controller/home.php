<?php
//------------action
//index
//contact
//bookDetail
//------------------

use \Model\Post;

//定数
const CONTACT_NAME_LEN = 29;
const CONTACT_TEXT_LEN = 1000;

class Controller_Home extends Controller_Public_Base{

  public function before()
   {
    //ログイン認証
    parent::before(); // この行がないと、テンプレートが動作しません!
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

            // 送信者のアドレスを指定（自分のサイト専用アドレス）
            $email->from('info@tomomi-s.xyz', 'BooksMAPお問い合わせフォーム');
            // 返信先のアドレスを指定（顧客のメールアドレス）
            $email->reply_to(Input::post('email'), Input::post('name'));
            // 受信者のアドレスを指定（自分と顧客）
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
            // メール本文用のViewに指定した本文を渡す
            $email->body(\View::forge('pages/mail', array('form' => $form)));

            //メール送信
            try
            {
              // 環境で場合分け
              if (Fuel::$env === Fuel::DEVELOPMENT) {
                  // 【開発環境（MAMPなど）】
                  // 実際にメールは飛ばさず、ログにURLを出して確認
                  \Log::info('メールが送信されました');
                  \Log::info('送信内容: ' . print_r($form, true));
              } else {
                  // 【本番環境（PRODUCTION）】
                  // 実際にメールを送信する
                  $email->send();
              }

                Session::set_flash('sucMsg','メールが送信されました');
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
