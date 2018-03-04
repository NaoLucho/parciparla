
--
-- Vider la table avant d'insérer `fos_user_user_group`
--
DELETE FROM `fos_user_user_group`;
ALTER TABLE `fos_user_user_group` AUTO_INCREMENT = 1;
--
-- Contenu de la table `fos_user_user_group`
--

INSERT INTO `fos_user_user_group` (`user_id`, `group_id`) VALUES
(2, 1),
(4, 5),
(5, 6),
(6, 7);
