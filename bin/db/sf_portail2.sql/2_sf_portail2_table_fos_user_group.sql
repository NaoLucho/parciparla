
--
-- Vider la table avant d'insérer `fos_user_group`
--

DELETE FROM `fos_user_group`;
ALTER TABLE `fos_user_group` AUTO_INCREMENT = 1;
--
-- Contenu de la table `fos_user_group`
--

INSERT INTO `fos_user_group` (`id`, `name`, `roles`) VALUES
(1, 'Pro', 'a:1:{i:0;s:8:"ROLE_PRO";}'),
(2, 'Users', 'a:1:{i:0;s:9:"ROLE_USER";}'),
(5, 'INTEGRATOR', 'a:1:{i:0;s:15:"ROLE_INTEGRATOR";}'),
(6, 'ADMIN_METIER', 'a:1:{i:0;s:17:"ROLE_ADMIN_METIER";}'),
(7, 'ADMIN_USER', 'a:1:{i:0;s:15:"ROLE_ADMIN_USER";}');
