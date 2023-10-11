<script type="text/javascript">
    if(typeof iocMedia != 'undefined'){
        iocMedia()(window.location.protocol, 
                document.getElementById("@ID_DIV@"),
                "img/film.png",
                "@QUERY@", "@ID_VIDEO@", "@HEIGHT@", "@WIDTH@");
    }else{
        require(["mediaScript"], function(mediaScript){
            mediaScript(window.location.protocol, 
                document.getElementById("@ID_DIV@"),
                "img/film.png",
                "@QUERY@", "@ID_VIDEO@", "@HEIGHT@", "@WIDTH@");
        });
    }    
</script>