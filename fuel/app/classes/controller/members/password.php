<?php

//------------action
//userPassEdit
//------------------

const PASS_LEN = 6;

class Controller_Members_Password extends Controller_Members_Base{

  //userInfoEdit //password
  // public function action_userPassEdit($userId = '', $nick = null, $pass = '' )
  public function action_userPassEdit()
  {

    ////////////////////////////////////
      $error = '';
      $formData = '';
      // パスワード再設定モード用のフラグ
      $reset_user_id = Session::get('password_reset_user_id');

      //ユーザー情報編集用のフォーム作成
      $usereditform = Fieldset::forge('passedit',
      array(
          'form_attributes' => array(
          'class' => 'col-sm-6 mx-auto',
          )
      )
    );

      $usereditform
      ->add(
        'old_password', 'パスワード(旧)',
        array('type' => 'password', 'class' => 'form-control js-form-user-name', 'placeholder'=>'半角英数字6文字', 'autocomplete' => 'off')
      )
      ->add_rule('required')
      ->add_rule('exact_length', PASS_LEN);

      $usereditform
      ->add(
        'password', 'パスワード(新)',
        array('type' => 'password', 'class' => 'form-control js-form-user-name', 'placeholder'=>'半角英数字6文字', 'autocomplete' => 'off')
      )
      ->add_rule('required')
      ->add_rule('exact_length', PASS_LEN);

      $usereditform
      ->add(
        'submit', '',
        array('type' => 'submit', 'class' => 'btn btn-outline-dark col-5 d-block mx-auto mt-4', 'value '=> '変更する')
      );

      // 再設定モードなら、旧パスワードの入力を免除
      if ($reset_user_id) {
          // フィールドセットから旧パスワードの入力を削除
          $usereditform->delete('old_password');
      }

      //submitされた時、
      //ポスト送信か確認(⇨validationクラスでバリデーションを実行するためにはPOST送信でなければならないため。)
      if(Input::method() === 'POST'){

        $val = $usereditform->validation();
        //入力を検証
        if( $val->run() ){
          //バリデーションに成功した場合の処理
          // バリデートに成功したフィールドと値の組を配列で取得する
            $formData = $val->validated();

            // 【パスワード再設定モードの場合】旧パスが不明なので、旧パスを確認せずにパスワードを上書きする
            if ($reset_user_id) {
              // ユーザーを取得
              $user = \Model\Users::find_by_pk($reset_user_id);

              if($user)
              {
                // Authの制約を回避するため、DBを直接書き換え
                // 新パスワードをハッシュ化
                $hashed_password = \Auth::instance()->hash_password($formData['password']);
                // 新パスワードに書き換え
                $user->password = $hashed_password;
                // 復元用に生成したハッシュと作成日時をクリア
                $profile_fields = @unserialize($user->profile_fields) ?: array();
                unset($profile_fields['lostpassword_hash']); //復元用のパスワード
                unset($profile_fields['lostpassword_created']); //復元用の日時
                $user->profile_fields = serialize($profile_fields);

                // DB更新
                $update = $user->save();

                if ($update)
                {
                  // 保存に成功したら強制ログインさせる
                  Auth::force_login($reset_user_id);
                  // 許可証を破棄
                  Session::delete('password_reset_user_id');
                }
              }

            }
            else
            {  // 通常モード
              $update = Auth::change_password($formData['old_password'],$formData['password']);
            }

          //データベースを更新
          if( $update ){
          // 更新できたら、セッションに値をいれ、メッセージを出す
            Session::set_flash('sucMsg','パスワードを変更しました！');
          // リダイレクト
            Response::redirect('members/mypage/index');
          }
          else
          {
            $error = $val->error();
            // セッションに値を入れ、メッセージを出す
            Session::set_flash('errMsg','登録できませんでした！');
          }

        }
        else
        {
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

      $this->template->content = View::forge('pages/userPassEdit');
      $this->template->btnContainer = View::set_global('btnContainer',View::forge('common/btnContainer'));
      // $this->template->userId = View::set_global('userId',$userId);
      // $this->template->nick = View::set_global('nick',$nick);
      // $this->template->pass = View::set_global('pass',$pass);
  }


}


 ?>
