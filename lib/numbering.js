jQuery(document).ready(function() {
    require(["ioc/dokuwiki/listHeadings"], function(listHeadings){
        listHeadings("content");
    })
});