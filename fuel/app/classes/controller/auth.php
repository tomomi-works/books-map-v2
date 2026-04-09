<?php

//------------action
//logout
//------------------

class Controller_Auth extends Controller_Template{

  public function action_logout(){
    //ログアウト
    // remember-me クッキーを削除し、意図的にログアウト
    \Auth::dont_remember_me();

    // ログアウト
    \Auth::logout();

    // ログアウトの成功をユーザーに知らせる
    Session::set_flash('sucMsg', 'ログアウトしました');

    // book/booklistsに戻る
    \Response::redirect('book/booklists');
  }


}

 ?>
