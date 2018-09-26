<?php defined('_JEXEC') or exit(); ?>

<form action="index.php?option=com_plants&view=plants" method="post" id="adminForm" name="adminForm">

    <?php if (!empty( $this->sidebar )) { ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>



    <div id="j-main-container" class="span10">
        <?php } else { ?>
        <div id="j-main-container">
            <?php } ?>

            <?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

            <table class="table table-stripped table-hover">

                <thead>
                <tr>
                    <th width="1%"><?php echo JText::_('#'); ?></th>
                    <th width="2%"><?php echo JHtml::_('grid.checkall'); ?></th>
                    <th width="10%"><?= JHTML::_( 'grid.sort', 'Title', 'p.sort_name', $this->sortDirection, $this->sortColumn); ?></th>
                    <th width="10%"><?= JHTML::_( 'grid.sort', 'Type', 't.title', $this->sortDirection, $this->sortColumn); ?></th>
                    <th width="25%"><?= JHTML::_( 'grid.sort', 'User', 'u.id', $this->sortDirection, $this->sortColumn); ?></th>
                    <th width="5%"><?= JHTML::_( 'grid.sort', 'Published', 'p.published', $this->sortDirection, $this->sortColumn); ?></th>
                    <th width="5%"><?= JHTML::_( 'grid.sort', 'Plant ID', 'p.id', $this->sortDirection, $this->sortColumn); ?></th>
                </tr>
                </thead>

                <tbody>
                <?php if(!empty($this->items)) { ?>
                    <?php foreach($this->items as $i=>$row) { ?>
                        <?php $link = 'index.php?option=com_plants&task=plant.edit&id='.$row->id; ?>
                        <tr>
                            <td><?php echo $this->pagination->getRowOffset($i); ?></td>
                            <td><?php echo JHtml::_('grid.id', $i, $row->id); ?></td>
                            <td>
                                <a href="<?php echo $link; ?>"><?php echo $row->sort_name; ?></a>
                            </td>
                            <td><?= $row->type ?></td>
                            <td><?= $row->name ?></td>
                            <?php if ($row->published == 2) : ?>
                                <td align="center"><span style="color: red">moderation</span></td>
                            <?php else : ?>
                                <td align="center"><?php echo JHtml::_('jgrid.published', $row->published, $i, 'plants.', true); ?></td>
                            <?php endif; ?>
                            <td><?= $row->id ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>

                <tfoot>
                <tr>
                    <td colspan="7">
                        <div class="pagination">
                            <?= $this->pagination->getListFooter(); ?>
                        </div>
                    </td>
                </tr>
                </tfoot>

            </table>

            <input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
            <input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />

            <input type="hidden" name="task" value="">
            <input type="hidden" name="boxchecked" value="">
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </div>
</form>