-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost:8889
-- 生成日時: 2026 年 4 月 10 日 04:53
-- サーバのバージョン： 8.0.44
-- PHP のバージョン: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `fuel_dev`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `books`
--

DROP TABLE IF EXISTS `books`;
CREATE TABLE IF NOT EXISTS `books` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int DEFAULT NULL,
  `cate_id` int DEFAULT NULL,
  `stat_id` int DEFAULT NULL,
  `price` int DEFAULT NULL,
  `img` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `short` mediumtext COLLATE utf8mb4_general_ci,
  `summary` mediumtext COLLATE utf8mb4_general_ci,
  `delete_flg` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `books`
--

INSERT INTO `books` (`id`, `title`, `user_id`, `cate_id`, `stat_id`, `price`, `img`, `short`, `summary`, `delete_flg`, `updated_at`, `created_at`) VALUES
(1, '自己という幻想', 1, 1, 1, 1800, 'ced4e1772740be0bbea3e90d4d3aa2a6.png', '「私」はどこに存在するのか？', '新明Pによる、仏教的・論理的な視点から「自分」という概念を解体する一冊。', 0, '2026-04-10 13:26:56', NULL),
(2, 'PHP 7.4 実用リファレンス', 1, 1, 1, 3200, 'e1ea71bde9ab31515b84f7e8747f672c.png', 'モダンPHPの基礎を固める', 'FuelPHPやLaravelの理解を深めるための技術書。現在進行形で学習中。', 0, '2026-04-10 13:26:43', NULL),
(3, 'システム思考の基礎', 1, 1, 1, 2500, 'b8502105be07d356bf7fd31dc0b09497.png', '複雑な問題を構造で捉える', '物事を単体ではなく「構造」で捉えるための論理的なトレーニング本。', 0, '2026-04-10 13:26:29', NULL),
(4, '仏教の論理と実践', 1, 1, 1, 2200, '912a93089d7caead9fe9422e91ee8a7c.png', 'システムとしての仏教', '佐々木閑先生の著作。論理的な枠組みとしての仏教を学ぶための積読本。', 0, '2026-04-10 13:26:16', NULL),
(5, 'SQL 魔法のレシピ', 1, 1, 1, 2800, 'ea6a27f13159e6f10d8a321e674bedc8.png', 'データ操作を自由自在に', '実務で役立つ複雑なクエリが満載。リファレンスとして活用。', 0, '2026-04-10 13:26:01', NULL),
(6, '深夜特急', 2, 3, 1, 800, 'c4e0bd112be386cdc55458174e0c3955.png', '旅に出たくなる不朽の名作', 'バックパッカーの聖典。testerさんが最も影響を受けた小説という設定。', 0, '2026-04-10 13:31:15', NULL),
(7, '極上パスタの秘密', 2, 5, 1, 1400, '005082123fa4f92338ee13d05ccb41c3.png', 'お家でプロの味を再現', '難しい工程なしで、驚くほど美味しいパスタが作れるレシピ集。', 0, '2026-04-10 13:29:40', NULL),
(8, 'VIVANT 下巻', 2, 3, 2, 900, '64271fe842ef639908d2eea71ab5453b.png', 'あの衝撃の物語を活字で', 'ドラマの興奮をそのままに。現在読書中。', 0, '2026-04-10 13:30:01', NULL),
(9, '🥞週末の簡単ホットケーキ', 2, 1, 1, 1100, '298887055f82635225f34c7b60718978.png', 'ふっくら焼くためのコツ', '誰でも失敗せずに分厚いホットケーキが焼けるようになる一冊。', 0, '2026-04-10 13:28:56', NULL),
(10, 'デザインの基本', 2, 1, 4, 2400, 'bcae81e462db8c1035941ea9b3a774e7.png', 'ノンデザイナーのためのガイド', 'いつか勉強しようと思っているデザインの基礎本。', 0, '2026-04-10 13:30:20', NULL),
(11, '星の王子さま', 3, 4, 1, 600, '330b5879c758b4a60f1107041e986bca.png', '大切なことは目に見えない', '不朽の名作。こういう純粋な本も読むという設定。', 0, '2026-04-10 13:35:13', NULL),
(12, 'ノンデザイナーズ・デザインブック', 3, 1, 2, 2300, 'f163ab3ec264a7b1c617befc3caad6fe.png', 'デザインの4原則を学ぶ', '現在読書中。エンジニアも知っておくべきデザインの基礎。', 0, '2026-04-10 13:34:49', NULL),
(13, 'SLAM DUNK 新装再編版', 3, 5, 1, 650, 'a7f0b81aa5220dda21356841b6589678.png', '諦めたらそこで試合終了ですよ', '名作コミック。熱い漫画も大好き。', 0, '2026-04-10 13:34:25', NULL),
(14, '確かな力が身につくJavaScript', 3, 1, 1, 2800, 'f99e34ff877b5c90439d113e24c705b0.png', 'フロントエンドもこなしたい', '実用的な技術書。PHPだけでなくJSも勉強中という設定。', 0, '2026-04-10 13:34:01', NULL),
(15, 'こころ', 3, 3, 1, 500, 'b7b45c02cf0e849aaf74422630d29b44.png', '夏目漱石の代表作', 'いつか読まなきゃと思っている名作文学。', 1, '2026-04-10 13:33:17', NULL),
(16, 'アジャイル開発の真髄', 1, 1, 1, 3000, 'deb86a342b99549819d4382fdc7cb442.png', 'チームで最高の成果を', 'masterさんの本棚に追加。開発手法への関心。', 0, '2026-04-10 13:25:47', NULL),
(17, 'はじめてのマインドフルネス', 1, 1, 1, 1600, 'f47e7b276ba86f504b872b4051d8f4ce.png', '心を整える技術', 'masterさんの自己研鑽。', 0, '2026-04-10 13:25:31', NULL),
(18, 'おいしいパンの作り方🍞', 2, 1, 4, 1800, 'f602776010dc6aed826790ef6b6f1ae8.png', '焼きたての香りに包まれて', 'testerさんの料理コレクション追加。', 0, '2026-04-10 13:28:23', NULL),
(19, '銀河鉄道の夜🚃', 2, 1, 1, 700, '7f90a5d1fa50db50f59fbf4d5381ea55.png', '本当の幸せを求めて', 'testerさんの文芸コレクション追加。', 0, '2026-04-10 13:27:58', NULL),
(20, 'なるほどデザイン', 3, 1, 2, 2200, '13f53a09986024d7e6a3b08e866adb71.png', '目で見て楽しむデザイン', 'juniorさんのデザイン本2冊目。', 0, '2026-04-10 13:33:02', NULL),
(21, 'プロジェクトヘイルメアリー上', 3, 3, 1, 1500, '87f57d7139e23c3bcad089409f011342.png', '宇宙に漂流した1人の人間が、かけがえのない存在と出会う話。', '中盤あたりから一気に面白い・・・！', 0, '2026-04-10 13:03:09', '2026-04-10 13:03:09'),
(22, '🚀プロジェクトヘイルメアリー　下巻', 3, 3, 1, 1500, 'a8ffd2c7e501ed978e29adda3e79b39f.png', '宇宙を漂う1人の人間が、かけがえのない存在と出会う🚀', '読むと勇気が出てくる本🚀', 0, '2026-04-10 13:24:51', NULL),
(23, 'こころ', 3, 3, 4, 500, 'b7b45c02cf0e849aaf74422630d29b44.png', '夏目漱石の代表作', 'いつか読まなきゃと思っている名作文学。', 0, '2026-04-10 13:33:22', '2026-04-10 13:33:22'),
(24, 'ヒュナム洞書店', 3, 1, 1, 800, '21c2e756a5c36c8cf37bb8106ff3c4cb.png', '書店を営む店主の話。', '１ページずつ大切に読みたい本。', 0, '2026-04-10 13:37:12', NULL),
(25, 'リーダブルコード', 3, 1, 2, 2600, 'no_image.png', 'より良いコードを書くために', '美しいコードを書くためのバイブル。読みやすさの重要性を再認識できる一冊。📖', 0, '2026-04-10 10:00:00', '2026-04-10 10:00:00'),
(26, '今日から始めるUI設計', 3, 7, 1, 2400, 'no_image.png', '使いやすさの本質を学ぶ', 'デザインの基礎が丁寧に解説されており、エンジニアにとっても非常に参考になる。', 0, '2026-04-09 15:00:00', '2026-04-09 15:00:00'),
(27, '君たちはどう生きるか', 3, 3, 1, 1300, 'no_image.png', '時代を超えて読み継がれる哲学', '漫画版で読みやすく、自分自身の生き方を深く考えさせられる名作。', 0, '2026-04-08 11:00:00', '2026-04-08 09:00:00'),
(28, '沈黙のWebマーケティング', 3, 2, 1, 2200, 'no_image.png', 'ストーリー形式で学ぶ基礎知識', '専門的な内容が漫画風のストーリーで展開されるため、一気に読み進められる。', 0, '2026-04-07 20:00:00', '2026-04-07 18:00:00'),
(29, 'ハイキュー!! 1巻', 3, 5, 1, 480, 'no_image.png', '頂の景色を目指す物語', '熱い展開が魅力のスポーツ漫画。読むとモチベーションが上がる。🔥', 0, '2026-04-06 21:00:00', '2026-04-06 21:00:00'),
(30, 'Webを支える技術', 3, 1, 4, 3000, 'no_image.png', 'HTTPとRESTの基本を理解する', 'Webの仕組みを根本から理解するための必読書。じっくり時間をかけて読みたい。', 0, '2026-04-05 12:00:00', '2026-04-05 12:00:00'),
(31, '独学コンピューターサイエンティスト', 3, 1, 2, 2800, 'no_image.png', 'プロの門を叩くためのガイド', '独学で技術を身につけるための道筋が示されている、心強いリファレンス。', 0, '2026-04-04 14:00:00', '2026-04-04 14:00:00'),
(32, 'こんまり流 片づけの魔法', 3, 2, 1, 1200, 'no_image.png', 'ときめきで選ぶ整理術', '整理整頓の習慣が身につく一冊。身の回りが整うと作業効率も上がる。✨', 0, '2026-04-03 12:00:00', '2026-04-03 10:00:00'),
(33, 'ドラえもん 1巻', 3, 5, 1, 500, 'no_image.png', '未来からきたロボットの物語', '時代が変わっても色褪せない、想像力を豊かにしてくれる癒やしの漫画。', 0, '2026-04-10 13:43:57', NULL),
(34, 'さよならの言い方なんて知らない', 3, 3, 1, 700, 'no_image.png', '心に刺さる青春ミステリー', '独特の世界観と繊細な心理描写が魅力的なシリーズ。大切に読みたい。', 1, '2026-04-10 13:43:19', NULL),
(35, 'さよならの言い方なんて知らない', 3, 3, 2, 700, '9637cdd5ba62950ae7bcb9f320a6e8d7.png', '心に刺さる青春ミステリー', '独特の世界観と繊細な心理描写が魅力的なシリーズ。大切に読みたい。', 0, '2026-04-10 13:43:28', '2026-04-10 13:43:28');

