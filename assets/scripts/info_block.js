$(document).ready(function(){
    var toggled=false;
    $('.description').click(function(){
        if(!toggled){
            toggled=true;
            $('.description_wrapper').css('height', 'auto');
            $('.description_toggle').html('Less...');
            $(this).css('cursor', 'auto');
        }
    });
    $('.description_toggle').click(function(){
        if(!toggled){
            toggled=true;
            $('.description_wrapper').css('height', 'auto');
            $('.description_toggle').html('Less...');
        }else{
            toggled=false;
            $('.description_wrapper').css('height', '10em;');
            $('.description_toggle').html('More...');
            $('.description').css('cursor', 'pointer');
        }
    });
});