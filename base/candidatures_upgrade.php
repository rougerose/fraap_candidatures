<?php
if (!defined("_ECRIRE_INC_VERSION")) return;

include_spip('inc/meta');
include_spip('base/create');

function candidatures_upgrade($nom_meta_base_version, $version_cible){

	$current_version = "0.0";

	if (isset($GLOBALS['meta'][$nom_meta_base_version])) {
		$current_version = $GLOBALS['meta'][$nom_meta_base_version];
	}

	if ($current_version=="0.0") {
		creer_base();
		maj_tables('spip_auteurs');
		ecrire_meta($nom_meta_base_version, $current_version=$version_cible);
	}

	// ajout d'un statut publie par defaut
/*	if (version_compare($current_version, '0.3', '<')) {
		include_spip('base/abstract_sql');
		sql_alter("TABLE spip_candidatures CHANGE statut statut VARCHAR(10) DEFAULT 'publie' NOT NULL");
		ecrire_meta($nom_meta_base_version,$current_version="0.3");
	}
	// ajout du statut publié pour les enregistrements déjà présents
	if (version_compare($current_version, '0.33', '<')) {
		include_spip('base/abstract_sql');
		$res = sql_select("statut", "spip_candidatures");
		while ($row = sql_fetch($res)){
			if(isset($row['statut'])) {
				if (!sql_updateq("spip_candidatures",array('statut'=>'publie'))) spip_log('mise à jour colonne statut rencontre un probleme','journal');
			}
		}
		ecrire_meta($nom_meta_base_version,$current_version="0.33");
	}
*/
}

function candidatures_vider_tables($nom_meta_base_version) {
	include_spip('base/abstract_sql');
	sql_drop_table("spip_candidatures");
	sql_alter("TABLE spip_auteurs DROP COLUMN prenom");
	sql_alter("TABLE spip_auteurs DROP COLUMN activite");
	effacer_meta($nom_meta_base_version);
}

?>
