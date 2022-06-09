({
    baseUrl: "./",
    out: '../html/_/js/build.js',
    name: 'main',
    paths: {
        requireLib: 'require',
        "jquery.ioc-tools": "../../../../lib_ioc/wikiiocmodel/templates/jquery.ioc-tools"
    },
    include: ['requireLib', 'jquery.ioc-tools'],
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
