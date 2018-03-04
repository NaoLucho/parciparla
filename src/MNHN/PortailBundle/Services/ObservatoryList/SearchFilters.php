<?php

namespace MNHN\PortailBundle\Services\ObservatoryList;

use Doctrine\ORM\EntityManager;

class SearchFilters {

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function getObservatories($request, $nbbypage, $postedValues = null, $page = null) {
        $F_Keywords;

        //Load search Form Elements:
        //Load Themes
        $themes = $this->em->getRepository('MNHNAdminBundle:G_ListItem')->createQueryBuilder('listitem')
            ->leftJoin('listitem.list', 'list')
            ->where('list.name = :lname')
            ->orderBy('listitem.order', 'ASC')
            ->setParameter('lname', 'themes')
            ->getQuery()->getResult();

        //Load Espèces
        $especes = $this->em->getRepository('MNHNAdminBundle:G_ListItem')->createQueryBuilder('listitem')
            ->leftJoin('listitem.list', 'list')
            ->where('list.name = :lname')
            ->orderBy('listitem.order', 'ASC')
            ->setParameter('lname', 'taxons')
            ->getQuery()->getResult();

        //Load Niveaux
        $niveaux = $this->em->getRepository('MNHNAdminBundle:G_ListItem')->createQueryBuilder('listitem')
            ->leftJoin('listitem.list', 'list')
            ->where('list.name = :lname')
            ->orderBy('listitem.order', 'ASC')
            ->setParameter('lname', 'program_accessibility')
            ->getQuery()->getResult();

        $F_ids_taxons = [];
        $F_str_place;
        $F_ids_themes = [];
        $F_ids_niveaux = [];
        
        //FILTRE TO ADD IF METHOD is POST
        if ($request->isMethod('POST')) {
            // array{ ["lieu"]=> string(0) "" ["theme"]=> array(1) { [0]=> string(1) "9" } ["taxon_Animaux"]=> array(3) { [0]=> string(2) "14" [1]=> string(2) "69" [2]=> string(2) "72" } ["taxon_Végétaux"]=> array(1) { [0]=> string(2) "78" } ["taxon_Champignons_&_lichens"]=> string(2) "81" ["mot_cle"]=> string(0) "" } 
            foreach ($postedValues as $key => $value) {
                //GET Filtre Lieu
                if ($key === "lieu")
                    $F_str_place = $value;

                if ($key === "mot_cle") {
                    $F_Keywords = $value;
                }


                if (!is_array($value)) {
                    $value = [$value];
                }


                //GET Filtre Themes
                if ($key === "theme")
                    $F_ids_themes = $value;
                    
                //GET Filtre Taxons
                if (substr($key, 0, strlen("taxon_")) === "taxon_") {
                    $F_ids_taxons = array_merge($F_ids_taxons, $value);
                }
                
                //GET Filtre Niveaux
                if ($key === "niveau")
                    $F_ids_niveaux = $value;

            }
            
            //var_dump($F_ids_taxons);
            $query = $this->em->getRepository('MNHNPortailBundle:P_Program')
                ->createQueryBuilder('program')
                ->where("program.isActiveProgram = true");

            if (count($F_ids_taxons) >= 1 || count($F_ids_themes) >= 1 || count($F_ids_niveaux) >= 1) {



                if (count($F_ids_themes) > 0) {
                    $query->join('program.themes', 'themes');
                    $query->andWhere('themes.id in (:themes)')
                        ->setParameter('themes', $F_ids_themes);
                }
                if (count($F_ids_taxons) > 0) {
                    $query->join('program.taxons', 'taxons');
                    $query->andWhere('taxons.id in (:taxons)')
                        ->setParameter('taxons', $F_ids_taxons);
                }
                if (count($F_ids_niveaux) > 0) {
                    $query->join('program.accessibility', 'accessibility');
                    $query->andWhere('accessibility.id in (:accessibility)')
                        ->setParameter('accessibility', $F_ids_niveaux);
                }
            }

            $programs = $query->getQuery()->getResult();
        }
        else {
            $programs = $this->em->getRepository('MNHNPortailBundle:P_Program')
                ->createQueryBuilder('program')
                ->where("program.isActiveProgram = true")
                ->getQuery()->getResult();
        }

        $items = [];
        for ($i = 0; $i < count($programs); $i++) {
            $localisations = [];
            $tab = $programs[$i]->getLocalisations();
            foreach ($tab as $element) {
                $localisations[] = [
                    "nom" => $element->getNom(),
                    "code" => $element->getCode()
                ];
            }

            $item = array(
                'thumbnail' => $programs[$i]->getLogoName(),
                'path' => 'WAITING FICHE observatoire',
                'title' => $programs[$i]->getName(),
                'structure' => ($programs[$i]->getStructureAnim() == null) ? "Indéfinie" : $programs[$i]->getStructureAnim()->getName(),
                'excerpt' => $programs[$i]->getDescription(),
                'structureid' => ($programs[$i]->getStructureAnim() == null) ? "Indéfinie" : $programs[$i]->getStructureAnim()->getId(),
                'programid' => $programs[$i]->getId(),
                'program' => $programs[$i],
                'accessibility' => $programs[$i]->getAccessibility(),
                'themes' => $programs[$i]->getThemes(),
                'taxons' => $programs[$i]->getTaxons(),
                'localisations' => $localisations
            );

            $checker = true;

            if (isset($F_Keywords) && strlen($F_Keywords) > 1) {
                if ($this->array_search_partial($F_Keywords, $item) !== true) {
                    $checker = false;
                }
            }
            if (isset($F_str_place) && strlen($F_str_place) > 1) {
                if ($this->array_search_partial($F_str_place, $item) !== true) {
                    $checker = false;
                }
            }

            if ($checker === true) {
                $items[] = $item;
            }
        }

        $pages = ceil(count($items) / $nbbypage);

        $pagination = false;

        if($page) {
            $itemspaginated = array_slice($items, ($page - 1) * $nbbypage, $nbbypage);
            $pagination = true;
        } else {
            $itemspaginated = array_slice($items, 0, $nbbypage);
        }

        return [
            'items' => $itemspaginated,
            'niveaux' => $niveaux,
            'themes' => $themes,
            'especes' => $especes,
            'pages' => $pages
        ];
    }

    public function array_search_partial($keyword, $arr)
    {
        foreach ($arr as $key => $value) {
            if (gettype($value) === "string" && stripos($value, $keyword) !== false) {
                return true;
            } else if (gettype($value) === "array") {
                foreach ($value as $key => $val) {
                    $check = array_filter($val, function ($var) use ($keyword) {
                        return preg_match("/\b$keyword\b/i", $var);
                    });

                    if (count($check) > 0) {
                        return true;
                    }

                }
            }
        }
    }
}