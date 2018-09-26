<?php

defined('_JEXEC') or exit();

JHtml::_( 'formbehavior.chosen', 'select' );
JHtml::_('behavior.formvalidation');

?>

<form action="index.php?option=com_plants&layout=edit&id=<?php echo $this->item->id; ?>" method="post" id="adminForm" name="adminForm" class="form-validate" enctype="multipart/form-data">

	<div class="form-horizontal">
		<fieldset class="adminform">

			<legend><?php echo JText::_('Edit user profile'); ?></legend>
			<div class="row-fluid">
				<div class="span9">
                    <div class="photo" style="padding-bottom: 20px">
                        <?php if($this->item->photo) : ?>
                            <img src="<?= JUri::root() . 'images/user_photos/' . $this->item->photo?>"  class="img-rounded" style="height: 300px">
                        <?php endif; ?>
                        <div>
                        <?php if(!empty($this->item->photo) && $this->item->photo != 'user.svg') :?>
                            <button style="margin-top: 10px" class="btn btn-danger delete" type="button">Delete photo</button>
                        <?php endif;?>
                        </div>
                    </div>
					<?php echo $this->form->renderField('name')?>
					<?php echo $this->form->renderField('username')?>
					<?php echo $this->form->renderField('password')?>
					<?php echo $this->form->renderField('email')?>
                    <?php if(!empty($this->item->photo)) :?>

                    <?php endif;?>
                    <div class="btn-group">


					<?= $this->form->renderField('photo') ?>

                    </div>
					<?php echo $this->form->renderField('gender')?>
					<?php echo $this->form->renderField('experience')?>
                    <?php echo $this->form->renderField('city_id')?>
					<?php echo $this->form->renderField('about_me')?>
                    <?php echo $this->form->renderField('secret_question')?>
                    <?php echo $this->form->renderField('secret_answer')?>
				</div>
				<div class="span3">
					<?php echo $this->form->renderFieldset('user_global')?>
				</div>
			</div>
		</fieldset>
	</div>

    <input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>

</form>

<script>
    jQuery(document).ready(function () {
        jQuery('.delete').on('click', function () {
            jQuery.ajax({
                type: 'POST',
                url: '<?= JUri::base() ."components/com_plants/assets/ajax.php"?>',
                data: { id: '<?= $this->item->id;?>' , src: '<?= $this->item->photo;?>' },
                success: function () {
                    location.reload();
                }
            })
        })
    })
</script>