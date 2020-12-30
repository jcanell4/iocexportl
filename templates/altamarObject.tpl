<!--script type="text/javascript">
    function _pi(){
        return "1326284612001";
    }
    function _pk(){
        return "AQ~~,AAABNMyTcTE~,zjiPB9Bfp4EykEGoTnvDHUfnwtGu2QvJ";
    }
    function _m(){
        return "https://c.brightcove.com/services/viewer/federated_f9?";
    }
    function _akdsaghj(){   
        return _m()+'width=@WIDTH@&height=@HEIGHT@&flashID=objvi@ID_VIDEO@&bgcolor=%23FFFFFF&playerID=@ID_VIDEO@&playerKey='+_pk()+'&isVid=true&dynamicStreaming=true&@videoPlayer=@ID_VIDEO@&autoplay=false&quality=low&autoStart=false';
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
        document.getElementById("@ID_DIV@").innerHTML = 
            '<object seamlesstabbing="undefined" class="BrightcoveExperience" '
	            +'id="objvi@ID_VIDEO@" '
                    +'data="'+_akdsaghj()+'" '
	            +'type="application/x-shockwave-flash" height="@HEIGHT@" width="@WIDTH@">'
                +'<param value="always" name="allowScriptAccess">'
                +'<param value="true" name="allowFullScreen">'
                +'<param value="false" name="seamlessTabbing">'
                +'<param value="true" name="swliveconnect">'
                +'<param value="window" name="wmode">'
                +'<param value="high" name="quality">'
                +'<param value="#FFFFFF" name="bgcolor">'
            +'</object>';
    }
</script-->
<object seamlesstabbing="undefined" class="BrightcoveExperience" id=objvi@ID_VIDEO@ data="https://c.brightcove.com/services/viewer/federated_f9?width=@WIDTH@&height=@HEIGHT@&flashID=objvi@ID_VIDEO@&bgcolor=%23FFFFFF&playerID=@ID_VIDEO@&playerKey=AQ~~,AAABNMyTcTE~,zjiPB9Bfp4EykEGoTnvDHUfnwtGu2QvJ&isVid=true&dynamicStreaming=true&@videoPlayer=@ID_VIDEO@&autoplay=false&quality=low&autoStart=false" type="application/x-shockwave-flash" height="@HEIGHT@" width="@WIDTH@">
    <param value="always" name="allowScriptAccess">
    <param value="true" name="allowFullScreen">
    <param value="false" name="seamlessTabbing">
    <param value="true" name="swliveconnect">
    <param value="window" name="wmode">
    <param value="high" name="quality">
    <param value="#FFFFFF" name="bgcolor">
</object>
