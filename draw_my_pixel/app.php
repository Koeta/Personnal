<?php

/**
 * Retourne une image de prévisualisation d'un dessin (miniature)
 * 
 * La fonction prend en paramètre un dessin decodé depuis un fichier JSON (name, mapping)
 * et construit l'image en fonction du mapping.
 *
 * @param string $mapping		Mapping du dessin, décodé depuis un fichier JSON
 * @param boolean $pixel 		Si vrai, reproduit le dessin avec un décalage de [$tileSize - 1] pixels (pas de remplissage)
 * @param integer $zoneSize		Taille de la zone de dessin
 * @param integer $tileSize		Taille d'un tile en pixel
 * @return base34				L'image retournée est encodée en base34 pour l'afficher en source d'une balise <img>
 */
function getPreview($mapping, $pixel = false, $zoneSize = 30, $tileSize = 3) {
	$mappingCount = count($mapping);

	$drawSize = $zoneSize * $tileSize; // Taille de l'image

	$image = imagecreatetruecolor($drawSize, $drawSize);
	$black = imagecolorallocate($image, 255, 255, 255);

	for($i = 0; $i < $mappingCount; $i++) {
		$x = $mapping[$i][1] * $tileSize;
		$y = $mapping[$i][0] * $tileSize;
		$x2 = $x + $tileSize;
		$y2 = $y + $tileSize;

		if($pixel) {
			imagesetpixel($image, $x, $y, $black);
		} else {
			imagefilledrectangle($image, $x, $y, $x2, $y2, $black);
		}
	}

	// Mise en cache de l'image pour la conversion
	ob_start(); 
	imagepng($image);
	$imageData = ob_get_contents(); 
	ob_end_clean ();

	imagedestroy($image);

	return base64_encode($imageData);
}