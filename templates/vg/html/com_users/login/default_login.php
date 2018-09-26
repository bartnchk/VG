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

$params = JComponentHelper::getParams('com_plants');

$fb_id = $params->get('id');
$fb_url = JUri::base() . 'auth/fbAuth';

$g_id = $params->get('google_id');
$g_url = JUri::base() . 'auth/gAuth';

function getSiteKey()
{
    $plugin = JPluginHelper::getPlugin('captcha', 'recaptcha');
    $pluginParams = new JRegistry();
    $pluginParams->loadString($plugin->params);

    return $pluginParams->get('public_key');
}

?>

<main class="signInPage" style="background-image: url('/templates/vg/img/signin-page.jpg')">

    <div class="card">
        <div class="signUpIn text-right">
            <a class="btn btn-pink btn-sm ml-auto"><?= JText::_('COM_PLANTS_SIGN_IN') ?></a>
            <a href="signup" class="btn btn-sm btn-pink inactive ml-2"><?= JText::_('COM_PLANTS_SIGN_UP') ?></a>
        </div>
        <div class="our_form">

            <div class="title"><?= JText::_('COM_PLANTS_SIGN_IN') ?></div>

            <div class="signWith d-flex justify-content-between mb-3">
                <a href="https://www.facebook.com/v2.11/dialog/oauth?client_id=<?= $fb_id ?>&redirect_uri=<?= $fb_url ?>&response_type=code&scope=public_profile,email" class="btn btn-lightBlue btn-lg"><?= JText::_('COM_PLANTS_SIGN_IN_WITH_FACEBOOK') ?></a>
                <a href="https://accounts.google.com/o/oauth2/auth?redirect_uri=<?= $g_url ?>&response_type=code&client_id=<?= $g_id ?>&scope=https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile" class="btn btn-blue btn-lg"><?= JText::_('COM_PLANTS_SIGN_IN_WITH_GOOGLE') ?></a>
            </div>

            <form action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post" class="form-validate form-horizontal well">
                <div class="input form-group">
                    <input type="text" name="username" id="username" value="" class="validate-username required form-control-lg w-100" size="25" required="" aria-required="true" autofocus="">
                </div>

                <div class="input form-group">
                    <input type="password" name="password" id="password" value="" class="validate-password required form-control-lg w-100" size="25" maxlength="99" required="" aria-required="true">
                </div>

                <div class="form-check mt-3">
                    <label class="form-check-label d-flex justify-content-between">
                        <input class="form-check-input" type="checkbox" name="remember" checked>
                        <?= JText::_('COM_PLANTS_REMEMBER_ME') ?>
                        <a href="/reset" class="forgotPswd"><?= JText::_('COM_PLANTS_FORGOT_PASSWORD') ?></a>
                    </label>
                </div>

                <div class="g-recaptcha" data-sitekey="<?= getSiteKey(); ?>"></div>

                <button type="submit" class="btn btn-green btn-lg btn-block mt-3"><?= JText::_('COM_PLANTS_SIGN_IN') ?></button>

                <?php $return = $this->form->getValue('return', '', $this->params->get('login_redirect_url', $this->params->get('login_redirect_menuitem'))); ?>
                <input type="hidden" name="return" value="<?php echo base64_encode($return); ?>" />
                <?= JHtml::_('form.token'); ?>
            </form>

        </div>
    </div>
</main>