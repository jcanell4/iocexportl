/**
 * Aquest plugin permet desplaçar elements a la columna B, alineant-los a dreta o esquerra segons la configuració.
 * L'alineació lateral només es fiable perls continguts centrals de ipus paràgraf i llistes.
 *
 * En el cas de dispositius mòbils es mostrarà només una icona per mostrar i ocultar-lo.
 *
 * Es pot configurar mitjançant un objecte de configuració amb les següents opcions:
 *     minWidth: 992, // int. Amplada mínima per mostrar-lo com a columna o com a desplegable
 *     columnAlign: 'right', // left | right
 *     beforeContainer: 'p, ul, ol', // selectors per col·locar l'element abans per amplada d'escriptori
 *     defaultIcon: '../../../img/iocinclude.png', // Icona per defecte per desplegar els elements en
 *                                                 // amplada mòbil. Si no s'especifica s'intenta utilitzar el fons
 *                                                 // del contenidor de la columna (la nota, el text, etc.)
 *     lateralMargin: '2em', // Marge lateral de la columna
 *     debug: false, // Si és cert mostra un requadre vermell o verd segons si s'ha desplaçat el bloc
 *                               // de contingut (la nota, la imatge lateral, el text, etc.) o no.
 *     forceClear: true, // força el reemplaçament de la propietat clear, accepta una query com a valor
 *
 * @example
 *     $(".iocnote, .iocreference, .ioctext, .iocfigurec").toBColumn({debug: true, columnAlign: 'left', forceClear: true});
 *     $(".iocnote, .iocreference, .ioctext, .iocfigurec").toBColumn({debug: true, columnAlign: 'left', forceClear: {query: 'p'}});
 *     $(".iocnote, .iocreference, .ioctext, .iocfigurec").toBColumn({minWidth: 0});
 *     jQuery(".iocfigurec").toBColumn();
 *
 * @known_issues
 *     No tots els elements permeten l'alinació de columnes, encara que s'apliqui la opció forceClear, per exemple
 *     les figures principals
 *
 * @author Xavier Garcia <xaviergaro.dev@gmail.com>
 */
(function ($) {

    $.fn.toBColumn = function (options) {
        var settings = $.extend({
            minWidth: 992, // int. Amplada mínima per mostrar-lo com a columna o com a desplegable
            columnAlign: 'right', // left | right
            beforeContainer: 'p, ul, ol', // selectors per col·locar l'element abans per amplada d'escriptori
            icon: false, // si s'assinga un icon es farà servir aquest, en lloc d'intentar cercar-lo
            defaultIcon: '../../../img/iocinclude.png', // Icona per defecte per desplegar els elements en
                                                        // amplada mòbil
            lateralMargin: '2em', // Marge lateral de la columna
            debug: false, // Si és cert mostra un requadre vermell o verd segons si s'ha desplaçat el bloc
                          // de contingut (la nota, la imatge lateral, el text, etc.) o no.
            forceClear: true, // força el reemplaçament de la propietat clear, accepta una query com a valor
            class: undefined // classe CSS per aplicar a la columna

        }, options);

        var id = 0;


        this.each(function () {

            var $this = jQuery(this);

            $this.css('float', settings.columnAlign);
            $this.css('clear', settings.columnAlign);

            if (settings.columnAlign === 'right') {
                $this.css('margin-left', settings.lateralMargin);
                $this.css('margin-right', 0);
            } else {
                $this.css('margin-left', 0);
                $this.css('margin-right', settings.lateralMargin);
            }

            if (settings.class) {
                $this.addClass(settings.class);
            }

            if (settings.minWidth >0 && window.matchMedia('(min-width: ' + settings.minWidth + 'px)').matches) {
                // S'ha de recol·locar a sobre
                var $target = $this.prevAll(settings.beforeContainer).first();

                // Ens assegurem que s'insereix abans d'un contenidor no buit
                while ($target.length > 0 && $target.children().length === 0 && $target.text().trim().length === 0) {
                    $target = $target.prevAll(settings.beforeContainer).first();
                }

                // Només cal recol·locar si s'ha trobat un node previ
                if ($target.length > 0) {
                    $this.insertBefore($target);

                    if (settings.debug === true) {
                        $target.css('background-color', '#f8d7da');
                        $target.css('border', '1px solid #f5c6cb');
                    }

                    // Reiniciem el clear perquè sinò no s'alineen correctament les columens
                    // (el default és clear: left)
                    $target.css('clear', 'inherit');

                    // El problema amb el clear és que si no s'afegeix només es produirà l'alineament correcte del
                    // primer paràgraf (perquè forcem el clear), però no hi ha garantia de que aquest
                    // sistema sigui correcte en tots els casos, per tant l'afegim com a opció
                    // Aquest clear sembla que només s'aplica als paràgraf, i es fa pel CSS (als UL i LI no els
                    // afecta)
                    if (settings.forceClear === true) {
                        $target.siblings('p').css('clear', 'inherit');
                    } else if (settings.forceClear) {
                        $target.siblings(settings.forceClear.query).css('clear', 'inherit');
                    }


                } else if (settings.debug === true) {
                    // No hi ha previ, no cal recol·locar
                    $target.css('background-color', '#d4edda');
                    $target.css('border', '1px solid #c3e6cb');
                }

            } else {
                // Ho converti'm en un clicable que es desplega

                // S'ha de mostrar quan es clica
                var $node = jQuery('<div style="float:' + settings.columnAlign + '" data-id="' + id +'"></div>');
                id++;

                var pattern = /url\("(.*)"\)?/gm;

                var icon ='';
                if (settings.icon) {
                    icon = settings.icon;
                } else if ($this.css('background-image')) {

                    var matches = pattern.exec($this.css('background-image'))
                    icon = matches ? matches[1] : false;
                    // console.log("S'ha assignat l'icon?", icon)
                }

                // Si arribat a aquest punt sense icona fem servir el valor per defecte
                if (!icon) {
                    console.warn("Using default icon");
                    icon = settings.defaultIcon;
                }

                var $img = jQuery('<img src="' + icon + '">');
                $node.append($img);
                $node.insertBefore($this);
                $this.appendTo($node);

                $this.css('display', 'none');

                var toggle = false;

                // Mostra o amaga l'element
                $node.on('click', function () {
                    toggle = !toggle;
                    $this.css('display', toggle ? 'block' : 'none');
                    $node.css('float', toggle ? 'none' : 'right');
                    $img.css('display', toggle ? 'none' : 'block');
                });

            }
        });

        return this;
    };

}(jQuery));
