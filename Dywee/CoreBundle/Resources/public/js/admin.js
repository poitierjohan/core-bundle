/**
 * Created by Olivier on 6/02/15.
 */
$("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");

    var request = $.ajax({
        url: 'ajax.php?action=track_side_bar',type: 'post', dataType: 'json',
        data: {type: ($("#wrapper").hasClass('toggled'))?'mini':'normal'}
    });

});