/**
 * Lib wiki page counter
 * 
 * @author     Marc Català <mcatala@ioc.cat>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */
jQuery.noConflict();
jQuery(document).ready(function() {
	jQuery('#content ul > li > div > a[class=wikilink1]').each(function(key, value) {
  		var id = jQuery(this).attr('title').replace(/:/g,'_');
		var tag = jQuery('<span id="'+id+'"><img src="lib/plugins/iocexportl/img/loader.gif" alt="Generant arxiu" /></span>').appendTo(jQuery(this).parent());
		jQuery.ajax({
          	url: "lib/plugins/iocexportl/calculate.php",
          	global: false,
          	type: "POST",
          	data: "id=" + this.title,
          	dataType: "json",
          	async: true,
          	success: function(data, textStatus, xhr){
          		if(jQuery.isPlainObject(data)){
                            var strCarac = "<strong> " 
                                     +data.totalCounter.value 
                                     +" caràcters</strong>";
                            if(data.counterType == 1){
                                 strCarac += "<strong> (" 
                                     +data.reusedContentCounter.value+ " "
                                     +data.reusedContentCounter.tag
                                     +" i "
                                     +data.newContentCounter.value+" "
                                     +data.newContentCounter.tag
                                     +") </strong>";
                            }
                            tag.hide().fadeOut("slow").html(strCarac).fadeIn("slow");
          		}else{
                            tag.hide();
          		}
     		},
                    error: function(xhr, textStatus, errorThrown){
                        tag.hide().fadeOut("slow").html(xhr.responseText).fadeIn("slow");
      		}
        });
	});
});