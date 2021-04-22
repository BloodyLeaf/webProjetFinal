
var tableReservation

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

    tableUtilisateurs =  $('#utilisateurs').DataTable({

      "language": {
          "lengthMenu": "Afficher _MENU_ par pages",
          "search": "Rechercher:",
          "zeroRecords": "Aucun utilisateur trouvé",
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

  tableReservation =  $('#reservation').DataTable({

    "language": {
        "lengthMenu": "Afficher _MENU_ par pages",
        "search": "Rechercher:",
        "zeroRecords": "Aucune réservation trouvée",
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

//Assure que ce sois un nombre positif
if(!(/^0*[1-9]\d*$/.test(e.value))){
  alert("Vous devez entré un nombre positif")
  return
}


if(currentTotal != e.value){


      //Créer l'objet
   var xhttp = new XMLHttpRequest();

   // Fonction qui sera appelée quand l'état de la requête change
   xhttp.onreadystatechange = function() {
  
   // Si l'état est 4 (terminé) et le statut 200 (OK)
   if (this.readyState == 4 && this.status == 200) {
    
    alert("Quantité changé !")
    location.reload()
   }
 };
 //Quel URL doit-on appeler et est-ce en GET ou en POST
 xhttp.open("POST", "chanqueQTETotal", true);
 xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
 // Envoie la requête
 xhttp.send("id="+id+"&qte="+e.value);
}
  
}



function modifierEtat(id, e){



  //Créer l'objet
  var xhttp = new XMLHttpRequest();

  // Fonction qui sera appelée quand l'état de la requête change
  xhttp.onreadystatechange = function() {
     
  // Si l'état est 4 (terminé) et le statut 200 (OK)
  if (this.readyState == 4 && this.status == 200) {
       
  alert("État changé !")
    
  }

  };
  //Quel URL doit-on appeler et est-ce en GET ou en POST
  xhttp.open("POST", "changeEtat", true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  // Envoie la requête
  xhttp.send("id="+id+"&etat="+e.value);

}



/*
fonction qui lie les bon champs au formulaire dynamiquement 
*/ 
function retourPiece(e){
  
  let row = e.dataset.rownb
  let id = e.dataset.idreservation
  let data = tableReservation.row(row).data()
  document.getElementById("nomEtudiant").value = data[0]
  document.getElementById("piece").value = data[1]
  document.getElementById("QTEEmprunter").value = data[2]

}



/*
fonction qui affiche le champ description dans le retour si la piece est briser
*/ 
function showDescription(){

  let radioOui = document.getElementById("ouiBrise")
  let desc = document.getElementById("descriptionPieceBrise")
  if(radioOui.checked){
    desc.style.display = "block"
  }
  else{
    desc.style.display = "none"
  }
}


/*
fonction qui reset les valeurs du formulaire de retour a chaque fin de clic de annuler
*/
function reset(){
  document.getElementById("nonBrise").checked = true
  document.getElementById("QTERetourner").value = ""
  document.getElementById("descriptionPieceBrise").style.display = "none"
}





