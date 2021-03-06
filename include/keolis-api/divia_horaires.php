<?php
require 'include/php/dbh.php';
$codeville = '217';
$transport = 'divia';
$ville = 'Dijon';
$ref_id = $_GET['ref'];

//Récupération de l'XML par l'API Keolis
$horaires_data = simplexml_load_file('http://timeo3.keolis.com/relais/' . $codeville . '.php?xml=3&refs=' . $ref_id . '&ran=1');

//Setup variables
$ligne = '';
$arret = '';
$direction = '';

//Liste des lignes & arrets
foreach ($horaires_data->horaires as $val) {
        $ligne = $horaires_data->horaires->horaire->description->ligne;
        $arret = $horaires_data->horaires->horaire->description->arret;
        $direction = $horaires_data->horaires->horaire->description->vers;
    }

//Nombre d'horaires disponibles à afficher
foreach ($horaires_data->horaires->horaire->passages->attributes() as $a => $value) {
    $nombrePassages = $value;
}

//Tableau avec les horaires
$horaires_xml = array();

//On récupère les horaires
$j = 0;
foreach ($horaires_data->horaires->horaire->passages->passage as $character) {
    //On récupère les horaires bus/maintenant
    $now=strtotime($horaires_data->heure);
    $bus=strtotime($horaires_data->horaires->horaire->passages->passage[$j]->duree);

    //Différence de temps
    $time=$bus-$now;
    
    //Conversion en minutes
    $mn = $time / 60;

    //On envoie la valeur dans le tableau
    array_push($horaires_xml, $mn);

    $j++;
}
?>