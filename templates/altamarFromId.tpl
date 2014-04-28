<script type="text/javascript">
    function _pi(){
        return "1326284612001";
    }
    function _pk(){
        return "AQ~~,AAABNMyTcTE~,zjiPB9Bfp4EykEGoTnvDHUfnwtGu2QvJ";
    }
    function _m(){
        return "http://c.brightcove.com/services/viewer/federated_f9?isVid=1";
    }
    function _akdsaghj(){     
        return 'http://c.brightcove.com/services/viewer/federated_f9?isVid=1?playerKey='+_pk()+'&playerID=@ID_VIDEO@'
        //return 'http://c.brightcove.com/services/viewer/federated_f9/1326284612001?isVid=1&isUI=1&playerKey=AQ~~,AAABNMyTcTE~,zjiPB9Bfp4EykEGoTnvDHUfnwtGu2QvJ&videoID=@ID_VIDEO@';
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
        document.getElementById("@ID_DIV@").innerHTML = '<object seamlesstabbing="undefined" '
        + 'class="BrightcoveExperience" id="objvi@ID_VIDEO@" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" ' 
        + 'codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,47,0" '
        + 'height="@HEIGHT@" width="@WIDTH@">'
        + ' <param value="always" name="allowScriptAccess"/>'
        + ' <param value="true" name="allowFullScreen"/>'
        + ' <param value="false" name="seamlessTabbing"/>'
        + ' <param value="true" name="swliveconnect"/>'
        + ' <param value="window" name="wmode"/>'
        + ' <param value="low" name="quality"/>'
        + ' <param value="#FFFFFF" name="bgcolor"/>'
        + ' <param name="movie" value="'+ _m() + '" />'
        + ' <param name="flashVars" value="@videoPlayer=@ID_VIDEO@&'
                +'playerID='+_pi()+'&playerKey='+_pk()+'&domain=embed&dynamicStreaming=true" />'
        + ' <param name="base" value="http://admin.brightcove.com" />'
        + ' <param value="false" name="play" />'
        + ' <embed src="'+_m()+'" bgcolor="#FFFFFF" flashVars="@videoPlayer=@ID_VIDEO@&playerID='+_pi()+'&playerKey='+_pk()+'&domain=embed&dynamicStreaming=true" base="http://admin.brightcove.com" name="flashObj" width="@WIDTH@" height="@HEIGHT@" seamlesstabbing="false" type="application/x-shockwave-flash" allowFullScreen="true" swLiveConnect="true" allowScriptAccess="always" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" play="false">'
        //+ ' <embed src="'+_m()+'" bgcolor="#FFFFFF" flashVars="playerID=@ID_VIDEO@&playerKey='+_pk()+'&domain=embed&dynamicStreaming=true" base="http://admin.brightcove.com" name="flashObj" width="@WIDTH@" height="@HEIGHT@" seamlesstabbing="false" type="application/x-shockwave-flash" allowFullScreen="true" swLiveConnect="true" allowScriptAccess="always" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" play="false">'
        + ' </embed>' 
        + '</object>';
    }
</script>