//fonction permettant d'un previsualiser un fichier uploade on l'utilise pour les images des profils pour utilisateurs      
function  previsualiserImage(idFichier, idPreview){
	
	var file=document.getElementById(""+idFichier).files[0];
	var fr=new FileReader();
	var preview=document.getElementById(""+idPreview);
	fr.onprogress=function(){
		preview.innerHTML='Chargement...';
	}
	fr.onerror=function(){
		preview.innerHTML='Oups...';
	}
	fr.onload=function(){
		preview.src=fr.result;
		//preview.appendChild(document.createTextNode(fr.result));
		}
		//fr.readAsText(file);
		fr.readAsDataURL(file);

}

function genererImagePreview(id, idFichier, src){

}
