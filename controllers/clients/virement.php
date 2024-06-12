<?php

include_once dirname(__DIR__,2).DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . "Client.php";
include_once dirname(__DIR__,2).DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . "Compte.php";
include_once dirname(__DIR__,2).DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "Db.php";

// traitement des requette avec post
if ($_SERVER['REQUEST_METHOD'] === "PUT"){
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data)) {
        $modelCompte = new Compte($dbi);

        $idEmetteur = intval(cleanData($data->idEmetteur));
        $idRecepteur = intval(cleanData($data->idRecepteur));
        $soldeVirement = floatval(cleanData($data->soldeVirement));
        
        /** traitement des informations du client qui effectue le virement */
        $modelCompte->setId($idEmetteur);
        $ancienSoldeCompteEmetteur = $modelCompte->soldeCompte()->fetch()->solde;
        
        if ($ancienSoldeCompteEmetteur < $soldeVirement) {
            return print json_encode(['response' => "Impossible, votre solde actuel est inferieur au montant de virement "],http_response_code(200), JSON_PRETTY_PRINT);
        }
        $modelCompte->setSolde($ancienSoldeCompteEmetteur - $soldeVirement);
        $modelCompte->versementOrRetraitOrVirement();
            
        /** traitement des informations du client qui recoit le virement */
        $modelCompte->setId($idRecepteur);
        $ancienSoldeCompteRecepteur = $modelCompte->soldeCompte()->fetch()->solde;
        $modelCompte->setSolde($ancienSoldeCompteRecepteur + $soldeVirement);
        if ($modelCompte->versementOrRetraitOrVirement()) {
            return print json_encode(['response' => "virement effectuer avec success "],http_response_code(200), JSON_PRETTY_PRINT);
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
