<?php
//------------action
// post_fav
// post_favnon
// post_interest
// post_interestnon
//------------------


class Controller_Regist extends Controller_Rest{

    public function post_fav(){

      // ログインしていない場合の処理はさせない
      if (!\Auth::check()) {
        return;
      }

      //getパラメーターから、
      //books_idを取得
      $books_id = $_POST['bookid'];
      //ユーザがログイン中なら array(driver_id, user_id) 形式の配列を、そうでなければ false を返す。
      $u_id = Auth::instance()->get_user_id();
      //ユーザーIDを格納
      $u_id = $u_id[1];

        $info = array();
        $info['book_id'] = (int)$books_id;
        $info['user_id'] = $u_id;
        $info['delete_flg'] = 0;
        $info['updated_at'] = null;
        $info['created_at'] = null;

        $fav = \Model\Favorite::forge();
        $fav->set($info);

        //お気に入り登録済みでない場合は登録
        $exist = \Model\Favorite::find_by(
                array(
                  'book_id' => (int)$books_id,
                  'user_id' => $u_id,
              ), null, null, 1);
        // $exist->find_by_pk($exist['id']);
        if(!empty($exist)){
          Session::set_flash('sucMsg', 'すでに登録されています');
        }
        else{
          //お気に入り登録
          $fav->save();
        }


    }

    public function post_favnon(){

      // ログインしていない場合の処理はさせない
      if (!\Auth::check()) {
        return;
      }

      //getパラメーターから、books_idを取得
      $books_id = $_POST['bookid'];
      //ユーザがログイン中なら array(driver_id, user_id) 形式の配列を、そうでなければ false を返す。
      $u_id = Auth::instance()->get_user_id();
      //ユーザーIDを格納
      $u_id = $u_id[1];

      $fav = \Model\Favorite::forge();
      //userIDとbooks_idが合致するモノのfavoriteIDを取得
      $fav = \Model\Favorite::find_one_by(
              array(
                'book_id' => (int)$books_id,
                'user_id' => $u_id,
            ), null, null, 1);
      //favoriteのidを取得
      //$fav['id'];
      $fav->find_by_pk($fav['id']);
      if (!empty($fav))
      {
          //お気に入りを削除
          $fav->delete();
      }

    }


    ////////////////
    ///**post_interest
    //**post_interestnon
    ////////////////
    public function post_interest(){

      // ログインしていない場合の処理はさせない
      if (!\Auth::check()) {
        return;
      }

      //getパラメーターから、
      //books_idを取得
      $books_id = $_POST['bookid'];
      //ユーザがログイン中なら array(driver_id, user_id) 形式の配列を、そうでなければ false を返す。
      $u_id = Auth::instance()->get_user_id();
      //ユーザーIDを格納
      $u_id = $u_id[1];

        $info = array();
        $info['book_id'] = (int)$books_id;
        $info['user_id'] = $u_id;
        $info['delete_flg'] = 0;
        $info['updated_at'] = null;
        $info['created_at'] = null;

        $interest = \Model\Interest::forge();
        $interest->set($info);

        //お気に入り登録済みでない場合は登録
        $exist = \Model\Interest::find_by(
                array(
                  'book_id' => (int)$books_id,
                  'user_id' => $u_id,
              ), null, null, 1);
        // $exist->find_by_pk($exist['id']);
        if(!empty($exist)){
          Session::set_flash('sucMsg', 'すでに登録されています');
        }
        else{
          //お気に入り登録
          $interest->save();
        }


    }

    public function post_interestnon(){

      // ログインしていない場合の処理はさせない
      if (!\Auth::check()) {
        return;
      }

      //getパラメーターから、books_idを取得
      $books_id = $_POST['bookid'];
      //ユーザがログイン中なら array(driver_id, user_id) 形式の配列を、そうでなければ false を返す。
      $u_id = Auth::instance()->get_user_id();
      //ユーザーIDを格納
      $u_id = $u_id[1];

      $interest = \Model\Interest::forge();
      //userIDとbooks_idが合致するモノのfavoriteIDを取得
      $interest = \Model\Interest::find_one_by(
              array(
                'book_id' => (int)$books_id,
                'user_id' => $u_id,
            ), null, null, 1);
      //favoriteのidを取得
      //$fav['id'];
      $interest->find_by_pk($interest['id']);
      if (!empty($interest))
      {
          //お気に入りを削除
          $interest->delete();
      }

    }





}

 ?>
