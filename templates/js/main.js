require(["jquery.min","jquery-ui.min","jquery.imagesloaded","render","functions","doctools","quiz","searchtools", "mediaScript"], function(jQuery,jUi,jIl,render,func,Highlight,quiz,Search, mediaScript){
	$("article").imagesLoaded(function(){
		render.infoTable();
		render.infoFigure();
	});
	Highlight();
	if (func.ispageSearch()){
		Search.init();
	}


});

jQuery(document).ready(function () {
	// $(".iocnote, .iocreference, .ioccopytoclipboard, .ioctext, .iocfigurec").toBColumn({debug: true, forceIcons: true});
	$(".iocnote, .iocreference, .ioccopytoclipboard, .ioctext, .iocfigurec").toBColumn();
});