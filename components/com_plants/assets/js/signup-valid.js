jQuery(document).ready(function(){

    jQuery('form').on('submit', function(e){
        var email    = jQuery('input[name="jform[email]"]').val();
        var password = jQuery('input[name="jform[password]"]').val();
        //var captcha  = grecaptcha.getResponse();

        /*if(captcha.trim() === '') {
            return false;
        }*/

        if(!email || !password /*|| !captcha*/)
        {
            alert('Fill the form correctly');
            return false;
        }
    });
})