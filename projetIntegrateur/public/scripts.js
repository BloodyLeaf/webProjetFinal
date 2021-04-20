
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






function changeQTE(id, e){

    console.log(id)
    console.log(e.value)

    
}



