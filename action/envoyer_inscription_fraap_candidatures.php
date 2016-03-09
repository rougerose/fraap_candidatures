<?php
if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * construction du mail envoyant les identifiants
 * fonction redefinissable qui doit retourner un tableau
 * dont les elements seront les arguments de inc_envoyer_mail
 *
 * http://doc.spip.org/@envoyer_inscription_dist
 *
 * @param array $desc
 * @param string $nom
 * @param string $mode
 * @param array $options
 * @return array
 */

 // surcharge de la fonction dist:
 // - modele du mail
 // - ajout du prénom

function action_envoyer_inscription_fraap_candidatures($desc, $nom, $prenom, $mode, $options=array()) {

	$contexte = array_merge($desc,$options);
	$contexte['nom'] = $nom;
  $contexte['prenom'] = $prenom;
	$contexte['mode'] = $mode;
	$contexte['url_confirm'] = generer_url_action('confirmer_inscription','',true,true);
	$contexte['url_confirm'] = parametre_url($contexte['url_confirm'],'email',$desc['email']);
	$contexte['url_confirm'] = parametre_url($contexte['url_confirm'],'jeton',$desc['jeton']);

	$message = recuperer_fond('modeles/mail_inscription_candidatures',$contexte);
	$from = (isset($options['from'])?$options['from']:null);
	$head = null;
	return array("", $message,$from,$head);
}

?>
