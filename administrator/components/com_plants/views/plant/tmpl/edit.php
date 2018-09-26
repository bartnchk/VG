<?php

defined('_JEXEC') or exit();

JHtml::_('jquery.framework');
JHtml::_('behavior.formvalidator');
//JHtml::_( 'formbehavior.chosen', 'select' );

JFactory::getDocument()->addScriptDeclaration('
	Joomla.submitbutton = function(task)
	{
		if (task == "plant.cancel" || document.formvalidator.isValid(document.getElementById("plant-form")))
		{
			Joomla.submitform(task, document.getElementById("plant-form"));
		}
	};
');

JFactory::getDocument()->addScript('https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.3/js/standalone/selectize.js');
JFactory::getDocument()->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.3/css/selectize.default.min.css');

?>

<form action="index.php?option=com_plants&layout=edit&id=<?php echo $this->item->id; ?>" method="post" id="plant-form" name="adminForm" class="form-validate" enctype="multipart/form-data">

    <div class="form-horizontal">

        <legend><?= JText::_('PLANT_STATUS'); ?></legend>
        <?= $this->form->renderFieldset('state') ?>

        <fieldset class="adminform">
            <legend><?= JText::_('PLANT_DETAILS'); ?></legend>
            <div class="row-fluid">

                <div class="span4">
                    <?= $this->form->renderField('sort_name') ?>
                    <?= $this->form->renderField('alias') ?>
                    <?= $this->form->renderField('plant_category_id') ?>
                    <?= $this->form->renderField('plant_type_id') ?>
                    <?= $this->form->renderField('description') ?>

                    <div class="control-group">
                        <div class="control-label">
                            <label id="jform_barcode-lbl" for="jform_barcode">
                                City
                            </label>
                        </div>
                        <div class="controls">
                            <select name="city_id" id="jform_city" style="width: 220px">
                                <option value="<?= $this->item->city_id ?>"><?= $this->item->city ?></option>
                            </select>
                        </div>
                    </div>

                    <?= $this->form->renderField('seeds_photo') ?>
                    <?= $this->form->renderField('barcode_photo') ?>
                    <?= $this->form->renderField('barcode') ?>
                    <?= $this->form->renderField('manufactured') ?>
                    <?= $this->form->renderField('planting_date') ?>
                    <?= $this->form->renderField('sowing_date') ?>
                    <?= $this->form->renderField('preseeding') ?>
                    <?= $this->form->renderField('transplantation_date') ?>
                    <?= $this->form->renderField('purchased') ?>
                    <?= $this->form->renderField('price') ?>
                    <?= $this->form->renderField('rate') ?>
                    <?= $this->form->renderField('top_plant') ?>
                </div>

                <div class="span4" id="custom-fields">
                    <?= $this->form->renderFieldset('custom')?>
                </div>

                <div class="span4" style="display: flex; flex-wrap: wrap;">


                    <?php
                        if($this->photos)
                        {
                            foreach ($this->photos as $item)
                                echo '<div style="width: 200px; margin: 0 10px 10px 10px; border: 1px solid lightgrey">
                                        <div>
                                            <img src="' . JUri::root() . 'images/plants/' . $item->src . '" />
                                        </div>
                                        
                                        <div style="text-align: center; padding: 5px;">
                                            <a href="#" class="btn btn-default deletephoto" data-src="' . $item->src . '">delete</a>
                                        </div>
                                      </div>';
                        }
                    ?>
                </div>

            </div>
        </fieldset>
    </div>

    <input type="hidden" name="task" value="">
    <?php echo JHtml::_('form.token'); ?>

</form>


<script>

    var currentType = '';
    var currentTypeData = '';

    jQuery( document ).ready(function(){

        var category_id = jQuery('#jform_plant_category_id').val();

        //save current custom fields data
        currentType     = getCurrentType();
        currentTypeData = getCurrentTypeData();

        if(category_id == 0)
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

        jQuery('.deletephoto').on('click', function(){
            var src = jQuery(this).attr('data-src');

            jQuery.ajax({
                type: 'POST',
                url: 'index.php?option=com_plants&task=plant.deletePhoto',
                data: { src: src },
                success: function (result) {
                    location.reload();
                }
            });

        })
    });

    //get plant types from category id
    function getTypesData(categoryId) {

        jQuery.ajax({
            type: 'POST',
            url: '<?= JUri::base() ."components/com_plants/assets/get_plant_types.php"?>',
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
                url: '<?= JUri::base() ."components/com_plants/assets/get_plant_fields.php"?>',
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
            html += '<div class="control-group">';
            html +=     '<div class="control-label">';
            html +=         '<label id="jform_custom' + item.id + '-lbl" for="jform_custom' + item.id + '">';
            html +=             item.name;
            html +=         '<label>';
            html +=     '</div>';
            html +=     '<div class="controls">';
            html +=         '<input type="text" name="jform[custom_fields][' + item.id + ']" id="jform_custom' + item.id + '" class="inputbox">';
            html +=     '</div>';
            html += '</div>';
        });

        jQuery('#custom-fields').html(html);
    }

    //clear html inside div#custom-fields
    function resetPlantFields() {
        jQuery('#custom-fields').html('');
    }

    //return current type id of plant
    function getCurrentType() {
        return jQuery('#jform_plant_type_id').val();
    }

    //return current html inside div#custom-fields
    function getCurrentTypeData() {
        return jQuery('#custom-fields').html();
    }

    jQuery('#jform_city').selectize({
        maxItems: 1,
        load: function (query, callback) {
            if (!query.length) return callback();
            jQuery.ajax({
                type: 'POST',
                url: window.location.protocol + '//' + window.location.host + '/index.php?option=com_plants&task=plantsettings.getCities',
                data: {query: query},
                success: function (result) {
                    console.log(JSON.parse(result).length);
                    callback(JSON.parse(result));
                }
            });
        }
    });

</script>


