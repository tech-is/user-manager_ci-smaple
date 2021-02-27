SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `user_manager`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ユーザID',
  `created_at` int(11) UNSIGNED NOT NULL COMMENT '作成日',
  `updated_at` int(11) UNSIGNED DEFAULT NULL COMMENT '更新日',
  `deleted_at` int(11) UNSIGNED DEFAULT NULL COMMENT '削除日',
  `email` varchar(100) DEFAULT NULL COMMENT 'メールアドレス',
  `password` varchar(255) DEFAULT NULL COMMENT 'パスワード',
  `name` varchar(50) DEFAULT NULL COMMENT '名前',
  `icon_url` varchar(255) DEFAULT NULL COMMENT 'プロフィールアイコンURL',
  `about` varchar(100) DEFAULT NULL COMMENT '自己紹介'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ユーザID';
