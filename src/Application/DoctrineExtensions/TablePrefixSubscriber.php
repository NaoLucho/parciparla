<?php

namespace Application\DoctrineExtensions;

use \Doctrine\ORM\Event\LoadClassMetadataEventArgs;

/**
 * Description of TablePrefixSubscriber
 *
 * @author avanzu
 */
class TablePrefixSubscriber implements \Doctrine\Common\EventSubscriber
{

    protected $prefix = '';

    public function __construct($prefix = '')
    {
        $this->prefix = (string) $prefix;
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();

        if (strlen($this->prefix)) {
            if (0 !== strpos($classMetadata->getTableName(), $this->prefix)) {
                $classMetadata->setTableName($this->prefix . $classMetadata->getTableName());
            }
        }
        foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
            if ($mapping['type'] == \Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_MANY) {
                if (!isset($classMetadata->associationMappings[$fieldName]['joinTable'])) {
                    continue;
                }
                if ($classMetadata->associationMappings[$fieldName]['joinTable'] != []) {
                    $mappedTableName = $classMetadata->associationMappings[$fieldName]['joinTable']['name'];
                    if (0 !== strpos($mappedTableName, $this->prefix)) {
                        $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix . $mappedTableName;
                    }
                }
            }
        }
    }
    public function getSubscribedEvents()
    {
        return array('loadClassMetadata');
    }
}


// SELECT  CONCAT(TABLE_NAME,'') FROM information_schema.tables
// WHERE TABLE_NAME like 'at_%';

// INSERT INTO  at_fos_user_group SELECT * FROM  fos_user_group;
// INSERT INTO  at_fos_user_user SELECT * FROM  fos_user_user;
// INSERT INTO  at_app_menu SELECT * FROM  app_menu;
// INSERT INTO  at_app_page SELECT * FROM  app_page;
// INSERT INTO  at_app_menu_page SELECT * FROM  app_menu_page;
// INSERT INTO  at_app_content SELECT * FROM  app_content;
// INSERT INTO  at_app_page_content SELECT * FROM  app_page_content;
// INSERT INTO  at_app_pages_rights SELECT * FROM  app_pages_rights;
// INSERT INTO  at_app_contents_rights SELECT * FROM  app_contents_rights;
// INSERT INTO  at_g_list SELECT * FROM  g_list;
// INSERT INTO  at_g_listitem SELECT * FROM  g_listitem;
// INSERT INTO  at_article SELECT * FROM  article;
// INSERT INTO  at_carousel SELECT * FROM  carousel;
// INSERT INTO  at_comment SELECT * FROM  comment;
// INSERT INTO  at_db_log SELECT * FROM  db_log;
// INSERT INTO  at_db_log_correspondence SELECT * FROM  db_log_correspondence;
// INSERT INTO  at_f_fieldtype SELECT * FROM  f_fieldtype;
// INSERT INTO  at_f_field SELECT * FROM  f_field;
// INSERT INTO  at_f_form SELECT * FROM  f_form;
// INSERT INTO  at_f_formfield SELECT * FROM  f_formfield;
// INSERT INTO  at_f_formfieldrights SELECT * FROM  f_formfieldrights;
// INSERT INTO  at_fichier_pdf SELECT * FROM  fichier_pdf;
// INSERT INTO  at_geom SELECT * FROM  geom;
// INSERT INTO  at_lien SELECT * FROM  lien;
// INSERT INTO  at_media__gallery SELECT * FROM  media__gallery;
// INSERT INTO  at_media__media SELECT * FROM  media__media;
// INSERT INTO  at_media__gallery_media SELECT * FROM  media__gallery_media;


// DROP TABLE media__gallery_media;
// DROP TABLE media__media;
// DROP TABLE media__gallery;
// DROP TABLE lien;
// DROP TABLE geom;
// DROP TABLE fichier_pdf;
// DROP TABLE f_formfieldrights;
// DROP TABLE f_formfield;
// DROP TABLE f_form;
// DROP TABLE f_field;
// DROP TABLE f_fieldtype;
// DROP TABLE db_log_correspondence;
// DROP TABLE db_log;
// DROP TABLE comment;
// DROP TABLE carousel;
// DROP TABLE article;
// DROP TABLE g_listitem;
// DROP TABLE g_list;
// DROP TABLE app_contents_rights;
// DROP TABLE app_pages_rights;
// DROP TABLE app_page_content;
// DROP TABLE app_content;
// DROP TABLE app_menu_page;
// DROP TABLE app_page;
// DROP TABLE app_menu;
// DROP TABLE fos_user_user;
// DROP TABLE fos_user_group;
// DROP TABLE fos_user_user_group;


// // DELETE ALL TABLES
// SET FOREIGN_KEY_CHECKS = 0;
// SET GROUP_CONCAT_MAX_LEN=32768;
// SET @tables = NULL;
// SELECT GROUP_CONCAT('`', table_name, '`') INTO @tables
//   FROM information_schema.tables
//   WHERE table_schema = (SELECT DATABASE());
// SELECT IFNULL(@tables,'dummy') INTO @tables;

// SET @tables = CONCAT('DROP TABLE IF EXISTS ', @tables);
// PREPARE stmt FROM @tables;
// EXECUTE stmt;
// DEALLOCATE PREPARE stmt;
// SET FOREIGN_KEY_CHECKS = 1;