-- --------------------------------------------------------

--
-- テーブルの構造 `book_status`
--

DROP TABLE IF EXISTS `book_status`;
CREATE TABLE IF NOT EXISTS `book_status` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `book_status`
--

INSERT INTO `book_status` (`id`, `name`) VALUES
(1, '読了'),
(2, '読書中'),
(3, '未読'),
(4, '積読');

-- --------------------------------------------------------

--
-- テーブルの構造 `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, '専門・技術'),
(2, 'ビジネス・実用'),
(3, '文芸・小説'),
(4, '児童書・絵本'),
(5, 'コミック・雑誌'),
(6, '哲学・思想'),
(7, 'アート・デザイン'),
(8, 'その他');

-- --------------------------------------------------------

--
-- テーブルの構造 `favorite`
--

DROP TABLE IF EXISTS `favorite`;
CREATE TABLE IF NOT EXISTS `favorite` (
  `id` int NOT NULL AUTO_INCREMENT,
  `book_id` int NOT NULL,
  `user_id` int NOT NULL,
  `delete_flg` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `favorite`
--

INSERT INTO `favorite` (`id`, `book_id`, `user_id`, `delete_flg`, `updated_at`, `created_at`) VALUES
(4, 2, 1, 0, NULL, NULL),
(5, 22, 1, 0, NULL, NULL),
(6, 6, 2, 0, NULL, NULL),
(7, 24, 3, 0, NULL, NULL),
(8, 19, 3, 0, NULL, NULL),
(9, 18, 3, 0, NULL, NULL),
(10, 21, 3, 0, NULL, NULL),
(11, 22, 3, 0, NULL, NULL),
(12, 34, 3, 0, NULL, NULL),
(13, 32, 3, 0, NULL, NULL),
(14, 13, 3, 0, NULL, NULL),
(15, 11, 3, 0, NULL, NULL),
(16, 35, 3, 0, NULL, NULL),
(17, 33, 3, 0, NULL, NULL),
(18, 14, 3, 0, NULL, NULL),
(19, 6, 3, 0, NULL, NULL),
(20, 20, 3, 0, NULL, NULL),
(21, 23, 3, 0, NULL, NULL),
(22, 7, 3, 0, NULL, NULL),
(23, 9, 3, 0, NULL, NULL),
(24, 1, 3, 0, NULL, NULL),
(25, 2, 3, 0, NULL, NULL),
(26, 3, 3, 0, NULL, NULL),
(27, 4, 3, 0, NULL, NULL),
(28, 5, 3, 0, NULL, NULL),
(29, 17, 3, 0, NULL, NULL),
(30, 16, 3, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- テーブルの構造 `interest`
--

DROP TABLE IF EXISTS `interest`;
CREATE TABLE IF NOT EXISTS `interest` (
  `id` int NOT NULL AUTO_INCREMENT,
  `book_id` int NOT NULL,
  `user_id` int NOT NULL,
  `delete_flg` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `interest`
--

INSERT INTO `interest` (`id`, `book_id`, `user_id`, `delete_flg`, `updated_at`, `created_at`) VALUES
(2, 8, 1, 0, NULL, NULL),
(3, 15, 1, 0, NULL, NULL),
(4, 4, 2, 0, NULL, NULL),
(5, 22, 2, 0, NULL, NULL),
(6, 11, 2, 0, NULL, NULL),
(7, 14, 3, 0, NULL, NULL),
(8, 12, 3, 0, NULL, NULL),
(9, 8, 3, 0, NULL, NULL),
(10, 19, 3, 0, NULL, NULL),
(11, 18, 3, 0, NULL, NULL),
(12, 1, 3, 0, NULL, NULL),
(13, 17, 3, 0, NULL, NULL),
(14, 31, 3, 0, NULL, NULL),
(15, 2, 3, 0, NULL, NULL),
(16, 20, 3, 0, NULL, NULL),
(17, 35, 3, 0, NULL, NULL),
(18, 33, 3, 0, NULL, NULL),
(19, 6, 3, 0, NULL, NULL),
(20, 23, 3, 0, NULL, NULL),
(21, 7, 3, 0, NULL, NULL),
(22, 9, 3, 0, NULL, NULL),
(23, 5, 3, 0, NULL, NULL),
(24, 22, 3, 0, NULL, NULL),
(25, 16, 3, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` int DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nickname` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_login` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `login_hash` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `profile_fields` mediumtext COLLATE utf8mb4_general_ci,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `group`, `email`, `nickname`, `last_login`, `login_hash`, `profile_fields`, `created_at`, `updated_at`) VALUES
(1, 'master', 'hCx4E/tthvdMMPFYoL/EKYtlbv9Ag1R3Che/1sDkaA0=', 1, 'master@example.com', NULL, '1775795115', 'e6de7b9de1b9866219193531e912f3ef4b03d1d4', 'a:0:{}', 1775790277, NULL),
(2, 'tester', 'wbZuyaq239yn9qyPRPeWLDX8aFl4KJJ7O2eQVLqJjJI=', 1, 'tester@example.com', NULL, '1775795256', 'ffe088b9cb45bc93fdfd2671b2cd6c8ba4b39986', 'a:0:{}', 1775790894, NULL),
(3, 'guest1', 'wEGnuGyuhY2dLobocq3Usv5ITl0MK/iNUbhiv2Rc9x4=', 1, 'guest1@guest1.com', NULL, '1775795542', '4545f956d7827468429dd7c9f9f0df82434d0c9e', 'a:0:{}', 1775792481, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
