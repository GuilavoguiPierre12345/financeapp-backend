<?php

include_once dirname(__DIR__,2).DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . "Client.php";
include_once dirname(__DIR__,2).DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . "Compte.php";
include_once dirname(__DIR__,2).DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "Db.php";

// traitement des requette avec post
if ($_SERVER['REQUEST_METHOD'] === "POST"){
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data)) {
        /** AJOUT DU CLIENT */
        $modelClient = new Client(
            $dbi,
            cleanData($data->nom),
            cleanData($data->prenom),
            cleanData($data->genre),
            cleanData($data->telephone)
        );
        $modelClient->setAgence(cleanData($data->agence));
        
        if ($modelClient->create()) {
            $currentClientId = $modelClient->dernierClientId();
            $currentClientId = $currentClientId->fetch();
            /** AJOUT DU COMPTE CLIENT */
            $modelCompte = new Compte($dbi);
            $modelCompte->setTypec(cleanData($data->typec));
            $modelCompte->setSolde(cleanData($data->solde));
            $modelCompte->setClient($currentClientId->id);

            if($modelCompte->create()) {
                return print json_encode(['response' => "Le compte du client : " . $data->prenom . " " . $data->nom . " ajouter avec success"],http_response_code(200), JSON_PRETTY_PRINT);
            }
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
