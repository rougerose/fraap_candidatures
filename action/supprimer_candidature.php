<?php

if (!defined("_ECRIRE_INC_VERSION")) return;

function action_supprimer_candidature_dist() {
	$securiser_action = charger_fonction('securiser_action', 'inc');
	$arg = $securiser_action();

	if (!preg_match(",^(\d+)$,", $arg, $r)) {
		 spip_log("action_supprimer_candidature_dist $arg pas compris");
	} else {
		action_supprimer_candidature_post($r[1]);
	}
}

function action_supprimer_candidature_post($id_candidature) {
	sql_delete("spip_candidatures", "id_candidature=" . sql_quote($id_candidature));
	include_spip('inc/invalideur');
	suivre_invalideur("id='id_candidature/$id_candidature'");
}
?>