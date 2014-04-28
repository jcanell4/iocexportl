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
