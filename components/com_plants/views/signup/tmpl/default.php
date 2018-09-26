<?php

require_once (JPATH_ROOT."/vendor/google/recaptcha/src/autoload.php");

defined('_JEXEC') or die;
JFactory::getDocument()->addScript('/components/com_plants/assets/js/signup-valid.js');

$fb_id = $this->params->get('id');
$fb_url = JUri::base() . 'signup/fbSignup';

$g_id = $this->params->get('google_id');
$g_url = JUri::base() . 'signup/gSignup';

$fhref = 'https://www.facebook.com/v2.11/dialog/oauth?client_id=' . $fb_id . '&redirect_uri=' . $fb_url . '&response_type=code&scope=public_profile,email';
$ghref = 'https://accounts.google.com/o/oauth2/auth?redirect_uri=' . $g_url . '&response_type=code&client_id=' . $g_id . '&scope=https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile';

?>

<main class="signUpPage" style="background-image: url('/templates/vg/img/signup_bg.jpg')">
    <div class="card">

        <div class="signUpIn text-right">
            <a href="login" class="btn btn-pink btn-sm inactive ml-auto"><?= JText::_('COM_PLANTS_SIGN_IN') ?></a>
            <a class="btn btn-sm btn-pink ml-2"><?= JText::_('COM_PLANTS_SIGN_UP') ?></a>
        </div>

        <div class="our_form">

            <div class="title"><?= JText::_('COM_PLANTS_SIGN_UP') ?></div>
            <div class="signWith d-flex justify-content-between mb-3">
                <a href="<?= $fhref ?>" class="btn btn-lightBlue btn-lg"><?= JText::_('COM_PLANTS_SIGNUP_WITH_FACEBOOK') ?></a>
                <a href="<?= $ghref ?>" class="btn btn-blue btn-lg"><?= JText::_('COM_PLANTS_SIGNUP_WITH_GOOGLE') ?></a>
            </div>

            <form>
                <div class="input form-group">
                    <?= $this->form->getField('email')->input ?>
                </div>

                <div class="input form-group">
                    <?= $this->form->getField('password')->input ?>
                </div>

                <?= $this->form->getField('captcha')->input ?>

                <button type="submit" class="btn btn-green btn-lg btn-block mt-3"><?= JText::_('COM_PLANTS_SIGNUP') ?></button>

                <input type="hidden" name="task" value="signup.register" />
                <?= JHtml::_('form.token'); ?>
            </form>

        </div>
    </div>
</main>