<?php

require 'app.php';

$drawings = [];

foreach(glob('drawings/*.json') as $drawing) {
    $drawings[] = json_decode(file_get_contents($drawing));
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>API 20 :: DrawMyPixelTile</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="assets/css/main.css">
    </head>
    <body>

        <h1>DrawMyPixel</h1>

        <div id="content">
            <div id="menu">
                <div class="options">
                    <div class="option-bloc">
                        <label for="">Effet</label>
                        <select class="option" data-option="drawingEffect">
                            <option value="random" selected>random</option>
                            <option value="two-axis">two-axis</option>
                            <option value="canon">canon</option>
                        </select>
                    </div>

                    <div class="option-bloc">
                        <label for="">Style</label>
                        <select class="option" data-option="tileStyle">
                            <option value="default">default</option>
                            <option value="blueblink" selected>blueblink</option>
                            <option value="blueblink-echo">blueblink-echo</option>
                        </select>
                    </div>
                </div>
                <div id="drawings">
                    <?php foreach($drawings as $drawing) : ?>
                    <div class="drawing" data-file="<?php echo $drawing->name; ?>">
                        <div class="drawing-name"><?php echo htmlspecialchars($drawing->name); ?></div>
                        <img class="drawing" src="data:image/png;base64,<?php echo getPreview($drawing->mapping, true); ?>">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div id="drawing-wrapper">
                <div id="drawing"></div>
                <div id="draw-axis-x" class="draw-axis"></div>
                <div id="draw-axis-y" class="draw-axis"></div>
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="assets/js/draw-my-pixel.js"></script>
    </body>
</html>