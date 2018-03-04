
--
-- Vider la table avant d'insérer `app_page_content`
--

-- TRUNCATE TABLE `app_page_content`;
--
-- Contenu de la table `app_page_content`
--

INSERT INTO `app_page_content` (`id`, `page_id`, `content_id`, `position`) VALUES
(5, 2, 4, 'MainMenu'),
(6, 2, 3, 'Header_Logo'),
(7, 2, 2, 'Content_1'),
(8, 2, 5, 'Content_2'),
(9, 2, 7, 'Footer_Left.2'),
(10, 3, 9, 'Template'),
(11, 1, 14, 'Content_5'),
(12, 4, 11, 'Header_2'),
(13, 4, 12, 'Header_1'),
(16, 3, 4, 'Menu'),
(18, 10, 17, 'Content_1'),
(19, 9, 18, 'Content_1'),
(20, 11, 19, 'Content_1'),
(21, 4, 20, 'Footer_2 col-md-6'),
(22, 12, 21, 'Content_1'),
(23, 4, 7, 'Footer_3'),
(24, 5, 22, 'Content_1'),
(25, 7, 23, 'Content_1'),
(26, 13, 24, 'Content'),
(27, 1, 25, 'Content_2'),
(28, 14, 26, 'Content');
