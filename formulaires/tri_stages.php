<?php
if (!defined("_ECRIRE_INC_VERSION")) return;

include_spip('inc/date_gestion');

function formulaires_tri_stages_charger_dist() {
	$valeurs = array(
		'date_debut' => '',
		'date_fin' => '',
		'regions' => '',
		'ville_stage' => '',
		'ecole' => '',
		'ville_ecole' => '',
		'diplome' => '',
		'niveau' => '',
		'competences_offre' => '',
		'competences_recherche' => ''
	);

	$valeurs['regions'] = unserialize($valeurs['regions']);
	$valeurs['competences_offre'] = unserialize($valeurs['competences_offre']);
	$valeurs['competences_recherche'] = unserialize($valeurs['competences_recherche']);

	return $valeurs;
}

function formulaires_tri_stages_verifier_dist(){
	$erreurs = array();

	// vérfication champs ville stage qui peut comporter plusieurs villes
	$ville_stage = _request('ville_stage');
	if ($ville_stage) {
		preg_match_all("/[-0-9a-zA-Z_]+/",$ville_stage,$recherche);
		$nbre = count($recherche[0]);
		$sortie = '';
		if ($recherche && $nbre > 1) {
			foreach ($recherche[0] as $k => $v) {
				if ($k < ($nbre - 1)) {
					$sortie .= $v.', ';
				} else {
					$sortie .= $v;
				}
			}
			if ($input == $sortie) {
				return;
			} else {
			//	$erreurs["ville_stage"] = _T('candidatures:erreur_champ_ville_stage');
				set_request("ville_stage",$sortie);
			}
		}
	}

	// vérification des dates (via spip-bonux)
	$horaire = false;

	$date_debut = verifier_corriger_date_saisie('debut',$horaire,$erreurs);
	$date_fin = verifier_corriger_date_saisie('fin',$horaire,$erreurs);

	if ($date_debut AND $date_fin AND $date_fin<$date_debut)
	$erreurs['date_fin'] = _T('candidatures:erreur_date_avant_apres');

	return $erreurs;
}

function formulaires_tri_stages_traiter_dist(){
	$res = array();

/*	foreach (
		array(
			'regions',
			'ville_stage',
			'date_debut',
			'date_fin',
			'ecole',
			'ville_ecole',
			'diplome',
			'niveau',
			'competences_offre',
			'competences_recherche'
		) as $champ
	)
	$res[$champ] = _request($champ);
*/
/*	if ($res['date_debut']) {
		$res['date_debut'] = _request('date_debut');
		$res['date_debut'] = date('Y-m-d',verifier_corriger_date_saisie('debut',$horaire,$erreurs));
	}
*/
	$res["editable"] = true;
	return $res;
}

?>