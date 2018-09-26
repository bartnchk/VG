<?php

defined('_JEXEC') or exit();

JHtml::_( 'formbehavior.chosen', 'select' );
JHtml::_('behavior.formvalidation');
?>

<form action="index.php?option=com_plants&layout=edit&id=<?php echo $this->item->id; ?>" method="post" id="adminForm" name="adminForm" class="form-validate" enctype="multipart/form-data">

	<div class="form-horizontal">
		<fieldset class="adminform">

			<div class="row-fluid">
				<div class="span12">
					<?php echo $this->form->renderFieldset('post')?>
				</div>
			</div>
		</fieldset>
	</div>

	<input type="hidden" name="task" value="">
	<?php echo JHtml::_('form.token'); ?>

</form>