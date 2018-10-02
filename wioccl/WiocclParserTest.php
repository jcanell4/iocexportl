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

$dataSource = [
    'semestre' => 2,
    'itinerariRecomanatS1' => '<<verd>>',
    'itinerariRecomanatS2' => '<<cotxe>>',
    'dedicacio' => 8,
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
	}
]',
    'datesAC' => '[{
        "unitat": "1",
		"enunciat": "2/2/2018",
		"lliurament": "3/3/2018",
		"solució": "4/4/2018",
		"qualificació": "4/4/2018"
	},
	{
		"unitat": "2",
		"enunciat": "2/2/2016",
		"lliurament": "3/3/2016",
		"solució": "4/4/2016",
		"qualificació": "4/4/2016"
	}
]'
];
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

$t='Les dates clau del semestre, que també podeu consultar al calendari de l\'aula, són les següents: (veure:table:TA1:).

::table:TA1
  :title:Dates clau
  :type:io_pt
  :footer::
^  unitat  ^  data de publicació de l\'enunciat  ^ data de publicació de la solució ^ data de publicació de la qualificació ^
<WIOCCL:FOREACH var="item" array="{##datesAC##}">
| U{##item[\'unitat\']##} | {#_DATE("{##item[\'enunciat\']##}")_#} | {#_DATE("{##item[\'lliurament\']##}")_#} | {#_DATE("{##item[\'solució\']##}")_#} | {#_DATE("{##item[\'qualificació\']##}")_#} |
</WIOCCL:FOREACH>
:::';
$p = new WiocclParser($t, [], $dataSource);
print_r('<pre>');
print_r($p->getValue());
print_r('</pre>');