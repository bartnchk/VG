<?php
    defined('_JEXEC') or exit();
?>

<form action="index.php?option=com_plants&view=cities" method="post" id="adminForm" name="adminForm">

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
                        <th width="3%"><?php echo JHtml::_('grid.checkall'); ?></th>
                        <th width="50%"><?= JHTML::_( 'grid.sort', 'City', 'name_en', $this->sortDirection, $this->sortColumn); ?></th>
                        <th width="14">Lat</th>
                        <th width="14">Lon</th>
                        <th width="14%"><?= JHTML::_( 'grid.sort', 'ID', 'id', $this->sortDirection, $this->sortColumn); ?></th>
                    </tr>
                </thead>

                <tfoot>
                    <tr>
                        <td colspan="6">
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
                        <?php $link = 'index.php?option=com_plants&task=city.edit&id='.$row->id; ?>
                        <tr>
                            <td><?php echo $this->pagination->getRowOffset($i); ?></td>
                            <td><?php echo JHtml::_('grid.id', $i, $row->id); ?></td>
                            <td><a href="<?php echo $link; ?>"><?php echo $row->name_en; ?></a></td>
                            <td><?= $row->lat; ?></td>
                            <td><?= $row->lon; ?></td>
                            <td><?= $row->id; ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>

            </table>

            <input type="hidden" name="task" value="">
            <input type="hidden" name="boxchecked" value="">

            <input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
            <input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />

            <?php echo JHtml::_('form.token'); ?>

        </div>
    </div>
</form>