/**
 * La fonction pour charger les types etablissements
 * @param url
 * @param select
 */
function getTypeEstablishment(url,select){
	$.ajax({
        url: url,
        dataType: 'json', // on veut un retour JSON
        success: function(json) {
            $.each(json, function(index, value) { // pour chaque noeud JSON
                // on ajoute l option dans la liste
            	select.append('<option value="'+ index +'">'+ value +'</option>');
            });
        }
    });
}