({
    baseUrl: "./",
    out: '../html/_/js/build.js',
    optimize: "uglify2",
    name: 'main',
    paths: {
        requireLib: 'require',
        hyphenator: 'Hyphenator',
        'modernizr-1.7': 'modernizr-1.7.min',
        "jquery.ioc-tools": "../../../../lib_ioc/wikiiocmodel/templates/jquery.ioc-tools"
    },
    include: ['requireLib', 'hyphenator', 'modernizr-1.7', 'jquery.ioc-tools'],
    shim: {
        'jquery-ui.min': {
            deps: ['jquery.min']
        },
        'jquery.imagesloaded': {
            deps: ['jquery.min'],
            exports: 'jQuery.fn.imagesLoaded'
        }
    }
})

//CALDRIA AFEGIR AQUÍ LA COPIA DEL FITXER CSS amb l'atribut dir: [RUTA AL DIRECTORI ON HI HA FITXERS A IMPORTAR]
