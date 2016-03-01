<?php
if (!defined('_ECRIRE_INC_VERSION')) return;

function fraap_candidatures_insert_head($flux) {
  $dossier = 'javascript/';
  $scripts = array(
    // lib
    'jquery.hoverIntent.minified.js', // lib
		// script
		'candidatures-public.js'
  );
  foreach($scripts as $valeur) {
    $flux .="\n".'<script src="'.find_in_path($dossier.$valeur).'" type="text/javascript"></script>';
  }
  return $flux;
}


function fraap_candidatures_insert_head_prive($flux){
	
	$flux .="\n".'<script src="'.find_in_path('javascript/candidatures-prive.js').'" type="text/javascript"></script>';
	$flux .="\n".'<link rel="stylesheet" href="'.find_in_path('css/candidatures-prive.css').'" type="text/css" media="screen" />';

	//$flux .= '<link rel="stylesheet" href="'.find_in_path('css/jquery-ui-1.8.16/css/flick/jquery-ui-1.8.16.custom.css').'" type="text/css" media="screen" />'."\n";
	//$flux .= '<link rel="stylesheet" href="'.find_in_path('css/candidatures-prive.css').'" type="text/css" media="screen" />'."\n";
	//$flux .= '<script type="text/javascript" src="'.find_in_path('javascript/candidatures-prive.js').'"></script>'."\n";
	return $flux;
}

?>
