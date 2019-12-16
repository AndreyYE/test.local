-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Дек 16 2019 г., 11:03
-- Версия сервера: 10.4.10-MariaDB
-- Версия PHP: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `test1`
--

-- --------------------------------------------------------

--
-- Структура таблицы `authors`
--

CREATE TABLE `authors` (
  `id` int(6) UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `authors`
--

INSERT INTO `authors` (`id`, `name`) VALUES
(17, 'Jon'),
(18, 'Shevchenko'),
(19, 'Ukrainian'),
(20, 'Franco'),
(21, 'Orwell'),
(22, 'author_13');

-- --------------------------------------------------------

--
-- Структура таблицы `author_books`
--

CREATE TABLE `author_books` (
  `author_id` int(6) UNSIGNED NOT NULL,
  `book_id` int(6) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `author_books`
--

INSERT INTO `author_books` (`author_id`, `book_id`) VALUES
(17, 38),
(18, 37),
(19, 37),
(20, 39),
(20, 40),
(20, 41),
(20, 42),
(20, 43),
(20, 44),
(20, 45),
(20, 46),
(21, 38);

-- --------------------------------------------------------

--
-- Структура таблицы `author_publishes`
--

CREATE TABLE `author_publishes` (
  `author_id` int(6) UNSIGNED NOT NULL,
  `publish_id` int(6) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `author_publishes`
--

INSERT INTO `author_publishes` (`author_id`, `publish_id`) VALUES
(17, 5),
(18, 4),
(19, 4),
(20, 3),
(20, 4),
(20, 5),
(20, 6),
(21, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `books`
--

CREATE TABLE `books` (
  `id` int(6) UNSIGNED NOT NULL,
  `publish_id` int(6) UNSIGNED DEFAULT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `publication_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `books`
--

INSERT INTO `books` (`id`, `publish_id`, `name`, `publication_date`) VALUES
(37, 4, 'Book Shevchenko Ukrainian', '2019-12-15 21:58:12'),
(38, 5, 'book Jon Orwell', '2019-12-15 21:58:18'),
(39, 3, 'book franko 1', '2019-12-15 21:59:52'),
(40, 4, 'book franko 2', '2019-12-15 21:59:52'),
(41, 5, 'book franko 3', '2019-12-15 21:59:52'),
(42, 6, 'book franko 4', '2019-12-15 21:59:52'),
(43, 3, 'book franko 5', '2019-12-15 21:59:52'),
(44, 4, 'book franko 6', '2019-12-15 21:59:52'),
(45, 5, 'book franko 7', '2019-12-15 21:59:52'),
(46, 6, 'book franko 8', '2019-12-15 21:59:52');

-- --------------------------------------------------------

--
-- Структура таблицы `publishes`
--

CREATE TABLE `publishes` (
  `id` int(6) UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `publishes`
--

INSERT INTO `publishes` (`id`, `name`, `reg_date`) VALUES
(3, 'publish1', '2019-12-15 21:54:36'),
(4, 'publish2', '2019-12-15 19:45:18'),
(5, 'publish3', '2019-12-15 21:54:22'),
(6, 'publish4', '2019-12-15 21:54:22');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(6) UNSIGNED NOT NULL,
  `email` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `token_access` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `time_expired_token` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `author_books`
--
ALTER TABLE `author_books`
  ADD PRIMARY KEY (`author_id`,`book_id`),
  ADD KEY `FK_book` (`book_id`);

--
-- Индексы таблицы `author_publishes`
--
ALTER TABLE `author_publishes`
  ADD PRIMARY KEY (`author_id`,`publish_id`),
  ADD KEY `publish` (`publish_id`);

--
-- Индексы таблицы `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `books_publishes_fk` (`publish_id`);

--
-- Индексы таблицы `publishes`
--
ALTER TABLE `publishes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `books`
--
ALTER TABLE `books`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT для таблицы `publishes`
--
ALTER TABLE `publishes`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `author_books`
--
ALTER TABLE `author_books`
  ADD CONSTRAINT `FK__author` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`),
  ADD CONSTRAINT `FK_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);

--
-- Ограничения внешнего ключа таблицы `author_publishes`
--
ALTER TABLE `author_publishes`
  ADD CONSTRAINT `FK__author_1` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`),
  ADD CONSTRAINT `publish` FOREIGN KEY (`publish_id`) REFERENCES `publishes` (`id`);

--
-- Ограничения внешнего ключа таблицы `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_publishes_fk` FOREIGN KEY (`publish_id`) REFERENCES `publishes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
