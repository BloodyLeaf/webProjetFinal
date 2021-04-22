
/* Custom filter pour filtrer la table par categorie */
$.fn.dataTable.ext.search.push(
  function( settings, data, dataIndex ) {
    if ( settings.nTable.id !== 'inventaire' ) {
      return true;
    }
      var categorieFilter = $("#filterCategorie").val();
      var categorie = data[2]; 

      if ( ( categorieFilter == "" ) ||
           ( categorieFilter == categorie ) )
      {
          return true;
      }
      return false;
  }
);



/* Custom filter pour filtrer la table par État de réservation */

$.fn.dataTable.ext.search.push(
  function( settings, data, dataIndex ) {
    //Si c'est n'est pas la table Réservation, n'applique pas le filtre
    if ( settings.nTable.id !== 'reservation' ) {
      return true;
    }
    
      var filterEtat = $("#filterEmprunt").val();   //Va chercher la valeur du select avec les filtres
      var Etat = data[6];                           //Va chercher les valeurs des ID État qui sont dans une colonne caché

      if ( ( filterEtat == "" ) ||                  // si le filtre est vide ou à un état en particulier, affiche le tout
           ( filterEtat == Etat ) )
      {
          return true;
     }
      return false;
  }
);




var tableReservation


/*
table inventaire
*/ 
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

    //Event listener pour les filtre de la table inventaire
    $('#filterCategorie').change( function() {
      tableInventaire.draw();
    } );




    /*
    table Utilisateur
    */ 
    tableUtilisateurs =  $('#utilisateurs').DataTable({

      "language": {
          "lengthMenu": "Afficher _MENU_ par pages",
          "search": "Rechercher:",
          "zeroRecords": "Aucune utilisateur trouvé",
          "info": "Page _PAGE_ de _PAGES_",
          "infoEmpty": "Aucune réservation trouvé",
          "infoFiltered": "(filtrer dans _MAX_ enregistrement)",
          "paginate": {
    "next": "Page suivante",
    "previous": "page précédente"
    
  }
      },

      responsive: true,

  
  });


  /*
  table Reservation
  */ 
  tableReservation =  $('#reservation').DataTable({

    "language": {
        "lengthMenu": "Afficher _MENU_ par pages",
        "search": "Rechercher:",
        "zeroRecords": "Aucune réservation trouvée",
        "info": "Page _PAGE_ de _PAGES_",
        "infoEmpty": "Aucune Réservation dans l'inventaire",
        "infoFiltered": "(filtrer dans _MAX_ enregistrement)",
        "paginate": {
  "next": "Page suivante",
  "previous": "page précédente"
  
}
    },

    responsive: true,


    "columnDefs": [
      {
          "targets": [ 6 ],
          "visible": false,
          "searchable": true
      }
  ],

  


});

  //Event listener pour les filtres de la table réservation
 $('#filterEmprunt').change( function() {
  tableReservation.draw();
} );
    
  

} );



function message(idDisplay,idText,message){
  document.getElementById(idText).innerHTML = message
  $(idDisplay).fadeIn();
  setTimeout(function() {
    $(idDisplay).fadeOut();
    }, 2000);
}

