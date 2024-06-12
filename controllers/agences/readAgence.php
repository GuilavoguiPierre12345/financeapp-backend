<?php

include_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . "Agence.php";
include_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "Db.php";

// traitement des requette avec post
if ($_SERVER['REQUEST_METHOD'] === "GET") {

    $modelAgence = new Agence($dbi);
    $agences = $modelAgence->read();
    if ($agences->rowCount() > 0) {
        $agences = $agences->fetchAll();
        return print json_encode(['response' => $agences], http_response_code(200), JSON_PRETTY_PRINT);
    } else {
        return print json_encode(['response' => "La liste des agences est vide !"], http_response_code(200), JSON_PRETTY_PRINT);
    }
} else {
    return print json_encode(['error' => "Vous n'etes autorise !"], JSON_PRETTY_PRINT);
}

/** cette mothode permet de nottoyer la valeur envoyer par le frontend */
function cleanData($data)
{
    return trim(htmlentities($data));
}
