define (["doctools"],function(){

	var DOCUMENTATION_OPTIONS = {
			  URL_ROOT: '',
			  VERSION: '2.7.2',
			  COLLAPSE_MODINDEX: false,
			  FILE_SUFFIX: '.html',
			  HAS_SOURCE: true
			};
/**
 * helper function to return a node containing the
 * search summary for a given text. keywords is a list
 * of stemmed words, hlwords is the list of normal, unstemmed
 * words. the first one is used to find the occurance, the
 * latter for highlighting it.
 */

jQuery.makeSearchSummary = function(text, keywords, hlwords) {
  var textLower = text.toLowerCase();
  var start = 0;
  $.each(keywords, function() {
    var i = textLower.indexOf(this.toLowerCase());
    if (i > -1)
      start = i;
  });
  start = Math.max(start - 120, 0);
  var excerpt = ((start > 0) ? '...' : '') +
  $.trim(text.substr(start, 240)) +
  ((start + 240 - text.length) ? '...' : '');
  var rv = $('<div class="summary"></div>').text(excerpt);
  $.each(hlwords, function() {
    rv = rv.highlightText(this, 'highlight');
  });
  return rv;
}


/**
 * Porter Stemmer
 */
var PorterStemmer = function() {

  var step2list = {
    ational: 'ate',
    tional: 'tion',
    enci: 'ence',
    anci: 'ance',
    izer: 'ize',
    bli: 'ble',
    alli: 'al',
    entli: 'ent',
    eli: 'e',
    ousli: 'ous',
    ization: 'ize',
    ation: 'ate',
    ator: 'ate',
    alism: 'al',
    iveness: 'ive',
    fulness: 'ful',
    ousness: 'ous',
    aliti: 'al',
    iviti: 'ive',
    biliti: 'ble',
    logi: 'log'
  };

  var step3list = {
    icate: 'ic',
    ative: '',
    alize: 'al',
    iciti: 'ic',
    ical: 'ic',
    ful: '',
    ness: ''
  };

  var c = "[^aeiou]";          // consonant
  var v = "[aeiouy]";          // vowel
  var C = c + "[^aeiouy]*";    // consonant sequence
  var V = v + "[aeiou]*";      // vowel sequence

  var mgr0 = "^(" + C + ")?" + V + C;                      // [C]VC... is m>0
  var meq1 = "^(" + C + ")?" + V + C + "(" + V + ")?$";    // [C]VC[V] is m=1
  var mgr1 = "^(" + C + ")?" + V + C + V + C;              // [C]VCVC... is m>1
  var s_v   = "^(" + C + ")?" + v;                         // vowel in stem

  this.stemWord = function (w) {
    var stem;
    var suffix;
    var firstch;
    var origword = w;

    if (w.length < 3)
      return w;

    var re;
    var re2;
    var re3;
    var re4;

    firstch = w.substr(0,1);
    if (firstch == "y")
      w = firstch.toUpperCase() + w.substr(1);

    // Step 1a
    re = /^(.+?)(ss|i)es$/;
    re2 = /^(.+?)([^s])s$/;

    if (re.test(w))
      w = w.replace(re,"$1$2");
    else if (re2.test(w))
      w = w.replace(re2,"$1$2");

    // Step 1b
    re = /^(.+?)eed$/;
    re2 = /^(.+?)(ed|ing)$/;
    if (re.test(w)) {
      var fp = re.exec(w);
      re = new RegExp(mgr0);
      if (re.test(fp[1])) {
        re = /.$/;
        w = w.replace(re,"");
      }
    }
    else if (re2.test(w)) {
      var fp = re2.exec(w);
      stem = fp[1];
      re2 = new RegExp(s_v);
      if (re2.test(stem)) {
        w = stem;
        re2 = /(at|bl|iz)$/;
        re3 = new RegExp("([^aeiouylsz])\\1$");
        re4 = new RegExp("^" + C + v + "[^aeiouwxy]$");
        if (re2.test(w))
          w = w + "e";
        else if (re3.test(w)) {
          re = /.$/;
          w = w.replace(re,"");
        }
        else if (re4.test(w))
          w = w + "e";
      }
    }

    // Step 1c
    re = /^(.+?)y$/;
    if (re.test(w)) {
      var fp = re.exec(w);
      stem = fp[1];
      re = new RegExp(s_v);
      if (re.test(stem))
        w = stem + "i";
    }

    // Step 2
    re = /^(.+?)(ational|tional|enci|anci|izer|bli|alli|entli|eli|ousli|ization|ation|ator|alism|iveness|fulness|ousness|aliti|iviti|biliti|logi)$/;
    if (re.test(w)) {
      var fp = re.exec(w);
      stem = fp[1];
      suffix = fp[2];
      re = new RegExp(mgr0);
      if (re.test(stem))
        w = stem + step2list[suffix];
    }

    // Step 3
    re = /^(.+?)(icate|ative|alize|iciti|ical|ful|ness)$/;
    if (re.test(w)) {
      var fp = re.exec(w);
      stem = fp[1];
      suffix = fp[2];
      re = new RegExp(mgr0);
      if (re.test(stem))
        w = stem + step3list[suffix];
    }

    // Step 4
    re = /^(.+?)(al|ance|ence|er|ic|able|ible|ant|ement|ment|ent|ou|ism|ate|iti|ous|ive|ize)$/;
    re2 = /^(.+?)(s|t)(ion)$/;
    if (re.test(w)) {
      var fp = re.exec(w);
      stem = fp[1];
      re = new RegExp(mgr1);
      if (re.test(stem))
        w = stem;
    }
    else if (re2.test(w)) {
      var fp = re2.exec(w);
      stem = fp[1] + fp[2];
      re2 = new RegExp(mgr1);
      if (re2.test(stem))
        w = stem;
    }

    // Step 5
    re = /^(.+?)e$/;
    if (re.test(w)) {
      var fp = re.exec(w);
      stem = fp[1];
      re = new RegExp(mgr1);
      re2 = new RegExp(meq1);
      re3 = new RegExp("^" + C + v + "[^aeiouwxy]$");
      if (re.test(stem) || (re2.test(stem) && !(re3.test(stem))))
        w = stem;
    }
    re = /ll$/;
    re2 = new RegExp(mgr1);
    if (re.test(w) && re2.test(w)) {
      re = /.$/;
      w = w.replace(re,"");
    }

    // and turn initial Y back to y
    if (firstch == "y")
      w = firstch.toLowerCase() + w.substr(1);
    return w;
  }
}


/**
 * Search Module
 */
var Search = {

  _pulse_status : -1,

  init : function() {
      var params = $.getQueryParameters();
      if (params.q) {
          var query = params.q[0];
          $('input[name="q"]')[0].value = query;
          this.performSearch(query);
      }
  },

  stopPulse : function() {
      this._pulse_status = 0;
  },

  startPulse : function() {
    if (this._pulse_status >= 0)
        return;
    function pulse() {
      Search._pulse_status = (Search._pulse_status + 1) % 4;
      var dotString = '';
      for (var i = 0; i < Search._pulse_status; i++)
        dotString += '.';
      Search.dots.text(dotString);
      if (Search._pulse_status > -1)
        window.setTimeout(pulse, 500);
    };
    pulse();
  },

  /**
   * perform a search for something
   */
  performSearch : function(query) {
    // create the required interface elements
    this.out = $('#search-results');
    this.title = $('<h2 class="nocount">' + '@IOCSEARCHING@' + '</h2>').appendTo(this.out);
    this.dots = $('<span></span>').appendTo(this.title);
    this.status = $('<p style="display: none"></p>').appendTo(this.out);
    this.output = $('<ul class="search-result"/>').appendTo(this.out);

    $('#search-progress').text('@PREPARINGSEARCH@');
    this.startPulse();
    this.query(query);
  },

  query : function(query) {
    // stem the searchterms and add them to the
    // correct list
    var stemmer = new PorterStemmer();
    var searchterms = [];
    var excluded = [];
    var hlterms = [];
    var tmp = query.split(/\s+/);
    var object = (tmp.length == 1) ? tmp[0].toLowerCase() : null;
    for (var i = 0; i < tmp.length; i++) {
      // ignore leading/trailing whitespace
      if (tmp[i] == "")
        continue;
      // stem the word
      var word = stemmer.stemWord(tmp[i]).toLowerCase();
      // select the correct list
      if (word[0] == '-') {
        var toAppend = excluded;
        word = word.substr(1);
      }
      else {
        var toAppend = searchterms;
        hlterms.push(tmp[i].toLowerCase());
      }
      // only add if not already in the list
      if (!$.contains(toAppend, word))
        toAppend.push(word);
    };
    var highlightstring = '?highlight=' + $.urlencode(hlterms.join(" "));

//    console.debug('SEARCH: searching for:');
//    console.info('required: ', searchterms);
//    console.info('excluded: ', excluded);

    // prepare search
    $('#search-progress').empty();
    var filenames = new Array("@IOCFILENAMES@")
	getContent(filenames);

	function getContent(filenames){
		var patt1=new RegExp('<article[^>]*>(\n.*)*</article>',"gim");
		var patt2=new RegExp(searchterms.join('|'),"ig");
		var patt3=new RegExp(excluded.join('|'),"ig");
		var patt4=new RegExp('<h1>(?:<a[^>]+>)?(.*?)(?:</a>)?</h1>',"gim");
		file = filenames.pop();
		if (file){
			$.ajax({
			    type: "GET",
			    url: DOCUMENTATION_OPTIONS.URL_ROOT + file,
			    dataType: "html",
			    success: function(data) { 
					var txt = patt1.exec(data);
					var title = patt4.exec(txt);
					if (title && title.length>1){
						title = title[1];
					}
					txt = txt[0].replace(/<[^>]+>|\n|\s{2,}|&#\d+;/gi, ' ');
					if (patt2.test(txt)){
						if (excluded.length > 0 && patt3.test(txt)){
							displayNextItem('', '', filenames.length, '');	
						}else{
							displayNextItem(this.url, txt, filenames.length, title);
						}
					}else{
						displayNextItem('', '', filenames.length, '');
					}
					getContent(filenames);
			    },
			    error: function(x) { alert(x.responseText); }
			});
		}
	}

    function displayNextItem(item, data, pos, title) {
	    var listItem = $('<li style="display:none"></li>');
		if (data){
		    listItem.append($('<a/>').attr(
		      'href', item + highlightstring).html(title));
		    var url = item.replace(/\?\w+=\w+/,'')
		    listItem.append('<div class="urlpath">'+url.replace(/WebContent\//,'...')+'</div>');
		    listItem.append($.makeSearchSummary(data, searchterms, hlterms));
		    Search.output.append(listItem);
		    listItem.slideDown(5);
		}
		if (pos < 1) {
			Search.stopPulse();
			Search.title.text('@IOCSEARCHRESULTS@');

			var elems = jQuery('.search-result').children().length;
			if (elems < 1){
			  Search.status.text('@IOCNOSEARCHRESULTS@');
			}else{
				var text = '@IOCSEARCHFINISHED@';
				if (elems > 1){
					text = text.replace(/\w\//g,'');
				}else{
					text = text.replace(/\/\w+/g,'');
				}
				Search.status.text(text.replace('%s', elems));
			}
			Search.status.fadeIn(500);
		}
    }
  }
}

return Search;
});
