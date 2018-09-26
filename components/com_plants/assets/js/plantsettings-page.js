jQuery( document ).ready(function(){

    //init city field selectize
    citySlz();

    //save current custom fields data
    var currentType     = getCurrentType();
    var currentTypeData = getCurrentTypeData();
    var currentCategory = getCurrentCategory();

    //load plant types if category has been chosen
    if( currentCategory && !currentType) 
        getTypesData( currentCategory );

    //disable plant type field if category don't chosen
    if(currentCategory == 0)
        jQuery('#jform_plant_type_id').prop('disabled', 'disabled').trigger("liszt:updated");


    jQuery('#jform_plant_category_id').change(function(){

        resetPlantFields();

        jQuery('#jform_plant_type_id').prop('disabled', '').trigger("liszt:updated");

        var categoryId = jQuery(this).val();
        getTypesData(categoryId);
    });

    jQuery('#jform_plant_type_id').change(function(){

        if(currentType == getCurrentType())
            jQuery('#custom-fields').html(currentTypeData);
        else
            getPlantFields();
    });

    jQuery('.delete-plant-photo').on('click', function(){

        var src   = jQuery(this).attr('data-src');
        var type  = 'photo';
        var token = jQuery('#token').attr('name');

        jQuery.ajax({
            type: 'POST',
            url: 'index.php?option=com_plants&task=plantsettings.deletePhoto&' + token + '=1',
            data: { 'filename' : src, 'type' : type },
            success: function () {
                location.reload();
            }
        });
    });


    //submit form handler
    jQuery('#plantform').on('submit', function(event){

        if( !validForm() )
        {
            alert('Fill all required fields');
            event.preventDefault();
        }

    });

    jQuery('input[name="preseeding"]').on('click', function(){
        jQuery('.trans-wrap').toggle();
    });

    //get plant types by category id
    function getTypesData(categoryId) {

        jQuery.ajax({
            type: 'POST',
            url: window.location.protocol+'//'+window.location.host + '/administrator/components/com_plants/assets/get_plant_types.php',
            data: { id: categoryId },
            success: function (result) {
                setPlantTypes(result);
            }
        });
    }


    //set plant types
    function setPlantTypes(data) {

        var options = JSON.parse(data);
        var html = '<option value="0">Select type</option>\n';

        options.forEach(function(item){
            html += '<option value="' + item.id + '">' + item.title + '</option>\n';
        });

        jQuery('#jform_plant_type_id').html(html).trigger("liszt:updated");
    }


    //get fields from plant type id
    function getPlantFields() {

        var plantTypeId = jQuery('#jform_plant_type_id').val();

        if(plantTypeId) {
            jQuery.ajax({
                type: 'POST',
                url: window.location.protocol+'//'+window.location.host + '/administrator/components/com_plants/assets/get_plant_fields.php',
                data: { id: plantTypeId },
                success: function (result) {
                    if(result)
                        addPlantFields(result);
                    else
                        resetPlantFields();
                }
            });
        }
    }


    //create custom fields inside div#custom-fields
    function addPlantFields(data) {

        var html = '';
        var fields = JSON.parse(data);

        fields.forEach(function(item){
            html += '<div class="form-group">';
            html +=     '<label id="jform_custom' + item.id + '-lbl" for="jform_custom' + item.id + '">';
            html +=         item.name;
            html +=     '</label>';
            html +=     '<input type="text" name="jform[custom_fields][' + item.id + ']" id="jform_custom' + item.id + '" class="form-control">';
            html += '</div>';
        });

        jQuery('#custom-fields').html(html);
    }


    //clear html inside div#custom-fields
    function resetPlantFields() {
        jQuery('#custom-fields').html('');
    }


    //return current category id
    function getCurrentCategory() {
        return jQuery('#jform_plant_category_id').val();
    }


    //return current type id
    function getCurrentType() {
        return jQuery('#jform_plant_type_id').val();
    }


    //return current html inside div#custom-fields
    function getCurrentTypeData() {
        return jQuery('#custom-fields').html();
    }


    //plant fields validator
    function validForm() {

        var sortName      = jQuery('#jform_sort_name').val();
        var plantCategory = jQuery('#jform_plant_category_id').val();
        var plantPhoto    = jQuery('#my-file').val();

        if(jQuery('.plant-photo').length > 0)
            plantPhoto = true;


        //valid email field if user logged in
        if (jQuery('#email').length > 0) {
            var email = jQuery('#email').val();
            if( !validEmail(email) ) return false;
        }

        if( sortName &&
            plantCategory &&
            plantPhoto
        ) return true;

        return false;
    }


    //delete plant photo
    jQuery('.delete-plant-photo').on('click', function(){
        var src = jQuery(this).parent().find('img').attr('alt');
        deletePhoto(src, 'photo');
    });


    //delete barcode photo
    jQuery('#delete-barcode-photo').on('click', function(){
        var src = jQuery('#barcode-photo').attr('alt');
        deletePhoto(src, 'barcode');
    });


    //delete seeds photo
    jQuery('#delete-seeds-photo').on('click', function(){
        var src = jQuery('#seeds-photo').attr('alt');

        deletePhoto(src, 'seeds');
    });


    function deletePhoto(filename, type){

        var token = jQuery('#token').attr('name');

        jQuery.ajax({
            type: 'POST',
            url: window.location.protocol + '//' + window.location.host + '/index.php?option=com_plants&task=plantsettings.deletePhoto&' + token + '=1' ,
            data: { filename: filename, type: type },
            success: function () {
                location.reload();
            }
        });
    }


    function validEmail(email) {
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email))
            return (true);

        return (false);
    }


    //city field selectize init
    function citySlz() {

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
    }


});
