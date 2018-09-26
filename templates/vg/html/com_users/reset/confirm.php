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

$lang = JFactory::getLanguage();
$extension = 'com_plants';
$base_dir = JPATH_SITE;
$language_tag = $lang->getTag();
$reload = true;
$lang->load($extension, $base_dir, $language_tag, $reload);

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
            <form id="user-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=reset.confirm'); ?>" method="post" class="form-validate form-horizontal well">
                <div class="input form-group">
                    <label class="mb-2"><?= JText::_('COM_PLANTS_USERNAME') ?></label>
                    <input type="text" name="jform[username]" id="jform_username" value="" class="form-control-lg w-100 required" size="30" required="" aria-required="true">
                    <label class="mb-2"><?= JText::_('COM_PLANTS_VERIFICATION_CODE') ?></label>
                    <input type="text" name="jform[token]" id="jform_token" value="" class="form-control-lg w-100 required" size="32" required="" aria-required="true">
                </div>
                <button type="submit" class="btn btn-green btn-lg btn-block mt-3 validate">
                    <?= JText::_('COM_PLANTS_SUBMIT') ?>
                </button>
                <?php echo JHtml::_('form.token'); ?>
            </form>
        </div>
    </div>
</main>


