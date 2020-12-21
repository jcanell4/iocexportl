require([
    "ioc/dokuwiki/listHeadings",
    "dojo/ready"
], function(listHeadings, ready){
    ready(function(){
        listHeadings("content");
    });
    
});