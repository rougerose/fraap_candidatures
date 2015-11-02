<?php
if (!defined("_ECRIRE_INC_VERSION")) return;

include_spip('inc/actions');
include_spip('inc/editer');

function formulaires_editer_candidature_charger_dist($id_candidature='new', $id_auteur='',$retour=''){
	$valeurs = formulaires_editer_objet_charger('candidature', $id_candidature, '', '', $retour, '');
	$valeurs['id_auteur'] = $id_auteur;
	$valeurs['regions'] = unserialize($valeurs['regions']);
	$valeurs['competences_offre'] = unserialize($valeurs['competences_offre']);
	$valeurs['competences_recherche'] = unserialize($valeurs['competences_recherche']);
	$valeurs['_hidden'] .= "<input type='hidden' name='id_auteur' value='".$valeurs['id_auteur']."' />";

	// modification d'une candidature déjà existante
	if ($id_candidature = intval($id_candidature)) {
		$valeurs['_hidden'] .= "<input type='hidden' name='id_candidature' value='".$valeurs['id_candidature']."' />";
	}

	return $valeurs;
}

function formulaires_editer_candidature_verifier_dist($id_candidature='new',$id_auteur='',$retour=''){
	$erreurs = formulaires_editer_objet_verifier('candidature', $id_candidature, array(
		'regions','date_debut','date_fin','ecole','diplome','niveau','competences_offre','competences_recherche')
		);

	// vérification des dates (via spip-bonux)
	include_spip('inc/date_gestion');
	$horaire = false;
	$date_debut = verifier_corriger_date_saisie('debut',$horaire,$erreurs);
	$date_fin = verifier_corriger_date_saisie('fin',$horaire,$erreurs);

	if ($date_debut AND $date_fin AND $date_fin<$date_debut)
	$erreurs['date_fin'] = _T('candidatures:erreur_date_avant_apres');

	return $erreurs;
}

function formulaires_editer_candidature_traiter_dist($id_candidature='new',$id_auteur,$retour=''){
	$res = array();
	$action_editer = charger_fonction("editer_candidature",'action');
	list($id,$err) = $action_editer();
	if ($err){
		$res['message_erreur'] = $err;
	}
	else {
	//	spip_log('candidature '.$id_candidature.' / retour '.$retour.' / id '.$id,'journal');
		($id_candidature == 'new') ? $res['message_ok'] = _T('candidatures:message_candidature_enregistree') : $res['message_ok'] = _T('candidatures:message_candidature_modifiee');
		if ($retour) {
			$retour = parametre_url($retour,'id_candidature',$id_candidature);
			$res['redirect'] = $retour;
		}
	}
	$res['editable'] = true;
	return $res;
}

?>