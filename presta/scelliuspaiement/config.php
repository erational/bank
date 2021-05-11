<?php
/*
 * Paiement Bancaire
 * module de paiement bancaire multi prestataires
 * stockage des transactions
 *
 * Auteurs :
 * Cedric Morin, Nursit.com
 * (c) 2012-2019 - Distribue sous licence GNU/GPL
 * Lyra 
 *
 */
if (!defined('_ECRIRE_INC_VERSION')){
	return;
}

/* Scellius Paiement ----------------------------------------------------------- */


/**
 *
 * Scellius Paiement est fourni par LyraNetworks en marque blanche à La Banque Postale comme PayZen dont il partage une grande partie du code
 *
 */


function scelliuspaiement_lister_cartes_config($c, $cartes = true){
	include_spip('inc/bank');
	$config = array('presta' => 'scelliuspaiement', 'type' => isset($c['type']) ? $c['type'] : 'acte', 'service' => 'scelliuspaiement');

	include_spip("presta/payzen/inc/payzen");
	$liste = payzen_available_cards($config);

	$others = array('SDD', 'E_CV');
	foreach ($liste as $k => $v){
		if ($cartes AND in_array($k, $others)){
			unset($liste[$k]);
		}
		if (!$cartes AND !in_array($k, $others)){
			unset($liste[$k]);
		}
	}
	return $liste;
}


/**
 * Titre "paiement SEPA" eventuel
 * @param $mode
 * @param $id_transaction
 * @return mixed|string
 */
function presta_scelliuspaiement_titre_type_paiement_dist($mode, $id_transaction){

	if ($id_transaction
		AND $trans = sql_fetsel("refcb", "spip_transactions", "id_transaction=" . intval($id_transaction))
		AND strncmp($trans['refcb'], "SEPA", 4)==0){
		return _T("bank:label_type_paiement_sepa", array('presta' => "ScelliusPaiement"));
	}

	return "";
}
