<?php defined('_JEXEC') or exit(); ?>

<form action="index.php?option=com_sitemap&view=items" method="post" id="adminForm" name="adminForm">

	<?php if (!empty( $this->sidebar )) { ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span">
	<?php } else { ?>
	<div id="j-main-container">
		<?php } ?>

		<table class="table table-striped">

			<thead>
			<tr>
				<th width="20%">Loc</th>
				<th width="20%">Changefreq</th>
				<th width="20%">Lastmode</th>
				<th width="20%">Priority</th>
			</tr>
			</thead>
            <tfoot>
            <tr>
                <td colspan="5">
                    <div class="pagination">
						<?php echo $this->pagination->getListFooter(); ?>
						<?php echo $this->pagination->getLimitBox(); ?>
                    </div>
                </td>
            </tr>
            </tfoot>

			<tbody>
			<?php if(!empty($this->items)) { ?>
				<?php foreach($this->items as $i=>$row) { ?>
					<tr>
						<td><?= $row->loc;?></td>
						<td><?= $row->changefreq?></td>
						<td><?= $row->lastmode?></td>
						<td><?= $row->priority?></td>
					</tr>
				<?php } ?>
			<?php } ?>
			</tbody>

		</table>

		<input type="hidden" name="task" value="">
		<input type="hidden" name="boxchecked" value="">
		<?php echo JHtml::_('form.token'); ?>

</form>
