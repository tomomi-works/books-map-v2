<?php
//------------action
//registPass(パスワードを忘れた人のための再登録ページ)
//------------------

const EMAIL_MIN_LEN = 1;
const EMAIL_MAX_LEN =255;
const USER_NAME_LEN = 6;
const PASS_LEN = 6;

class Controller_Password extends Controller_Template{

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
               //ユーザーが居た以前のページか、
               //以前のページが検出できない場合はmembers/mypage/indexへ
               \Response::redirect_back('/home/index');
               //success message
               // Session::set_flash('sucMsg','ログインしました');
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
       //テンプレ
       $this->template->head = View::forge('template/head');
       $this->template->footer = View::forge('template/footer');
       $this->template->header = View::forge('template/header');
       $this->template->loginuser = View::set_global('loginuser' ,$loginuser);
   }



   //registPass
   public function action_registPass($hash = null)
   {
     $form = array();
     $errors = '';
     //コンタクトフォームを作成
     $resist_form = Fieldset::forge('contact',
       array(
        'form_attributes' => array(
            'class' => 'container-fluid'
            )
        )
      );

      //email
      $resist_form->add(
          'email', 'E-mail',
          array('type' => 'email', 'class' => 'form-control js-contact-email', 'value' => Input::post('email') ? Input::post('email') : ''),
          array('required', 'valid_email')
      );

      //送信ボタン
      $resist_form->add(
          'submit', '',
          array('type' => 'submit', 'class' => 'btn btn-primary col-sm-3', 'value' => '送信')
      )
      ->set_template('<div class=\"{error_class}\">{label}{required}</div><div class="field-fieldname form-group text-center">{field} {description} {error_msg}</div>');


      if(Input::method() === 'POST'){

        $post_email = Input::post('email');

        // 投稿されたメールアドレスを持っている？
        // \Model\Users
        $user = \DB::select_array(\Config::get('simpleauth.table_columns', array('*')))
          ->where('email', '=', $post_email)
          ->from(\Config::get('simpleauth.table_name'))
          ->as_object()->execute(\Config::get('simpleauth.db_connection'))->current();

          // このユーザーを知っている？
           if ($user)
           {
               // 復元ハッシュを生成
               $hash = \Auth::instance()->hash_password(\Str::random()).$user->id;

                // そして、ユーザープロファイルに格納
                 \Auth::update_user(
                     array(
                         'lostpassword_hash' => $hash,
                         'lostpassword_created' => time()
                     ),
                     $user->username
                 );

                 // リセットリンクを記載したメールを送信
                 \Package::load('email');
                 $email = \Email::forge();

                 // 電子メールメッセージを生成するためにビューファイルを使用
                 $form = array();
                 $form['url'] = \Uri::create('password/registPass/' . base64_encode($hash). '/' );
                 $form['user'] = $user->username;

                 $email->body(\View::forge('pages/lostpassword'), View::set_global('form',$form) );

                 // それに件名を与える
                 $email->subject('BooksMAPパスワード変更');

                 // 差出人と宛先を追加
                 $email->from('booksmap@tomomi-s.xyz', 'booksmap事務局');
                 $email->to($user->email, $user->username);


                 // 全てがうまくいった場合の処理
                 try
                 {
                   // 環境で場合分け
                   if (Fuel::$env === Fuel::DEVELOPMENT) {
                       // 【開発環境（MAMPなど）】
                       // 実際にメールは飛ばさず、ログにURLを出して確認
                       \Log::info('【開発環境デバッグ】再設定URL: ' . $form['url']);
                   } else {
                       // 【本番環境（PRODUCTION）】
                       // 実際にメールを送信する
                       $email->send();
                   }
                     // メールしたことをユーザーに通知
                     Session::set_flash('sucMsg', $post_email .'に再設定用のURLを送信しました');
                 }

                 // バリデーションエラー
                 catch(\EmailValidationFailedException $e)
                 {
                     Session::set_flash('errMsg','メール送信に失敗しました');
                 }
                 // その他、メールサーバーの認証失敗など
                 catch(\Exception $e)
                 {
                     // エラーを管理者が確認できるログに記録
                     logger(\Fuel::L_ERROR, '*** Error sending email ('.__FILE__.'#'.__LINE__.'): '.$e->getMessage());
                     Session::set_flash('errMsg','メール送信に失敗しました');
                 }
           }
           else
           {
             // セキュリティ面を考慮し、メール送信の旨を表示
             // ユーザーがメールアドレスの間違いに気がつけるよう、送信したメールアドレスを表示
             Session::set_flash('sucMsg',$post_email . 'に再設定用のURLを送信しました。');
           }

         }
         elseif ($hash !== null)
         // フォームの投稿が無く、 URL で渡されたハッシュを持っていますか？
         {
             // ハッシュをデコード
             $decoded = base64_decode($hash);
             // ハッシュからユーザーIDを取得
             $userid = substr($decoded, 44);

             // そして、この ID を持つユーザーを見つける
             if ($user = \Model\Users::find_by_pk($userid))
             {
               $profile_fields = @unserialize($user->profile_fields) ?: array();
               // 復元用ハッシュ値をセット
               $db_hash = isset($profile_fields['lostpassword_hash']) ? $profile_fields['lostpassword_hash'] : null;
               // 復元用の日付をセット
               $db_created = isset($profile_fields['lostpassword_created']) ? $profile_fields['lostpassword_created'] : 0;

               if( $db_hash !== null //ハッシュを持っている
                   and $db_hash === $decoded //ハッシュ値が一致
                   and (time() - $db_created) < 86400) //24時間未満の応答を許可
               {
                   // ログインさせずに、セッションに「許可証」としてユーザーIDを置いておく
                   Session::set('password_reset_user_id', $user->id);
                   Session::set_flash('sucMsg', '認証に成功しました。新しいパスワードを設定してください。');
                   Response::redirect('members/mypage/userPassEdit');
               }

             }

             // ハッシュがおかしい場合
             Session::set_flash('errMsg', 'リンクが違います。もう一度メールを送信する所からお試しください。');
         }


       $this->template->resist_form = View::set_global('resist_form',$resist_form->build(),false);

       $this->template->content = View::forge('pages/registPass');
       $this->template->btnContainer = View::set_global('btnContainer',View::forge('common/btnContainer'));
   }
}
