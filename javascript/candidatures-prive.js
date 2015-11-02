jQuery(document).ready(function($) {
	// Stuff to do as soon as the DOM is ready. Use $() w/o colliding with other libs;
	$("#candidatures").each(function(){
		var $supprimer = $(this).find('a.supprimer'); console.log($supprimer);

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
					buttons: [{
						text: "Ok",
						class: "bouton small",
						click: function(){ $(this).dialog("close"); location.href  = $lien; }
					},{
						text: "Annuler",
						class: "bouton small",
						click: function(){ $(this).dialog("close"); }
					}]
				});
			$dialog.dialog("open");
			return false;
		});


	});



});
