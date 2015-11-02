jQuery(document).ready(function($) {

	// menu disponible lorsqu'un visiteur est identifié
	// seule la phrase de bienvenu est visible, le reste est masqué
	// et accessible par survol de la souris
	$("#identification-detail").hide().parent().children("p.bienvenue").append('<span class="icon" />');
	$("#menu-identification").hoverIntent( showDetail, hideDetail );

	function showDetail(){
		$(this).find("#identification-detail").slideDown();
	}

	function hideDetail(){
		$(this).find("#identification-detail").slideUp();
	}



	// formulaire_editer : bouton "enregistrer" est masqué tant qu'on a pas saisi ou modifié un champ
	var modifierForm_init = function() {
		$(".formulaire_editer").each(function(){
			var	$bouton = $(this).find('p.boutons'),
				$champs = $('input,' + 'select');
			$bouton.hide();
			$champs.change(function(){
				$bouton.slideDown();
			});
		});
	}
	// il faut que le bouton soit caché après le rechargement du formulaire
	modifierForm_init();
	onAjaxLoad(modifierForm_init);


	// Stages > zones d'actions :
	// 	- class ui-icon sur tous les boutons ajouter, trier, modifier et supprimer,
	//	- confirmation préalable avant de supprimer,
	//	- le bouton trier fait apparaitre le formulaire,
	//	- les critères de recherche de l'utilisateur sont affichés par spip,
	//	mais on peut les supprimer individuellement et soumettre à nouveau le formulaire.

	$(".actions").each(function(){
		var $boutons = $(this).find(".bt").prepend('<span class="icon" />'),
			$supprimer = $boutons.filter(".supprimer"),
			$trier = $boutons.filter(".trier"),
			$formulaire_tri_conteneur = $(this).find("#formulaire_tri_stages").hide(),
			$formulaire_tri = $formulaire_tri_conteneur.children("form");

		// critères de recherche utilisateur
		$("#criteres-utilisateur").each(function(){
			var $criteres = $(this).find("dd"),
			//	$champs = $criteres.children("span.data-env"),
				$checkbox = $criteres.filter(".formTypeCheckbox").find("span.data-env"),
				$input = $criteres.filter(".formTypeInput"),
				$tableau_criteres = $.merge($checkbox,$input);

			// ajout bouton de suppression du critère
			$tableau_criteres.map(function(i,el){
				$(el).append("<a href='#champ_"+el.id+"' title='supprimer'> </a>");
				return el;
			});

			var $liens = $tableau_criteres.children("a");
			$liens.click(function(){
				// supprimer le parent immédiat
				$(this).parent().remove();
				// à partir du hash chercher l'élément équivalent dans le formulaire et vider le champs
				$input = $(this.hash);
				if ($input.attr("type") == "text") { $input.val(''); }
				else if ($input.get(0).type == "checkbox") { $input.attr("checked",false); }
				// soumettre à nouveau le formulaire
				$formulaire_tri.submit();
				return false;
			});
		});

		// confirmation préalable avant de supprimer une candidature (page profil)
		$supprimer.click(function(){
			var	$lien = $(this).attr('href'),
				$dialog = $('<div></div>').html("<p>Souhaitez-vous continuer ?</p>").dialog({
					autoOpen: false,
					title: "Vous avez demandé la suppression d'une candidature",
					resizable: false,
					width: '460',
					minHeight:'auto',
					modal: true,
					buttons: [
					{
						text: "Ok",
						className: "bouton small",
						click: function(){ $(this).dialog("close"); location.href  = $lien; }
					},{
						text: "Annuler",
						className: "bouton small",
						click: function(){ $(this).dialog("close"); }
					}
					]
				});
			$dialog.dialog("open");
			return false;
		});

		// afficher le formulaire de tri
		$trier.click(function(){
			$(this).removeAttr("href");
			$(this).toggleClass('ouvert');
			$formulaire_tri_conteneur.slideToggle();
			return false;
		});
	});

	// Présentation en colonnes de certaines parties des formulaires
	$("#formulaire_tri_stages #localisation li.editer_regions,#formulaire_tri_stages #competences li.editer_competences_offre,#formulaire_tri_stages #recherches li.editer_competences_recherche").columnSplit({
		col:4,cible:'div.choix'
	});
	$("#formulaire_editer_candidature #localisation li.editer_regions,#formulaire_editer_candidature #activites ul.fields li").columnSplit({col:3,cible:'div.choix'})



	// vérification du champ ville_stage dans le formulaire de stages (tri, dépot de candidature)
	// afin que la saisie soit de la forme "Paris, Lyon, Marseille".
	$("#champ_ville_stage").blur(function(){
		var entree = $(this).val(),
			recherche = entree.match(/[-0-9a-zA-Z_]+/g),
			sortie = '',
			nbre = recherche.length,
			$parent = $(this).parent("li"),
			$precedent = $(this).prev("p.explication");
		if (recherche && nbre > 1) {
			jQuery.each(recherche,function(k,v){
				if (k < (nbre - 1)) {sortie += v+', ';}
				else {sortie += v;}
			});
			if (entree == sortie) {
				return; }
			else {
				$(this).val(sortie);
				// mettre en évidence qu'il y a une correction
				$parent.addClass("erreur");
				$precedent.before("<span class='erreur_message'>Votre saisie a été corrigée. Veuillez vérifier.</span>");
			}
		}
	});
});




(function($) {
	$.fn.columnSplit = function(options) {
		var defaults = {
			col: 2,
			cible: ''
		};

		this.each(function() {
			var $t = $(this),
			settings = jQuery.extend(defaults, options),
			$enfants = $t.children(settings.cible),
			enfants_nombre = $enfants.length;
			enfants_blocs = Math.ceil(enfants_nombre / settings.col);

			$t.addClass("colonnes");
			$t.wrapInner('<div class="js-conteneur clearfix" />');

			for (i = 1; i <= settings.col; i++) {
				var rang = '';
				if (i == settings.col) rang = 'last-child';
				$t.children('div.js-conteneur').append('<div class="bloc item' + i + ' ' + rang + '" />');
			}

			var $blocs = $t.children('div.js-conteneur').children('div.bloc'),
			k = 1;
			$enfants.each(function(index) {
				var idx = index + 1;
				if (idx > enfants_blocs * (settings.col - 1)) {
					$blocs.filter('.item' + settings.col).append($(this));
				}
				else {
					if (idx <= (enfants_blocs * k)) {
						$blocs.filter('.item' + k).append($(this));
					}
					else {
						$blocs.filter('.item' + (k + 1)).append($(this));
						k = k + 1;
					}
				}
			});
		});
	}
})(jQuery);
