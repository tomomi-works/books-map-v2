<?php
//------------------
// membersページの共通処理
//------------------
class Controller_Members_Base extends Controller_Template{
  
    public function before()
    {
        // Controller_Templateの準備(この行は必須)
        parent::before();

        // グループチェック
        $groups = \Auth::get_groups();
        $group = isset($groups[0][1]) ? $groups[0][1] : null;

        // ログイン認証
        if (\Auth::check() && $group == 1) {
            // ログイン済
            $loginuser = true;
        } else {
            // 未ログイン
            $loginuser = false;
            \Session::set_flash('errMsg', 'ログインしていません');

            // 子クラスで $redirect_to が指定されていればそこへ、なければデフォルトへ
            $dest = (isset($this->redirect_to)) ? $this->redirect_to : 'book/booklists';
            \Response::redirect($dest);
        }

        // 2. 共通のテンプレート設定
        $this->template->head = \View::forge('template/head');
        $this->template->footer = \View::forge('template/footer');
        $this->template->header = \View::forge('template/header');

        // 全Viewで使えるようにグローバル変数としてセット
        \View::set_global('loginuser', $loginuser);
    }
}
