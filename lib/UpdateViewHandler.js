require([
        "ioc/wiki30/dispatcherSingleton",
        "ioc/wiki30/UpdateViewHandler",
        "dojo/domReady!"
    ], function (wikiIocDispatcher, UpdateViewHandler) {
        var updateHandler = new UpdateViewHandler();

        updateHandler.update = function () {
            var disp = wikiIocDispatcher;

            disp.changeWidgetProperty('exportPdf', "visible", false);
            disp.changeWidgetProperty('exportHtml', "visible", false);
            disp.changeWidgetProperty('exportOnePdf', "visible", false);

            var isButtonVisible =  false;
            if (disp.getGlobalState().login) {
                if (Object.keys(disp.getGlobalState().permissions).length>0) {
                    isButtonVisible = (disp.getGlobalState().permissions['isadmin'] 
                                            | disp.getGlobalState().permissions['ismanager']
                                            | disp.getGlobalState().permissions['canExport']);
                }
            }
            if (disp.getGlobalState().currentTabId) {
                var page = disp.getGlobalState().pages[disp.getGlobalState().currentTabId];
                if(page.type === 'exportPdf' && page.action === 'view') {
                    disp.changeWidgetProperty('exportPdf', "visible", isButtonVisible && true);
                } else if(page.type === 'exportHtml' && page.action === 'view') {
                    disp.changeWidgetProperty('exportHtml', "visible", isButtonVisible && true);
                } else if(page.type === 'exportOnePdf' && page.action === 'view') {
                    disp.changeWidgetProperty('exportHtml', "visible", isButtonVisible && true);
                }
            }
        };
        wikiIocDispatcher.addUpdateView(updateHandler);
});