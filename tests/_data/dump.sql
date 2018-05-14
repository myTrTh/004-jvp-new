-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Май 05 2018 г., 06:53
-- Версия сервера: 10.1.29-MariaDB-6
-- Версия PHP: 7.1.15-1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `testmyframework`
--

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE `news` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `article` text COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`id`, `title`, `article`, `user_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(2, 'Тест для редактирования', 'Статья для редактирования', 1, NULL, NULL, '2018-05-01 20:08:28');

-- --------------------------------------------------------

--
-- Структура таблицы `reset_passwords`
--

CREATE TABLE `reset_passwords` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `confirmation_datetime` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `reset_passwords`
--

INSERT INTO `reset_passwords` (`id`, `user_id`, `status`, `confirmation_token`, `confirmation_datetime`, `deleted_at`, `created_at`, `updated_at`) VALUES
(9, '1', 1, '$2y$10$/vXayAxtP/Vz.TiLb4zxmOikThw6/CEh6NeOZI7OdepJzyYJINVF.', '2018-04-11 00:00:00', NULL, NULL, NULL),
(10, '1', 0, '$2y$10$/vXayAxtP/Vz.TiLb4zxmOikThw6/CEh6NeOZI7OdepJzyYJINVa', '2018-04-11 00:00:00', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `role` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id`, `role`, `created_at`, `updated_at`) VALUES
(1, 'ROLE_USER', NULL, NULL),
(2, 'ROLE_ADMIN', NULL, '2018-05-01 20:08:29'),
(3, 'ROLE_SUPER_ADMIN', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `role_user`
--

CREATE TABLE `role_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `role_user`
--

INSERT INTO `role_user` (`id`, `role_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, NULL, NULL),
(3, 1, 3, NULL, NULL),
(12, 1, 4, NULL, NULL),
(13, 1, 5, NULL, NULL),
(14, 2, 5, NULL, NULL),
(15, 1, 6, NULL, NULL),
(16, 2, 6, NULL, NULL),
(17, 3, 6, NULL, NULL),
(29, 1, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `confirmation_datetime` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `image`, `is_active`, `confirmation_token`, `confirmation_datetime`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'john_smith', 'john_smith@gmail.com', '$2y$10$KiJzhw1XMIqnE/IO2jqtPujZnYVMO6178eshCM8Var6P84RqWj0iS', NULL, 1, NULL, NULL, NULL, NULL, '2018-05-01 20:08:31'),
(2, 'axl_rose', 'axl_rose@gmail.com', '$2y$10$o9dWjq4a5/mRAuTUBCQexuV.GM/zRoaTEqaIJnkiKIxWUewB3zkjS', NULL, 1, NULL, NULL, NULL, NULL, NULL),
(3, 'jack_daniels', 'jack_daniels@gmail.com', '$2y$10$o9dWjq4a5/mRAuTUBCQexuV.GM/zRoaTEqaIJnkiKIxWUewB3zkjS', NULL, 1, NULL, NULL, NULL, NULL, NULL),
(4, 'user_user', 'user_user@gmail.com', '$2y$10$o9dWjq4a5/mRAuTUBCQexuV.GM/zRoaTEqaIJnkiKIxWUewB3zkjS', NULL, 1, NULL, NULL, NULL, NULL, NULL),
(5, 'user_admin', 'user_admin@gmail.com', '$2y$10$o9dWjq4a5/mRAuTUBCQexuV.GM/zRoaTEqaIJnkiKIxWUewB3zkjS', NULL, 1, NULL, NULL, NULL, NULL, NULL),
(6, 'user_super_admin', 'user_super_admin@gmail.com', '$2y$10$o9dWjq4a5/mRAuTUBCQexuV.GM/zRoaTEqaIJnkiKIxWUewB3zkjS', NULL, 1, NULL, NULL, NULL, NULL, NULL),
(7, 'user_token1', 'user_token1@gmail.com', '$2y$10$o9dWjq4a5/mRAuTUBCQexuV.GM/zRoaTEqaIJnkiKIxWUewB3zkjS', NULL, 0, 'token_old', '2018-04-01 00:00:00', NULL, NULL, NULL),
(8, 'user_token2', 'user_token2@gmail.com', '$2y$10$o9dWjq4a5/mRAuTUBCQexuV.GM/zRoaTEqaIJnkiKIxWUewB3zkjS', NULL, 1, 'token_already', '2018-04-10 00:00:00', NULL, NULL, NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `reset_passwords`
--
ALTER TABLE `reset_passwords`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `news`
--
ALTER TABLE `news`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `reset_passwords`
--
ALTER TABLE `reset_passwords`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
