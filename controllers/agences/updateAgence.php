<?php

include_once dirname(__DIR__,2).DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . "Agence.php";
include_once dirname(__DIR__,2).DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "Db.php";

// traitement des requette avec post
if ($_SERVER['REQUEST_METHOD'] === "PUT"){
    $data = json_decode(file_get_contents("php://input"));
    if (!empty($data)) {
        $modelAgence = new Agence($dbi);
        $modelAgence->setNom(cleanData($data->nom));
        $modelAgence->setAdresse(cleanData($data->adresse));
        $modelAgence->setId(cleanData($data->id));
        
        if ($modelAgence->update()) {
            return print json_encode(['response' => "Agence " . $data->nom . " update avec success"],http_response_code(200), JSON_PRETTY_PRINT);
        }

    } else {
        return print json_encode(['response' => "La liste des donnees est vide !"],http_response_code(200), JSON_PRETTY_PRINT);
    }
} else {
    return print json_encode(['error' => "Vous n'etes autorise !"],JSON_PRETTY_PRINT);
}

/** cette mothode permet de nottoyer la valeur envoyer par le frontend */
function cleanData($data) {
    return trim(htmlentities($data));
}