function changeQTE(id, e,currentTotal){

//Assure que ce sois un nombre positif
if(!(/^0*[0-9]\d*$/.test(e.value))){
  message("#errorMessageDisplay", "errorMessage","Entré une valeur positive")
  return
}


if(currentTotal != e.value){


      //Créer l'objet
   var xhttp = new XMLHttpRequest();

   // Fonction qui sera appelée quand l'état de la requête change
   xhttp.onreadystatechange = function() {
  
   // Si l'état est 4 (terminé) et le statut 200 (OK)
   if (this.readyState == 4 && this.status == 200) {
    
   
    if(this.responseText == "fail"){
      message("#errorMessageDisplay", "errorMessage","Entré une valeur positive")
    }
    else{
      message("#successMessageDisplay", "successMessage","Quantité changée !")
      setTimeout(function() {
        location.reload()
        }, 2200);
    }
    
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
       
    message("#successMessageDisplay", "successMessage","État changée !")
      setTimeout(function() {
        location.reload()
        }, 2200);
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
function retourPieceForm(e){
  reset()
  
  let row = e.dataset.rownb
  let id = e.dataset.idreservation
  let data = tableReservation.row(row).data()
  document.getElementById("nomEtudiant").value = data[0]
  document.getElementById("piece").value = data[1]
  document.getElementById("QTEEmprunter").value = data[2]
  document.getElementById("reservationID").value = id
  
}



/*
fonction qui affiche le champ description,qte perdu et qte brise dans le retour si la piece est briser/perdu
*/ 
function showDescription(){


  let radioOui = document.getElementById("ouiBrise")
  let desc = document.getElementById("descriptionPieceBrise")
  let qteBrise = document.getElementById("QTEBriseDisplay")
  let qtePerdu = document.getElementById("QTEPerduDisplay")

  if(radioOui.checked){
    desc.style.display = "block"
    qteBrise.style.display = "block"
    qtePerdu.style.display = "block"
  }
  else{
    desc.style.display = "none"
    qteBrise.style.display = "none"
    qtePerdu.style.display = "none"
  }
}


/*
fonction qui reset les valeurs du formulaire de retour a chaque clic du bouton retourner
*/
function reset(){
  document.getElementById("nonBrise").checked = true
  document.getElementById("QTERetourner").value = ""
  document.getElementById("QTEBrise").value = 0
  document.getElementById("QTEPerdu").value = 0
  document.getElementById("descriptionPieceBrise").style.display = "none"
  document.getElementById("descriptionPieceBrise").value = ""
  document.getElementById("QTEBriseDisplay").style.display = "none"
  document.getElementById("QTEPerduDisplay").style.display = "none"
}




/*
fonction qui fait le traitement des retours de pièces
*/
function traitementRetourPiece(){


  let QTERetourner = document.getElementById("QTERetourner").value
  let QTEEmprunter = document.getElementById("QTEEmprunter").value
  let incidentEmprunt = false


  //Assure que ce sois un nombre positif pour la QTE retourner et qu'elle sois inférieure à la QTE emprunter
  if(!(/^0*[1-9]\d*$/.test(QTERetourner)) || parseInt(QTERetourner) > parseInt(QTEEmprunter)){
  message("#errorMessageDisplayReturn", "errorMessageReturn","Enter une quantité retourné valide")
  return
  }

  let id = document.getElementById("reservationID").value
  let incident  = document.getElementById("ouiBrise")
  let QTEPerdu  = document.getElementById("QTEPerdu").value
  let QTEBrise  = document.getElementById("QTEBrise").value
  let desc  = document.getElementById("descriptionPieceBrise").value

  //Si il à des pièces qui soit endommagé
  if(incident.checked){

    //Assure que ce sois un nombre positif pour la QTE perdu
    if(!(/^0*[0-9]\d*$/.test(QTEPerdu))){
      message("#errorMessageDisplayReturn", "errorMessageReturn","Enter une quantité perdue valide")
      return
    }
    //Assure que ce sois un nombre positif pour la QTE Brisé
    if(!(/^0*[0-9]\d*$/.test(QTEBrise))){
      message("#errorMessageDisplayReturn", "errorMessageReturn","Enter une quantité Brisée valide")
      return
    }

    //Assure qu'il y a bel et bien un bris si la case oui est coché
    if(QTEPerdu == 0 && QTEBrise == 0){
      message("#errorMessageDisplayReturn", "errorMessageReturn","Coché non si il n'y à pas de pièces brisé ou perdu ! ")
      return
    }

    //Assure que l'adition de QTE Brisé et QTE perdu ne dépasse pas la QTE retourner
    if( (parseInt(QTEPerdu) + parseInt(QTEBrise)) > parseInt(QTERetourner)){
      message("#errorMessageDisplayReturn", "errorMessageReturn","Enter des quantités perdues et brisée valides")
      return
    }


    //Assure qu'il y a une description
    if (desc == ""){
      message("#errorMessageDisplayReturn", "errorMessageReturn","Vous devez entrez une description de l'évenement")
      return
    }

    //flag pour savoir qu'il y a eu une pièce endommagé ou perdu
    incidentEmprunt = true
  }

  
  //Créer l'objet
  var xhttp = new XMLHttpRequest();

  // Fonction qui sera appelée quand l'état de la requête change
  xhttp.onreadystatechange = function() {
     
  // Si l'état est 4 (terminé) et le statut 200 (OK)
  if (this.readyState == 4 && this.status == 200) {
       
    if(this.responseText == "fail")
      message("#errorMessageDisplay", "errorMessage","Une erreur c'est produite")
    else{
      message("#successMessageDisplay", "successMessage","Retour effectué !")
      setTimeout(function() {
        location.reload()
        }, 2200);
    }
  }

  };
  //Quel URL doit-on appeler et est-ce en GET ou en POST
  xhttp.open("POST", "retourPiece", true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  if(incidentEmprunt)
    xhttp.send("id="+id+"&QTERetourner="+QTERetourner+"&QTEPerdu="+QTEPerdu+"&QTEBrise="+QTEBrise+"&desc="+desc);
  else
    xhttp.send("id="+id+"&QTERetourner="+QTERetourner);



  $('#modalRetour').modal('hide');
}



