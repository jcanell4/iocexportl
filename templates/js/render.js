define(function() {
	jQuery.expr[':'].parents = function(a,i,m){
	    return jQuery(a).closest(m[3]).length < 1;
	};

	var infoFigure = function(){
		jQuery('figure img').each(function(key, value){
			var img = jQuery(this);
			var width = img.width();
			var info = img.parents('figure').children().filter('figcaption');
			var foot = img.parents('.iocfigure').children().filter('.footfigure');
			info.css('width',width);
			if (img.closest('.iocexample').length === 0){
				info.css('max-width','75%');
				foot.css('max-width','75%');
			}
			foot.css('width',width);
		});
	};

	var infoTable = function(){
		jQuery('div.ioctable table, div.iocaccounting table').each(function(key, value){
			var table = jQuery(this);
			var width = table.width();
			var info = table.parents('.iocaccounting,.ioctable').children().filter('.titletable');
			var foot = table.parents('.iocaccounting,.ioctable').children().filter('.foottable');
			info.css('width',width);
			info.css('max-width','100%');
			foot.css('width',width);
			foot.css('max-width','100%');
		});
	};
	
	var thTable = function(){
		jQuery('div.ioctable table th').each(function(key, value){
			var th = jQuery(this);
			th.closest("tr").addClass("borderth");
		});
	};
	
	var previewImage = function(img){
		var src = $(img).attr('src');
		$('#back_preview').removeClass("hidden");
		$('#preview .prevcontent').empty();
		$('#preview .prevcontent').html('<span class="closepreview"></span><img src="'+ src +'" alt="Image preview" />')
					 .closest('#preview')
					 	.removeClass()
					 	.addClass('zoomout')
					 .end()
					 .imagesLoaded(function(){
						var $img = $(this).find('img'); 
						height = $img.height();
						width = $img.width();
						if (height > $(window).height()){
							height = $(window).height() - 50;
							$(this).css('height', height);
							$img.css('max-height', height);
						}
						if (width > $(window).width() - 50){
							width = $(window).width() - 50;
							$(this).css('width', width);
							$img.css('max-width', width);
						}
						$(this).find('.closepreview').css('margin-right',-($img.width()/2)-16);
						$(this).css('margin-top',-(height/2));
		});
	};
	
	return {"infoTable":infoTable,
		    "infoFigure":infoFigure,
		    "thTable":thTable,
		    "previewImage":previewImage};
});
