<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');

?>

<main class="passwordResetPage" style="background-image: url('/templates/vg/img/pass_bg.jpg')">
    <!-- your code -->
    <div class="card">
        <div class="signUpIn text-right">
            <a href="login" class="btn btn-sm btn-pink inactive ml-auto"><?= JText::_('COM_PLANTS_SIGN_IN') ?></a>
            <a href="signup" class="btn btn-sm btn-pink inactive ml-2"><?= JText::_('COM_PLANTS_SIGN_UP') ?></a>
        </div>
        <div class="our_form">
            <div class="title"><?= JText::_('COM_PLANTS_PASSWORD_RESET') ?></div>
            <p><?= JText::_('COM_PLANTS_PASSWORD_RESET_TEXT') ?></p>
            <form id="user-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=reset.complete'); ?>" method="post" class="form-validate form-horizontal well">
                <div class="input form-group">
                    <label class="mb-2"><?= JText::_('COM_PLANTS_PASSWORD') ?></label>
                    <input type="password" name="jform[password1]" id="jform_password1" value="" autocomplete="off" class="form-control-lg w-100 validate-password required" size="30" maxlength="99" required="" aria-required="true">
                    <label class="mb-2"><?= JText::_('COM_PLANTS_PASSWORD_RESET_TEXT') ?></label>
                    <input type="password" name="jform[password2]" id="jform_password2" value="" autocomplete="off" class="form-control-lg w-100 validate-password required" size="30" maxlength="99" required="" aria-required="true">
                </div>
                <button type="submit" class="btn btn-green btn-lg btn-block mt-3 validate">
                    <?= JText::_('COM_PLANTS_SUBMIT') ?>
                </button>
                <?php echo JHtml::_('form.token'); ?>
            </form>
        </div>
    </div>
</main>

<?= JText::_('COM_PLANTS_') ?>
