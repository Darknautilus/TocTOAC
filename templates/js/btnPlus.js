
	
	var c,c2, ch;
	
	// ajouter un champ avec son "name" propre;
	function plus(){
		c=document.getElementById('cadre');
		c2=c.getElementsByTagName('input');
		ch=document.createElement('input');
		
		
		ch.setAttribute('type','text');
		ch.setAttribute('name','ch'+c2.length);
		ch.setAttribute('onblur', "value=('')");
		ch.setAttribute('onfocus', "value=('')");
		ch.setAttribute('value', "libelle");
		c.appendChild(ch);
	
		document.getElementById('sup').style.display='inline';
	}
	
	// supprimer le dernier champ;
	function moins(){
		if(c2.length>0){c.removeChild(c2[c2.length-1])}
		if(c2.length==0){document.getElementById('sup').style.display='none'};
	}

