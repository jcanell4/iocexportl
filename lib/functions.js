/* 
 * funcions simples de javascrit
 * @culpable Rafael
 */

/*
 * Copia en el portaretalls el contingut del bloc amb onClick
 */
function copyToClipboard(id){
    var copyText=document.getElementById(id).innerHTML;
    var regex = /(<p>|<\/p>|<div.*?>|<\/div>|<li.*?>)/g;
    copyText = copyText.replace(regex, "\n");
    regex = /(\n\n|&nbsp;)/g;
    copyText = copyText.replace(regex, "");
    regex = /<(.|\n)*?>/g;
    copyText = copyText.replace(regex, "");
    navigator.clipboard.writeText(copyText);
    alert(copyText);
};

