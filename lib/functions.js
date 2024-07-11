/* 
 * funcions simples de javascrit
 * @culpable Rafael
 */

/*
 * Copia en el portaretalls el contingut del bloc amb onClick
 */
function copyToClipboard(id){
    var copyText=document.getElementById(id).innerHTML;
    navigator.clipboard.writeText(copyText);
};

