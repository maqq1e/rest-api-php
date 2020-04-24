-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 24 2020 г., 07:17
-- Версия сервера: 10.3.13-MariaDB-log
-- Версия PHP: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `api_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `email`
--

CREATE TABLE `email` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `is_main` int(1) NOT NULL DEFAULT 0,
  `created` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `email`
--

INSERT INTO `email` (`id`, `user_id`, `email`, `is_main`, `created`) VALUES
(1, 1, 'test@test.ru', 0, '2020-04-23'),
(3, 4, 'yandex@yandex.ru', 1, '2020-04-24'),
(4, 1, 'ramb@ramb.ramb', 0, '2020-04-24'),
(5, 1, '123@123.com', 1, '2020-04-24');

-- --------------------------------------------------------

--
-- Структура таблицы `tel`
--

CREATE TABLE `tel` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `number` varchar(18) NOT NULL,
  `type` int(1) NOT NULL COMMENT '0 - мобильный, 1 - рабочий, 2 - домашний',
  `is_main` int(1) NOT NULL DEFAULT 0,
  `created` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tel`
--

INSERT INTO `tel` (`id`, `user_id`, `number`, `type`, `is_main`, `created`) VALUES
(1, 1, '88005553535', 0, 0, '2020-04-22'),
(2, 1, '003', 1, 1, '2020-04-22');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `middle` varchar(50) NOT NULL COMMENT 'Отчество',
  `created` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `middle`, `created`) VALUES
(1, 'Тестовое имя', 'Тестовая фамилия', 'Тестовое отчество', '2020-04-22'),
(4, 'Дмитрий', 'Постречев', 'Владимирович', '2020-04-22');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `email`
--
ALTER TABLE `email`
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `tel`
--
ALTER TABLE `tel`
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `email`
--
ALTER TABLE `email`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `tel`
--
ALTER TABLE `tel`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
