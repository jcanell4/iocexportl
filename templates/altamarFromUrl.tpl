<script type="text/javascript">
    function _akdsaghj(){
        return "https://bcove.me/@ID_VIDEO@";
    }
    if(window.location.protocol=="file:"){
        document.getElementById("@ID_DIV@").innerHTML ="<img src='" 
                  + "img/film.png"
                  + "' alt='Per veure el vídeo cal estar connectat al campus' "
                  + "height='@HEIGHT@' width='@WIDTH@'/>";
        require(["dojo/ready", "dijit/Tooltip"], function(ready, Tooltip){
            ready(function(){
                new Tooltip({
                    connectId: ["@ID_DIV@"],
                    label: "Per veure el vídeo cal estar connectat al campus"
                });
            });
        });    
    }else{
        document.getElementById("@ID_DIV@").innerHTML = "<iframe height='@HEIGHT@' width='@WIDTH@' src='"
                  + _akdsaghj() + "'></iframe>";
    }
</script>