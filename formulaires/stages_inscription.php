<?php

/***************************************************************************\
 *  SPIP, Systeme de publication pour l'internet                           *
 *                                                                         *
 *  Copyright (c) 2001-2011                                                *
 *  Arnaud Martin, Antoine Pitrou, Philippe Riviere, Emmanuel Saint-James  *
 *                                                                         *
 *  Ce programme est un logiciel libre distribue sous licence GNU/GPL.     *
 *  Pour plus de details voir le fichier COPYING.txt ou l'aide en ligne.   *
\***************************************************************************/

if (!defined('_ECRIRE_INC_VERSION')) { return;
}

function formulaires_stages_inscription_charger_dist($mode, $focus, $id = 0) {
  $valeurs = [
	'nom' => '',
	'prenom' => '',
	'activite' => '',
	'mail' => '',
	'pass' => '',
	'pass2' => '',
	'conditions' => '',
	'id' => $id
  ];
  if ($mode == '1comite') {
	$valeurs['_commentaire'] = _T('pass_espace_prive_bla');
  } else { $valeurs['_commentaire'] = _T('pass_forum_bla');
  }

  if (!tester_config($id, $mode)) {
	$valeurs['editable'] = false;
  }

  return $valeurs;
}

// Si inscriptions pas autorisees, retourner une chaine d'avertissement
function formulaires_stages_inscription_verifier_dist($mode, $focus, $id = 0) {

  $erreurs = [];
  include_spip('inc/filtres');
  if (!tester_config($id, $mode) or (strlen(_request('nobot')) > 0)) {
	$erreurs['message_erreur'] = _T('rien_a_faire_ici');
  }

  if (!$nom = _request('nom')) {
	$erreurs['nom'] = _T('fraap_candidatures:form_nom_obligatoire');
  }
  if (!$mail = _request('mail')) {
	$erreurs['mail'] = _T('fraap_candidatures:form_mail_obligatoire');
  }
  if (!$prenom = _request('prenom')) {
	$erreurs['prenom'] = _T('fraap_candidatures:form_prenom_obligatoire');
  }
  if (!$activite = _request('activite')) {
	$erreurs['activite'] = _T('fraap_candidatures:form_activite_obligatoire');
  }
  if (!$pass = _request('pass')) {
	$erreurs['pass'] = _T('fraap_candidatures:form_pass_obligatoire');
  }
  if (!$pass2 = _request('pass2')) {
	$erreurs['pass2'] = _T('fraap_candidatures:form_pass_obligatoire');
  }
  if (strlen($pass) < 6) {
	  $erreurs['pass'] = _T('fraap_candidatures:pass_trop_court');
  } else {
	if ($pass != $pass2) {
	  $erreurs['pass2'] = _T('fraap_candidatures:pass_pas_identique');
	}
  }
  if (!$conditions = _request('conditions')) {
	$erreurs['conditions'] = _T('fraap_candidatures:form_conditions_obligatoire');
  }

	// bloquer les spams @qq.com
	if (strpos($mail, '@qq.com')) {
		$mail = 'spam detected';
	}

  // compatibilite avec anciennes fonction surchargeables
  // plus de definition par defaut
  if (!count($erreurs)) {
	if (function_exists('test_inscription')) {
	  $f = 'test_inscription';
	} else { $f = 'test_inscription_dist';
	}
	$declaration = $f($mode, $mail, $nom, $prenom, $pass, $activite, $id);
	if (is_string($declaration)) {
	  $k = (strpos($declaration, 'mail')  !== false) ?
		'mail' : 'nom';
	  $erreurs[$k] = _T($declaration);
	} else {
	  include_spip('base/abstract_sql');

	  if ($row = sql_fetsel('statut, id_auteur, login, email', 'spip_auteurs', 'email=' . sql_quote($declaration['email']))) {
		if (($row['statut'] == '5poubelle') and !$declaration['pass']) {
		  // irrecuperable
		  $erreurs['message_erreur'] = _T('form_forum_access_refuse');
		} elseif (($row['statut'] != 'nouveau') and !$declaration['pass']) {
		  // deja inscrit
		  $erreurs['message_erreur'] = _T('form_forum_email_deja_enregistre');
		}
		spip_log($row['id_auteur'] . ' veut se resinscrire');
	  }
	}
  }
  return $erreurs;
}

