<?php

defined('_JEXEC') or exit();

JHtml::_('jquery.framework');
JHtml::_('behavior.formvalidator');
JHtml::_( 'formbehavior.chosen', 'select' );

JFactory::getDocument()->addScriptDeclaration('
	Joomla.submitbutton = function(task)
	{
		if (task == "plant.cancel" || document.formvalidator.isValid(document.getElementById("plant-form")))
		{
			Joomla.submitform(task, document.getElementById("plant-form"));
		}
	};
');
?>

<form action="index.php?option=com_plants&layout=edit&id=<?php echo $this->item->id; ?>" method="post" id="plant-form" name="adminForm" class="form-validate" enctype="multipart/form-data">

    <div class="form-horizontal">
        <fieldset class="adminform">
            <legend><?= 'Plantfield details' ?></legend>
            <div class="row-fluid">
                <div class="span12">
                    <?= $this->form->renderFieldset('plantfield')?>
                </div>
            </div>
        </fieldset>
    </div>

    <input type="hidden" name="task" value="">
    <?php echo JHtml::_('form.token'); ?>

</form>


