<?php

require_once "WiocclParser.php";

//$t = 'Text normal al començament <WIOCCL:IF condition="{##semestre##}==2">(primer if la condició es certa) segona opció parsejada: {##itinerariRecomanatS2##} lalala <WIOCCL:IF condition="{##semestre##}==2">(if niuat la condició es certa) segona opció parsejada: {##itinerariRecomanatS3##}</WIOCCL:IF>lelele </WIOCCL:IF> (això està fora dels if) asdfasd fasd un altre de diferent: <WIOCCL:IF condition="{##semestre##}==3">(això es un altre if la condició es falsa) segona opció parsejada: {##itinerariRecomanatS2##}</WIOCCL:IF> (això es el final sense ifs) dddd';
//$t = '::table:TA0
//  :title:Planificació UFX
//  :type:io_pt
//  :footer::
//^  tipus	^  eina	 ^  opcionalitat	 ^  puntuable  ^
//<WIOCCL:FOREACH var="item" array="{##einesAprenentatge##}">| {##item[\'tipus\']##} | {##item[\'eina\']##} | {##item[\'opcionalitat\']##} | <WIOCCL:IF condition="{##item[\'puntuable\']##}==\'true\'">si</WIOCCL:IF><WIOCCL:IF condition="{##item[\'puntuable\']##}==\'false\'">no</WIOCCL:IF> |
//</WIOCCL:FOREACH>
//:::';