function formulaires_stages_inscription_traiter_dist($mode, $focus, $id = 0) {

  $nom = _request('nom');
  $mail_complet = _request('mail');

  // infos supplémentaires
  $prenom = _request('prenom');
  $activite = _request('activite');
  $pass = _request('pass');

  $inscrire_visiteur = charger_fonction('inscrire_visiteur_candidatures_fraap', '');
  $desc = $inscrire_visiteur($mode, $mail_complet, $nom, $prenom, $activite, $pass, ['id' => $id]);

  // if (function_exists('test_inscription_fraap_candidatures'))
  //   $f = 'test_inscription_fraap_candidatures';
  // else   $f = 'test_inscription_dist';
  // $desc = $f($mode, $mail_complet, $nom, $prenom, $pass, $activite, $id);

	// erreur
	if (is_string($desc)) {
		return ['message_erreur' => $desc];
	} else {
		return ['message_ok' => _T('fraap_candidatures:identifiant_mail'),'id_auteur' => $desc['id_auteur']];
	}
}


function inscrire_visiteur_candidatures_fraap($statut, $mail_complet, $nom, $prenom, $activite, $pass, $options = []) {
  if (!is_array($options)) { $options = ['id' => $options];
  }
  include_spip('action/inscrire_auteur');
	if (function_exists('test_inscription')) {
		$f = 'test_inscription';
	} else { $f = 'test_inscription_dist';
	}
	$desc = $f($statut, $mail_complet, $nom, $options);

	if (!is_array($desc)) {
	return _T($desc);
	}

  // ajouter les arguments restants
  $desc['prenom'] = $prenom;
  $desc['activite'] = $activite;
  $desc['pass'] = $pass;

  include_spip('base/abstract_sql');
	$res = sql_select('statut, id_auteur, login, email', 'spip_auteurs', 'email=' . sql_quote($desc['email']));

  // erreur ?
	if (!$res) {
		return _T('titre_probleme_technique');
	}

	$row = sql_fetch($res);

  sql_free($res);

  if ($row) {
		if (isset($options['force_nouveau']) and $options['force_nouveau'] == true) {
			$desc['id_auteur'] = $row['id_auteur'];
			$desc = inscription_nouveau($desc);
		} else { 			$desc = $row;
		}
  } else {
	// s'il n'existe pas deja, creer les identifiants
		$desc = inscription_nouveau($desc);
  }

  if (!is_array($desc)) {
	return $desc;
  }
  // le mot de passe a été saisi par le visiteur,
  // donc on ne fait rien ici
	// generer le mot de passe (ou le refaire si compte inutilise)
	// $desc['pass'] = creer_pass_pour_auteur($desc['id_auteur']);

	// attribuer un jeton pour confirmation par clic sur un lien
	$desc['jeton'] = auteur_attribuer_jeton($desc['id_auteur']);

  // ajouter la zone restreinte stages
  sql_insertq('spip_zones_liens', ['id_zone' => '1', 'id_objet' => $desc['id_auteur'], 'objet' => 'auteur']);

	// charger de suite cette fonction, pour ses utilitaires
	$envoyer_inscription = charger_fonction('envoyer_inscription_fraap_candidatures', 'action');
	list($sujet,$msg,$from,$head) = $envoyer_inscription($desc, $nom, $prenom, $statut, $options);

	$notifications = charger_fonction('notifications', 'inc');
	notifications_envoyer_mails($mail_complet, $msg, $sujet, $from, $head);

	// Notifications
	$notifications(
		'inscription',
		$desc['id_auteur'],
		['nom' => $desc['nom'], 'email' => $desc['email']]
	);

	return $desc;
}
