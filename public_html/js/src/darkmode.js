checkDarkMode = function() {
    if($(this).prop('checked'))
    {
        $('body').addClass('dark-mode');
        $('nav').addClass('dark-mode');
        $('button').addClass('dark-mode');
        $('.navbar-brand').addClass('dark-mode');
        $('.dropdown-toggle').addClass('dark-mode');
        $('.label').addClass('dark-mode');
        $('.label-success').addClass('dark-mode');
        $('#profilePic').addClass('dark-mode');
        $('#brandIcon').addClass('dark-mode');
        $('.panel').addClass('dark-mode');
        $('.panel-body').addClass('dark-mode');


    }
    else
    {
        $('body').removeClass('dark-mode');
        $('nav').removeClass('dark-mode');
        $('button').removeClass('dark-mode');
        $('.navbar-brand').removeClass('dark-mode');
        $('.dropdown-toggle').removeClass('dark-mode');
        $('.label').removeClass('dark-mode');
        $('.label-success').removeClass('dark-mode');
        $('#profilePic').removeClass('dark-mode');
        $('#brandIcon').removeClass('dark-mode');
        $('.panel').removeClass('dark-mode');
        $('.panel-body').removeClass('dark-mode');


    }

}
$('#mode').change(checkDarkMode());
