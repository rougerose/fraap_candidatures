<?php
if (!defined("_ECRIRE_INC_VERSION")) return;

function action_editer_candidature_dist(){

	$securiser_action = charger_fonction('securiser_action', 'inc');
	$arg = $securiser_action();

	// si id_candidature n'est pas un nombre, c'est une creation
	// mais on verifie qu'on a toutes les donnees qu'il faut.
	if (!$id_candidature = intval($arg)) {
		$id_auteur = _request('id_auteur');
		if (!$id_candidature = stage_action_insert_candidature($id_auteur))
			return array(false,_L('echec'));
	}

	$err = action_candidature_set($id_candidature);
	return array($id_candidature,$err);
}

// creer un nouvel evenement
function stage_action_insert_candidature($id_auteur){

	// nouvelle candidature
	$id_candidature = sql_insertq("spip_candidatures", array(
		'id_auteur'=>intval($id_auteur),'date'=>date("Y-m-d H:i:s"), "maj"=>date("Y-m-d H:i:s")
		));

	if (!$id_candidature){
		spip_log("action formulaire insert candidature : impossible d'ajouter une candidature",'journal');
		return false;
	}
	return $id_candidature;
}


function action_candidature_set($id_candidature, $set=null){
	$err = '';

	if (is_null($set)){
		$c = array();
		foreach (array(
			'regions','ville_stage','date_debut','date_fin','ecole','ville_ecole','diplome','niveau','competences_offre','competences_recherche'
		) as $champ)
			$c[$champ] = _request($champ);

	}
	else
		$c = $set;

	$c['regions'] = serialize($c['regions']);
	$c['competences_offre'] = serialize($c['competences_offre']);
	$c['competences_recherche'] = serialize($c['competences_recherche']);

	include_spip('inc/date_gestion');
	$horaire = false;
	$date_debut = verifier_corriger_date_saisie('debut',$horaire,$erreurs);
	$date_fin = verifier_corriger_date_saisie('fin',$horaire,$erreurs);

	$c['date_debut'] = date('Y-m-d',$date_debut);
	$c['date_fin'] = date('Y-m-d',$date_fin);


	include_spip('inc/modifier');
	stage_action_revision_candidature($id_candidature, $c);

	return $err;
}

// Enregistre une revision de candidature
function stage_action_revision_candidature ($id_candidature, $c=false) {
	modifier_contenu('candidature', $id_candidature,'',$c);

	return ''; // pas d'erreur
}

?>