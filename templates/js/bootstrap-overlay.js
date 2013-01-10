/*

Fichier rajoutant des fonctionnalités à Bootstrap

Sommaire :
	- HTML in Popovers

*/

/*
	HTML in Popovers
	Permet de définir le contenu d'un popover comme étant du HTML
	
	Utilisation :
		L'élément déclenchant le popover doit avoir la classe .html-popover-trigger
		Le div contenant le HTML à insérer doit avoir un id.
		L'élément déclencheur doit avoir un attribut html-popover-content référençant cet id (i.e html-popover-content="#monId")

*/
$(document).ready(function(){
  $('.html-popover-trigger').each(function() {
	  $(this).popover({ 
		  html : true,
		  content: function() {
			  return $($(this).attr("html-popover-content")).html();
		  }
	  });
  });
});