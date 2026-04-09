<?php
//------------------
// membersページの共通処理
//------------------
class Controller_Members_Base extends Controller_App_Base{

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

        // パスワード再設定用の許可証を持っているかチェック
        $is_password_reset_mode = \Session::get('password_reset_user_id');

        if($is_login && $group == 1){
            // ログイン済
            $loginuser = true;

        }elseif ($is_password_reset_mode) {
            // パスワード再設定モードの人の処理

            $loginuser = false;
            // 現在アクセスしているコントローラーとアクションをチェック
            $current_controller = \Request::active()->controller;
            $current_action = \Request::active()->action;

            // Controller_Members_Mypage　かつ、　userPassEdit以外なら追い出す。
            if ($current_controller !== 'Controller_Members_Mypage' || $current_action !== 'userPassEdit')
            {
                \Session::set_flash('errMsg', 'パスワードの変更を完了させてください。');
                \Response::redirect('members/mypage/userPassEdit');
            }

        }else{
          // 未ログインユーザーへの処理
          $loginuser = false;
          \Session::set_flash('errMsg', 'ログインしていません');
          // 子クラスで $redirect_to が指定されていればそこへ、なければデフォルトへ
          $dest = (isset($this->redirect_to)) ? $this->redirect_to : 'book/booklists';
          \Response::redirect($dest);
        }

    }
}
