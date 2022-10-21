require([
    "ioc/wiki30/dispatcherSingleton",
    "ioc/wiki30/UpdateViewHandler",
    "dojo/domReady!"
], function (getDispatcher, UpdateViewHandler) {

    var wikiIocDispatcher = getDispatcher();
    var updateHandler = new UpdateViewHandler();

    updateHandler.update = function () {
        var disp = wikiIocDispatcher;
        var globalState = disp.getGlobalState();

        disp.initUpdateWidgetProperty('exportPdf', "visible", false);
        disp.initUpdateWidgetProperty('exportHtml', "visible", false);
        disp.initUpdateWidgetProperty('exportOnePdf', "visible", false);

        var perm = 0;
        var id = globalState.currentTabId;
        if (id) {
            var content = globalState.getContent(id);
            if (content && content.perm) {
                perm = content.perm;
            }
        }
        var isButtonVisible = false;
        if (globalState.login) {
            if (Object.keys(globalState.permissions).length > 0) {
                isButtonVisible = (globalState.permissions['isadmin']
                                    | globalState.permissions['ismanager']
                                    | (globalState.permissions['canExport'] && globalState.permissions['perm'] > 4) /*este no se usa*/
                                    | (globalState.permissions['ismanualsfp'] && perm > 4));
            }
        }
        //[NOTA:Rafa] globalState.permissions['perm'] y globalState.getContent(id).perm son iguales
        
        if (globalState.currentTabId) {
            var page = globalState.getContent(globalState.currentTabId);
            if (page.action === 'view') {
                if (page.exportableType === 'exportPdf' | page.exportableType === 'exportHtml' | page.exportableType === 'exportOnePdf') {
                    disp.changeWidgetProperty(page.exportableType, "visible", isButtonVisible && true);
                }
            }
        }
    };
    
    wikiIocDispatcher.addUpdateView(updateHandler);

});
