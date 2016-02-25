$(function() {

    var editor = {
        maxSize: 30
    };

    var app = {
        spawnSpeed: 40, // ms
        animSpeed: 400, // ms interval
        currentTile: 0,
        tileSize: 20,
        tilesCount: null,
        tileStyle: 'default',
        interval: null,
        drawing: null,
        drawingSize: 0,
        drawingEffect: 'random',
        animation: 'linear',
        drawAxisX: $('#draw-axis-x'),
        drawAxisY: $('#draw-axis-y'),
        init: function() {
            $(document).on('click', 'div.drawing', function() {
                app.getDrawing($(this).data('file'));
            });

            this.tileStyle = $('[data-option=tileStyle]').val();
            this.drawingEffect = $('[data-option=drawingEffect]').val();

            $('.option').change(function() {
                var option = $(this).data('option');
                app.endDrawing();
                app[option] = $(this).val();
            });
        },
        getDrawing: function(filename) {
            this.currentTile = 0;
            if(this.interval != null) {
                clearInterval(this.interval);
            }

            this.drawingSize = editor.maxSize * app.tileSize;

            $('#drawing').html('').css({
                width: app.drawingSize + 'px',
                height: app.drawingSize + 'px'
            });

            $.getJSON('drawings/' + filename + '.json', function(drawing) {
                app.drawing = drawing;
                app.tilesCount = drawing.mapping.length;
                app.drawingIntro();
            });
        },
        drawingIntro: function() {
            this.draw();
        },
        draw: function() {
            this.interval = setInterval(function() {
                if(app.currentTile < app.tilesCount) {
                    app.drawTile(app.drawing.mapping[app.currentTile]);
                    app.currentTile++;
                } else {
                    app.endDrawing();
                    clearInterval(app.interval);
                }
            }, app.spawnSpeed);
        },
        drawTile: function(tile) {
            var tileBloc = document.createElement('div');

            $('#drawing').append(tileBloc);

            this.applyTileStyle(tileBloc);
            this.drawEffect(tile, tileBloc);
        },
        applyTileStyle: function(tileBloc) {
            tileBloc.className = 'tile tile-' + this.tileStyle;
        },
        drawEffect: function(tile, tileBloc) {
            var x = tile[1] * this.tileSize;
            var y = tile[0] * this.tileSize;

            switch(this.drawingEffect) {

                // Apparition des tiles de manière aléatoire autour de la zone
                // Animation des tiles vers leur position finale
                case 'random':
                    tileBloc.style.left = (Math.random() * this.drawingSize) + 'px';
                    tileBloc.style.top = (Math.random() * this.drawingSize) + 'px';

                    $(tileBloc).animate({
                        left: x + 'px',
                        top: y + 'px',
                        width: this.tileSize + 'px',
                        height: this.tileSize + 'px'
                    }, app.animSpeed, app.animation);
                    break;

                // Apparition des tiles directement à leur position
                // Déplacement des "têtes d'écriture" sur les axes X et Y
                case 'two-axis':
                    tileBloc.style.left = x + 'px';
                    tileBloc.style.top = y + 'px';
                    tileBloc.style.width = this.tileSize + 'px';
                    tileBloc.style.height = this.tileSize + 'px';

                    var offset = (this.tileSize / 2) + 8;

                    this.drawAxisX.css('left', (x + offset) + 'px');
                    this.drawAxisY.css('top', (y + offset) + 'px');
                    break;

                // TODO
                // Apparition des tiles au niveau du canon
                // Animation du canon, prend le bon angle suivant la destination du tile
                // Animation des tiles vers leur position finale
                case 'canon':
                    tileBloc.style.left = ((this.maxSize + this.tileSize) / 2) + 'px';
                    tileBloc.style.bottom = '0px';

                    $(tileBloc).animate({
                        left: x + 'px',
                        top: y + 'px',
                        width: this.tileSize + 'px',
                        height: this.tileSize + 'px'
                    }, app.animSpeed, app.animation);
                    break;
            }
        },
        endDrawing: function() {
            switch(this.drawingEffect) {
                case 'two-axis':
                    $('.draw-axis').animate({
                        left: 0,
                        top: 0
                    }, app.animSpeed, app.animation);
                    break;
            }
        }
    }

    app.init();

});