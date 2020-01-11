<?php
/**
 * Fichier gérant l'installation et désinstallation du plugin
 *
 * @plugin     fraap_candidatures
 * @copyright  2019
 * @author     Christophe Le Drean
 * @licence    GNU/GPL
 * @package    SPIP\fraap_candidatures\Installation
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

//include_spip('inc/cextras');
include_spip('base/fraap_candidatures');

/**
 * Fonction d'installation et de mise à jour du plugin.
 *
 * @param string $nom_meta_base_version
 *     Nom de la meta informant de la version du schéma de données du plugin installé dans SPIP
 * @param string $version_cible
 *     Version du schéma de données dans ce plugin (déclaré dans paquet.xml)
 * @return void
**/
function fraap_candidatures_upgrade($nom_meta_base_version, $version_cible) {
	$maj = array();

	$maj['create'] = array(
		array('maj_tables', array('spip_candidatures'))
	);

	$maj['1.1.0'] = array(
		array('sql_alter', 'TABLE spip_auteurs MODIFY COLUMN activite TINYINT(1) NOT NULL DEFAULT 0')
	);

	include_spip('base/upgrade');
	maj_plugin($nom_meta_base_version, $version_cible, $maj);
}


/**
 * Fonction de désinstallation du plugin Numéros de Vacarme.
 *
 * @param string $nom_meta_base_version
 *     Nom de la meta informant de la version du schéma de données du plugin installé dans SPIP
 * @return void
**/
function fraap_candidatures_vider_tables($nom_meta_base_version) {
	// sql_drop_table('spip_candidatures');
	effacer_meta($nom_meta_base_version);
}
