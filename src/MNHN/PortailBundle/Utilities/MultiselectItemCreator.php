<?php

namespace MNHN\PortailBundle\Utilities;

class MultiselectItemCreator
{
    /**
     * Ajoute des nouveaux items à un multiselect avec tags
     * Cette fonction ne fonctionne qu'avec les liste globales
     * @param $fieldName nom du champ dans le form
     * @param $listName nom de la liste
     * 
     */
    public function multiselectItemCreator($fieldName, $listName) {
        //Ajout de nouveaux taxons si non existants
        $tab = $data[$fieldName];

        $data = [];

            
            //On récupère la liste taxon
        $list = $this->em->getRepository(G_List::class)->findOneByName($listName);

            //On créé un taxon si non existant
        foreach ($tab as $tabItem) {
                // On vérifie s'il existe
            $item = $this->em->getRepository(G_ListItem::class)->find($tabItem);
            if ($item == null) {
                   // Create the new taxon
                $item = new G_ListItem();
                $item->setName($tabItem);
                $item->setList($list);
                $this->em->persist($item);
                $this->em->flush();
            }
            array_push($data, $item->getId() . "");

        }
        return $data;
    }
}