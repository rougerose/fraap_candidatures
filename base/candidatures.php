<?php
if (!defined("_ECRIRE_INC_VERSION")) return;

function candidatures_declarer_tables_interfaces($interface){
	$interface['table_des_tables']['candidatures'] = 'candidatures';
//	$interface['table_des_traitements']['RACE']['chats'] = _TRAITEMENT_TYPO;
	return $interface;
}
function candidatures_declarer_tables_principales($tables_principales){
	$candidatures = array(
		"id_candidature" => "bigint(21) NOT NULL",
		"id_auteur" => "bigint(21) NOT NULL",
		"statut" 	=> "varchar(10) NOT NULL DEFAULT 'publie'",
		"date"		=> "datetime NOT NULL",
		"maj" 		=> "timestamp",
		"regions"	=> 'text NOT NULL DEFAULT ""',
		"ville_stage" => 'text NOT NULL DEFAULT ""',
		"date_debut" => "date NOT NULL",
		"date_fin"	=> "date NOT NULL",
		"ecole"		=> 'text NOT NULL DEFAULT ""',
		"ville_ecole" => 'text NOT NULL DEFAULT ""',
		"diplome"	=> 'text NOT NULL DEFAULT ""',
		"niveau"	=> 'text NOT NULL DEFAULT ""',
		"competences_offre" => 'text NOT NULL DEFAULT ""',
		"competences_recherche" => 'text NOT NULL DEFAULT ""',
		);

	$candidatures_cles = array(
		"PRIMARY KEY"	=> "id_candidature",
		"KEY id_auteur" => "id_auteur"
		);

	$tables_principales['spip_candidatures'] = array(
		'field' => &$candidatures,
		'key' => &$candidatures_cles,
		'join' => array('id_auteur' => 'id_auteur')
	);

	// ajout colonnes prenom et activite sur spip_auteurs
	$tables_principales['spip_auteurs']['field']['prenom'] = 'text NOT NULL DEFAULT ""';
	$tables_principales['spip_auteurs']['field']['activite'] = 'tinyint(1) NOT NULL';

	return $tables_principales;
}

?>
