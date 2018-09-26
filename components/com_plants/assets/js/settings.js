jQuery(window).on('load', function(){

    jQuery('#city').selectize({
        maxItems: 1,
        load: function (query, callback) {
            if (!query.length) return callback();
            jQuery.ajax({
                type: 'POST',
                url: window.location.protocol + '//' + window.location.host + '/index.php?option=com_plants&task=plantsettings.getCities',
                data: {query: query},
                success: function (result) {
                    callback(JSON.parse(result));
                }
            });
        }
    });

    jQuery('.form-wrap').on('submit', function(){
        pass1 = jQuery('#jform_password1').val();
        pass2 = jQuery('#jform_password1').val();

        if(pass1.trim() || pass2.trim()){
            if(pass1 !== pass2)
            {
                alert('Password do not match');
                return false;
            }
        }

        //check privacy policy field confirmation
        if(!jQuery('#privacy_policy').is(':checked'))
        {
            alert('You must confirm privacy policy');
            return false;
        }

    });
});
