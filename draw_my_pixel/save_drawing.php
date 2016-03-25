<?php

    // Appel Ajax
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
              !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
              strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

    // Données reçues
    $drawing = isset($_POST['drawing']) ? $_POST['drawing'] : false;

    // Test
    if(!$isAjax || !$drawing) exit;

    $rowSize = 30;                              // Nombre de tiles dans une ligne (de dessin)
    $json = json_decode($drawing);              // Dessin JSON
    $jsonStructure = ['name', 'mapping'];       // Structure valide
    $mappingCount = count($json['mapping']);    // Taille du mapping

    // Validité du JSON
    if(
        json_last_error() == JSON_ERROR_NONE || // Erreur JSON
        $jsonStructure != array_keys($json) ||  // Structure du JSON
        $mappingCount > $rowSize * $rowSize     // Taille du mapping
    ) exit;

    // ----- Validation des données -----

    // Nom du dessin
    if(!preg_match('/^([\w-_.]+){50}$/', $json['name'])) exit; // Format du nom

    // Format du mapping
    for($i = 0; $i < $mappingCount; $i++) {
        $x = $json['mapping'][$i][1];
        $y = $json['mapping'][$i][0];

        if($x < 0 || $x >= $rowSize || $y < 0 || $y >= $rowSize) exit;
    }

    // Enregistrement du fichier JSON
    $filename = $json['name'] . time() . '.json'; // Nom du fichier
    $file = fopen('drawings/' . $filename, 'w');
    fwrite($file, $json);
    fclose($file);

    // Retour JSON
    echo json_encode(true);
?>