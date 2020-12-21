/**
 * Lib wiki activity chooser
 * 
 * @author     Josep Cañellas <jcanell4@ioc.cat>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */
require([
    "ioc/dokuwiki/runChooser",
    "dojo/ready"
], function(run, ready){
    ready(function(){
        run("content");
    });
    
});