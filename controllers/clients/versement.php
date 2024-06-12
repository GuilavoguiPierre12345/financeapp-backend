<?php

include_once dirname(__DIR__,2).DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . "Client.php";
include_once dirname(__DIR__,2).DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . "Compte.php";
include_once dirname(__DIR__,2).DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "Db.php";

// traitement des requette avec post
if ($_SERVER['REQUEST_METHOD'] === "PUT"){
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data)) {
        $modelCompte = new Compte($dbi);
        $modelCompte->setId(cleanData($data->id));
        $nouveauSolde = floatval($modelCompte->soldeCompte()->fetch()->solde) + floatval(cleanData($data->soldeSaisi));
        $modelCompte->setSolde($nouveauSolde);
        
        if($modelCompte->versementOrRetraitOrVirement()){
            return print json_encode(['response' => "Versement de  " . $data->soldeSaisi . " " .  " effectuer avec success. Nouveau solde est : " . $nouveauSolde ],http_response_code(200), JSON_PRETTY_PRINT);
        }

    } else {
        return print json_encode(['response' => "Aucune donnees dans la requete !"],http_response_code(200), JSON_PRETTY_PRINT);
    }
} else {
    return print json_encode(['error' => "Vous n'etes autorise !"],JSON_PRETTY_PRINT);
 
}

/** cette mothode permet de nottoyer la valeur envoyer par le frontend */
function cleanData($data) {
    return trim(htmlentities($data));
}