require([
        "dijit/registry",
        "dojo/dom",
        "dojo/dom-form",
        "dojo/on",
        "dojo/domReady!"
    ], function (registry, dom, domForm, on) {
        var exportPdfButton = registry.byId('exportPdf');
        var exportHtmlButton = registry.byId('exportHtml');
        var exportOnePdfButton = registry.byId('exportOnePdf');
        
        var fOnClick=function(){
             var id = this.dispatcher.getGlobalState().getCurrentId();
            registry.byId("zonaMetaInfo").selectChild(id + "_iocexportl") //TO DO [Josep] canviar per una constant
            this.setStandbyId(id + "_iocexportl");
        };

        
        var fGetQuery=function(){
            var id = this.dispatcher.getGlobalState().getCurrentId();
            var aux = [];
            var nodeForm = dom.byId("export__form_" + id);
            for(var i=0; i<nodeForm.elements.length; i++){
                aux[i] = nodeForm.elements[i].disabled;
                if(aux[i]){
                    nodeForm.elements[i].disabled=false;
                }
            }
            var form = domForm.toObject(nodeForm);
            var ret = "id="+ form.pageid + "&mode="+ form.mode             
            +  "&ioclanguage="+ form.ioclanguage + "&toexport="+ form.toexport;
        
            for(var i=0; i<nodeForm.elements.length; i++){
                nodeForm.elements[i].disabled = aux[i];
            }
            
            return ret;
        };
        if(exportPdfButton){
            exportPdfButton.getQuery=fGetQuery;
            exportPdfButton.set("hasTimer", true);
            on(exportPdfButton, "click", fOnClick);
        }
        if(exportHtmlButton){
            exportHtmlButton.getQuery=fGetQuery;       
            exportHtmlButton.set("hasTimer", true);
            on(exportHtmlButton, "click", fOnClick);
        }
        if(exportOnePdfButton){
            exportOnePdfButton.getQuery=fGetQuery;
            exportOnePdfButton.set("hasTimer", true);
            on(exportOnePdfButton, "click", fOnClick);
        }
});


