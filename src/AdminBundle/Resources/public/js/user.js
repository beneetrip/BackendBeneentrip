/*
Cette fonction permet d'obtenir grace a Ajax la liste des user qu'on va afficher dans un div appele zone_tableau apres avoir 
supprimee un user avec pour id numero
*/

function delete_user(numero)
{
   $.ajax({
     type: 'get',
     url: "{{ path('supprimerUtilisateur',{'id': numero}) }}",
     data: {
     },
     success: function (response) {
       document.getElementById("zone_tableau").innerHTML=response;
       effacerNotification(); 
     }
   });
}
