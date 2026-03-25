<?php
/**
 * Options for the iocexportl plugin
 *
 * @author Marc Català <mcatala@ioc.cat>
 */

$meta['jquery_url']   = array('string');
$meta['allowexport']   = array('onoff');
$meta['counter']   = array('onoff');
$meta['toccontents'] = array('onoff');
$meta['largetablecaptmargin'] = array('onoff');
$meta['latex_table_backend'] = array('multichoice', '_choices' => array('auto', 'new', 'legacy'));
$meta['UsersWithPdfSelf-generationAllowed'] = array('string');
$meta['ContentPageName'] = array('string');
$meta['saveWorkDir'] = array('onoff');

