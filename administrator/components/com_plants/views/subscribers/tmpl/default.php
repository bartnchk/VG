<?php
    defined('_JEXEC') or exit();
?>

<form action="index.php?option=com_plants&view=subscribers" method="post" id="adminForm" name="adminForm">

	<?php if (!empty( $this->sidebar )) { ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>



	<div id="j-main-container" class="span10">
		<?php } else { ?>
		<div id="j-main-container">
			<?php } ?>



			<table class="table table-stripped table-hover">

				<thead>
				<tr>
					<th width="2%"><?php echo JText::_('#'); ?></th>
					<th width="3%"><?php echo JHtml::_('grid.checkall'); ?></th>
					<th width="95%">E-mail</th>
				</tr>
				</thead>

				<tfoot>
				<tr>
					<td colspan="5">
						<div class="pagination">
<!--							--><?php //echo $this->pagination->getListFooter(); ?>
						</div>
					</td>
				</tr>
				</tfoot>

				<tbody>

				<?php if(!empty($this->items)) { ?>
					<?php $i = 1; foreach($this->items as $item) { ?>
						<tr>
                            <td><?php echo $i++;?></td>
                            <td><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
							<td><?php echo $item->email;?></td>
						</tr>
					<?php } ?>
				<?php } ?>
				</tbody>

			</table>

			<input type="hidden" name="task" value="">
			<input type="hidden" name="boxchecked" value="">
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</div>
</form>