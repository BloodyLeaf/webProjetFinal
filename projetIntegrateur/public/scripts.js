
var tableInventaire

$(document).ready( function () {

    tableInventaire =  $('#inventaire').DataTable({

        "language": {
            "lengthMenu": "Afficher _MENU_ par pages",
            "search": "Rechercher:",
            "zeroRecords": "Aucune pièce trouvées",
            "info": "Page _PAGE_ de _PAGES_",
            "infoEmpty": "Aucune pièce dans l'inventaire",
            "infoFiltered": "(filtrer dans _MAX_ enregistrement)",
            "paginate": {
      "next": "Page suivante",
      "previous": "page précédente"
      
    }
        },

        responsive: true,

    
    });
    
   

} );


function changeQTE(id, e,currentTotal){

if(currentTotal != e.value){
      //Créer l'objet
   var xhttp = new XMLHttpRequest();

   // Fonction qui sera appelée quand l'état de la requête change
   xhttp.onreadystatechange = function() {
  
   // Si l'état est 4 (terminé) et le statut 200 (OK)
   if (this.readyState == 4 && this.status == 200) {
    
     alert("Quantité changé !")
 
   }
 };
 //Quel URL doit-on appeler et est-ce en GET ou en POST
 xhttp.open("POST", "chanqueQTETotal", true);
 xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
 // Envoie la requête
 xhttp.send("id="+id+"&qte="+e.value);
}
  
}



