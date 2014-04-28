define(function(){
   var _ehjkhjkl=function(node, data, img, h, w /*, Tooltip*/){
        node.innerHTML ="<img src='" + img 
        + "' alt='"+data+"' " 
        + "height='"+h+"' width='"+w+"' title='"+data+"'/>";
        /*
        new Tooltip({
             connectId: ["@ID_DIV@"],
             label: data
        });
        */
    }
    
    var _dhjdksab=function (node, data, id, h, w){
        node.innerHTML = '<object seamlesstabbing="undefined" '
       + 'class="BrightcoveExperience" id="objvi'+id+'" data="' 
       + data + '&videoID='+id+'" type="application/x-shockwave-flash" '
       + 'height="'+h+'" width="'+w+'">'
       + ' <param value="always" name="allowScriptAccess"/>'
       + ' <param value="true" name="allowFullScreen"/>'
       + ' <param value="false" name="seamlessTabbing"/>'
       + ' <param value="true" name="swliveconnect"/>'
       + ' <param value="window" name="wmode"/>'
       + ' <param value="low" name="quality"/>'
       + ' <param value="#FFFFFF" name="bgcolor"/>'
       + ' <param value="false" name="play"/>'
       + '</object>';
    }
    
    var _shjtyvxi=function (node, img, q, id, h, w){   
        jQuery.ajax({
            url: "//ioc.xtec.cat/secretaria/ioc/materials/videoService.php?type="
                        +q+"&callback=?",
            crossDomain:true,
            dataType: "jsonp",
            success:function(/*PlainObjectData*/ text
                                , /*String*/ status
                                , /*jgXHR*/ jgXHR ){
                if(text.type=='data'){
                     _dhjdksab(node, text.value, id, h, w);
                }else{
                     _ehjkhjkl(node, text.value, img, h, w);
                }
            },
            error: function(/*jgXHR*/ jgXHR, /*String*/ error, /*String*/ ex){
                _ehjkhjkl(node, "ERROR EN CARREGAR EL VÍDEO.", img, h, w /*, Tooltip*/);
            }            
        });
    }
    
    var ret=function (prot, node, img, q, id, h, w){   
        if(prot=="file:"){
            _ehjkhjkl(node, 'Per veure el vídeo cal estar connectat al campus', 
                        img, h, w);
        }else{
            _shjtyvxi(node, img, q, id, h, w);
        }
    }
    return ret;
});




