/**
 * Created by Olivier on 6/02/15.
 */

//Add by Johan 23-08-2016
function validate_ajax_form(userConfig)
{
    var config = {
        form_name: 'MyFormName',
        container_id: '#modalForm',
        target_success: {
            dom: '.#Target',
            element_to_add: '<tr>__content__</tr>' //__content__ will be replaced by the renderView from the controller
        },
        button_validate: {
            dom: '.#SubmitButton',
            text_waiting: '<i class="fa fa-spin fa-spinner"></i> Please wait',
            class_waiting: "disabled"
        }
    };

    //Réécriture des paramètres
    $.each(userConfig, function(key, value)
    {
        config[key] = userConfig[key];
    });

    $(config.button_validate.dom).on("click", function(e){
        e.preventDefault();

        var $form = $('[name="' + config.form_name + '"]');
        var $formInputs = $('[name="' + config.form_name + '"] input, select, textarea');
        //console.log($form);

        var values = {};
        $.each($formInputs, function(i, field) {
            //console.log(field);
            values[field.id] = field.value;
        });
        //console.log(values);

        var originalText = $(this).html();
        $(this).html(config.button_validate.text_waiting);
        $(this).attr('disabled', 'disabled');
        $(this).toggleClass(config.button_validate.class_waiting);

        $.ajax({
            type: 'POST',
            url: $form.attr('action'),
            dataType: 'json',
            data: values,
            success: function(dataReceived) {
                var $target = $(config.target_success.dom);
                $target.append(config.target_success.element_to_add.replace('__content__', dataReceived.content));

                $(config.container_id).modal('hide');
            },
            error: function() {
                $(this).html(originalText);
                $(this).toggleClass(config.button_validate.class_waiting);
                $(this).removeAttr('disabled');
            }
        });
    });
}

function dywee_handle_form_collection(container) {
    dywee_handle_form_collection(container, null);
}

