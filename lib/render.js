require([
    "ioc/dokuwiki/runRender",
    "dojo/ready"
], function(runRender, ready){
    ready(function(){
        runRender("bodyContent");
    });
    
});

