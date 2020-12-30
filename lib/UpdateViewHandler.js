require([
        "ioc/wiki30/dispatcherSingleton",
        "ioc/wiki30/UpdateViewHandler",
        "dojo/domReady!"
    ], function (getDispatcher, UpdateViewHandler) {
        var wikiIocDispatcher = getDispatcher();

        var updateHandler = new UpdateViewHandler();

        updateHandler.update = function () {
            var disp = wikiIocDispatcher;

            disp.initUpdateWidgetProperty('exportPdf', "visible", false);
            disp.initUpdateWidgetProperty('exportHtml', "visible", false);
            disp.initUpdateWidgetProperty('exportOnePdf', "visible", false);

            var isButtonVisible =  false;
            if (disp.getGlobalState().login) {
                if (Object.keys(disp.getGlobalState().permissions).length>0) {
                    isButtonVisible = (disp.getGlobalState().permissions['isadmin'] 
                                            | disp.getGlobalState().permissions['ismanager']
                                            | disp.getGlobalState().permissions['canExport']);
                }
            }
            if (disp.getGlobalState().currentTabId) {
                var page = disp.getGlobalState().getContent(disp.getGlobalState().currentTabId);
                if(page.exportableType === 'exportPdf' 
                                && page.action === 'view') {
                    disp.changeWidgetProperty('exportPdf', 
                                                "visible", 
                                                isButtonVisible && true);
                } else if(page.exportableType === 'exportHtml' 
                                && page.action === 'view') {
                    disp.changeWidgetProperty('exportHtml', 
                                                "visible", 
                                                isButtonVisible && true);
                } else if(page.exportableType === 'exportOnePdf' 
                                && page.action === 'view') {
                    disp.changeWidgetProperty('exportOnePdf', 
                                                "visible", 
                                                isButtonVisible && true);
                }
            }
        };
        wikiIocDispatcher.addUpdateView(updateHandler);
});