function dywee_handle_form_collection(container, userConfig) {
    var config = {
        container_type: 'table',
        container_child: 'tbody',
        label: 'Element',
        allow_add: true,
        allow_delete: true,
        auto_add: true, //for add imeditalty a new element if count = 0
        add_btn: {
            container_class: '',
            target: '',
            'class': 'btn btn-default',
            icon: '',
            text: 'Ajouter un élément'
        },
        remove_btn: {
            target: '',
            'class': 'btn btn-default',
            icon: 'fa fa-trash',
            text: 'Supprimer'
        }
    };
    //Réécriture des paramètres
    if(userConfig){
        $.each(userConfig, function(key, value)
        {
            config[key] = userConfig[key];
        });
    }

    var $container = $(config.container_type+'#'+container);

    // On ajoute un lien pour ajouter une nouvelle catégorie
    if(config.allow_add == true)
    {
        var $containerAddBtn = $('<div></div>');
        if (config.add_btn.container_class != '')
            $containerAddBtn.addClass(config.add_btn.container_class);

        var $addLink = $containerAddBtn.append('<a href="#" id="add_category" class="'+config.add_btn.class+'">'+config.add_btn.icon+' '+config.add_btn.text+'</a>');


        if (config.add_btn.target == '')
            $container.append($addLink);
        else
            $container.find(config.add_btn.target).html($addLink);

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
    if (index == 0 && config.auto_add == true) {
        addCategory($container);
    } else {
        // Pour chaque catégorie déjà existante, on ajoute un lien de suppression
        var child = config.container_type;
        if (config.container_child != "") child = config.container_child;
        var $children = $container.children(child);
        if (config.container_type == 'table')
            $children = $container.children(child).children('tr');
        $children.each(function() {
            if(config.allow_delete == true)
                addDeleteLink($(this));
        });

        //$('.select2').select2();

    }

    // La fonction qui ajoute un formulaire Categorie
    function addCategory($container) {
        try
        {
            var $prototype = $($container.attr('data-prototype').replace(/__name__label__/g, config.label+' n°' + (index+1))
                .replace(/__name__/g, index));
        }
        catch(e)
        {
            var $prototype = $($container.attr('data-prototype'));
        }

        // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
        if(config.allow_delete)
            addDeleteLink($prototype);

        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;

        //$('.select2').select2();
    }

    // La fonction qui ajoute un lien de suppression d'une catégorie
    function addDeleteLink($prototype) {
        // Création du lien
        if (config.remove_btn.icon == '')
            $deleteLink = $('<a href="#" class="'+config.remove_btn.class+'">'+config.remove_btn.text+'</a>');
        else
            $deleteLink = $('<a href="#" class="'+config.remove_btn.class+'"><i class="'+config.remove_btn.icon+'"></i>'+config.remove_btn.text+'</a>');

        // Ajout du lien
        if (config.remove_btn.target == '')
            $prototype.append($deleteLink);
        else
            $prototype.find(config.remove_btn.target).html($deleteLink);

        // Ajout du listener sur le clic du lien
        $deleteLink.click(function(e) {
            $prototype.remove();
            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }

    //$('.select2').select2();
}

function dywee_handle_delete_btn() {
    $('[data-action="ajax-delete"]').unbind('click').click(function(e)
    {
        e.preventDefault();
        var $btn = $(this);
        var btn_text = $btn.html();

        $btn.html('<i class="fa fa-spinner fa-spin"></i>');
        var route = ($(this).attr('data-route')) ? Routing.generate($(this).attr('data-route'), {id: $(this).attr('data-id') }) : $(this).attr('href');

        //console.log(route);

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
    //$('.select2').select2();
});

(function ( $ ) {

    $.fn.preload = function( options ) {

        // This is the easiest way to have default options.
        var settings = $.extend({
            btn: null,
            route: null,
            routingData: {},
            ajaxData: {},
            btnContent: null,
            data: null,
            callback: null,
            loaded: false,
            compiledRoute: null,
            $btn: null,
            isBtnModified: false
        }, options);

        route(this);
        handleBtn(this);
        launchAjax();

        function handleBtn(btn)
        {
            btn.on('click', function(e)
            {
                e.preventDefault();

                if(!settings.loaded)
                {
                    settings.isBtnModified = true;
                    settings.btnContent = $(this).html();
                    $(this).find('i').attr('class', 'fa fa-spinner fa-spin');
                }

                settings.$btn = $(this);

                var checker = setInterval(function(){
                    if(settings.loaded) {
                        clearInterval(checker);
                        if(settings.btnContent && settings.isBtnModified)
                        {
                            settings.isBtnModified = false;
                            settings.$btn.html(settings.btnContent);
                        }
                        if(settings.callback)
                            settings.callback(settings.data, settings);
                        else {
                            console.log('[Preload][Error] No callback defined for btn');
                            console.log(btn);
                        }
                    }
                }, 500);
            });
        }

        function launchAjax()
        {
            $.post({
                url: settings.compiledRoute,
                dataType: 'json',
                data: settings.ajaxData,
                success: function(loadedData)
                {
                    if(loadedData.status == 'success')
                    {
                        settings.data = loadedData;
                        settings.loaded = true;
                    }
                    else console.log('Erreur dans une requête ajax (btn: '+settings.btn+')');
                }
            });
        }

        function route(btn)
        {
            console.log(btn.attr('href'));
            if(btn.attr('href') != '#' && btn.attr('href') != '')
                settings.compiledRoute = btn.attr('href');
            if(settings.route != null)
            {
                if($.isEmptyObject(settings.routingData))
                    settings.compiledRoute = Routing.generate(settings.route);
                else
                    settings.compiledRoute = Routing.generate(settings.route, settings.routingData);
            }
            console.log('ici');
            console.log(settings.compiledRoute);
        }

    };

}( jQuery ));
