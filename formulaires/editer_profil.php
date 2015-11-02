<?php

if (!defined('_ECRIRE_INC_VERSION')) return;

include_spip('inc/filtres');

function formulaires_editer_profil_charger_dist($id_auteur,$retour='') {
	$valeurs = array();
	if($id_auteur = intval($id_auteur)) {
		$row = sql_fetsel("*","spip_auteurs","id_auteur=$id_auteur");

		$valeurs['nom'] = $row['nom'];
		$valeurs['prenom'] = $row['prenom'];
		$valeurs['activite'] = $row['activite'];
		$valeurs['email'] = $row['email'];
	}
	return $valeurs;
}

function formulaires_editer_profil_verifier_dist($id_auteur,$retour='') {

	$email = _request('email');
	$pass = _request('new_pass');
	$pass2 = _request('new_pass2');

	foreach(array('nom','prenom','activite','email') as $obligatoire)
		if (!_request($obligatoire)) $erreurs[$obligatoire] = _T('candidatures:form_champ_obligatoire');

	if ($email AND !email_valide($email)) {
		$erreurs['email'] = _T('form_email_non_valide');
	}

	if ($p = _request('new_pass')) {
		if (strlen($p) < 6) {
			$erreurs['new_pass'] = _T('candidatures:pass_trop_court');
		} elseif ($p != _request('new_pass2')) {
			$erreurs['new_pass2'] = _T('candidatures:pass_pas_identique');
		}
	}
	if(!$mdp && $mdp2){
		$erreurs['new_pass']= _T('candidatures:pass_pas_identique');
	}

	return $erreurs;
}

function formulaires_editer_profil_traiter_dist($id_auteur,$retour='') {
	$res = array();

	$res['nom'] = _request('nom');
	$res['prenom'] = _request('prenom');
	$res['activite'] = _request('activite');
	$res['email'] = _request('email');
	$pass = _request('new_pass');
	$pass2 = _request('new_pass2');

	if($pass) {
		if($pass == $pass2){
			include_spip('inc/acces');
			$htpass = generer_htpass($pass);
			$alea_actuel = creer_uniqid();
			$alea_futur = creer_uniqid();
			$new_pass = md5($alea_actuel.$pass);
			$res['pass'] = $new_pass;
			$res['htpass'] = $htpass;
			$res['alea_actuel'] = $alea_actuel;
			$res['alea_futur'] = $alea_futur;
			$res['low_sec'] = '';
		}
	}

	if ($id_auteur = intval($id_auteur)) {
		sql_updateq('spip_auteurs', $res , "id_auteur=$id_auteur");
		$res['message_ok'] = _T('candidatures:form_profil_modifie');
		

	}
	else { $res['message_erreur'] = _T('candidatures:form_probleme'); }
	return $res;
}


?>