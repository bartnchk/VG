<?php

defined('_JEXEC') or die;
$loggeduser = JFactory::getUser();

?>
<form action="index.php?option=com_plants&view=users" method="post" id="adminForm" name="adminForm">

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
					<th width="10%"><?= JHTML::_( 'grid.sort', 'Login', 'username', $this->sortDirection, $this->sortColumn); ?></th>
					<th width="10%">Name</th>
					<th width="7%"><?php echo JHTML::_( 'grid.sort', 'Email', 'email', $this->sortDirection, $this->sortColumn); ?></th>

					<th width="7%"><?php echo JHTML::_( 'grid.sort', 'State', 'block', $this->sortDirection, $this->sortColumn); ?></th>
					<th width="5%"><?php echo JHTML::_( 'grid.sort', 'ID', 'id', $this->sortDirection, $this->sortColumn); ?></th>
				</tr>
				</thead>

				<tbody>
				<?php if(!empty($this->items)) { ?>
					<?php foreach($this->items as $i=>$row) {
						$canChange = $loggeduser->authorise('core.edit.state',	'com_users');
						?>
						<?php $link = 'index.php?option=com_plants&task=user.edit&id='.$row->id; ?>
						<?php if(!$row->user_id_request) : ?>
                            <tr>
                        <?php else : ?>
                            <tr style="background-color: #ff6666">
                        <?php endif; ?>
							<td><?php echo $this->pagination->getRowOffset($i); ?></td>
							<td><?php echo JHtml::_('grid.id', $i, $row->juser_id); ?></td>
							<td><a href="<?php echo $link; ?>"><?php echo $row->username; ?></a></td>
							<td><?php echo $row->name; ?></td>
							<td><?php echo $row->email; ?></td>
							<td><?php if ($canChange) : ?>
									<?php
									$self = $loggeduser->id == $row->juser_id;
									echo JHtml::_('jgrid.state', JHtmlPlants::blockStates($self), $row->block, $i, 'users.', !$self);
									?>
								<?php else : ?>
									<?php echo JText::_($row->block ? 'JNO' : 'JYES'); ?>
								<?php endif; ?></td>
							<td><?php  echo $row->juser_id; ?></td>
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
