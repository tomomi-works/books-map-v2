<?php
//------------------
// 全ページの共通処理
//------------------
class Controller_App_Base extends Controller_Template{

    public function before()
    {
        // Controller_Templateの準備(この行は必須)
        parent::before();

        $loginuser = false;
        // ログイン済みかチェック
        $is_login = \Auth::check();
        // グループチェック
        $groups = \Auth::get_groups();
        $group = isset($groups[0][1]) ? $groups[0][1] : null;

        // ログイン済みフラグ
        $loginuser = ($is_login && $group == 1) ? true : false;
        // パスワード再設定用の許可証を持っているかチェック
        $is_password_reset_mode = \Session::get('password_reset_user_id');


        //ログインしていない場合の共通処理
        if (!$is_login) {

          //ログインフォームの生成
          $errors = array();
          $login_form = Fieldset::forge('loginform');

          // --- フォームの定義 ---
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


          //ログインフォームが投稿された場合
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
                    if (\Input::param('remember', false)){
                        // remember-me クッキーを作成
                        \Auth::remember_me();
                    }
                    else{
                        // 存在する場合、 remember-me クッキーを削除
                        \Auth::dont_remember_me();
                    }

                    // ログイン
                    //ユーザーが居た以前のページか、以前のページが検出できない場合はmembers/mypage/indexへ

                    \Session::set_flash('sucMsg','ログインしました');
                    \Response::redirect('members/mypage/index');
                }

            }else{
              // 失敗 エラーを配列形式で格納
              $errors = $login_form->error();
              Session::set_flash('errMsg','ログインに失敗しました');
              $login_form->repopulate();

            }

            // フォーム送信からの入力値をフィールドに設定する。
            $login_form->repopulate();
          }

          // 全Viewで使えるようにセット
          \View::set_global('login_form', $login_form->build(), false);
          \View::set_global('errors', $errors);
        }


        // 共通のテンプレート設定
        $this->template->head = \View::forge('template/head');
        $this->template->footer = \View::forge('template/footer');
        $this->template->header = \View::forge('template/header');

        // 全Viewで使えるようにグローバル変数としてセット
        \View::set_global('loginuser', $loginuser);
    }
}
