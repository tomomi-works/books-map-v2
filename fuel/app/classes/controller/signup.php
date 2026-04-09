<?php
//------------action
//signUp
//------------------


//定数はController_Public_Baseにて定義

class Controller_Signup extends Controller_Public_Base{


  public function before()
  {
     //ログイン認証
       parent::before(); // この行がないと、テンプレートが動作しません!

       // ログイン済みなら新規登録ページは見せない
        if (\Auth::check()) {
             \Response::redirect('book/booklists');
        }
  }


  public function action_index()
  {
    $error = '';
    $formData = '';

    //FieldSetクラス
    //Fieldsetクラスからインスタンスを生成
    $form = Fieldset::forge('signupform',array('form_attributes' => array('class' => 'col-sm-6 mx-auto',)));

    // 検証ルール付きFieldset Fieldを生成
    $form
    ->add('username', 'ユーザー名',
      array('type' => 'text', 'class' => 'form-control js-form-user-name', 'placeholder'=>'半角英数字6文字', 'autocomplete' => 'off') )
    ->add_rule('required')
    ->add_rule('exact_length', USER_NAME_LEN);

    $form
    ->add('email', 'email',
      array('type' => 'email', 'class' => 'form-control js-form-email', 'autocomplete' => 'off') )
    ->add_rule('required')
    ->add_rule('valid_email')
    ->add_rule('min_length', EMAIL_MIN_LEN)
    ->add_rule('max_length', EMAIL_MAX_LEN);

    $form
    ->add(
      'password', 'パスワード',
      array('type' => 'password', 'class' => 'form-control js-form-user-name', 'placeholder'=>'半角英数字6文字', 'autocomplete' => 'off')
    )
    ->add_rule('required')
    ->add_rule('exact_length', PASS_LEN);

    $form
    ->add(
      'submit', '',
      array('type' => 'submit', 'class' => 'btn btn-outline-dark col-5 d-block mx-auto mt-4', 'value '=> '登録' )
    );


    //submitされた時、
    //ポスト送信か確認(⇨validationクラスでバリデーションを実行するためにはPOST送信でなければならないため。)
    if(Input::method() === 'POST'){

      $val = $form->validation();
      //入力を検証
      if( $val->run() ){
        //バリデーションに成功した場合の処理
        // バリデートに成功したフィールドと値の組を配列で取得する
          $formData = $val->validated(); //ok

        //Authインスタンス生成
          $auth = Auth::instance();
        //userの作成
        try{
          $create = $auth->create_user($formData['username'], $formData['password'], $formData['email']);

          if( $create ){
            Session::set_flash('sucMsg','登録しました！');
            //ログインさせる
            $name = Input::post('username');
            $pass = Input::post('password');
            Auth::login($name, $pass);

            // リダイレクト
            Response::redirect_back('book/bookLists');
          }else{
            Session::set_flash('errMsg','ユーザー登録に失敗しました。');
          }

        }
        catch (\SimpleUserUpdateException $e)
        {
          // メールアドレスが重複
          if ($e->getCode() == 2)
          {
            Session::set_flash('errMsg','登録済みのユーザーです。');
          }
          // ユーザー名が重複
          elseif ($e->getCode() == 3)
          {
            Session::set_flash('errMsg','登録済みのユーザーです。');
          }
          // これは起こり得ないが、ずっとそうとは限らない...
          else
          {
            Session::set_flash('errMsg',$e->getMessage());
          }
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
      $form->repopulate();

    }

    //view
    $this->template->content = View::forge('auth/signup');
    $this->template->btnContainer = View::set_global('btnContainer',View::forge('common/btnContainer'));
    $this->template->signupform = View::set_global('signupform',$form->build(), false);
    $this->template->error = View::set_global('error', $error);

  }

}


 ?>
