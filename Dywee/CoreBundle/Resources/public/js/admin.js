/**
 * Created by Olivier on 6/02/15.
 */

$(document).ready(function()
{
    //Gestion des boutons delete
    $('a[data-action="ajax-delete"]').click(function(e)
    {
        e.preventDefault();
        var $btn = $(this);
        $btn.html('<i class="fa fa-spinner fa-spin"></i>');
        var route = Routing.generate($(this).attr('data-route'), {id: $(this).attr('data-id') });

        if (!$('#dataConfirmModal').length)
            $('body').append('<div class="modal fade" id="dataConfirmModal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Attention!</h4></div><div class="modal-body"><p><i class="fa fa-spinner fa-spin"></i> Veuillez patienter </p></div><div class="modal-footer"><a class="btn btn-danger" id="dataConfirmOK">Supprimer</a><button type="button" class="btn btn-default" data-dismiss="modal" id="dataConfirmAboard">Annuler</button></div></div></div></div>');
        $('#dataConfirmModal').find('.modal-body').html('<p>Etes-vous sur de vouloir supprimer cet élément?</p><p>Sera supprimé : ' + $(this).attr('data-text') + '</p><p>Cette action est irréversible.</p>');

        $('#dataConfirmAboard').click(function(){
            $btn.html('<i class="fa fa-trash"></i>');
        });

        $('#dataConfirmOK').click(function(e)
        {
            $confirmBtn = $(this);
            $confirmBtn.addClass('disabled').html('<i class="fa fa-spinner fa-spin"></i> Veuillez patienter');
            $.post(route , function( data ) {
                data = JSON.parse(data);
                if(data.type == "success")
                {
                    $('#dataConfirmModal').modal('hide');
                    $confirmBtn.removeClass('disabled').html('Supprimer');
                    $btn.html('<i class="fa fa-trash-o"></i>');
                    $btn.closest($btn.attr('data-container')).fadeOut("slow");
                }
                else {
                    $modal = $("#dywee-modal");
                    $modal.find('.modal-header').html('<h1>Erreur</h1>');
                    $modal.find('.modal-body').html('<p>Une erreur est survenue pendant la suppression</p><p>Veuillez contacter un administrateur</p>');
                    $btn = $('<button type="button" class="btn btn-default">Fermer</button>');
                    $modal.find('.modal-footer').html($btn);
                    $modal.modal('show');
                }
            });
        });
        $('#dataConfirmModal').modal({show:true});
        return false;


    });
});