/**
 * Created by Olivier on 6/02/15.
 */

function dywee_handle_form_collection(container) {
    dywee_handle_form_collection(container, null);
}

function dywee_handle_form_collection(container, userConfig) {
    var config = {
        container_type: 'div',
        label: 'Element',
        allow_add: true,
        allow_delete: true,
        add_btn: {
            'class': 'btn btn-default',
            icon: '',
            text: 'Ajouter un élément'
        },
        remove_btn: {
            'class': 'btn btn-default',
            icon: 'fa fa-trash',
            text: 'Supprimer'
        }
    };
    //Réécriture des paramètres
    $.each(userConfig, function(key, value)
    {
        config[key] = userConfig[key];
    });

    var $container = $(config.container_type+'#'+container);

    console.log($container);

    // On ajoute un lien pour ajouter une nouvelle catégorie
    if(config.allow_add == true)
    {
        var $addLink = $('<a href="#" id="add_category" class="'+config.add_btn.class+'">'+config.add_btn.text+'</a>');
        $container.append($addLink);
        // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
        $addLink.click(function(e) {
            addCategory($container);
            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find(':input').length;

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    if (index == 0) {
        addCategory($container);
    } else {
        // Pour chaque catégorie déjà existante, on ajoute un lien de suppression
        $container.children('div').each(function() {
            if(config.allow_delete == true)
                addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire Categorie
    function addCategory($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($container.attr('data-prototype').replace(/__name__label__/g, config.label+' n°' + (index+1))
            .replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
        if(config.allow_delete)
            addDeleteLink($prototype);

        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;

        $('.select2').select2();
    }

    // La fonction qui ajoute un lien de suppression d'une catégorie
    function addDeleteLink($prototype) {
        // Création du lien
        $deleteLink = $('<a href="#" class="'+config.remove_btn.class+'">'+config.remove_btn.text+'</a>');

        // Ajout du lien
        $prototype.append($deleteLink);

        // Ajout du listener sur le clic du lien
        $deleteLink.click(function(e) {
            $prototype.remove();
            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }

    $('.select2').select2();
}

function dywee_handle_delete_btn() {
    $('[data-action="ajax-delete"]').unbind('click').click(function(e)
    {
        e.preventDefault();
        var $btn = $(this);
        var btn_text = $btn.html();

        $btn.html('<i class="fa fa-spinner fa-spin"></i>');
        var route = ($(this).attr('data-route')) ? Routing.generate($(this).attr('data-route'), {id: $(this).attr('data-id') }) : $(this).attr('href');

        console.log(route);

        var $confirmModal = $('#dataConfirmModal');

        if (!$confirmModal.length)
        {
            $confirmModal = $('<div class="modal fade" id="dataConfirmModal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Attention!</h4></div><div class="modal-body"><p><i class="fa fa-spinner fa-spin"></i> Veuillez patienter </p></div><div class="modal-footer"><a class="btn btn-danger" id="dataConfirmOK">Supprimer</a><button type="button" class="btn btn-default" data-dismiss="modal" id="dataConfirmAboard">Annuler</button></div></div></div></div>');
            $('body').append($confirmModal);
        }

        var content = '<p>Etes-vous sur de vouloir supprimer cet élément?</p>';
        var element = $(this).attr('data-text');
        if(typeof(element) != 'undefined' && element != "")
            content += '<p>(Sera supprimé: <b>' + element + '</b>)</p>';
        content += '<p>Cette action est irréversible.</p>'
        $confirmModal.find('.modal-body').html(content);

        $confirmModal.on('hide.bs.modal', function () {
            $btn.html(btn_text);
        })

        $('#dataConfirmOK').click(function(e)
        {
            $confirmBtn = $(this);
            $confirmBtn.addClass('disabled').html('<i class="fa fa-spinner fa-spin"></i> Veuillez patienter');
            $.post(route , function( data ) {
                data = JSON.parse(data);
                if(data.type == "success" || data.status =='success')
                {
                    $('#dataConfirmModal').modal('hide');
                    $confirmBtn.removeClass('disabled').html('Supprimer');
                    $btn.html(btn_text);
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
        $confirmModal.modal({show:true});
        return false;


    });
}

function dywee_reset_handler(handler) {
    if(handler == 'delete_button')
        dywee_handle_delete_btn();
}

$(document).ready(function() {
    //Gestion des boutons delete
    dywee_handle_delete_btn();
    $('.select2').select2();
});