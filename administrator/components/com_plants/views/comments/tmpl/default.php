<?php defined('_JEXEC') or exit(); ?>

<form action="index.php?option=com_plants&view=comments" method="post" id="adminForm" name="adminForm">

	<?php if (!empty( $this->sidebar )) { ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php } else { ?>

		<div id="j-main-container">
			<?php } ?>

            <?php
            // Search tools bar
            echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
            ?>

			<table class="table table-stripped table-hover">
				<thead>
                    <tr>
                        <th width="1%"><?= JText::_('#'); ?></th>
                        <th width="2%"><?= JHtml::_('grid.checkall'); ?></th>
                        <th width="5%">Image</th>
                        <th width="8%"><?= JHTML::_( 'grid.sort', 'Plant', 'sort_name', $this->sortDirection, $this->sortColumn); ?></th>
                        <th width="10%"><?= JHTML::_( 'grid.sort', 'User', 'user_id', $this->sortDirection, $this->sortColumn); ?></th>
                        <th width="40%">Comments</th>
                        <th width="10%"><?= JHTML::_( 'grid.sort', 'Date', 'created_at', $this->sortDirection, $this->sortColumn); ?></th>
                        <th width="7%"><?= JHTML::_( 'grid.sort', 'State', 'state', $this->sortDirection, $this->sortColumn); ?></th>
                    </tr>
				</thead>

				<tbody>
				<?php if(!empty($this->items)) { ?>

					<?php foreach($this->items as $i=>$row) { ?>
						<tr>
							<td><?php echo $this->pagination->getRowOffset($i); ?></td>
							<td><?php echo JHtml::_('grid.id', $i, $row->id); ?></td>
                            <td>
							<?php if (!empty($row->src)) {?>
								<img src="<?php echo JUri::root() .'images/plants/'. $row->src?>" alt="" style="width: 100px">
							<?php } else {?>
                                <img src="data:image/png;base64,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" alt="">
                            <?php }?>
                            </td>
							<td><?= $row->sort_name; ?></td>
							<?php if ($row->first_name || $row->last_name) : ?>
                                <td><?= $row->first_name . ' ' . $row->last_name; ?></td>
                            <?php else : ?>
                                <td><?= $row->email ?></td>
                            <?php endif; ?>
                            <td><?= $row->comment; ?></td>
							<td><?= date('d-m-Y', strtotime($row->created_at)); ?></td>
							<td><?= JHtml::_('jgrid.published', $row->state, $i, 'comments.', true); ?></td>
						</tr>
					<?php } ?>
				<?php } ?>
				</tbody>

				<tfoot>
				<tr>
					<td colspan="8">
						<div class="pagination">
<!--							--><?php //echo $this->pagination->getLimitBox(); ?>
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