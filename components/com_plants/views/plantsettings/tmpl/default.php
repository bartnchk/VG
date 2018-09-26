<?php defined('_JEXEC') or die; ?>

<main class="newPlantPage">

    <?php if( !$this->detect->isMobile() ) : ?>
        <section class="last-added" style="background-image: url('/templates/vg/img/new_plant_bg.jpg')">
            <div class="container last-added-wrap">
                <h2 class="title"> <?= JText::_('COM_PLANTS_LAST_ADDED') ?> </h2>
                <div class="last-added-slider" data-slick='{"slidesToShow": 5, "slidesToScroll": 3}'>
                    <?php foreach ($this->lastAddedPlants as $item) : ?>
                        <div class="slider-item">
                            <a href="/plant?id=<?= $item->id ?>" target="_blank">
                                <?php if($item->src) : ?>
                                    <img src="/images/plants/<?= $item->src ?>" alt="plant image">
                                <?php else : ?>
                                    <img src="/images/plants/cover.png" alt="plant image">
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="form-container container">
        <div class="form-wrap-aside-text">
            <p><?= JText::_('COM_PLANTS_NEW_PLANT') ?></p>
        </div>
        <form id="plantform" class="form-wrap" method="post" enctype="multipart/form-data">
            <div class="upper-section">
                <div class="content-wrap">
                    <div class="form-group">
                        <label for=""><?= JText::_('COM_PLANTS_SORT_NAME') ?> <span>*</span></label>
                        <?php
                            $sort_name = '';

                            if( $this->plantData['sort_name'] )
                                $sort_name = $this->plantData['sort_name'];
                            elseif( $this->session->get('jform')['sort_name'] )
                                $sort_name = $this->session->get('jform')['sort_name'];
                        ?>
                        <input type="text" name="jform[sort_name]" id="jform_sort_name" value="<?= $sort_name ?>" class="form-control" placeholder="Sort name" required>
                    </div>

                    <div class="form-group plant-category">
                        <label for=""><?= JText::_('COM_PLANTS_PLANT_CATEGORY') ?> <span>*</span></label>

                        <select id="jform_plant_category_id" name="jform[plant_category_id]" class="form-control default-select required" required>

                            <option value=""><?= JText::_('COM_PLANTS_SELECT_CATEGORY') ?></option>
                            <?php foreach ($this->categories as $k => $category) : ?>
                                <?php if($this->plantData['plant_category_id'] == $category->id ||
                                         $this->session->get('jform')['plant_category_id'] &&
                                         $this->session->get('jform')['plant_category_id'] == $category->id) : ?>
                                    <option value="<?= $category->id ?>" selected><?= $category->title ?></option>
                                <?php else : ?>
                                    <option value="<?= $category->id ?>"><?= $category->title ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>

                    </div>

                    <div class="form-group plant-type">
                        <label for=""><?= JText::_('COM_PLANTS_PLANT_TYPE') ?></label>

                        <select id="jform_plant_type_id" name="jform[plant_type_id]" class="form-control default-select required" required="" aria-required="true">

                            <option value="0"><?= JText::_('COM_PLANTS_SELECT_TYPE') ?></option>
                            <?php foreach ($this->types as $k => $type) : ?>
                                <?php
                                    if($this->plantData['plant_type_id'] == $type->id ||
                                         $this->session->get('jform')['plant_type_id'] && $this->session->get('jform')['plant_type_id'] == $type->id ) :
                                ?>

                                    <option value="<?= $type->id ?>" selected><?= $type->title ?></option>
                                <?php else : ?>
                                    <option value="<?= $type->id ?>"><?= $type->title ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>

                    </div>

                    <div id="custom-fields">
                        <?php if ( !empty($this->customFields) ) : ?>
                            <?php foreach ($this->customFields as $field) : ?>
                                <div class="form-group">
                                    <label for=""><?= $field->title ?></label>
                                    <input class="form-control" type="text" value="<?= $field->value ?>" name="jform[custom_fields][<?= $field->id ?>]" />
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="city"><?= JText::_('COM_PLANTS_CITY') ?></label>
                        <select name="jform[city_id]" id="city" autocomplete="off">
                            <?php if(isset($this->plantData['city']) && $this->plantData['city']) : ?>
                                <option value="<?= $this->plantData['city_id'] ?>"><?= $this->plantData['city'] ?></option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group image-upload">
                        <label><?= JText::_('COM_PLANTS_PHOTO_OF_PLANT') ?> <span>*</span></label>
                        <div class="upload-image-wrap">
                            <?php if( !empty($this->photos) ) : ?>
                                <?php foreach($this->photos as $photo) : ?>
                                    <div class="photo-wrap">
                                        <div class="photo">
                                            <img class="plant-photo" src="/images/plants/<?= $photo->src ?>" alt="<?= $photo->src ?>">
                                        </div>
                                        <button type="button" class="delete-btn delete-plant-photo" data-src="<?= $photo->src ?>">
                                            <span class="delete-btn-line"></span>
                                            <span class="delete-btn-line"></span>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <div class="upload-image-upper">
                                <div class="input-file-container">
                                    <input class="input-file" id="my-file" type="file" name="photos" data-multiple="true">
                                    <label tabindex="0" for="my-file" class="input-file-trigger"><?= JText::_('COM_PLANTS_SELECT_A_FILE') ?></label>
                                </div>
                                <ul class="img-list"></ul>
                            </div>
                            <p class="image-upload-hint"><?= JText::_('COM_PLANTS_MAXIMUM_UPLOAD_SIZE') ?> <span>10 MB</span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bottom-section">
                <div class="form-group image-upload">
                    <label><?= JText::_('COM_PLANTS_PHOTO_OF_PACKING') ?></label>
                    <div class="upload-image-wrap">
                        <?php if($this->plantData['seeds_photo']) : ?>
                            <div class="photo-wrap">
                                <div class="photo">
                                    <img id="seeds-photo" src="/images/seeds_photo/<?= $this->plantData['seeds_photo'] ?>" alt="<?= $this->plantData['seeds_photo'] ?>">
                                </div>
                                <button type="button" class="delete-btn" id="delete-seeds-photo">
                                    <span class="delete-btn-line"></span>
                                    <span class="delete-btn-line"></span>
                                </button>
                            </div>
                        <?php endif; ?>
                        <div class="upload-image-upper">
                            <div class="input-file-container">
                                <input class="input-file" id="my-file2" type="file" name="seeds_photo">
                                <label tabindex="0" for="my-file2" class="input-file-trigger"><?= JText::_('COM_PLANTS_SELECT_A_FILE') ?></label>
                            </div>
                            <ul class="img-list"></ul>
                        </div>
                        <p class="image-upload-hint"><?= JText::_('COM_PLANTS_MAXIMUM_UPLOAD_SIZE') ?> <span>10 MB</span></p>
                    </div>
                </div>

                <div class="form-group image-upload barcode">
                    <label><?= JText::_('COM_PLANTS_BARCODE') ?></label>
                    <div class="upload-image-wrap">
                        <div>
                            <?php if($this->plantData['barcode_photo']) : ?>
                                <div class="photo-wrap">
                                    <div class="photo">
                                        <img id="barcode-photo" src="/images/barcodes_photo/<?= $this->plantData['barcode_photo'] ?>" alt="<?= $this->plantData['barcode_photo'] ?>">
                                    </div>
                                    <button type="button" class="delete-btn" id="delete-barcode-photo">
                                        <span class="delete-btn-line"></span>
                                        <span class="delete-btn-line"></span>
                                    </button>
                                </div>
                            <?php endif; ?>
                            <div class="upload-image-upper">
                                <div class="input-file-container">
                                    <input class="input-file" id="my-file3" type="file" name="barcode_photo">
                                    <label tabindex="0" for="my-file3" class="input-file-trigger"><?= JText::_('COM_PLANTS_SELECT_A_FILE') ?></label>
                                </div>
                                <ul class="img-list"></ul>
                            </div>
                            <p class="image-upload-hint"><?= JText::_('COM_PLANTS_MAXIMUM_UPLOAD_SIZE') ?> <span>10 MB</span></p>
                        </div>
                        <?php
                            $barcode = '';

                            if($this->plantData['barcode'])
                                $barcode = $this->plantData['barcode'];
                            else
                                $barcode = $this->session->get('jform')['barcode'];
                        ?>
                        <input class="form-control" type="text" placeholder="5168 23..." name="jform[barcode]" value="<?= $barcode ?>">
                    </div>
                </div>

                <div class="form-group radio">
                    <label><?= JText::_('COM_PLANTS_PRESEEDING') ?></label>
                    <div class="controls-group experience">

                        <?php if($this->plantData['preseeding'] || $this->session->get('jform')['preseeding']) : ?>
                            <div class="radio-button">
                                <input type="radio" id="no" name="jform[preseeding]" class="preseeding" value="0" />
                                <label for="no"><?= JText::_('COM_PLANTS_NO') ?></label>
                            </div>
                            <div class="radio-button">
                                <input type="radio" id="yes" name="jform[preseeding]" class="preseeding" value="1" checked />
                                <label for="yes"><?= JText::_('COM_PLANTS_YES') ?></label>
                            </div>
                        <?php else : ?>
                            <div class="radio-button">
                                <input type="radio" id="no" name="jform[preseeding]" class="preseeding" value="0" checked />
                                <label for="no"><?= JText::_('COM_PLANTS_NO') ?></label>
                            </div>
                            <div class="radio-button">
                                <input type="radio" id="yes" name="jform[preseeding]" class="preseeding" value="1" />
                                <label for="yes"><?= JText::_('COM_PLANTS_YES') ?></label>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

                <div class="form-group date">
                    <div class="date-pickers-wrap">
                        <div class="trans-wrap">
                            <label><?= JText::_('COM_PLANTS_PLANTING') ?></label>
                            <div class="planting date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                            <?php

                                if($this->plantData['planting_date'])
                                    $ts = strtotime($this->plantData['planting_date']);
                                elseif($this->session->get('jform')['planting_date'])
                                    $ts = strtotime($this->session->get('jform')['planting_date']);
                                else
                                    $ts = 0;

                                if($ts > 0)
                                    $planting_date = date('d-m-Y', $ts);
                                else
                                    $planting_date = '';
                            ?>
                                <input type="text" class="form-control" name="jform[planting_date]" value="<?= $planting_date ?>" />
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                        <div class="trans-wrap toggle">
                            <label><?= JText::_('COM_PLANTS_TRANSPLANTATION') ?></label>
                            <div class="trans date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                <?php
                                    if($this->plantData['transplantation_date'])
                                        $ts = strtotime($this->plantData['transplantation_date']);
                                    elseif($this->session->get('jform')['transplantation_date'])
                                        $ts = strtotime($this->session->get('jform')['transplantation_date']);
                                    else
                                        $ts = 0;

                                    if($ts > 0)
                                        $transplantation_date = date('d-m-Y', $ts);
                                    else
                                        $transplantation_date = '';
                                ?>
                                <input name="jform[transplantation_date]" type="text" class="form-control" value="<?= $transplantation_date ?>"/>
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label><?= JText::_('COM_PLANTS_PURCHASED') ?></label>
                    <?php
                        $purchased = '';
                        if($this->plantData['purchased'])
                            $purchased = $this->plantData['purchased'];
                        else
                            $purchased = $this->session->get('jform')['purchased'];
                    ?>
                    <input type="text" class="form-control" name="jform[purchased]" value="<?= $purchased ?>" />
                </div>

                <div class="form-group price">
                    <label><?= JText::_('COM_PLANTS_PRICE') ?></label>
                    <div class="price-input-wrap">
                        <?php
                            $price = '';
                            if($this->plantData['price'])
                                $price = $this->plantData['price'];
                            else
                                $price = $this->session->get('jform')['price'];
                        ?>

                        <input type="text" pattern="[0-9]+([\.,][0-9]+)?" class="form-control price-input" name="jform[price]" value="<?= $price ?>" step="any" min="0.00" max="999.99"/>

                        <span>€</span>
                    </div>
                </div>

                <div class="form-group">
                    <label><?= JText::_('COM_PLANTS_SEED_PRODUCER') ?></label>
                    <?php
                        $manufactured = '';
                        if($this->plantData['manufactured'])
                            $manufactured = $this->plantData['manufactured'];
                        else
                            $manufactured = $this->session->get('jform')['manufactured'];
                    ?>
                    <input type="text" class="form-control" name="jform[manufactured]" value="<?= $manufactured ?>"/>
                </div>

                <div class="form-group seeds-in-package">
                    <label><?= JText::_('COM_PLANTS_SEEDS_IN_PACKAGE') ?></label>
                    <div class="seeds-in-package-wrap">
                        <div class="controls-group">
                            <div class="radio-button">
                                <?php if($this->plantData['seeds_in_package'] == 1 || $this->session->get('jform')['seeds_in_package'] == 1 ) : ?>
                                    <input type="radio" id="tooLes" name="jform[seeds_in_package]" checked value="1">
                                <?php else : ?>
                                    <input type="radio" id="tooLes" name="jform[seeds_in_package]" value="1">
                                <?php endif; ?>
                                <label for="tooLes"><?= JText::_('COM_PLANTS_TOO_LESS') ?></label>
                            </div>
                            <div class="radio-button">
                                <?php if($this->plantData['seeds_in_package'] == 2 || $this->session->get('jform')['seeds_in_package'] == 2 ) : ?>
                                    <input type="radio" id="normal" name="jform[seeds_in_package]" checked value="2">
                                <?php else : ?>
                                    <input type="radio" id="normal" name="jform[seeds_in_package]" value="2">
                                <?php endif; ?>
                                <label for="normal"><?= JText::_('COM_PLANTS_NORMAL') ?></label>
                            </div>
                            <div class="radio-button">
                                <?php if($this->plantData['seeds_in_package'] == 3 || $this->session->get('jform')['seeds_in_package'] == 3 ) : ?>
                                    <input type="radio" id="many" name="jform[seeds_in_package]" checked value="3">
                                <?php else : ?>
                                    <input type="radio" id="many" name="jform[seeds_in_package]" value="3">
                                <?php endif; ?>
                                <label for="many"><?= JText::_('COM_PLANTS_MANY') ?></label>
                            </div>
                        </div>
                        <ul class="rating-wrap">
                            <li>
                                <div id="rating-stars-input">
                                    <?php if($this->plantData['germinability'] == 5 || $this->session->get('jform')['germinability'] == 5) : ?>
                                        <input id="star0" type="radio" name="jform[germinability]" checked value="5"/>
                                    <?php else : ?>
                                        <input id="star0" type="radio" name="jform[germinability]" value="5"/>
                                    <?php endif; ?>
                                    <label title="bad" for="star0"></label>

                                    <?php if($this->plantData['germinability'] == 4 || $this->session->get('jform')['germinability'] == 4) : ?>
                                        <input id="star-1" type="radio" name="jform[germinability]" checked value="4"/>
                                    <?php else : ?>
                                        <input id="star-1" type="radio" name="jform[germinability]" value="4"/>
                                    <?php endif; ?>
                                    <label title="poor" for="star-1"></label>

                                    <?php if($this->plantData['germinability'] == 3 || $this->session->get('jform')['germinability'] == 3) : ?>
                                        <input id="star-2" type="radio" name="jform[germinability]" checked value="3"/>
                                    <?php else : ?>
                                        <input id="star-2" type="radio" name="jform[germinability]" value="3"/>
                                    <?php endif; ?>
                                    <label title="regular" for="star-2"></label>

                                    <?php if($this->plantData['germinability'] == 2 || $this->session->get('jform')['germinability'] == 2) : ?>
                                        <input id="star-3" type="radio" name="jform[germinability]" checked value="2"/>
                                    <?php else : ?>
                                        <input id="star-3" type="radio" name="jform[germinability]" value="2"/>
                                    <?php endif; ?>
                                    <label title="good" for="star-3"></label>

                                    <?php if($this->plantData['germinability'] == 1 || $this->session->get('jform')['germinability'] == 1) : ?>
                                        <input id="star-4" type="radio" name="jform[germinability]" checked value="1"/>
                                    <?php else : ?>
                                        <input id="star-4" type="radio" name="jform[germinability]" value="1"/>
                                    <?php endif; ?>
                                    <label title="gorgeous" for="star-4"></label>
                                </div>
                                <div class="rating-title"><?= JText::_('COM_PLANTS_GERMINABILITY') ?></div>
                            </li>
                            <li>
                                <div id="rating-stars-input">
                                    <?php if($this->plantData['yield'] == 5 || $this->session->get('jform')['yield'] == 5) : ?>
                                        <input id="star-5" type="radio" name="jform[yield]" checked value="5"/>
                                    <?php else : ?>
                                        <input id="star-5" type="radio" name="jform[yield]" value="5"/>
                                    <?php endif; ?>
                                    <label title="bad" for="star-5"></label>

                                    <?php if($this->plantData['yield'] == 4 || $this->session->get('jform')['yield'] == 4) : ?>
                                        <input id="star-6" type="radio" name="jform[yield]" checked value="4"/>
                                    <?php else : ?>
                                        <input id="star-6" type="radio" name="jform[yield]" value="4"/>
                                    <?php endif; ?>
                                    <label title="poor" for="star-6"></label>

                                    <?php if($this->plantData['yield'] == 3 || $this->session->get('jform')['yield'] == 3) : ?>
                                        <input id="star-7" type="radio" name="jform[yield]" checked value="3"/>
                                    <?php else : ?>
                                        <input id="star-7" type="radio" name="jform[yield]" value="3"/>
                                    <?php endif; ?>
                                    <label title="regular" for="star-7"></label>

                                    <?php if($this->plantData['yield'] == 2 || $this->session->get('jform')['yield'] == 2) : ?>
                                        <input id="star-8" type="radio" name="jform[yield]" checked value="2"/>
                                    <?php else : ?>
                                        <input id="star-8" type="radio" name="jform[yield]" value="2"/>
                                    <?php endif; ?>
                                    <label title="good" for="star-8"></label>

                                    <?php if($this->plantData['yield'] == 1 || $this->session->get('jform')['yield'] == 1) : ?>
                                        <input id="star-9" type="radio" name="jform[yield]" checked value="1"/>
                                    <?php else : ?>
                                        <input id="star-9" type="radio" name="jform[yield]" value="1"/>
                                    <?php endif; ?>
                                    <label title="gorgeous" for="star-9"></label>
                                </div>
                                <div class="rating-title"><?= JText::_('COM_PLANTS_YIELD') ?></div>
                            </li>
                            <li>
                                <div id="rating-stars-input">
                                    <?php if($this->plantData['easy_care'] == 5 || $this->session->get('jform')['easy_care'] == 5) : ?>
                                        <input id="star-15" type="radio" name="jform[easy_care]" checked value="5"/>
                                    <?php else : ?>
                                        <input id="star-15" type="radio" name="jform[easy_care]" value="5"/>
                                    <?php endif; ?>
                                    <label title="bad" for="star-15"></label>

                                    <?php if($this->plantData['easy_care'] == 4 || $this->session->get('jform')['easy_care'] == 4) : ?>
                                        <input id="star-16" type="radio" name="jform[easy_care]" checked value="4"/>
                                    <?php else : ?>
                                        <input id="star-16" type="radio" name="jform[easy_care]" value="4"/>
                                    <?php endif; ?>
                                    <label title="poor" for="star-16"></label>

                                    <?php if($this->plantData['easy_care'] == 3 || $this->session->get('jform')['easy_care'] == 3) : ?>
                                        <input id="star-17" type="radio" name="jform[easy_care]" checked value="3"/>
                                    <?php else : ?>
                                        <input id="star-17" type="radio" name="jform[easy_care]" value="3"/>
                                    <?php endif; ?>
                                    <label title="regular" for="star-17"></label>

                                    <?php if($this->plantData['easy_care'] == 2 || $this->session->get('jform')['easy_care'] == 2) : ?>
                                        <input id="star-18" type="radio" name="jform[easy_care]" checked value="2"/>
                                    <?php else : ?>
                                        <input id="star-18" type="radio" name="jform[easy_care]" value="2"/>
                                    <?php endif; ?>
                                    <label title="good" for="star-18"></label>

                                    <?php if($this->plantData['easy_care'] == 1 || $this->session->get('jform')['easy_care'] == 1) : ?>
                                        <input id="star-19" type="radio" name="jform[easy_care]" checked value="1"/>
                                    <?php else : ?>
                                        <input id="star-19" type="radio" name="jform[easy_care]" value="1"/>
                                    <?php endif; ?>
                                    <label title="gorgeous" for="star-19"></label>
                                </div>
                                <div class="rating-title"><?= JText::_('COM_PLANTS_EASY_CARE') ?></div>
                            </li>
                            <li>
                                <div id="rating-stars-input">
                                    <?php if($this->plantData['author_recomends'] == 5 || $this->session->get('jform')['author_recomends'] == 5) : ?>
                                        <input id="star-10" type="radio" name="jform[author_recomends]" checked value="5"/>
                                    <?php else : ?>
                                        <input id="star-10" type="radio" name="jform[author_recomends]" value="5"/>
                                    <?php endif; ?>
                                    <label title="bad" for="star-10"></label>

                                    <?php if($this->plantData['author_recomends'] == 4 || $this->session->get('jform')['author_recomends'] == 4) : ?>
                                        <input id="star-11" type="radio" name="jform[author_recomends]" checked value="4"/>
                                    <?php else : ?>
                                        <input id="star-11" type="radio" name="jform[author_recomends]" value="4"/>
                                    <?php endif; ?>
                                    <label title="poor" for="star-11"></label>

                                    <?php if($this->plantData['author_recomends'] == 3 || $this->session->get('jform')['author_recomends'] == 3) : ?>
                                        <input id="star-12" type="radio" name="jform[author_recomends]" checked value="3"/>
                                    <?php else : ?>
                                        <input id="star-12" type="radio" name="jform[author_recomends]" value="3"/>
                                    <?php endif; ?>
                                    <label title="regular" for="star-12"></label>

                                    <?php if($this->plantData['author_recomends'] == 2 || $this->session->get('jform')['author_recomends'] == 2) : ?>
                                        <input id="star-13" type="radio" name="jform[author_recomends]" checked value="2"/>
                                    <?php else : ?>
                                        <input id="star-13" type="radio" name="jform[author_recomends]" value="2"/>
                                    <?php endif; ?>
                                    <label title="good" for="star-13"></label>

                                    <?php if($this->plantData['author_recomends'] == 1 || $this->session->get('jform')['author_recomends'] == 1) : ?>
                                        <input id="star-14" type="radio" name="jform[author_recomends]" checked value="1"/>
                                    <?php else : ?>
                                        <input id="star-14" type="radio" name="jform[author_recomends]" value="1"/>
                                    <?php endif; ?>
                                    <label title="gorgeous" for="star-14"></label>
                                </div>
                                <div class="rating-title"><?= JText::_('COM_PLANTS_AUTHOR_RECOMMENDS') ?></div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="form-group descr">
                    <label><?= JText::_('COM_PLANTS_DESCRIPTION') ?></label>
                    <?php
                        $description = '';
                        if($this->plantData['description'])
                            $description = $this->plantData['description'];
                        elseif($this->session->get('jform')['description'])
                            $description = $this->session->get('jform')['description'];
                    ?>
                    <textarea name="jform[description]" class="form-control"><?= $description ?></textarea>
                </div>

                <?php if($this->user->guest) : ?>
                <div class="form-group auth">
                    <label><?= JText::_('COM_PLANTS_LOGIN_PASSWORD') ?> <span>*</span></label>
                    <div class="auth-inputs">
                        <input name="email" type="email" class="form-control" id="email" required>
                        <input name="password" type="password" class="form-control" id="password" required>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($this->plantData['id']) : ?>
                    <input type="submit" class="submit-btn" value="Submit">
                <?php else : ?>
                    <input type="submit" class="submit-btn" value="Submit for moderation">
                <?php endif; ?>

            </div>

            <input type="hidden" name="jform[id]" value="<?= $this->plantData['id'] ? $this->plantData['id'] : 0 ?>">
            <input type="hidden" name="task" value="plantsettings.save">
            <input type="hidden" name="<?= JSession::getFormToken() ?>" value="1" id="token" />

        </form>
    </section>

</main>