//$dataSource = [
//    "responsable"=>"admin", 
//    "autor"=>"usuarinou",
//    "tipusPla"=>"loe", 
//    "tipusBlocModul"=>"1r. bloc",
//    "cicle"=>"Desenvolupament d'Aplicacions Web", 
//    "modul"=>"M\u00f2dul de Prova ", 
//    "durada"=>0,
//    "professors"=>"Vanesa i Jordi", 
//    "urlMaterialDidactic"=>"http:\/ioc.xtec.cat\/adreca\/on\/esta\/el\/material.html", 
//    "dedicacio"=>"8", 
//    "requerimentsMatricula"=>"", 
//    "descripcio"=>"**Negreta**! Aqu\u00ed descobrireu com es fa un ou ferrat. \u00c9s important que **mireu** tot el que hi ha a aqu\u00ed.**Negreta**", 
//    "semestre"=>"1", 
//    "itinerariRecomanat"=>"6", 
//    "taulaDadesUnitats"=>[
//        [
//            "unitat formativa"=>"1",
//            "unitat"=>"1",
//            "nom"=>"Programaci\u00f3 de processos i serveis",
//            "hores"=>"33"
//        ],[
//            "unitat formativa"=>"1",
//            "unitat"=>"2",
//            "nom"=>"Nom de la U2",
//            "hores"=>"33"
//        ],[
//            "unitat formativa"=>"2",
//            "unitat"=>"3",
//            "nom"=>"Nom de la  U3",
//            "hores"=>"33"
//        ],[
//            "unitat formativa"=>"2",
//            "unitat"=>"4",
//            "nom"=>"Nom de la  U4",
//            "hores"=>"33"
//        ],[
//            "unitat formativa"=>"3",
//            "unitat"=>"5",
//            "nom"=>"Nom de la  U5",
//            "hores"=>"33"
//        ]
//    ], 
//    "einesAprenentatge"=>[
//        [
//            "tipus"=>"lectura",
//            "eina"=>"Llibre de l'aula",
//            "opcionalitat"=>"lectura obligada",
//            "puntuable"=>"false"
//        ],[
//            "tipus"=>"autoavaluci\u00f3",
//            "eina"=>"Exercicis d'autoavaluaci\u00f3 i activitats",
//            "opcionalitat"=>"opcional per\u00f2 recomanat",
//            "puntuable"=>"false"
//        ],[
//            "tipus"=>"f\u00f2rum",
//            "eina"=>"F\u00f2rum de l'aula",
//            "opcionalitat"=>"Participaci\u00f3 obligada",
//            "puntuable"=>"false"
//        ],[
//            "tipus"=>"q\u00fcestionari",
//            "eina"=>"Q\u00fcestionari obert a l'aula",
//            "opcionalitat"=>"opcional",
//            "puntuable"=>"true"
//         ],[
//             "tipus"=>"exercicis",
//             "eina"=>"Exercicis d'avaluaci\u00f3 cont\u00ednua",
//             "opcionalitat"=>"opcional",
//             "puntuable"=>"true"
//        ],[
//            "tipus"=>"q\u00fcestionari",
//            "eina"=>"Quaestionari bla, bla",
//            "opcionalitat"=>"opcional",
//            "puntuable"=>"false"
//        ]
//    ], 
//    "calendari"=>[
//        [
//            "unitat"=>"1",
//            "per\u00edode"=>"Apartat 1",
//            "inici"=>"2018\/09\/06",
//            "final"=>"2018\/07\/23"
//        ],[
//            "unitat"=>"1",
//            "per\u00edode"=>"Apartat 2",
//            "inici"=>"2018\/09\/24",
//            "final"=>"2018\/10\/10"
//        ],[
//            "unitat"=>"2",
//            "per\u00edode"=>"Apartat 1",
//            "inici"=>"2018\/10\/11",
//            "final"=>"2018\/11\/01"
//        ],[
//            "unitat"=>"3",
//            "per\u00edode"=>"Apartat 1",
//            "inici"=>"2018\/11\/02",
//            "final"=>"2018\/11\/23"
//        ],[
//            "unitat"=>"3",
//            "per\u00edode"=>"Apartat 2",
//            "inici"=>"2018\/11\/24",
//            "final"=>"2018\/12\/22"
//        ],[
//            "unitat"=>"3",
//            "per\u00edode"=>"Apartat 3",
//            "inici"=>"2019\/01\/07",
//            "final"=>"2019\/01\/18"
//        ]
//    ], 
//    "ponderacioEACs"=>"30",
//    "numLliuraments"=>"5",
//    "tipusLliuraments"=>"EACs", 
//    "datesAC"=>[
//        [
//            "unitat"=>"1",
//            "enunciat"=>"2018\/09\/12",
//            "lliurament"=>"2018\/09\/27",
//            "soluci\u00f3"=>"2018\/09\/29",
//            "qualificaci\u00f3"=>"2018\/10\/01"
//        ],[
//            "unitat"=>"2",
//            "enunciat"=>"2018\/09\/30",
//            "lliurament"=>"2018\/10\/11",
//            "soluci\u00f3"=>"2018\/10\/13",
//            "qualificaci\u00f3"=>"2018\/10\/18"
//        ],[
//            "unitat"=>"3",
//            "enunciat"=>"2018\/10\/25",
//            "lliurament"=>"2018\/11\/15",
//            "soluci\u00f3"=>"2018\/11\/17",
//            "qualificaci\u00f3"=>"2018\/11\/24"
//        ]
//    ], 
//    "dadesExtres"=>[
//        [
//            "nom"=>"data_XXX",
//            "tipus"=>"dada",
//            "valor"=>"29\/09\/2018"
//        ],[
//            "nom"=>"color_tapa",
//            "tipus"=>"dada",
//            "valor"=>"groc"
//        ]
//    ], 
//    "notaMinimaAC"=>"0", 
//    "dataPaf1"=>"2018-05-18", 
//    "dataPaf2"=>"2018-06-01", 
//    "Nota_minima_a_la_PAF_per_poder_comptar_l'AC"=>"4", 
//    "fitxercontinguts"=>"continguts", 
//    "itinerariRecomanatS1"=>"2", 
//    "itinerariRecomanatS2"=>"3", 
//    "notaMinimaPAF"=>"4", 
//    "dadesQualificacioUFs"=>[
//        [
//            "unitat formativa"=>"1",
//            "tipus qualificaci\u00f3"=>"participaci\u00f3 al f\u00f2rum",
//            "ponderaci\u00f3"=>"5"
//        ],[
//            "unitat formativa"=>"1",
//            "tipus qualificaci\u00f3"=>"EAC1",
//            "ponderaci\u00f3"=>"30"
//        ],[
//            "unitat formativa"=>"1",
//            "tipus qualificaci\u00f3"=>"PAF",
//            "ponderaci\u00f3"=>"65"
//        ],[
//            "unitat formativa"=>"2",
//            "tipus qualificaci\u00f3"=>"EAC2",
//            "ponderaci\u00f3"=>"30"
//        ],[
//            "unitat formativa"=>"2",
//            "tipus qualificaci\u00f3"=>"PAF",
//            "ponderaci\u00f3"=>"70"
//        ],[
//            "unitat formativa"=>"3",
//            "tipus qualificaci\u00f3"=>"Participaci\u00f3 al f\u00f2rum",
//            "ponderaci\u00f3"=>"5"
//        ],[
//            "unitat formativa"=>"3",
//            "tipus qualificaci\u00f3"=>"EAC",
//            "ponderaci\u00f3"=>"25"
//        ],[
//            "unitat formativa"=>"3",
//            "tipus qualificaci\u00f3"=>"PAF",
//            "ponderaci\u00f3"=>"70"
//        ]
//    ], 
//    "dadesQualificacioFinal"=>[
//        [
//            "unitat formativa"=>"1",
//            "ponderaci\u00f3"=>"30"
//        ],[
//            "unitat formativa"=>"2",
//            "ponderaci\u00f3"=>"40"
//        ],[
//            "unitat formativa"=>"3",
//            "ponderaci\u00f3"=>"30"
//        ]
//    ], 
//    "plantilla"=>"plantilles:docum_ioc:pla_treball_fp:##tipusPla##:continguts", 
//    "dataQualificacioPaf1"=>"2018-05-25", 
//    "dataQualificacioPaf2"=>"2018-06-10", 
//    "duradaCicle"=>"", 
//    "coordinador"=>"Al\u00edcia", 
//    "taulaDadesUF"=>[
//        [
//            "bloc"=>0,
//            "unitat formativa"=>1,
//            "nom"=>"Nom de la UF 1"
//        ],[
//            "bloc"=>0,
//            "unitat formativa"=>2,
//            "nom"=>"Nom de la UF2"
//        ],[
//            "bloc"=>0,
//            "unitat formativa"=>3,
//            "nom"=>"Indiqueu el nom de la UF"
//        ]
//    ], 
//    "resultatsAprenentatge"=>[
//        [
//            "id"=>"RA1",
//            "descripcio"=>"Descripci\u00f3 de l'RA1"
//        ],[
//            "id"=>"RA2",
//            "descripcio"=>"Descripci\u00f3 de l'RA2"
//        ],[
//            "id"=>"RA3",
//            "descripcio"=>"Descripci\u00f3 de l'RA3"
//        ]
//    ]
//];

