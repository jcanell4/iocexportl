define (["render"],function(render){
    var disableshortcuts = false;
    var ltoc = parseInt($("#toc").css('left'),10);
    var lbridge = parseInt($("#bridge").css('left'),10);
    var lfavcount = parseInt($("#favcounter").css('left'),10);
    var lfavorites = parseInt($("#favorites").css('left'),10);
    var topheader = parseInt($("#header").css('height'),10);
    var paddingarticle = parseInt($("article").css('padding-bottom'),10);
    var defaultsettings = '{"menu":[{"mvisible":1}],"toc":[{"tvisible":0}],"settings":[{"fontsize":2,"contrast":0,"alignment":0,"hyphen":0,"width":2,"mimages":1,"scontent":1}]}';
    var defaultbookmarks = '{"fav":[{"urls":"0"}]}';
    var defaultbookquizzes = '{"quiz":[{"urls":"0"}]}';
    var showtooltips = false;
    var cookiesufix = '@IOCCOOKIENAME@';
    var cookiegeneral = 'ioc_settings';//Always same settings for all materials
    var cookiefavorites = 'ioc_'+cookiesufix+'_bookmarks';
    var cookiequizzes = 'ioc_'+cookiesufix+'_quizzes';
	
    $('#menu li').click(function(e) {
        setmenu(this);
    });

    $('#menu li div img').hover(
        function(e){
            if(showtooltips){
                showhelp($(this).closest("li"),true,false);
            }
        },
        function(e){
            if(showtooltips){
                showhelp($(this).closest("li"),false,false);
            }
        }
    );

    $('#sidebar-hide img').hover(
        function(e){
            if(showtooltips){
                showhelp($(this).parent(),true,false);
            }
        },
        function(e){
            if(showtooltips){
                showhelp($(this).parent(),false,false);
            }
        }
    );

    $('#search > form > input').hover(
        function(e){
            if(showtooltips){
                showhelp($(this).closest("div"),true,false);
            }
        },
        function(e){
            if(showtooltips){
                showhelp($(this).closest("div"),false,false);
            }
        }
    );

    $('#favcounter span').hover(
        function(e){
            if(showtooltips){
                showhelp($(this).parent(),true,false);
            }
        },
        function(e){
            if(showtooltips){
                showhelp($(this).parent(),false,false);
            }
        }
    );

    $('#content').on("click", function(e) {
            setmenu(null);
            $("#help").addClass("hidden");
    });

    $('#sidebar-hide').click(function(e) {
        e.preventDefault();
        $("#aside").animate({
                left: parseInt($("#aside").css('left'),10) == 0 ?
                -$("#aside").outerWidth() :
                0
        });
        $("#toc").animate({
                left: ((parseInt($("#toc").css('left'),10) == ltoc) && ($("#toc").css('display') !== 'none')) ?
                -$("#aside").outerWidth()-$("#toc").outerWidth():
                ltoc
        });
        $("#settings").animate({
                left: ((parseInt($("#settings").css('left'),10) == ltoc) && ($("#settings").css('display') !== 'none')) ?
                -$("#aside").outerWidth()-$("#settings").outerWidth():
                ltoc
        });

        $("#bridge").animate({
                left: ((parseInt($("#bridge").css('left'),10) == lbridge) && ($("#bridge").css('display') !== 'none')) ?
                -$("#aside").outerWidth()-$("#bridge").outerWidth():
                lbridge
        });


        $("#favcounter").animate({
                left: ((parseInt($("#favcounter").css('left'),10) == lfavcount) && ($("#favcounter").css('display') !== 'none')) ?
                -$("#aside").outerWidth()-$("#favcounter").outerWidth():
                lfavcount
        });

        $("#favorites").animate({
                left: ((parseInt($("#favorites").css('left'),10) == lfavorites) && ($("#favorites").css('display') !== 'none')) ?
                -$("#aside").outerWidth()-$("#favorites").outerWidth():
                lfavorites
        });
        if (!$("#favorites").hasClass("hidden")){
                $("#bridge").toggleClass("hidden");
        }

        setbackground(($("body").css('background-position') !== '0px 0px'));
        var info=getcookie(cookiegeneral);
        var patt=/"mvisible":0/g;
        if (patt.test(info)){
            setCookieProperty(cookiegeneral,'mvisible', 1);
        }else{
            setCookieProperty(cookiegeneral,'mvisible', 0);
        }
    });

    //Contrast
    $('#style-newspaper').click(function() {
        setContrast(0);
        setCookieProperty(cookiegeneral,'contrast', 0);
    });
    
    $('#style-novel').click(function() {
    	setContrast(1);
        setCookieProperty(cookiegeneral,'contrast', 1);
    });
    
    $('#style-ebook').click(function() {
    	setContrast(2);
        setCookieProperty(cookiegeneral,'contrast', 2);
    });

    $('#style-inverse').click(function() {
    	setContrast(3);
        setCookieProperty(cookiegeneral,'contrast', 3);
    });

    $('#style-athelas').click(function() {
    	setContrast(4);
        setCookieProperty(cookiegeneral,'contrast', 4);
    });
    
    //Alignment
    $('#text-alignment').click(function() {
        if ($(this).attr("checked")){
                setAlignment(1);
                setCookieProperty(cookiegeneral,'alignment', 1);
        }else{
        setAlignment(0);
                setCookieProperty(cookiegeneral,'alignment', 0);
        }
    });
    
    //Hyphenation
    $('#text-hyphen').click(function() {
        if ($(this).attr("checked")){
        setHyphenation(0);
                setCookieProperty(cookiegeneral,'hyphen', 0);
        }else{
        setHyphenation(1);
                setCookieProperty(cookiegeneral,'hyphen', 1);
        }
    });
    
    
    //Show or hide secondary content
    $('#main-images').click(function() {
        if ($(this).attr("checked")){
                setMainFig(1);
                render.infoFigure();
                setCookieProperty(cookiegeneral,'mimages', 1);
        }else{
                setMainFig(0);
                setCookieProperty(cookiegeneral,'mimages', 0);
        }
    });
   
    
    //Show or hide secondary content
    $('#secondary-content').click(function() {
        if ($(this).attr("checked")){
        setSecContent(1);
                setCookieProperty(cookiegeneral,'scontent', 1);
        }else{
                setSecContent(0);
                setCookieProperty(cookiegeneral,'scontent', 0);
        }
    });
    
    
    $('#upbutton').click(function() {
        $("html,body").animate({
                scrollTop: 0
        }, 500);
    });
    
    $('#navmenu').click(function() {
        setCookieProperty(cookiegeneral,'selected','null');
    });

    $('#favcounter').click(function() {
        var info = getcookie(cookiefavorites);
        var object = $.parseJSON(info);
        if ($("#favorites").hasClass('hidden')){
                setFavUrls(object.fav[0].urls);
        }else{
                $("#favorites").addClass('hidden');
                $("#bridge").addClass('hidden');
        }
    });

   
    $("#frmsearch input[name='q']").on('keypress', function (event){
            if (event.which === 13){
                    $("#frmsearch").submit();
            }
    });

    $("#frmsearch").focusin(function(){
            disableshortcuts = true;
    });

    $("#frmsearch").focusout(function(){
            disableshortcuts = false;
    });

    $(".mediavideo").focusin(function(){
            disableshortcuts = true;
    });

    $(".mediavideo").focusout(function(){
            disableshortcuts = false;
    });

    $(window).on('keyup', function (event){
            if(ispageIndex() || ispageSearch()){
                    return;
            }
            //ESC
            if (event.keyCode === 27){
                    event.preventDefault();
                    $("#help, #back_preview, #preview").addClass("hidden");
                    setmenu(null);
            }
    });

    $(window).on('keypress', function (event){
            if(ispageIndex() || ispageSearch()){
                    return;
            }
            if (!disableshortcuts){
                    switch(event.which){
                            //?
                            case 63:$("#help").toggleClass("hidden");
                                     break;
                            //b
                            case 98:var top;
                                            top = $(".footer").offset().top;
                                            $(window).scrollTop($(window).scrollTop()+top);
                                     break;
                            //g
                            case 103:setmenu($("#menu li[name='toc']"));
                                             break;
                            //h
                            case 104:url = $("#prevpage a").attr("href");
                                             if (url){
                                                     document.location.href = url;
                                             }
                                             break;
                            //i
                            case 105:document.location.href = $("#navmenu ul > li > a").attr("href");
                                             break;
                            //j
                            case 106:$(window).scrollTop($(window).scrollTop()+100)
                                             break;
                            //k
                            case 107:$(window).scrollTop($(window).scrollTop()-100);
                                             break;
                            //l
                            case 108:url = $("#nextpage a").attr("href");
                                             if (url){
                                                     document.location.href = url;
                                             }
                                             break;
                            //o
                            case 111:setmenu($("#menu li[name='settings']"));
                                             break;
                            //p
                            case 112:setmenu($("#menu li[name='printer']"));
                                             break;
                            //s
                            case 115:setmenu($("#menu li[name='favorites']"));
                                             break;
                            //t
                            case 116:$(window).scrollTop(0);
                                             break;
                    }
            }
    });

    var setFontsize = (function (info){
            var options=new Array("text-tiny","text-small","text-normal","text-big","text-huge");
            $("article").addClass(options[info]);
            $(".pnpage").addClass(options[info]);
            for (i=0;i<options.length;i++){
                    if (i==info){
                            continue;
                    }
                    $("article").removeClass(options[i]);
                    $(".pnpage").removeClass(options[i]);
            }
            if (info === options.length-1){
                    $("article").css("padding-bottom","8em");
            }else{
                    $("article").css("padding-bottom",paddingarticle);
            }
            render.infoTable();
            render.infoFigure();
            setCookieProperty(cookiegeneral,'fontsize', info);
    });

    var setContrast = (function (info){
            var options=new Array("style-newspaper","style-novel","style-ebook","style-inverse","style-athelas");
            $('#'+options[info]).addClass('active');
            $("body").addClass(options[info]);
            for (i=0;i<options.length;i++){
                    if (i==info){
                            continue;
                    }
                    $('#'+options[i]).removeClass('active');
                    $("body").removeClass(options[i]);
            }
    });

    var setAlignment = (function (info){
            var options=new Array("text-left","text-justify");
            other = (info==0)?1:0;
            $("article").addClass(options[info]);
    $("article").removeClass(options[other]);
    });

    var setHyphenation = (function (info){
            var state = (info==0)?false:true;
            Hyphenator.doHyphenation = state;
            Hyphenator.toggleHyphenation();
    });

    var setMainFig = (function (show){
            if (show == 1){
                    $("article .iocfigure").removeClass("hidden");
            }else{
                    $("article .iocfigure").addClass("hidden");
            }
    });

    var setSecContent = (function (show){
            var elements = new Array("iocfigurec", "iocnote", "ioctext", "iocreference");
            for (i=0;i<elements.length;i++){
                    if (show == 1){
                            $("article ."+elements[i]).removeClass("hidden");
                    }else{
                            $("article ."+elements[i]).addClass("hidden");
                    }
            }
    });

    var setArticleWidth = (function (info){
            var options=new Array("x-narrow","narrow","medium","wide","x-wide","x-extra-wide");
            $("article").addClass(options[info]);
            for (i=0;i<options.length;i++){
                    if (i==info){
                            continue;
                    }
                    $("article").removeClass(options[i]);
            }
            var width = parseInt($("article").outerWidth(true)) + 20;
            $(document).ready(setpnpage());
            render.infoTable();
            render.infoFigure();
            setCookieProperty(cookiegeneral,'width', info);
    });

    var setpnpage = (function (){
        var width = parseInt($("article").outerWidth(true)) + 20;
        if (isIE() || isChrome() || isNaN(width)){
                width = parseInt($("article").css('width')) + 80;
        }
        $(".pnpage").css({"width":width, "margin-left":-(width/2)});
    });

    var setWidthSlider = (function (value){
            $("#slider-width").slider("option", "value", value);
    });

    var setFontSlider = (function (value){
            $("#slider-font").slider("option", "value", value);
    });

    var setCookieProperty = (function (name, n, value){
            var info=getcookie(name);
            if (info){
                    if (typeof value == 'number'){
                            var patt = new RegExp("\""+n+"\":\\d+", 'g');
                            info=info.replace(patt, "\""+n+"\":"+value);
                    }else{
                            var patt = new RegExp("\""+n+"\":\".*?\"", 'g');
                            info=info.replace(patt, "\""+n+"\":\""+value+"\"");
                    }
                    setcookie(name,info);
            }
    });

    var settings = (function (info){
        if (ispageIndex()){
                $("body").removeClass().addClass('index');
        }else{
                setContrast(info.settings[0]['contrast']);
                setFontsize(info.settings[0]['fontsize']);
                setFontSlider(info.settings[0]['fontsize']);
                setAlignment(info.settings[0]['alignment']);
                setHyphenation(info.settings[0]['hyphen']);
                setArticleWidth(info.settings[0]['width']);
                setWidthSlider(info.settings[0]['width']);
                setMainFig(info.settings[0]['mimages']);
                setSecContent(info.settings[0]['scontent']);
                setCheckboxes(info);
                render.thTable();
                postohashword();
        }
    });

    var setCheckboxes = (function (info){
            if(info.settings[0]['alignment']==1){
                    $("#text-alignment").attr("checked","checked");
            }else{
                    $("#text-alignment").removeAttr("checked");
            }
            if(info.settings[0]['hyphen']==0){
                    $("#text-hyphen").attr("checked","checked");
            }else{
                    $("#text-hyphen").removeAttr("checked");
            }
            if(info.settings[0]['mimages']==1){
                    $("#main-images").attr("checked","checked");
            }else{
                    $("#main-images").removeAttr("checked");
            }
            if(info.settings[0]['scontent']==1){
                    $("#secondary-content").attr("checked","checked");
            }else{
                    $("#secondary-content").removeAttr("checked");
            }
    });

    var sidemenu = (function (info){
        if (!ispageIndex()){
            $("#toc").css('left', ltoc);
            $("#settings").css('left', ltoc);
            if (info.menu[0]['mvisible']==1){
                    $("#aside").css('left','0px');
                    setbackground(true);
            }else{
                    $("#aside").css('left',-$("#aside").outerWidth());
                    $("#favcounter").css('left',-$("#aside").outerWidth()-$("#favcounter").outerWidth());
                    setbackground(false);
            }
            info = getcookie(cookiefavorites);
            if (info){
                    var object = $.parseJSON(info);
                    setFavCounter(object.fav[0]['urls']);
            }
        }
    });

    var setmenu = (function (obj){
        hideMenuOptions();
        if (!obj){
            $("#menu ul").children().each(function(i){
                $(this).removeClass('menuselected');
            });
            return;
        }
        var show = true;
        var type = $(obj).attr("name");
        $(obj).closest('ul').children().each(function(i){
            if (type === $(this).attr("name") && $(obj).hasClass('menuselected')){show=false;}
            $(this).removeClass('menuselected');
        });
        if (show){
            if (type === 'toc'){
                $(obj).addClass('menuselected');
                $('#toc').removeClass('hidden');
                $("#bridge").removeClass('hidden');
                $("#bridge").css('top',$("#toc").css('top'));
                enablemenuoption();
            }else{
                if(type === 'settings'){
                   $(obj).addClass('menuselected');
                   $('#settings').removeClass('hidden');
                   $("#bridge").removeClass('hidden');
                   $("#bridge").css('top',$("#settings").css('top'));
                }else{
                    if(type === 'printer'){
                        window.print();
                    }else{
                        if(type === 'favorites'){
                            var url = $(obj).find('div>img').attr('src');
                            if (/favorites/.test(url)){
                                url = url.replace(/favorites/, 'fav_ok');
                            }else{
                                url = url.replace(/fav_ok/, 'favorites');
                            }
                            $(obj).find('div>img').attr('src', url);
                            editFavorite(document.location.pathname,false);
                        }else{
                            if(type === 'help_icon'){
                                var url = $(obj).find('div>img').attr('src');
                                if (/help_icon\./.test(url)){
                                    url = url.replace(/help_icon/, 'help_icon_active');
                                    showtooltips = true;
                                }else{
                                    url = url.replace(/help_icon_active/, 'help_icon');
                                    hidetooltips();
                                    showtooltips = false;
                                }
                                $(obj).find('div>img').attr('src', url);
                            }
                        }
                    }
                }
            }
        }
    });

    var calpostooltips = (function (){
            $("#menu li").each(function(){
                    var type = $(this).attr("name");
                    var tooltip = $('#help-'+type);
                    var item_pos = $(this).offset();
                    tooltip.css({'visibility':'hidden'}).removeClass('hidden');
                    tooltip.css({'top':(item_pos.top-(tooltip.outerHeight(true)/2) + 18),
                                             'visibility':'visible'})
                                            .addClass('hidden');
            });
            calposfavcountooltip();
            var item = $("#sidebar-hide").offset();
            var tooltip = $("#help-sidebar-hide");
            tooltip.css({'visibility':'hidden'}).removeClass('hidden');
            tooltip.css({top:(item.top-(tooltip.outerHeight(true)/2) + 12),
                                     'visibility':'visible'})
                                    .addClass('hidden');
            item = $("#search input[type='text']");
            var item_pos = item.offset();
            tooltip = $("#help-search");
            tooltip.css({'visibility':'hidden'}).removeClass('hidden');
            tooltip.css({top:item_pos.top + (tooltip.outerHeight(true)/2) + 15,
                                 left:(item_pos.left + (item.outerWidth(true)/2) - (tooltip.outerWidth(true)/2) - 18),
                                 'visibility':'visible'})
                                            .addClass('hidden');
            tooltip = $("#help-search");
            tooltip.css({'visibility':'hidden'}).removeClass('hidden');
            tooltip.css({top:item_pos.top + (tooltip.outerHeight(true)/2) + 15,
                                 left:(item_pos.left + (item.outerWidth(true)/2) - (tooltip.outerWidth(true)/2) - 18),
                                 'visibility':'visible'})
                                            .addClass('hidden');
    });

    var calposfavcountooltip = (function (){
            var item = $("#favcounter").offset();
            var tooltip = $("#help-favcounter");
            tooltip.css({'visibility':'hidden'}).removeClass('hidden');
            tooltip.css({'top':(item.top-(tooltip.outerHeight(true)/2) + 10),
                                     'visibility':'visible'})
                                    .addClass('hidden');
    });

    var hidetooltips = (function (){
            $("#help-tooltips > div").each(function(){
                    $(this).addClass("hidden");
            });
    });


    var showhelp = (function (obj, show, header){
            var type = (header)?'header':$(obj).attr("name");
            var tooltip = $('#help-'+type);
            if(show){
                    tooltip.removeClass('hidden');
                    tooltip.fadeTo("fast", 0.8);
            }else{
                    tooltip.fadeTo("fast", 0, function(){tooltip.addClass('hidden');});
            }

            if (header && show){
                    var item_pos = $(obj).offset();
                    tooltip.css({top:item_pos.top - (tooltip.outerHeight()/2) + 15,
                                            left:item_pos.left + $(obj).width() + 40
                    });
            }
    });

    var enablemenuoption = (function(){
        var url = document.location.pathname;
        var dir = "WebContent/";
        //Comprovar si estem en les pàgines d'introduccio
        var patt = new RegExp(dir,'g');
        if (!patt.test(url)){
            var node = url.match(/\w+(?=\.html)/);
        }else{
            url = url.slice(url.indexOf(dir)+dir.length,url.length);
            var elements = url.split("/");
            var node = '';
            var parent_node = '';
            for (i=0;i<elements.length;i++) {
                if (elements[i].indexOf('html')==-1){
                    parent_node+=elements[i];
                }
                node+=elements[i];
            }
            //Remove html extension
            node = node.replace(/\.html/,'');

            if (parent_node != ''){
                $('#'+parent_node).parent().each(function(key, value) {
                    $(this).show();
                });
                $('#'+parent_node+"> ul").show();
                $('#'+parent_node+"> p > .buttonexp").addClass("tocdown");
            }
        }
        if (node != ''){
            $('#'+node).addClass("optselected");
        }
    });

    var setbackground = (function(show){
            if(!show){
                    $("body").css("background-position","-60px 0");
            }else{
                    $("body").css("background-position","0 0");
            }
    });

    var ispageIndex = (function (){
            var url = document.location.pathname;
            return /index\.html|.*?\/(?!.*?\.html$)/.test(url);
    });

    var ispageSearch = (function (){
            var url = document.location.pathname;
            return /search\.html/.test(url);
    });

    var ispageExercise = (function (){
            var url = document.location.pathname;
            return /activitats\.html|exercicis\.html/.test(url);
    });

    var ispagenoHeader = (function (){
            var url = document.location.pathname;
            var patt = new RegExp("/a\\d+/","g")
            return !patt.test(url);
    });

    var islocalChrome = (function (){
            return (/Chrome/.test(navigator.userAgent) && /file/.test(document.location.protocol));
    });

    var isChrome = (function (){
            return (/Chrome/.test(navigator.userAgent));
    });

    var isIE = (function (){
            return (/MSIE/.test(navigator.userAgent));
    });

    var postohashword = (function (){
            var url = document.location.hash;
            if (url){
                    url = url.replace(/#/,'');
                    var offset = $("a[id='"+url+"']").offset();
                    if (offset !== null){
                            $(window).scrollTop(offset.top-110);
                    }
            }
    });

    var postosearchword = (function (){
            var url = document.location.search;
            if (/highlight/.test(url)){
                            var offset = $(".highlight:first").offset();
                            if (offset !== null){
                                    $(window).scrollTop(offset.top-80);
                            }
            }
    });

    var indexToc = (function(show){
            if (show){
                    var mtop = parseInt($(".meta").outerHeight(true))+parseInt($(".meta img").outerHeight(true));
                    $(".meta").css('margin-top',-mtop);
                    $(".metainfobc").addClass('hidden');
                    $(".metainfobr").addClass('hidden');
                    $("#header .head").css('margin-top', '0px');
                    $("#header .headdocument").removeClass('hidden');
                    $(".headtoc h1 img").hide();
                    $(".headtoc").css('margin-top', -$("#content").outerHeight(true)+$(".headtoc h1").outerHeight(true)-40);
                    $(".headtoc").css('min-height','100%');
                    $(".headtoc").removeClass("headtocdown").addClass("headtocup");
                    $(".headtoc h1").removeClass("hover");
                    $(".indextoc").show();
                    $("#button_start").show();
            }else{
                    $(".meta").css('margin-top','5px');
                    $(".metainfobc").removeClass('hidden');
                    $(".metainfobr").removeClass('hidden');
                $(".metainfobc").css('margin-top',$(window).height()-$(".headtoc h1").outerHeight(true)-$(".metainfobc").outerHeight()-5);
                $(".metainfobr").css('margin-top',$(window).height()-$(".headtoc h1").outerHeight(true)-$(".metainfobr").outerHeight()-5);
                var htop = parseInt($(window).height()-$(".headtoc h1").outerHeight(true)-40);
                if (htop < 0){
                    htop = 0;
                }
                    $("#header .head").css('margin-top', htop);
                    $("#header .headdocument").addClass('hidden');
                    $(".headtoc h1 img").show();
                    $(".headtoc").css('margin-top', -$(".headtoc h1").outerHeight(true));
                    $(".headtoc").css('min-height','0');
                    $(".indextoc").hide();
                    $(".headtoc h1").addClass("hover");
                    $("#button_start").hide();
            }
    });

    //Add or remove a url inside cookie
    var editFavorite = (function(url,idheader){
            var info = getcookie(cookiefavorites);
            if (info){
                    var object = $.parseJSON(info);
                    var urls = [];
                    //escape parentheses
                    var escapedurl = url.replace(/\(|\)/g,'\\$&');
                    var patt = new RegExp(";;"+escapedurl+"\\|.*?(?=$|;;)", 'g');
                    if(patt.test(object.fav[0]['urls'])){
                            urls = object.fav[0]['urls'].replace(patt, "");
                            setCookieProperty(cookiefavorites,'urls', urls);
                            setFavCounter(urls);
                    }else{
                            var title;
                            if (idheader){
                                    title = $("h2,h3,h4").children("a").filter("a[id="+idheader+"]").parent().text();
                            }else{
                                    title = $("h1").text();
                                    title = title.replace(/ /,"");
                            }
                            if (title == ""){
                                    title = url;
                            }
                            url = object.fav[0]['urls'] + ";;" + url + "|" + title;
                            setCookieProperty(cookiefavorites,'urls', url);
                            setFavCounter(url);
                    }
            }
    });

    var setFavCounter = (function (urls){
            var patt = new RegExp(";;", 'g');
            var result = urls.match(patt);
            if(result && result.length > 0){
                    $("#favcounter").removeClass("hidden");
                    $("#favcounter span").html(result.length);
                    if (result.length === 1){
                            calposfavcountooltip();
                    }
            }else{
                    $("#favcounter").addClass("hidden");
            }
    });

    var setFavButton = (function (info){
            if (info){
                    var url = document.location.pathname;
                    var patt = new RegExp(";;"+url+"\\|.*?(?=$|;;)", 'g');
                    if(patt.test(info.fav[0]['urls'])){
                            var obj = $("#menu li[name=favorites]").find('div>img')
                            var src = $(obj).attr('src');
                            src = src.replace(/\w+(?=\.png)/, 'fav_ok');
                            $(obj).attr('src', src);
                    }
            }
    });

    var setFavHeaders = (function (info){
            if (info){
                    var url = document.location.pathname;
                    var patt;
                    $("h2,h3,h4").each(function(i){
                            patt = new RegExp(";;"+url+"#"+$(this).children("a").attr("id")+"\\|.*?(?=$|;;)", 'g');
                            if(patt.test(info.fav[0]['urls'])){
                                    $(this).children("span[name='star']").removeClass().addClass("starmarked").show();
                            }
                    });
            }
    });

    var setCheckExercises = (function (info){
            if (info){
                    var url = document.location.pathname;
                    var patt;
                    $("h2").each(function(i){
                            patt = new RegExp(";;"+url+"\\|"+$(this).children("a").attr("id"), 'g');
                            if(patt.test(info.quiz[0]['urls'])){
                                    $(this).children("span[name='check']").addClass("check").css('display','inline-block');
                            }
                    });
            }
    });

    var editCheckExercise = (function(url,idheader){
            var info = getcookie(cookiequizzes);
            if (info){
                    var object = $.parseJSON(info);
                    var urls = [];
                    var patt = new RegExp(";;"+url+"\\|"+idheader, 'g');
                    if(!patt.test(object.quiz[0]['urls'])){
                            url = object.quiz[0]['urls'] + ";;" + url + "|" + idheader;
                            $("h2 > a[id='"+idheader+"']").siblings("span[name='check']").addClass("check").css('display','inline-block');
                            setCookieProperty(cookiequizzes,'urls', url);
                    }
            }
    });

    var setFavUrls = (function (urls){
            var patt = new RegExp("[^;|]+\\|.*?(?=$|;;)", 'g');
            var result = urls.match(patt);
            hideMenuOptions();
            if(result && result.length > 0){
                    var pattu = new RegExp('u\\d+','g');
                    var patts = new RegExp('a\\d+','g');
                    var pattae = new RegExp('activitats(?=\.html)|exercicis(?=\.html)','g');
                    var unit = '';
                    var section = '';
                    var info = '';
                    var list = '<div class="menucontent">';
                    var type;
                    var style = '';
                    list += '<ul class="favlist">';
                    result.sort();
                    for (url in result){
                            style = 'favcontent';
                            data = result[url].split("|");
                            type = data[0].match(pattae);
                            if (type){
                                    if(type == 'activitats'){
                                            style = 'favactivity';
                                    }else{
                                            style = 'favexercise';
                                    }
                            }
                            unit = result[url].match(pattu);
                            unit = (unit)?unit + "<span></span>":'';
                            section = result[url].match(patts);
                            section = (section)?section+"<span></span>":''
                            info =  unit + section;
                            list += '<li class="'+style+'"><a href="'+data[0]+'">'+info+' '+data[1]+'</a></li>';
                    }
                    list += '</ul>';
                    list += '</div>';
                    $("#favorites").html(list);
                    $("#favorites").removeClass("hidden");
                    $("#bridge").removeClass('hidden').addClass("tinybridge");
                    $("#bridge").css('top',$("#favorites").css('top'));
            }
    });

    var setNumberHeader = (function (){
            var url = document.location.pathname;
            var patt = new RegExp('/a\\d+(?=/)','g');
            var result = url.match(patt);
            if (result){
                    patt = new RegExp('\\d+','g');
                    result = parseInt(result[0].match(patt));
                    $("article").css("counter-reset","counth1 "+ (result-1));
            }
    });

    var setNumFigRef = (function (){
            $("article .iocfigure > a").each(function(i){
                    $(".figref > a[href=\"#"+$(this).attr("name")+"\"]").append("."+(i+1));
            });
    });

    var setNumTabRef = (function (){
            $("article .ioctable .titletable > a, article .iocaccounting .titletable > a").each(function(i){
                    $(".tabref > a[href=\"#"+$(this).attr("name")+"\"]").append("."+(i+1));
            });
    });

    var hideMenuOptions = (function (){
            $('#toc').addClass('hidden');
            $('#settings').addClass('hidden');
            $("#favorites").addClass('hidden');
            $("#bridge").removeClass("tinybridge").addClass('hidden');
    });

    //Set params into our cookie
    var setcookie = (function(name, value){
            document.cookie = name+'=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
            document.cookie = name+"=" + escape(value) + "; path=/;";
    });

    //get params from our cookie
    var getcookie = (function(name){
            var i,x,y,ARRcookies=document.cookie.split(";");
            for (i=0;i<ARRcookies.length;i++)
            {
              x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
              y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
              x=x.replace(/^\s+|\s+$/g,"");
              if (x==name){
                    return unescape(y);
              }
            }
    });

    var get_params = (function(reset){
            var info = getcookie(cookiegeneral);
            if (info!=null && info!="" && !reset){
                    //Get and apply stored options
                    var object = $.parseJSON(info);
                    settings(object);
                    if(!ispageSearch()){
                            sidemenu(object);
                            if(ispageIndex()){
                                    indexToc(object.toc[0]['tvisible']==1);
                            }
                            info = getcookie(cookiefavorites);
                            if (info!=null && info!=""){
                                    object = $.parseJSON(info);
                                    setFavButton(object);
                                    setFavHeaders(object);
                            }else{
                                    setcookie(cookiefavorites,defaultbookmarks);
                            }
                            info = getcookie(cookiequizzes);
                            if (info!=null && info!=""){
                                    object = $.parseJSON(info);
                                    setCheckExercises(object);
                            }else{
                                    setcookie(cookiequizzes,defaultbookquizzes);
                            }
                    }
            }else{
                    if(ispageIndex()){
                            indexToc(!cookiesOK());
                    }
                    //Save default options
                    var object = $.parseJSON(defaultsettings);
                    settings(object);
                    sidemenu(object);
                    setcookie(cookiegeneral,defaultsettings);
                    setcookie(cookiefavorites,defaultbookmarks);
                    setcookie(cookiequizzes,defaultbookquizzes);
            }
    });

    var cookiesOK = (function(){
            document.cookie = 'ioc_html_test="test";';
            if (/ioc_html_test/.test(document.cookie)){
                    document.cookie = 'ioc_html_test=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
                    return true;
            }
            return false;
    });


    function basename(path) {
            return path.replace(/\\/g,'/').replace( /.*\//, '' );
    }

    //Header shadow
    $(window).scroll(function () {
            if ($(window).scrollTop() > 30){
                    $("header").addClass("header-shadow");
                    $("#upbutton").show("slow");
            }else{
                    $("header").removeClass("header-shadow");
                    $("#upbutton").hide("slow");
            }
    });

    jQuery(window).resize(function() {
            if (ispageIndex()){
                    indexToc($(".indextoc").css('display') !== 'none');
            }else{
                    if(!ispageSearch()){
                            calpostooltips();
                            setpnpage();
                    }
            }
    });

    //Show and hide list elements
    $(".expander ul").hide();
    $(".expander ul.toc_list").show();
    $(".expander .tocsection .buttonexp").on("click", function() {
        $("#toc .buttonexp").removeClass("tocdown");
        var parent = $(this).closest("li");
        if ($(this).closest("p").siblings("ul").css('display') == 'none'){
            $(this).addClass("tocdown");
        }
        if ($(parent).children("ul").css('display') != 'none'){
            $(parent).children("ul").hide('fast');
        }else{
            $(parent).children("ul").show('fast');
        }
        $(parent).siblings().children().filter('ul').hide('fast');
    });

    $(document).on("click", "a[href^='#']", function(e) {
        e.preventDefault();
        //An anchor
        if ($(this).attr("href") !== '#'){
            var url = $(this).attr("href");
            url = url.replace(/#/,'');
            var offset = $("article a[name='"+url+"']").offset();
            if (offset !== null){
                $(window).scrollTop($(window).scrollTop()+offset.top-60);
            }
        }
    });

    $.expr[':'].parents = function(a,i,m){
        return $(a).parents(m[3]).children('ul').length < 1;
    };

    //TOC
    $(".indextoc").hide();
    $("#button_start").hide();
    $(".headtoc").css('margin-top', -$(".headtoc h1").outerHeight(true)-$(".metainfobc").outerHeight());
    $(".headtoc h1").on("click", function() {
        if ($(".indextoc").css('display') == 'none'){
            var mtop = 0;
            if (parseInt($(".meta img").outerHeight(true)) > $(".meta").outerHeight(true)){
                mtop = parseInt($(".meta img").outerHeight(true));
            }else{
                mtop = parseInt($(".meta").outerHeight(true));
            }
            $(".meta").animate({
                'margin-top': parseInt($(".meta").css('margin-top'),10) == 5 ?
                        -mtop :
                        '5px',
                'visible':'inline'
            },1500);
            $("#header .head").animate({
                    'margin-top': '0px'
            },1500);
            $("#header .headdocument").removeClass('hidden');
            $(".headtoc").animate({
                    'margin-top': -$("#content").outerHeight(true)+$(".headtoc h1").outerHeight()-40
            },1500,function(){
                                    $(".metainfobc").addClass('hidden');
                                    $(".metainfobr").addClass('hidden');
                            }
            );
            $(".headtoc h1 img").slideUp("slow");
            $(".headtoc").css('min-height','100%');
            $(".headtoc").removeClass("headtocdown").addClass("headtocup");
            $(".headtoc h1").removeClass("hover");
            $(".indextoc").show();
            $("#button_start").slideDown("slow");
            setCookieProperty(cookiegeneral,'tvisible', 1);
        }
    });

    $("#button_start").on("click", function() {
            var mtop = 0;
            if (parseInt($(".meta img").outerHeight(true)) > $(".meta").outerHeight(true)){
                    mtop = parseInt($(".meta img").outerHeight(true));
            }else{
                    mtop = parseInt($(".meta").outerHeight(true));
            }
            $(".meta").animate({
                            'margin-top': parseInt($(".meta").css('margin-top'),10) == 5 ?
                                    -mtop :
                                    '5px',
                            'visible':'inline'
            },1500);
            $("#header .head").animate({
                    'margin-top': $(window).height()-$(".headtoc h1").outerHeight(true)-$(".head").outerHeight(true)
            },1500);
            $("#header .headdocument").addClass('hidden');
            $(".metainfobc").removeClass('hidden');
            $(".metainfobr").removeClass('hidden');
            $(".headtoc").animate({
                    'margin-top': -$(".headtoc h1").outerHeight(true)
            },1500, function(){
                                    $(".indextoc").hide();
                                    $(".headtoc").css('min-height','0');
                            }
            );
            $(this).slideUp("slow");
            $(".headtoc").removeClass("headtocup").addClass("headtocdown");
            $(".headtoc h1 img").slideDown("slow");
            $(".headtoc h1").addClass("hover");
            setCookieProperty(cookiegeneral,'tvisible', 0);
    });

    $("#slider-width").slider({
                            min:0,
                            max:5,
                            step:1,
                            range:'min',
                            animate:true,
                            slide: function( event, ui ) {
                               setArticleWidth(ui.value);
              }
    });
    $("#slider-font").slider({
                            min:0,
                            max:4,
                            step:1,
                            range:'min',
                            animate:true,
                            slide: function( event, ui ) {
                               setFontsize(ui.value);
              }
    });

    $("h2,h3,h4").each(function(i){
            if(ispageExercise()){
                    $(this).append('<span name="check"></span>');
            }
            $(this).append('<span class="star" name="star"></span>').children("span").hide();
    });

    $("h1 > a").hover(
            function(){
                    if(showtooltips){
                            showhelp($(this),true,true);
                    }
            },
            function(){
                    if(showtooltips){
                            showhelp($(this),false,true);
                    }
            }
    );

    $("h2 > a,h3 > a,h4 > a").hover(
            function(){
                    if ($(this).siblings("span[name='star']").hasClass("star") && !islocalChrome()){
                            $(this).siblings("span[name='star']").css('display', 'inline-block');
                    }
                    if(showtooltips){
                            showhelp($(this),true,true);
                    }
            },
            function(){
                    if ($(this).siblings("span[name='star']").hasClass("star") && !islocalChrome()){
                            $(this).siblings("span[name='star']").hide();
                    }
                    if(showtooltips){
                            showhelp($(this),false,true);
                    }
            }
    );

    $(document).on("click", "h2 > a,h3 > a,h4 > a", function(){
            if (islocalChrome()){
                    return;
            }
            var id = $(this).attr("id");
            editFavorite(document.location.pathname+"#"+id,id);
            if ($(this).siblings("span[name='star']").hasClass("star")){
                    $(this).siblings("span[name='star']").removeClass().addClass("starmarked");
                    $(this).siblings("span[name='star']").show();
            }else{
                    $(this).siblings("span[name='star']").removeClass().addClass("star");
            }
    });

    $(document).on("click", "article figure img", function(){
            render.previewImage($(this));
    });

    // Afegit zoom per les imatges laterals
    $(document).on("click", ".imgB", function(){
            let $this = $(this);
            $this.css('cursor', 'zoom-in')
            render.previewImage($this);
    });

    $(document).on("click", ".closepreview",function(){
            $('#back_preview, #preview').addClass('hidden');
    });

    $(document).on("click", "#preview", function(){
            $('#back_preview, #preview').addClass('hidden');
    });

    //Initialize menu and settings params
    get_params();
    setNumberHeader();
    setNumFigRef();
    setNumTabRef();


    if(ispagenoHeader()){
            $("h1,h2,h3").each(function(i){
                    $(this).addClass('nocount');
            });
    }
    if(ispageExercise()){
            $("h2").each(function(i){
                    $(this).addClass('nocount');
            });
    }
    if (islocalChrome()){
            $("#search").addClass("hidden");
            if(ispageIndex() || ispageSearch()){
                    $(".infomessage").removeClass("hidden");
            }else{
                    $("#menu li[name='favorites']").addClass("hidden");
            }
    }
    if(!ispageIndex() && !ispageSearch()){
        calpostooltips();
    }
    return {"editCheckExercise":editCheckExercise,
            "ispageSearch":ispageSearch,
            "postosearchword":postosearchword};
});
