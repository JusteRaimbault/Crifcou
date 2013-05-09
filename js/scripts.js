//scripts Js



//Gestion du chargement des pages

function loadCurrentPage(name,mapname){//pagename in post
    $("#contenu").load("php/utils/pageload.php", {"page" : name,"mapname":mapname});

}

function loadFromMenu(){
    $(".menulink").click(function(){
        loadCurrentPage($(this).attr("id"),"");
    });
}


function loadFromPopup(arg){
        loadCurrentPage("cartes",arg);
}

function setTitle(name){
        $("#title").html(name);
}





//root login management

function managelogin(){
    $("#loginForm").submit(function(){
        $.post("php/utils/login.php", $("#loginForm").serialize(), function(){
            loadCurrentPage("admin","");
        });
        return false;
    })

    $("#logout").click(function(){
        $.post("php/utils/logout.php",function(){
            loadCurrentPage("acceuil","");
        });
    })

}




//news page management
function toggleNews(){
    $(".newstoggle").click(function(){
        if($(this).text()=="Masquer le texte...") $(this).text("Afficher le texte...");
        else $(this).text("Masquer le texte...");
        $("#text"+$(this).attr("id")).toggle();
    })
}

/**
 *
 * Entries
 *
 */


function manageEntryForm(){
    $("#openEntryForm").click(function(){

                $("#entriesForm").lightbox_me({
                    centered:true,
                    overlayCSS:{background: 'black', opacity: .8},
                    destroyOnClose: true,
                    onClose: function(){loadCurrentPage("inscriptions","");}
                 });
            })

            $("#entriesForm").submit(function(){
                


                $.post("php/utils/newEntry.php",$("#entriesForm").serialize(),function(rep){
                    alert(rep);
                    $("#entriesForm").trigger('close');
                });


                return false;
            })
}

function manageEntryList(){
    $("#entriestable").dataTable({
        "oLanguage": {
            "sLengthMenu": "Afficher <select>"+
                            '<option value="30" selected="selected">30</option>'+
                            '<option value="40">40</option>'+
                            '<option value="50">50</option>'+
                            '<option value="-1">Tous</option>'+
                            "</select>  résultats par page",
            "sZeroRecords": "Aucun résultat",
            "sInfo": "Affichage de _START_ à _END_ de _TOTAL_ résultats",
            "sInfoEmpty": "Affichage de 0 à 0 de 0 résultats",
            "sInfoFiltered": "(filtré parmi _MAX_ résultats au total)",
            "sSearch":"Rechercher:"
        }});
}

function activateDatatable(id){
    $("#"+id).dataTable({
        "oLanguage": {
            "sLengthMenu": "Afficher <select>"+
                            '<option value="30" selected="selected">30</option>'+
                            '<option value="40">40</option>'+
                            '<option value="50">50</option>'+
                            '<option value="-1">Tous</option>'+
                            "</select>  résultats par page",
            "sZeroRecords": "Aucun résultat",
            "sInfo": "Affichage de _START_ à _END_ de _TOTAL_ résultats",
            "sInfoEmpty": "Affichage de 0 à 0 de 0 résultats",
            "sInfoFiltered": "(filtré parmi _MAX_ résultats au total)",
            "sSearch":"Rechercher:"
        }});
}

function closeForm(){ alert("Fichier envoyé !"); $(".addDoc").each(function(){ $(this).trigger('close');})}









function main(){
    loadCurrentPage("acceuil","");
    loadFromMenu();
}

$(document).ready(main);

