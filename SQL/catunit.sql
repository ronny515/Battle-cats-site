SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";



--

-- --------------------------------------------------------

--
-- 資料表結構 `catunit`
--

CREATE TABLE `catunit` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'AUTO_INCREMENT',
  `cat_id` int(11) NOT NULL,
  `form` int(10) UNSIGNED NOT NULL,
  `name` char(50) CHARACTER SET utf8 NOT NULL,
  `type` smallint(6) NOT NULL,
  `pic` char(50) CHARACTER SET utf8 NOT NULL,
  `hp` int(11) NOT NULL,
  `atk` int(11) NOT NULL,
  `dps` int(11) NOT NULL,
  `atktype` tinyint(4) DEFAULT NULL,
  `kb` int(11) NOT NULL,
  `speed` int(11) NOT NULL,
  `arange` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `aspeed` int(11) NOT NULL,
  `aspeed2` int(11) NOT NULL,
  `pspeed` int(11) NOT NULL,
  `lv` int(11) NOT NULL,
  `lv2` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- 傾印資料表的資料 `catunit`
--

INSERT INTO `catunit` (`id`, `cat_id`, `form`, `name`, `type`, `pic`, `hp`, `atk`, `dps`, `atktype`, `kb`, `speed`, `arange`, `price`, `aspeed`, `aspeed2`, `pspeed`, `lv`, `lv2`) VALUES
(5, 2, 2, '牆貓', 1, '', 400, 2, 62, 1, 1, 8, 110, 150, 67, 8, 250, 0, 0),
(4, 2, 1, '坦克貓', 1, '', 400, 2, 62, 1, 1, 8, 110, 150, 67, 8, 250, 0, 0),
(3, 1, 3, '摩西根貓', 1, '', 200, 16, 448, 0, 3, 10, 140, 75, 37, 8, 160, 0, 0),
(2, 1, 2, '健美貓', 1, '', 100, 8, 224, 0, 3, 10, 140, 75, 37, 8, 160, 0, 0),
(1, 1, 1, '貓咪', 1, '', 100, 8, 224, 0, 3, 10, 140, 75, 37, 8, 160, 0, 0);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `catunit`
--
ALTER TABLE `catunit`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
