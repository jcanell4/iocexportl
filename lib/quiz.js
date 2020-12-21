require([
    "ioc/dokuwiki/runQuiz",
    "dojo/ready"
], function(runQuiz, ready){
    ready(function(){
        runQuiz("bodyContent");
    });
    
});