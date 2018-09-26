function subscribe(email) {
    jQuery.ajax({
        url: window.location.protocol+'//'+window.location.host + '/index.php?option=com_plants&task=main.subscribe',
        type: 'POST',
        data: {email: email},
        success: function (result) {

             if(result === 'success')
                 alert('Done!');
             else
                 alert('Failed!');

        }
    });
}