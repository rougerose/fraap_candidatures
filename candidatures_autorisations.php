<?php
if (!defined("_ECRIRE_INC_VERSION")) return;
// fonction pour le pipeline, n'a rien a effectuer
function candidatures_autoriser(){}
// declarations d'autorisations
function autoriser_candidatures_bouton_dist($faire, $type, $id, $qui, $opt) {
        return autoriser('voir', 'candidatures', $id, $qui, $opt);
}

// la page des candidatures
function autoriser_candidatures_voir_dist($faire, $type, $id, $qui, $opt) {
        return true;
}
// une candidature
function autoriser_candidature_voir_dist($faire,$type,$id,$opt){
	return autoriser('modifier','candidature',$id,$qui,$opt);
}
// modifier
function autoriser_candidature_modifier_dist($faire,$type,$id,$opt){
	return in_array($qui['statut'], array('0minirezo', '1comite'));
}

// la pages des inscriptions
function autoriser_inscriptions_voir_dist($faire, $type, $id, $qui, $opt) {
        return true;
}


?>