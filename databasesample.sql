-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost:3306
-- 生成日時: 2021 年 3 月 20 日 02:42
-- サーバのバージョン： 5.7.32
-- PHP のバージョン: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- データベース: `yukigassen_APP`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `gameResults`
--

CREATE TABLE `gameResults` (
  `id` int(11) NOT NULL,
  `tournamentName` text,
  `myTeamName` text,
  `opponentTeamName` text,
  `firstSetMyCount` int(11) NOT NULL,
  `firstSetOpponentCount` int(11) NOT NULL,
  `secondSetMyCount` int(11) NOT NULL,
  `secondSetOpponentCount` int(11) NOT NULL,
  `thirdSetMyCount` int(11) DEFAULT NULL,
  `thirdSetOpponentCount` int(11) DEFAULT NULL,
  `victoryThrowMyCount` int(11) DEFAULT NULL,
  `victoryThrowOpponentCount` int(11) DEFAULT NULL,
  `totalSetMyCount` int(11) NOT NULL,
  `totalSetOpponentCount` int(11) NOT NULL,
  `winOrLose` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `gameResults`
--

INSERT INTO `gameResults` (`id`, `tournamentName`, `myTeamName`, `opponentTeamName`, `firstSetMyCount`, `firstSetOpponentCount`, `secondSetMyCount`, `secondSetOpponentCount`, `thirdSetMyCount`, `thirdSetOpponentCount`, `victoryThrowMyCount`, `victoryThrowOpponentCount`, `totalSetMyCount`, `totalSetOpponentCount`, `winOrLose`) VALUES
(46, '練習試合', '早大茶道部', '東儀', 6, 7, 7, 6, 6, 3, 0, 0, 19, 16, 1),
(47, '練習試合', '早大茶道部', '東儀', 6, 7, 7, 6, 6, 3, 0, 0, 19, 16, 1),
(48, '昭和新山国際雪合戦大会', 'でぃくさんず神手', 'ASSC', 6, 7, 7, 5, 6, 7, 0, 0, 19, 19, 1),
(49, '東海雪合戦大会', 'OZ', 'Big Wave', 7, 7, 7, 7, 7, 1, 0, 0, 21, 15, 1),
(50, '東海雪合戦大会', '岐阜KCY', '北軽ピーチ', 5, 7, 5, 7, 0, 0, 0, 0, 10, 14, 1),
(51, '日本選手権大会', '雪村時代', 'ZERO', 7, 7, 7, 7, 7, 6, 0, 0, 21, 20, 1),
(52, '練習試合', '早大茶道部', '早稲田雪合戦の会', 6, 6, 7, 6, 7, 6, 0, 0, 20, 18, 1);

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `gameResults`
--
ALTER TABLE `gameResults`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `gameResults`
--
ALTER TABLE `gameResults`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;