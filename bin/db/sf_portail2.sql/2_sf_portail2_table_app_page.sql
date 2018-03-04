
--
-- Vider la table avant d'insérer `app_page`
--

-- TRUNCATE TABLE `app_page`;
--
-- Contenu de la table `app_page`
--

INSERT INTO `app_page` (`id`, `name`, `slug`, `class`, `seotitle`, `seodesc`, `seokeywords`, `locked`) VALUES
(1, 'Bienvenue', 'home', 'common/commonStyle.css', 'Accueil', 'desc', 'key, eideod, deion, deee', 0),
(2, 'Exemple header footer spe', 'exemple1', NULL, NULL, NULL, NULL, 0),
(3, 'Exemple autre template', 'Exemple2', NULL, NULL, NULL, NULL, 0),
(4, 'default', 'default', NULL, 'Portail des Sciences Participatives', 'Portail porté par le MNHN, financé par la FNH. Référence les sites nationaux de France autour des sciences participatives', 'Sciences Participatives, MNHN, FNH, Portail, Programmes, Observation, Obsevatoire', 1),
(5, 'Mentions légales', 'Mentions_legales', NULL, NULL, NULL, NULL, 0),
(6, 'FAQ', 'FAQ', NULL, NULL, NULL, NULL, 0),
(7, 'Presse', 'presse', NULL, NULL, NULL, NULL, 0),
(8, 'Qui sommes-nous?', 'qui_sommes_nous', NULL, NULL, NULL, NULL, 0),
(9, 'Charte du portail', 'Charte_du_portail', NULL, NULL, NULL, NULL, 0),
(10, 'Déclarer ma structure', 'add_structure', NULL, NULL, NULL, NULL, 0),
(11, 'Annuaire des structures', 'annuaire_structure', NULL, NULL, NULL, NULL, 0),
(12, 'Examples for dev', 'examples', NULL, NULL, NULL, NULL, 1),
(13, 'Sciences Participatives', 'Sciences_Participatives', NULL, NULL, NULL, NULL, 0),
(14, 'Fiche Structure', 'fiche_structure', NULL, NULL, NULL, NULL, 0);
