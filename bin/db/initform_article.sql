--
-- execute this in sql after create db schema
--

INSERT INTO `f_form` (`id`, `name`, `title`, `entity`) VALUES
(1, 'article', 'Formulaire Article', 'SiteBundle\\Entity\\Article'),
(2, 'comment', 'Commentaires', 'SiteBundle\\Entity\\Comment');



INSERT INTO `f_fieldtype` (`id`, `name`, `base_type`, `component`) VALUES
(1, 'Text', 'string', 'text'),
(2, 'Boolean', 'boolean', 'boolean'),
(3, 'Select simple', 'object', 'select_simple'),
(4, 'Select [multiple]', 'object[]', 'select_multiple'),
(5, 'RadioList', 'object', 'radio'),
(6, 'CheckList', 'object[]', 'checklist'),
(7, 'Textarea', 'string', 'textarea'),
(8, 'Lien', 'url', 'link'),
(9, 'Image', 'image', 'image'),
(11, 'Nombre', 'integer', 'number'),
(12, 'Date', 'datetime', 'datetime'),
(13, 'Program_Objective', 'Entity[]', 'TableObjectivesType'),
(14, 'Inactif', 'string', 'inactif'),
(15, 'Program_nbByYear', 'Entity[]', 'TableNbByYearType'),
(16, 'RadioList_classic', 'object', 'radio'),
(17, 'Email', 'string', 'email'),
(18, 'Year', 'integer', 'year'),
(19, 'LocalisationType', 'Entity[]', 'LocalisationType'),
(20, 'ImageArray', 'Entity[]', 'ImageArray'),
(21, 'FileArray', 'Entity[]', 'FileArray'),
(22, 'Editeur de texte', 'string', 'TextEditor'),
(23, 'Rights', 'Entity[]', 'Rights'),
(24, 'FileUpload', 'file', 'file');


INSERT INTO `f_field` (`id`, `fieldtype_id`, `property`, `listname`, `label`, `placeholder`, `mandatory`, `info`, `limitnb`) VALUES
(1, 1, 'title', NULL, 'Titre', NULL, 1, NULL, NULL),
(2, 3, 'author', 'ApplicationSonataUserBundle:User.username#enabled=true', 'Auteur', NULL, 0, NULL, NULL),
(3, 9, 'photoFile', NULL, 'Illustration', NULL, 0, NULL, NULL),
(4, 22, 'content', NULL, 'Contenu', NULL, 0, NULL, NULL),
(5, 5, 'typeArticle', 'Liste=types_articles', 'Catégorie', NULL, 1, NULL, NULL),
(6, 3, 'article', 'SiteBundle\\Entity\\Article.title', 'Article', NULL, 1, NULL, NULL),
(7, 12, 'publishedAt', NULL, 'Date de publication', NULL, 0, NULL, NULL),
(8, 2, 'isActive', NULL, 'Visible', NULL, 0, NULL, NULL),
(9, 7, 'content', NULL, 'Commentaire', NULL, 1, NULL, 1000);

INSERT INTO `f_formfield` (`id`, `form_id`, `field_id`, `mandatory`, `position`, `info`) VALUES
(1, 1, 1, 1, 1, NULL),
(2, 1, 3, 0, 2, NULL),
(3, 1, 4, 1, 3, NULL),
(4, 1, 2, 0, 4, NULL),
(5, 1, 5, 1, 5, NULL),
(6, 2, 1, 0, 2, NULL),
(7, 2, 2, 0, 3, NULL),
(9, 2, 9, 0, 4, NULL),
(10, 2, 6, 0, 1, NULL),
(11, 2, 7, 0, 5, NULL),
(12, 2, 8, 0, 6, NULL);