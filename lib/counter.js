/**
 * Lib wiki page counter
 * 
 * @author     Josep Cañellas <jcanell4@ioc.cat>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */
require([
    "ioc/dokuwiki/runCounter",
    "dojo/ready"
], function(run, ready){
    ready(function(){
        run("content");
    });
    
});