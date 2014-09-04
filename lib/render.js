jQuery(document).ready(function() {
    require(["ioc/dokuwiki/runRender"], function(runRender){
        runRender("bodyCOntent");
    })
});