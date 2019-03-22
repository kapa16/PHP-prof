-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Мар 19 2019 г., 11:46
-- Версия сервера: 5.7.25-0ubuntu0.18.04.2
-- Версия PHP: 7.1.24-1+ubuntu16.04.1+deb.sury.org+1
CREATE DATABASE `geek_brains_shop`;
USE `geek_brains_shop`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `geek_brains_shop`
--

-- --------------------------------------------------------

--
-- Структура таблицы `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `size` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `images`
--

INSERT INTO `images` (`id`, `url`, `views`, `title`, `size`) VALUES
(1, 'img/1.jpg', 28, 'Картинка 1', 0),
(8, 'img/8.jpg', 5, 'Картинка 8', 0),
(2, 'img/2.jpg', 2, 'Картинка 2', 0),
(6, 'img/6.jpg', 1, 'Картинка 6', 0),
(3, 'img/3.jpg', 0, 'Картинка 3', 0),
(4, 'img/4.jpg', 0, 'Картинка 4', 0),
(5, 'img/5.jpg', 0, 'Картинка 5', 0),
(7, 'img/7.jpg', 0, 'Картинка 7', 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
