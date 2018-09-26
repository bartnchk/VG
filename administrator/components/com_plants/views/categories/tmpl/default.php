<?php

defined('_JEXEC') or exit();

?>

<form action="index.php?option=com_plants&view=categories" method="post" id="adminForm" name="adminForm">

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
					<th width="1%"><?php echo JText::_('#'); ?></th>
					<th width="2%"><?php echo JHtml::_('grid.checkall'); ?></th>
					<th width="30%"><?php echo JHTML::_( 'grid.sort', 'Category title', 'title', $this->sortDirection, $this->sortColumn); ?></th>
					<th width="7%"><?php echo JHTML::_( 'grid.sort', 'Category state', 'state', $this->sortDirection, $this->sortColumn); ?></th>
					<th width="5%"><?php echo JHTML::_( 'grid.sort', 'ID', 'id', $this->sortDirection, $this->sortColumn); ?></th>
				</tr>
				</thead>

				<tbody>
				<?php if(!empty($this->items)) { ?>

					<?php foreach($this->items as $i=>$row) {?>

						<?php $link = 'index.php?option=com_plants&task=category.edit&id='.$row->id; ?>
						<tr>
							<td><?php echo $this->pagination->getRowOffset($i); ?></td>
							<td><?php echo JHtml::_('grid.id', $i, $row->id); ?></td>
							<td><a href="<?php echo $link; ?>"><?php echo $row->title; ?></a></td>
							<td><?php echo JHtml::_('jgrid.published', $row->state, $i, 'categories.', true); ?></td>
							<td><?php  echo $row->id; ?></td>
						</tr>
					<?php } ?>
				<?php } ?>
				</tbody>

				<tfoot>
				<tr>
					<td colspan="7">
						<div class="pagination">
                            <?php echo $this->pagination->getLimitBox(); ?>
                            <?php echo $this->pagination->getListFooter(); ?>
						</div>
					</td>
				</tr>
				</tfoot>

			</table>

			<input type="hidden" name="task" value="">
			<input type="hidden" name="boxchecked" value="">
			<?php echo JHtml::_('form.token'); ?>

		</div>

		<input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />

</form>