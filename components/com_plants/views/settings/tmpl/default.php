<?php
    $day  = 0;
    $mnth = 0;
    $year = 0;

    $months = array(
        '01' => 'January',
        '02' => 'February',
        '03' => 'March',
        '04' => 'April',
        '05' => 'May',
        '06' => 'June',
        '07' => 'July',
        '08' => 'August',
        '09' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December'
    );

    $current_year = date('Y') - 1;

    if($this->item->birthday)
    {
        $timestamp = strtotime($this->item->birthday);
        $day   = date('d', $timestamp);
        $mnth = date('m', $timestamp);
        $year  = date('Y', $timestamp);
    }
?>

<script src='https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.3/js/standalone/selectize.js'></script>
<script src='/components/com_plants/assets/js/settings.js'></script>

<main class="settingsPage" style="background-image: url('/templates/vg/img/personal_bg.jpg')">
    <div class="container">
        <div class="page-heading">
            <h2><?= JText::_('COM_PLANTS_PERSONAL_INFORMATION') ?></h2>
        </div>
        <div class="inform-form-container">
            <form class="form-wrap" enctype="multipart/form-data" method="post">

                <div class="form-group">
                    <label for=""><?= JText::_('COM_PLANTS_EMAIL') ?></label>
                    <?= $this->form->getField('email')->input; ?>
                </div>

                <div class="form-group">
                    <label for=""><?= JText::_('COM_PLANTS_USERNAME') ?></label>
                    <span class="username" style="color: #c4c4c4"><?= $this->form->getValue('username') ?></span>
                </div>

                <div class="form-group">
                    <label for=""><?= JText::_('COM_PLANTS_PASSWORD') ?></label>
                    <?= $this->form->getField('password1')->input; ?>
                </div>

                <div class="form-group">
                    <label for=""><?= JText::_('COM_PLANTS_REENTER_PASSWORD') ?></label>
                    <?= $this->form->getField('password2')->input; ?>
                </div>

                <div class="form-group">
                    <label for=""><?= JText::_('COM_PLANTS_FIRST_NAME') ?></label>
                    <?= $this->form->getField('first_name')->input; ?>
                </div>

                <div class="form-group">
                    <label for=""><?= JText::_('COM_PLANTS_LAST_NAME') ?></label>
                    <?= $this->form->getField('last_name')->input; ?>
                </div>

                <div class="form-group">
                    <label for=""><?= JText::_('COM_PLANTS_BIRTHDAY') ?></label>
                    <div class="controls-group">

                        <div class="select-wrap">
                            <select name="jform[day]">
                                <option value="0"><?= JText::_('COM_PLANTS_SELECT_DAY') ?></option>
                                <?php for ($i = 1; $i <= 31; $i++) : ?>
                                    <?php if($day == $i) : ?>
                                        <option selected value="<?= $i ?>"><?= $i ?></option>
                                    <?php else : ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="select-wrap">
                            <select name="jform[month]">
                                <option value="0"><?= JText::_('COM_PLANTS_SELECT_MONTH') ?></option>
                                <?php foreach ($months as $k => $month) : ?>
                                    <?php if($mnth == $k) : ?>
                                        <option selected value="<?= $k ?>"><?= $month ?></option>
                                    <?php else : ?>
                                        <option value="<?= $k ?>"><?= $month ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="select-wrap">
                            <select name="jform[year]">
                                <option value="0"><?= JText::_('COM_PLANTS_SELECT_YEAR') ?></option>
                                <?php for ($i = $current_year; $i >= $current_year - 100; $i--) : ?>
                                    <?php if($year == $i) : ?>
                                        <option selected value="<?= $i ?>"><?= $i ?></option>
                                    <?php else : ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="form-group radio">
                    <label><?= JText::_('COM_PLANTS_GENDER') ?></label>
                    <div class="controls-group gender">
                        <div class="radio-button">
                            <?php if( $this->form->getField('gender')->value == 1 ) : ?>
                                <input type="radio" id="male" name="jform[gender]" checked value="1">
                            <?php else : ?>
                                <input type="radio" id="male" name="jform[gender]" value="1">
                            <?php endif; ?>
                            <label for="male"><?= JText::_('COM_PLANTS_MALE') ?></label>
                        </div>
                        <div class="radio-button">
                            <?php if( $this->form->getField('gender')->value == 2 ) : ?>
                                <input type="radio" id="female" name="jform[gender]" checked value="2">
                            <?php else : ?>
                                <input type="radio" id="female" name="jform[gender]" value="2">
                            <?php endif; ?>
                            <label for="female"><?= JText::_('COM_PLANTS_FEMALE') ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group radio">
                    <label><?= JText::_('COM_PLANTS_GARDERING_EXPERIENCE') ?></label>
                    <div class="controls-group experience">
                        <div class="radio-button">
                            <?php if($this->form->getField('experience')->value == 'beg') : ?>
                                <input type="radio" id="begginer" name="jform[experience]" checked value="beg">
                            <?php else : ?>
                                <input type="radio" id="begginer" name="jform[experience]" value="beg">
                            <?php endif; ?>
                            <label for="begginer"><?= JText::_('COM_PLANTS_BEGINNER') ?></label>

                        </div>
                        <div class="radio-button">
                            <?php if($this->form->getField('experience')->value == 'exp') : ?>
                                <input type="radio" id="experienced" name="jform[experience]" checked value="exp">
                            <?php else : ?>
                                <input type="radio" id="experienced" name="jform[experience]" value="exp">
                            <?php endif; ?>
                            <label for="experienced"><?= JText::_('COM_PLANTS_EXPERIENCED') ?></label>
                        </div>
                        <div class="radio-button">
                            <?php if($this->form->getField('experience')->value == 'adv') : ?>
                                <input type="radio" id="advanced" name="jform[experience]" checked value="adv">
                            <?php else : ?>
                                <input type="radio" id="advanced" name="jform[experience]" value="adv">
                            <?php endif; ?>
                            <label for="advanced"><?= JText::_('COM_PLANTS_ADVANCED') ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="city"><?= JText::_('COM_PLANTS_CITY') ?></label>
                    <select name="jform[city_id]" id="city">
                        <?php if($this->city->id) : ?>
                            <option value="<?= $this->city->id ?>"><?= $this->city->name_en ?></option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for=""><?= JText::_('COM_PLANTS_SECRET_QUESTION') ?></label>
                    <?= $this->form->getField('secret_question')->input; ?>
                </div>

                <div class="form-group">
                    <label for=""><?= JText::_('COM_PLANTS_SECRET_ANSWER') ?></label>
                    <?= $this->form->getField('secret_answer')->input; ?>
                </div>

                <div class="form-group image-upload">
                    <label><?= JText::_('COM_PLANTS_PHOTO') ?></label>
                    <div class="upload-image-wrap">
                        <?php if($this->item->photo) : ?>
                            <div class="photo-wrap">
                                <div class="photo">
                                    <img src="<?= '/images/user_photos/' . $this->item->photo ?>" alt="<?= $this->item->photo ?>">
                                </div>
                                <?php if($this->item->photo !== 'user.svg') : ?>
                                    <button type="button" class="delete-btn" id="delete-photo">
                                        <span class="delete-btn-line"></span>
                                        <span class="delete-btn-line"></span>
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <div class="upload-image-upper">
                            <div class="input-file-container">
                                <input class="input-file" id="my-file" type="file" name="photo">
                                <label tabindex="0" for="my-file" class="input-file-trigger"><?= JText::_('COM_PLANTS_SELECT_A_FILE') ?></label>
                            </div>
                            <ul class="img-list"></ul>
                        </div>
                        <p class="image-upload-hint"><?= JText::_('COM_PLANTS_MAXIMUM_UPLOAD_SIZE') ?> <span>10 MB</span></p>
                    </div>
                </div>

                <div class="form-group about-section">
                    <label><?= JText::_('COM_PLANTS_ABOUT_ME') ?></label>
                    <div class="about-wrap">
                        <?= $this->form->getField('about_me')->input; ?>
                        <p class="hint"><?= JText::_('COM_PLANTS_MAXIMUM_NUMBER_OF_CHARACTERS') ?> 180</p>
                        <div class="buttons-wrap">
                            <button type="submit"><?= JText::_('COM_PLANTS_SAVE') ?></button>
                            <button id="cancel"><?= JText::_('COM_PLANTS_CANCEL') ?></button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label><?= JText::_('SETTINGS_PRIVACY_POLICY') ?></label>

                    <?php if($this->item->submit_rules) : ?>
                        <input type="checkbox" name="jform[privacy_policy]" id="privacy_policy" checked>
                    <?php else : ?>
                        <input type="checkbox" name="jform[privacy_policy]" id="privacy_policy">
                    <?php endif; ?>
                </div>

                <input type="hidden" name="task" value="settings.updateProfile" />
                <?= JHtml::_('form.token'); ?>

            </form>
        </div>

        <div class="delete-request-form">
            <form class="form-group" action="/index.php?option=com_plants&task=settings.delRequest" method="post">
                <label><?= JText::_('COM_PLANTS_SEND_DELETE_REQUEST') ?></label>
                <div class="about-wrap">
                    <textarea id="message" name="message" maxlength="180"></textarea>
                    <button type="submit" id="delete-request-send"><?= JText::_('COM_PLANTS_SEND') ?></button>
                </div>
            </form>
        </div>

    </div>
</main>

<script>
    jQuery(document).ready(function(){

        jQuery("#delete-photo").click(function(){

            var src   = jQuery(".photo img").attr("alt");

            jQuery.ajax({
                type: "POST",
                url: "<?= JUri::base() . 'index.php?option=com_plants&task=settings.deletePhoto&' . JSession::getFormToken() . '=1'?>",
                data: "src=" + src + "&id=<?= $this->user->id ?>",
                success: function () {
                    location.reload();
                }
            });
        });

        jQuery('#cancel').on('click', function(event){
            event.preventDefault();
            history.back();
        });
    })
</script>