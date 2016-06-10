/**
 * Lib wiki form export
 * 
 * @author     Marc Català <mcatala@ioc.cat>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */
jQuery.noConflict();
jQuery("#export__form").submit(function(e) {
		e.preventDefault();
		var toexport = '';
		var iocTimer;
		jQuery("input:checked[name='toexport']").each(function(key, value) {
		  toexport = toexport + jQuery(this).attr('value') + ',';
		});
		jQuery.ajax({
          	url: jQuery(this).attr("action"),
          	global: false,
            beforeSend: function(){
            		time = 0;
            		countTime = (function() {
            			iocTimer = setTimeout ( "countTime()", 1000 );
	        			time += 1;
	        			jQuery("#timer").html(time+' seg.');
	        		});
            		iocTimer = setTimeout ( "countTime()", 1000);
 					jQuery("#exportacio").html('<img src="lib/plugins/iocexportl/templates/loader.gif" width="220" height="19" alt="Generant arxiu"/>').show();
 					jQuery("#exportacio").append('<span><strong id="timer">0 seg.</strong></span>');
					jQuery("#id_submit").attr("disabled", "disabled");
					jQuery("#export__form input[type='radio']").attr("disabled", "disabled");
            },
          	type: "POST",
          	data: "id="+ jQuery("input[name='pageid']").val() +"& mode="+ jQuery("input[name='mode']:checked").val() + "&ioclanguage="+ jQuery("input[name='ioclanguage']").val()
          	+ "&toexport="+ toexport,
          	dataType: "json",
          	async: true,
          	success: function(data, textStatus, xhr){
                    if(jQuery.isArray(data)){
                	if(data[0] == 'pdf'){
                   		jQuery("#exportacio").hide().fadeOut("slow").html('<a class="media mediafile mf_pdf" href="'+data[1]+'">'+data[2]+'</a>'+' <strong>|</strong> Pàgines: ' + data[4]+ ' <strong>|</strong> Mida: ' + data[3] + ' <strong>|</strong> Temps emprat: ' + data[5] + ' segons').fadeIn("slow");
            		}else if(data[0] == 'zip'){
                           jQuery("#exportacio").hide().fadeOut("slow").html('<a class="media mediafile mf_zip" href="'+data[1]+'">'+data[2]+'</a>'+' <strong>|</strong> Mida: ' +data[3]+ ' <strong>|</strong> Temps emprat: ' + data[4] + ' segons').fadeIn("slow");
            		}else{
            				jQuery("#exportacio").hide().fadeOut("slow").html('<strong>'+data[5]+'</strong><a class="media mediafile mf_txt" href="'+data[1]+'">'+data[2]+'</a>'+' <strong>|</strong> Mida: '+ data[3]+ ' <strong>|</strong> Temps emprat: ' + data[4] + ' segons').fadeIn("slow");
            		}
                    }else{
                                    jQuery("#exportacio").hide().fadeOut("slow").html('<strong>'+data+'</strong>').fadeIn("slow");
                    }
                    jQuery("#id_submit").removeAttr("disabled");
                    jQuery("#export__form input[type='radio']").removeAttr("disabled");
                    clearTimeout ( iocTimer );
      		},
			error: function(xhr, textStatus, errorThrown){
                alert(xhr.responseText);
            	jQuery("#exportacio").html('<strong>ERROR!</strong>');
            	jQuery("#id_submit").removeAttr("disabled");
            	jQuery("#export__form input[type='radio']").removeAttr("disabled");
            	clearTimeout ( iocTimer );
      		}
        });
		return false;
	});
