<?php
if (!defined("_ECRIRE_INC_VERSION")) return;

function fraap_candidatures_declarer_tables_interfaces($interfaces){
	$interfaces['table_des_tables']['candidatures'] = 'candidatures';
	return $interfaces;
}

function fraap_candidatures_declarer_tables_objets_sql($tables) {
	$tables['spip_candidatures'] = array(
		'type' => 'candidature',
		'table_objet' => 'candidatures',
		'principale' => 'oui',
		'page' => 'false',
		'field' => array(
			'id_candidature' => 'bigint(21) NOT NULL',
			'id_auteur' => 'bigint(21) NOT NULL',
			'statut' => 'varchar(10) NOT NULL DEFAULT "publie"',
			'date' => 'datetime NOT NULL',
			'maj' => 'timestamp',
			'regions' => 'text NOT NULL DEFAULT ""',
			'ville_stage' => 'text NOT NULL DEFAULT ""',
			'date_debut' => 'date NOT NULL',
			'date_fin' => 'date NOT NULL',
			'ecole' => 'text NOT NULL DEFAULT ""',
			'ville_ecole' => 'text NOT NULL DEFAULT ""',
			'diplome' => 'text NOT NULL DEFAULT ""',
			'niveau' => 'text NOT NULL DEFAULT ""',
			'competences_offre' => 'text NOT NULL DEFAULT ""',
			'competences_recherche' => 'text NOT NULL DEFAULT ""'
		),
		'key' => array(
			'PRIMARY KEY' => 'id_candidature',
			'KEY id_auteur' => 'id_auteur'
		),
		'titre' => '"" AS titre, "" AS lang',
		'date' => 'date',
		'champs_editables'  => array('regions', 'ville_stage', 'date_debut', 'date_fin', 'ecole', 'ville_ecole', 'diplome', 'niveau', 'competences_offre', 'competences_recherche'),
	);

	$tables['spip_auteurs']['field']['prenom'] = 'text NOT NULL DEFAULT ""';
	$tables['spip_auteurs']['field']['activite'] = 'tinyint(1) NOT NULL DEFAULT 0';

	return $tables;
}
/*
function fraap_candidatures_declarer_champs_extras($champs = array()) {
	$champs['spip_auteurs']['prenom'] = array(
		'saisie' => 'input',
		'options' => array(
			'nom' => 'prenom',
			'label' => _T('fraap_candidatures:label_prenom'),
			'type' => 'text',
			'sql' => 'text NOT NULL DEFAULT ""'
		)
	);

	$champs['spip_auteurs']['test'] = array(
		'saisie' => 'input',
		'options' => array(
			'nom' => 'test',
			'label' => 'test',
			'type' => 'text',
			'sql' => 'text NOT NULL DEFAULT ""'
		)
	);

	$champs['spip_auteurs']['activite'] = array(
		'saisie' => 'selection',
		'options' => array(
			'nom' => 'activite',
			'label' => _T('fraap_candidatures:label_activite'),
			'datas' => array(
				'0' => 'fraap_candidatures:type_defaut',
				'1' => 'fraap_candidatures:type_1',
				'2' => 'fraap_candidatures:type_2'
			),
			'sql' => 'tinyint(1) NOT NULL DEFAULT 0'
		)
	);

	return $champs;
}
*/

/*
function fraap_candidatures_declarer_tables_principales($tables_principales){
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
	$tables_principales['spip_auteurs']['field']['activite'] = 'tinyint(1) NOT NULL DEFAULT 0';

	return $tables_principales;
}
*/
