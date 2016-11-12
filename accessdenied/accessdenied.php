<?php
/**
 * mm_widget_accessdenied
 * @version 1.1.1 (2013-12-11)
 * 
 * @desc A widget for ManagerManager plugin that allows access to specific documents (by ID) to be denied without inheritance on the document editing page.
 * 
 * @uses MODXEvo.plugin.ManagerManager >= 0.6.
 * 
 * @param $documentIds {string_commaSeparated} — List of documents ID to prevent access. @required
 * @param $message {string} — HTML formatted message. Default: 'Access denied - Access to current document closed for security reasons.'.
 * @param $roles {string_commaSeparated} — The roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles). Default: ''.
 * 
 * @event OnDocFormPrerender
 * @event OnDocFormRender
 * 
 * @link http://code.divandesign.biz/modx/mm_widget_accessdenied/1.1.1
 * 
 * @author Icon by designmagus.com
 * @author Originally written by Metaller
 * @author DivanDesign
 * 
 * @copyright 2013
 */

function mm_widget_accessdenied(
	$documentIds = '',
	$message = '',
	$roles = ''
){
	if (!useThisRule($roles)){return;}
	
	global $modx;
	$e = &$modx->Event;
	
	if ($e->name == 'OnDocFormPrerender'){
		$widgetDir = $modx->config['site_url'].'assets/plugins/managermanager/widgets/accessdenied/';
		
		$output = includeJsCss($widgetDir.'accessdenied.css', 'html');
		
		$e->output($output);
	}else if ($e->name == 'OnDocFormRender'){
		if (empty($message)){$message = '<span>Access denied</span>Access to current document closed for security reasons.';}
		
		$docId = (int)$_GET[id];
		
		$documentIds = makeArray($documentIds);
		
		$output = '//---------- mm_widget_accessdenied :: Begin -----'.PHP_EOL;
		
		if (in_array($docId, $documentIds)){
			$output .=
'
$j("input, div, form[name=mutate]").remove(); // Remove all content from the page
$j("body").prepend(\'<div id="aback"><div id="amessage">'.$message.'</div></div>\');
$j("#aback").css({height: $j("body").height()} );
';
		}
		
		$output .= '//---------- mm_widget_accessdenied :: End -----'.PHP_EOL;
		
		$e->output($output);
	}
}
?>