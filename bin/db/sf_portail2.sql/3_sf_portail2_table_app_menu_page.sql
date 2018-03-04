
--
-- Vider la table avant d'insérer `app_menu_page`
--

-- TRUNCATE TABLE `app_menu_page`;
--
-- Contenu de la table `app_menu_page`
--

INSERT INTO `app_menu_page` (`id`, `page_id`, `menu_id`, `position`) VALUES
(2, 1, 1, '1'),
(7, 1, 2, '1'),
(8, 3, 2, '2'),
(9, 2, 2, '3'),
(16, 8, 3, '1'),
(17, 6, 3, '2'),
(18, 9, 3, '3'),
(19, 7, 3, '4'),
(20, 5, 3, '5'),
(21, 10, 1, '1.2'),
(22, 11, 1, '3'),
(23, 12, 1, '1.2.2'),
(24, 4, 2, '4'),
(26, 13, 1, '2');
