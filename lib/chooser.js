/**
 * Lib wiki activity chooser
 * 
 * @author     Marc Català <mcatala@ioc.cat>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */
jQuery.noConflict();
jQuery(document).ready(function() {
	var count = 0;
  	var patt=/htmlindex/g;
  	//Check whether file action has done before 
  	var html = patt.test(location.href);
	jQuery('#content ul > li > div > a[class=wikilink1]').each(function(key, value) {
  		var id = jQuery(this).attr('title').replace(/:/g,'_');
  		var disabled = (!html && count < 2)?'disabled="disabled"':'';
		var tag = jQuery('<input type="checkbox" id="'+id+'" name="toexport" checked="checked" value="'+this.title+'" '+disabled+'"/>');
		tag.prependTo(jQuery(this).parent());
		count += 1;
	});
});