$dataSource = [
'semestre' => 2,
 'itinerariRecomanatS1' => 'verd',
 'itinerariRecomanatS2' => 'cotxe',
 'dedicacio' => 8,
 "taulaDadesUnitats"=>'[
        [
            "unitat formativa"=>"1",
            "unitat"=>"1",
            "nom"=>"Programaci\u00f3 de processos i serveis",
            "hores"=>"33"
        ],[
            "unitat formativa"=>"1",
            "unitat"=>"2",
            "nom"=>"Nom de la U2",
            "hores"=>"33"
        ],[
            "unitat formativa"=>"2",
            "unitat"=>"3",
            "nom"=>"Nom de la  U3",
            "hores"=>"33"
        ],[
            "unitat formativa"=>"2",
            "unitat"=>"4",
            "nom"=>"Nom de la  U4",
            "hores"=>"33"
        ],[
            "unitat formativa"=>"3",
            "unitat"=>"5",
            "nom"=>"Nom de la  U5",
            "hores"=>"33"
        ]
  ]',     
  "taulaDadesUF"=>'[
        [
            "bloc"=>0,
            "unitat formativa"=>1,
            "nom"=>"Nom de la UF 1"
        ],[
            "bloc"=>0,
            "unitat formativa"=>2,
            "nom"=>"Nom de la UF2"
        ],[
            "bloc"=>0,
            "unitat formativa"=>3,
            "nom"=>"Indiqueu el nom de la UF"
        ]
    ]',     
    "calendari"=>'[
        [
            "unitat"=>"1",
            "per\u00edode"=>"Apartat 1",
            "inici"=>"2018\/09\/06",
            "final"=>"2018\/07\/23"
        ],[
            "unitat"=>"1",
            "per\u00edode"=>"Apartat 2",
            "inici"=>"2018\/09\/24",
            "final"=>"2018\/10\/10"
        ],[
            "unitat"=>"2",
            "per\u00edode"=>"Apartat 1",
            "inici"=>"2018\/10\/11",
            "final"=>"2018\/11\/01"
        ],[
            "unitat"=>"3",
            "per\u00edode"=>"Apartat 1",
            "inici"=>"2018\/11\/02",
            "final"=>"2018\/11\/23"
        ],[
            "unitat"=>"3",
            "per\u00edode"=>"Apartat 2",
            "inici"=>"2018\/11\/24",
            "final"=>"2018\/12\/22"
        ],[
            "unitat"=>"3",
            "per\u00edode"=>"Apartat 3",
            "inici"=>"2019\/01\/07",
            "final"=>"2019\/01\/18"
        ]
    ]',     
    "activitatsAprenentatge"=>'[
        [
            "unitat"=>"1",
            "per\u00edode"=>"Apartat 1",
            "eina"=>"2018\/09\/06",
            "descripció"=>"2018\/07\/23"
        ],[
            "unitat"=>"1",
            "per\u00edode"=>"Apartat 2",
            "eina"=>"2018\/09\/06",
            "descripció"=>"2018\/07\/23"
        ],[
            "unitat"=>"2",
            "per\u00edode"=>"Apartat 1",
            "eina"=>"2018\/09\/06",
            "descripció"=>"2018\/07\/23"
        ],[
            "unitat"=>"3",
            "per\u00edode"=>"Apartat 1",
            "eina"=>"2018\/09\/06",
            "descripció"=>"2018\/07\/23"
        ],[
            "unitat"=>"3",
            "per\u00edode"=>"Apartat 2",
            "eina"=>"2018\/09\/06",
            "descripció"=>"2018\/07\/23"
        ],[
            "unitat"=>"3",
            "per\u00edode"=>"Apartat 3",
            "eina"=>"2018\/09\/06",
            "descripció"=>"2018\/07\/23"
        ]
    ]',     
 'einesAprenentatge' => '[{
		"tipus": "aaaa",
		"eina": "bbb",
		"opcionalitat": "111",
		"puntuable": "true"
	},
	{
		"tipus": "jjj",
		"eina": "222",
		"opcionalitat": "rrr",
		"puntuable": "false"
	},
    {
		"tipus": "aaaa",
		"eina": "bbb",
		"opcionalitat": "111",
		"puntuable": "true"
	},
	{
		"tipus": "aaaa",
		"eina": "bbb",
		"opcionalitat": "111",
		"puntuable": "true"
	},
]',
 'datesAC' => '[
        {
            "unitat": "1",
            "test": "a",
            "enunciat": "2013/2/1",
		"lliurament": "2013/3/2",
		"solució": "2013/4/3",
		"qualificació": "2013/4/4"
	},
	{
		"unitat": "1",
		"test": "a",
		"enunciat": "2014/2/1",
		"lliurament": "2014/3/2",
		"solució": "2014/4/3",
		"qualificació": "4-4-2014"
	},
	{
		"unitat": "2",
		"test": "a",
		"enunciat": "2017/2/1",
		"lliurament": "2017/3/2",
		"solució": "2017/4/3",
		"qualificació": "4-4-2017"
	},
	{
		"unitat": "3",
		"test": "b",
		"enunciat": "2018/2/1",
		"lliurament": "2018/3/2",
		"solució": "2018/4/3",
		"qualificació": "4-4-2018"
	}
]'
];
$t='====== Planificació ======
<WIOCCL:FOR from="1" to="{#_COUNTDISTINCT({##taulaDadesUnitats##}, [\'unitat formativa\'])_#}" counter="ind">
La planificació establerta per a la UF{##ind##} és la següent: (veure:table:TPL{##ind##}:)
::table:TPL{##ind##}
  :title:Planificació UF{##ind##}.
  :type:io_pt 
  :footer::
[^ UF{##ind##} ^  <WIOCCL:SUBSET subsetvar="filtered" array="{##taulaDadesUF##}" arrayitem="itemsub" filter="{##itemsub[unitat formativa]##}=={##ind##}">{#_FIRST({##filtered##}, "FIRST[nom]")_#} ({#_FIRST({##filtered##}, "FIRST[hores]")_#})</WIOCCL:SUBSET>  ^
<WIOCCL:FOREACH  var="itemu" array="{##taulaDadesUnitats##}" filter="{##itemu[unitat formativa]##}=={##ind##}">
^  **Unitat {##itemu[unitat]##}: {##itemu[nom]##} ({##itemu[hores]##} h)**^^
<WIOCCL:FOREACH  var="item_per" array="{##calendari##}" filter="{##item_per[unitat]##}=={##itemu[unitat]##}">
^  **Apartat {##item_per[període]##}: XXXXXXXXXXXXXXX (xx h). Activitats d\'aprenentatge**  ^^
| <WIOCCL:FOREACH  var="item_act" array="{##activitatsAprenentatge##}" filter="{##item_act[unitat]##}=={##itemu[unitat]##}">
{##item_act[eina]##}: {##item_act[descripció]##} \\
</WIOCCL:FOREACH> ||</WIOCCL:FOREACH></WIOCCL:FOREACH>]
:::

</WIOCCL:FOR>';
//$t='Cada Unitat Formativa es divideix en diferents unitats:
//
//::table:TAUF
//  :title:Unitats fo
//  :type:io_pt
//<WIOCCL:FOR counter="ind" from="1" to="{#_COUNTDISTINCT({##datesAC##}, [\'unitat\'])_#}">
//^UF{##ind##} ^^^^
//^  U  ^  NOM  ^  Durada  ^  Temporalització  ^
//<WIOCCL:FOREACH var="item" array="{##datesAC##}" filter="{##item[unitat]##}=={##ind##}">
//|  U{##item[unitat]##}  |  {##item[test]##}  | {##item[enunciat]##} h  |  {##item[lliurament]##} |
//</WIOCCL:FOREACH>
//</WIOCCL:FOR>
//:::
//';
//$t = '<WIOCCL:IF condition="{##semestre##}==1">{##itinerariRecomanatS1##}</WIOCCL:IF><WIOCCL:IF condition="{##semestre##}==2">{##itinerariRecomanatS2##}</WIOCCL:IF> semestre de l\'itinerari formatiu i suposa una **dedicació setmanal mínima  de {##dedicacio##}h.**dds
//
//Per cursar aquest {##tipusModulBloc##} és requisit NO cursar simultàniamentDiria que hi ha 3 casos: (1)no cursar simultàniament, (2)cursar simultàniament o haver superat i (3)haver superat. En qualsevol cas manquen camps amb aquesta informació. És una inmnformació necessària a aquí?. Una solucó parcial seria reflectir tots els casos al camp requisit. Ho parlem haver superat els mòduls: {##requisits##} (en cas d\' idncompatibilitats) no entenc aquest parèntesi.
//
//====== TEST: FOREACH ======
//
//El material que treballareu és el següent:
//  * XXXX
//  * XXXX
//  * XXXX
//
//^  tipus	^  eina	 ^  opcionalitat	 ^  puntuable  ^
//<WIOCCL:FOREACH var="item" array="{##einesAprenentatge##}"">
//| {##item[\'tipus\']##} | {##item[\'eina\']##} | {##item[\'opcionalitat\']##} | <WIOCCL:IF condition="{##item[\'puntuable\']##}==\'true\'">si</WIOCCL:IF><WIOCCL:IF condition="{##item[\'puntuable\']##}==\'false\'">no</WIOCCL:IF> |
//</WIOCCL:FOREACH>';

/* Test foreach amb filtre */
//$t = 'Les dates clau del semestre, que també podeu consultar al calendari de l\'aula, són les següents: (veure:table:TA1:).
//
//::table:TA1
//  :title:Dates clau
//  :type:io_pt
//  :footer::
//^  unitat  ^  data de publicació de l\'enunciat  ^ data de publicació de la solució ^ data de publicació de la qualificació ^
//<WIOCCL:FOREACH var="item" array="{##datesAC##}" filter="{##item[unitat]##}=={##testitem[unitat]##}">
//| U{##item[unitat]##} | {#_DATE("{##item[enunciat]##}")_#} | {#_DATE("{##item[lliurament]##}")_#} | {#_DATE("{##item[solució]##}")_#} | {#_DATE("{##item[qualificació]##}")_#} |
//</WIOCCL:FOREACH>
//:::
//Test array length: {#_ARRAY_LENGTH({##datesAC##})_#}
//Test count distinct: {#_COUNTDISTINCT({##datesAC##}, ["unitat", "test"])_#}
//
//===== Desplegament dels RA =====
//
//En aquest bloc/mòdul es descriuen els següents resultats aprenentatge:
//
//<WIOCCL:FOREACH var="item" array="{##datesAC##}">
//  -{##item[unitat]##} {##item[test]##}
//</WIOCCL:FOREACH>
//
//';

/* Test subset, first i last */

//$t = '
//<WIOCCL:SUBSET subsetvar="filtered" array="{##datesAC##}" arrayitem="itemsub" filter="{##testitem[unitat]##}=={##itemsub[unitat]##}">
//{#_FIRST({##filtered##}, "FIRST[enunciat]")_#}
//{#_FIRST({##filtered##}, "FIRST")_#}
//{#_FIRST({##filtered##}, "{\"a\":\"FIRST[enunciat]\", \"b\":5, \"c\":true, \"d\":\"{##semestre##}\", \"z\":\"FIRST[lliurament]\"}")_#}
//-
//{#_LAST({##filtered##}, "LAST[enunciat]")_#}
//{#_LAST({##filtered##}, "LAST")_#}
//{#_LAST({##filtered##}, "{\"a\":\"LAST[enunciat]\", \"b\":5, \"c\":true, \"d\":\"{##semestre##}\", \"z\":\"LAST[lliurament]\"}")_#}
//
//</WIOCCL:SUBSET>
//';

//$t = 'aaa {#_DATE("{#_FIRST({##datesAC##}, "FIRST[enunciat]}")_#}", ".")_#}-{#_DATE("{#_LAST({##datesAC##}, "LAST[enunciat]")_#}", ".")_#} bbb';

$p = new WiocclParser($t, [], $dataSource);
print_r('<pre>');
print_r($p->getValue());
print_r('</pre>');
