<?php
    defined('_JEXEC') or die();
    $segment = JFactory::getApplication()->input->get('segment');

    $plants_counter = count($this->plants);

    $counter  = 0;
    $counter2 = 0;
    $sortby = null;

    if($plants_counter >= 6)
        $counter = 6;
    else
        $counter = $plants_counter;

    if($plants_counter >= 12)
        $counter2 = 12;
    elseif($plants_counter > 6)
        $counter2 = $plants_counter;

    if( !empty($this->filter_values['sortby']) )
        $sortby = $this->filter_values['sortby'];

    $data_max = ceil($this->maxPlantPrice);

    if($this->filter_values['price'][0])
        $data_from = $this->filter_values['price'][0];
    else
        $data_from = 0;

    if(isset($this->filter_values['price'][1]))
        $data_to = $this->filter_values['price'][1];
    else
        $data_to = $data_max;
?>

<main class="catalogPage">

    <section class="pictureDownload">

        <div class="background-layer mobile" style="background-image: url('/templates/vg/img/catalog_bg_mobile.jpg')"></div>
        <div class="background-layer desktop" style="background-image: url('/templates/vg/img/catalog-page.jpg')"></div>

        <div class="container">
            <div class="our_circle_subscription">
                <div class="our_icons">
                    <div class="our_three_icons d-flex justify-content-between">
                        <div class="bg_circle">
                            <img src="<?= JUri::base() . 'templates/vg/img/group.png'?>" alt="">
                        </div>
                        <div class="bg_circle">
                            <img src="<?= JUri::base() . 'templates/vg/img/pictures.png'?>" alt="">
                        </div>
                        <div class="bg_circle">
                            <img src="<?= JUri::base() . 'templates/vg/img/stars.png'?>" alt="">
                        </div>
                    </div>
                    <div class="block_plus">
                        <a href="/add">
                            <div class="bg_circle">
                                <img class="img_plus" src="<?= JUri::base() . 'templates/vg/img/plus.png'?>" alt="">
                            </div>
                            <div class="txt_add"><?= JText::_('COM_PLANTS_ADD_PLANTS')?></div>
                        </a>
                    </div>
                </div>

                <div class="txt_download"><?= JText::_('TAKE_A_PICTURE_UPLOAD') ?></div>

                <div class="catalog-filter-wrap">
                    <div class="filters-container">
                        <div class="filters-block">
                            <?php for($i=0; $i<3; $i++) : ?>
                                <div class="filter-item text-center <?= ($this->segment == $this->categories[$i]->alias) ? 'filter-item-active' : ''; ?>">
                                    <a href="/catalog/<?= $this->categories[$i]->alias ?>" class="img_icon">
                                        <img src="<?= JUri::base() . $this->categories[$i]->cover ?>" width="25px" height="25px">
                                        <div class="filter-title"><?= $this->categories[$i]->title ?></div>
                                    </a>
                                </div>
                            <?php endfor; ?>
                        </div>
                        <div class="filters-block-right">
                            <?php for($i=3; $i<6; $i++) : ?>
                                <div class="filter-item text-center <?= ($this->segment == $this->categories[$i]->alias) ? 'filter-item-active' : ''; ?>">
                                    <a href="/catalog/<?= $this->categories[$i]->alias ?>" class="img_icon">
                                        <img src="<?= JUri::base() . $this->categories[$i]->cover ?>" width="25px" height="25px">
                                        <div class="filter-title"><?= $this->categories[$i]->title ?></div>
                                    </a>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <div class="container content-wrap">
        <aside>
            <?php
                $action = '/catalog';
                if($segment)
                        $action .= '/' . $segment;
            ?>
            <form id="filter-form" class="filters-wrap" action="<?= $action ?><?= $this->detect->isMobile() ? '#plants-list-nav' : '' ?>" method="get">
                <h4 class="filters-wrap-title">Filter</h4>
                <ul class="rating-wrap">
                    <li>
                        <span class="rating-title"><?= JText::_('COM_PLANTS_SEED_GERMINABILITY')?></span>
                        <span class="rating-value">
                            <div id="rating-stars-input">
                                <input id="star-4" type="radio" name="germinability" value="5" <?= ($this->filter_values['germinability'] == 5) ? 'checked' : '' ?> />
                                <label title="gorgeous" for="star-4"></label>

                                <input id="star-3" type="radio" name="germinability" value="4" <?= ($this->filter_values['germinability'] == 4) ? 'checked' : '' ?> />
                                <label title="good" for="star-3"></label>

                                <input id="star-2" type="radio" name="germinability" value="3" <?= ($this->filter_values['germinability'] == 3) ? 'checked' : '' ?> />
                                <label title="regular" for="star-2"></label>

                                <input id="star-1" type="radio" name="germinability" value="2" <?= ($this->filter_values['germinability'] == 2) ? 'checked' : '' ?> />
                                <label title="poor" for="star-1"></label>

                                <input id="star-0" type="radio" name="germinability" value="1" <?= ($this->filter_values['germinability'] == 1) ? 'checked' : '' ?> />
                                <label title="bad" for="star-0"></label>
                            </div>
                        </span>
                    </li>
                    <li>
                        <span class="rating-title"><?= JText::_('COM_PLANTS_SEED_YIELD')?></span>
                        <span class="rating-value">
                            <div id="rating-stars-input">
                                <input id="star-9" type="radio" name="yield" value="5" <?= ($this->filter_values['yield'] == 5) ? 'checked' : '' ?> />
                                <label title="gorgeous" for="star-9"></label>

                                <input id="star-8" type="radio" name="yield" value="4" <?= ($this->filter_values['yield'] == 4) ? 'checked' : '' ?> />
                                <label title="good" for="star-8"></label>

                                <input id="star-7" type="radio" name="yield" value="3" <?= ($this->filter_values['yield'] == 3) ? 'checked' : '' ?> />
                                <label title="regular" for="star-7"></label>

                                <input id="star-6" type="radio" name="yield" value="2" <?= ($this->filter_values['yield'] == 2) ? 'checked' : '' ?> />
                                <label title="poor" for="star-6"></label>

                                <input id="star-5" type="radio" name="yield" value="1" <?= ($this->filter_values['yield'] == 1) ? 'checked' : '' ?> />
                                <label title="bad" for="star-5"></label>
                            </div>
                        </span>
                    </li>
                    <li>
                        <span class="rating-title"><?= JText::_('COM_PLANTS_EASY_CARE')?></span>
                        <span class="rating-value">
                            <div id="rating-stars-input">
                                <input id="star-14" type="radio" name="easy_care" value="5" <?= ($this->filter_values['easy_care'] == 5) ? 'checked' : '' ?> />
                                <label title="gorgeous" for="star-14"></label>

                                <input id="star-13" type="radio" name="easy_care" value="4" <?= ($this->filter_values['easy_care'] == 4) ? 'checked' : '' ?> />
                                <label title="good" for="star-13"></label>

                                <input id="star-12" type="radio" name="easy_care" value="3" <?= ($this->filter_values['easy_care'] == 3) ? 'checked' : '' ?> />
                                <label title="regular" for="star-12"></label>

                                <input id="star-11" type="radio" name="easy_care" value="2" <?= ($this->filter_values['easy_care'] == 2) ? 'checked' : '' ?> />
                                <label title="poor" for="star-11"></label>

                                <input id="star-10" type="radio" name="easy_care" value="1" <?= ($this->filter_values['easy_care'] == 1) ? 'checked' : '' ?> />
                                <label title="bad" for="star-10"></label>
                            </div>
                        </span>
                    </li>
                    <li>
                        <span class="rating-title"><?= JText::_('COM_PLANTS_AUTHOR_RECOMMENDS')?></span>
                        <span class="rating-value">
                            <div id="rating-stars-input">
                                <input id="star-19" type="radio" name="author_recommends" value="5" <?= ($this->filter_values['author_recomends'] == 5) ? 'checked' : '' ?> />
                                <label title="gorgeous" for="star-19"></label>

                                <input id="star-18" type="radio" name="author_recommends" value="4" <?= ($this->filter_values['author_recomends'] == 4) ? 'checked' : '' ?> />
                                <label title="good" for="star-18"></label>

                                <input id="star-17" type="radio" name="author_recommends" value="3" <?= ($this->filter_values['author_recomends'] == 3) ? 'checked' : '' ?> />
                                <label title="regular" for="star-17"></label>

                                <input id="star-16" type="radio" name="author_recommends" value="2" <?= ($this->filter_values['author_recomends'] == 2) ? 'checked' : '' ?> />
                                <label title="poor" for="star-16"></label>

                                <input id="star-15" type="radio" name="author_recommends" value="1" <?= ($this->filter_values['author_recomends'] == 1) ? 'checked' : '' ?> />
                                <label title="bad" for="star-15"></label>
                            </div>
                        </span>
                    </li>
                </ul>

                <div class="money-amount-wrap">
                    <div id="slider-range"></div>
                    <div class="amount-text-wrap">
                        <input type="text" name="price" data-from="<?= $data_from ?>" data-to="<?= $data_to ?>" data-max="<?= $data_max ?>" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold; background-color: #f8f8f8;">
                        <div class="helpBlock"></div>
                    </div>
                </div>

                <div class="city-picker-wrap">
                    <div class="form-group">
                        <label for="city">City</label>
                        <select id="city" name="city_id">
                            <?php if($this->filter_values['city_id']) : ?>
                                <option value="<?= $this->filter_values['city_id'] ?>" selected><?= $this->filter_values['city_name'] ?></option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <?php if( !empty($this->types) ) : ?>
                    <div class="type-picker-wrap">
                        <div class="form-group">
                            <label for="category"><?= JText::_('COM_PLANTS_PLANT_TYPE')?></label>
                            <select name="type" class="form-control" id="type">
                                <option value="0"><?= JText::_('COM_PLANTS_SELECT_TYPE')?></option>
                                <?php foreach ($this->types as $type) : ?>
                                    <?php if($this->filter_values['type'] == $type->id) : ?>
                                        <option value="<?= $type->id ?>" selected><?= $type->title ?></option>
                                    <?php else : ?>
                                        <option value="<?= $type->id ?>"><?= $type->title ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="buttons-wrap">
                    <button id="clean"><?= JText::_('COM_PLANTS_CLEAN')?></button>
                    <button type="submit"><?= JText::_('COM_PLANTS_SEARCH')?></button>
                </div>

                <input type="hidden" value="<?= $sortby ?>" name="sortby" id="sortby-input">

            </form>
            <div class="newsletter-wrap">
                <form class="form-group" id="subscription-form">
                    <label for="email"><?= JText::_('NEWSLETTER_SUBSCRIPTION')?></label>
                    <input class="form-control" type="email" placeholder="Email" id="email1">
                    <p class="bg_subscription-descr"><?= JText::_('NEWSLETTER_PRIVACY_POLICY') ?></p>
                    <button id="subscribe1" type="submit"><?= JText::_('COM_PLANTS_SUBSCRIBE')?></button>
                </form>
            </div>
        </aside>

        <main class="plants-list-wrap">
            <div class="plants-list-nav" id="plants-list-nav">
                <div class="nav-result"><?= $this->counter ?><?= $this->counter == 1 ? ' Result' : ' Results' ?></div>
                <div class="form-group nav-sort-by">
                    <label for="city"><?= JText::_('COM_PLANTS_SORT_BY')?></label>
                    <select id="sortby" class="form-control">

                        <option value="">-</option>

                        <?php if( !empty($this->filter_values['sortby']) ) : ?>

                            <?php if($this->filter_values['sortby'] == 'priceup') : ?>
                                <option value="priceup" selected><?= JText::_('COM_PLANTS_PRICE_UP')?></option>
                            <?php else : ?>
                                <option value="priceup"><?= JText::_('COM_PLANTS_PRICE_UP')?></option>
                            <?php endif; ?>

                            <?php if($this->filter_values['sortby'] == 'pricedown') : ?>
                                <option value="pricedown" selected><?= JText::_('COM_PLANTS_PRICE_DOWN')?></option>
                            <?php else : ?>
                                <option value="pricedown"><?= JText::_('COM_PLANTS_PRICE_DOWN')?></option>
                            <?php endif; ?>

                            <?php if($this->filter_values['sortby'] == 'nameup') : ?>
                                <option value="nameup" selected><?= JText::_('COM_PLANTS_NAME_UP')?></option>
                            <?php else : ?>
                                <option value="nameup"><?= JText::_('COM_PLANTS_NAME_UP')?></option>
                            <?php endif; ?>

                            <?php if($this->filter_values['sortby'] == 'namedown') : ?>
                                <option value="namedown" selected><?= JText::_('COM_PLANTS_NAME_DOWN')?></option>
                            <?php else : ?>
                                <option value="namedown"><?= JText::_('COM_PLANTS_NAME_DOWN')?></option>
                            <?php endif; ?>

                        <?php else : ?>
                            <option value="nameup"><?= JText::_('COM_PLANTS_NAME_UP')?></option>
                            <option value="namedown"><?= JText::_('COM_PLANTS_NAME_DOWN')?></option>
                            <option value="priceup"><?= JText::_('COM_PLANTS_PRICE_UP')?></option>
                            <option value="pricedown"><?= JText::_('COM_PLANTS_NAME_DOWN')?></option>
                        <?php endif; ?>

                    </select>
                </div>
            </div>

            <?php if($counter) : ?>
                <div class="plants-list">
                    <?php for($i = 0; $i < $counter; $i++) : ?>
                        <?php $plant = $this->plants[$i]; ?>
                        <div class="plant-item">
                            <div class="links-wrapper">
                                <a href="/plant?id=<?= $plant->id ?>" class="plant-item-upper">
                                    <?php if($plant->photo) : ?>
                                        <img class="plant-image" src="<?= '/images/plants/' . $plant->photo ?>" alt="plant image">
                                    <?php else : ?>
                                        <img class="plant-image" src="/images/plants/cover.png" alt="plant image">
                                    <?php endif; ?>
                                </a>
                                <?php if( !$this->user->guest ) : ?>
                                    <a href="/profile?id=<?= $plant->user_id ?>" class="author-wrap">
                                <?php else : ?>
                                    <span class="author-wrap">
                                <?php endif; ?>
                                    <?php if($plant->user_photo) : ?>
                                        <img src="<?= '/images/user_photos/' . $plant->user_photo ?>" alt="author avatar">
                                    <?php else : ?>
                                        <img src="/images/user_photos/user.svg" alt="author avatar">
                                    <?php endif; ?>

                                    <?php if($plant->user_first_name && $plant->user_last_name) : ?>
                                        <p class="author-name"><?= $plant->user_first_name . ' ' . $plant->user_last_name ?></p>
                                    <?php else : ?>
                                        <p class="author-name"><?= $plant->user_first_name ?></p>
                                    <?php endif; ?>
                                <?php if( $this->user->guest ) : ?>
                                    </span>
                                <?php else : ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <div class="plant-item-bottom">
                                <h3 class="plant-title">
                                    <a href="/plant?id=<?= $plant->id ?>">
                                        <?= $plant->sort_name ?>
                                    </a>
                                </h3>
                                <?php if($plant->manufactured) : ?>
                                    <p class="plant-location"><?= $plant->manufactured ?></p>
                                <?php endif; ?>
                                <p class="plant-descr"><?= $plant->description ?></p>
                                <ul class="plant-rating">
                                <li>
                                    <span class="rating-title"><?= JText::_('COM_PLANTS_GERMINABILITY')?></span>
                                    <div class="rating-value">
                                        <?php for($j = $plant->germinability; $j >= 1; $j--) : ?>
                                            <span class="fa fa-star checked"></span>
                                        <?php endfor; ?>
                                        <?php for($j = 5 - $plant->germinability; $j >= 1; $j--) : ?>
                                            <span class="fa fa-star"></span>
                                        <?php endfor; ?>
                                    </div>
                                </li>
                                <li>
                                    <span class="rating-title"><?= JText::_('COM_PLANTS_YIELD')?></span>
                                    <div class="rating-value">
                                        <?php for($j = $plant->yield; $j >= 1; $j--) : ?>
                                            <span class="fa fa-star checked"></span>
                                        <?php endfor; ?>
                                        <?php for($j = 5 - $plant->yield; $j >= 1; $j--) : ?>
                                            <span class="fa fa-star"></span>
                                        <?php endfor; ?>
                                    </div>
                                </li>
                                <li>
                                    <span class="rating-title"><?= JText::_('COM_PLANTS_EASY_CARE')?></span>
                                    <div class="rating-value">
                                        <?php for($j = $plant->easy_care; $j >= 1; $j--) : ?>
                                            <span class="fa fa-star checked"></span>
                                        <?php endfor; ?>
                                        <?php for($j = 5 - $plant->easy_care; $j >= 1; $j--) : ?>
                                            <span class="fa fa-star"></span>
                                        <?php endfor; ?>
                                    </div>
                                </li>
                                <li>
                                    <span class="rating-title"><?= JText::_('COM_PLANTS_AUTHOR_RECOMMENDS')?></span>
                                    <div class="rating-value">
                                        <?php for($j = $plant->author_recomends; $j >= 1; $j--) : ?>
                                            <span class="fa fa-star checked"></span>
                                        <?php endfor; ?>
                                        <?php for($j = 5 - $plant->author_recomends; $j >= 1; $j--) : ?>
                                            <span class="fa fa-star"></span>
                                        <?php endfor; ?>
                                    </div>
                                </li>
                            </ul>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>

    <!--            <div class="banner d-flex justify-content-around">-->
    <!--                <a href="#"><img class="banner_img" src="--><?//= JUri::base() . 'templates/vg/img/banner.jpg'?><!--" alt="Banner"> </a>-->
    <!--            </div>-->

                <div class="plants-list">
                    <?php for($i = 6; $i < $counter2; $i++) : ?>
                        <?php $plant = $this->plants[$i]; ?>
                        <div class="plant-item">
                            <div class="links-wrapper">
                                <a href="/plant?id=<?= $plant->id ?>" class="plant-item-upper">
                                    <?php if($plant->photo) : ?>
                                        <img class="plant-image" src="<?= '/images/plants/' . $plant->photo ?>" alt="plant image">
                                    <?php else : ?>
                                        <img class="plant-image" src="/images/plants/cover.png" alt="plant image">
                                    <?php endif; ?>
                                </a>
                                <?php if( !$this->user->guest ) : ?>
                                    <a href="/profile?id=<?= $plant->user_id ?>" class="author-wrap">
                                <?php else : ?>
                                    <span class="author-wrap">
                                <?php endif; ?>
                                    <?php if($plant->user_photo) : ?>
                                        <img src="<?= '/images/user_photos/' . $plant->user_photo ?>" alt="author avatar">
                                    <?php else : ?>
                                        <img src="/images/user_photos/user.svg" alt="author avatar">
                                    <?php endif; ?>

                                    <?php if($plant->user_first_name && $plant->user_last_name) : ?>
                                        <p class="author-name"><?= $plant->user_first_name . ' ' . $plant->user_last_name ?></p>
                                    <?php else : ?>
                                        <p class="author-name"><?= $plant->user_first_name ?></p>
                                    <?php endif; ?>
                                <?php if($this->user->guest) : ?>
                                    </span>
                                <?php else : ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <div class="plant-item-bottom">
                                <h3 class="plant-title">
                                    <a href="/plant?id=<?= $plant->id ?>">
                                        <?= $plant->sort_name ?>
                                    </a>
                                </h3>
                                <?php if($plant->manufactured) : ?>
                                    <p class="plant-location"><?= $plant->manufactured ?></p>
                                <?php endif; ?>
                                <p class="plant-descr">
                                    <?= $plant->description ?>
                                </p>
                                <ul class="plant-rating">
                                    <li>
                                        <span class="rating-title"><?= JText::_('COM_PLANTS_GERMINABILITY')?></span>
                                        <div class="rating-value">
                                            <?php for($j = $plant->germinability; $j >= 1; $j--) : ?>
                                                <span class="fa fa-star checked"></span>
                                            <?php endfor; ?>
                                            <?php for($j = 5 - $plant->germinability; $j >= 1; $j--) : ?>
                                                <span class="fa fa-star"></span>
                                            <?php endfor; ?>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="rating-title"><?= JText::_('COM_PLANTS_YIELD')?></span>
                                        <div class="rating-value">
                                            <?php for($j = $plant->yield; $j >= 1; $j--) : ?>
                                                <span class="fa fa-star checked"></span>
                                            <?php endfor; ?>
                                            <?php for($j = 5 - $plant->yield; $j >= 1; $j--) : ?>
                                                <span class="fa fa-star"></span>
                                            <?php endfor; ?>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="rating-title"><?= JText::_('COM_PLANTS_EASY_CARE')?></span>
                                        <div class="rating-value">
                                            <?php for($j = $plant->easy_care; $j >= 1; $j--) : ?>
                                                <span class="fa fa-star checked"></span>
                                            <?php endfor; ?>
                                            <?php for($j = 5 - $plant->easy_care; $j >= 1; $j--) : ?>
                                                <span class="fa fa-star"></span>
                                            <?php endfor; ?>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="rating-title"><?= JText::_('COM_PLANTS_AUTHOR_RECOMMENDS')?></span>
                                        <div class="rating-value">
                                            <?php for($j = $plant->author_recomends; $j >= 1; $j--) : ?>
                                                <span class="fa fa-star checked"></span>
                                            <?php endfor; ?>
                                            <?php for($j = 5 - $plant->author_recomends; $j >= 1; $j--) : ?>
                                                <span class="fa fa-star"></span>
                                            <?php endfor; ?>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php else : ?>

                <p class="no-results-message"><?= JText::_('COM_PLANTS_NO_ITEMS')?></p>
                <p class="random-plants"><?= JText::_('COM_PLANTS_RANDOM_PLANTS')?></p>

                <div class="plants-list">
                    <?php foreach ($this->randomPlants as $plant) : ?>
                        <div class="plant-item">
                            <div class="links-wrapper">
                                <a href="/plant?id=<?= $plant->id ?>" class="plant-item-upper">
                                    <?php if($plant->photo) : ?>
                                        <img class="plant-image" src="<?= '/images/plants/' . $plant->photo ?>" alt="plant image">
                                    <?php else : ?>
                                        <img class="plant-image" src="/images/plants/cover.png" alt="plant image">
                                    <?php endif; ?>
                                </a>
                                <?php if( !$this->user->guest ) : ?>
                                <a href="/profile?id=<?= $plant->user_id ?>" class="author-wrap">
                                    <?php else : ?>
                                    <span class="author-wrap">
                                <?php endif; ?>
                                        <?php if($plant->user_photo) : ?>
                                            <img src="<?= '/images/user_photos/' . $plant->user_photo ?>" alt="author avatar">
                                        <?php else : ?>
                                            <img src="/images/user_photos/user.svg" alt="author avatar">
                                        <?php endif; ?>

                                        <?php if($plant->user_first_name && $plant->user_last_name) : ?>
                                            <p class="author-name"><?= $plant->user_first_name . ' ' . $plant->user_last_name ?></p>
                                        <?php else : ?>
                                            <p class="author-name"><?= $plant->user_first_name ?></p>
                                        <?php endif; ?>
                                        <?php if($this->user->guest) : ?>
                                    </span>
                                    <?php else : ?>
                                </a>
                            <?php endif; ?>
                            </div>
                            <div class="plant-item-bottom">
                                <h3 class="plant-title">
                                    <a href="/plant?id=<?= $plant->id ?>">
                                        <?= $plant->sort_name ?>
                                    </a>
                                </h3>
                                <?php if($plant->manufactured) : ?>
                                    <p class="plant-location"><?= $plant->manufactured ?></p>
                                <?php endif; ?>
                                <p class="plant-descr">
                                    <?= $plant->description ?>
                                </p>
                                <ul class="plant-rating">
                                    <li>
                                        <span class="rating-title"><?= JText::_('COM_PLANTS_GERMINABILITY')?></span>
                                        <div class="rating-value">
                                            <?php for($j = $plant->germinability; $j >= 1; $j--) : ?>
                                                <span class="fa fa-star checked"></span>
                                            <?php endfor; ?>
                                            <?php for($j = 5 - $plant->germinability; $j >= 1; $j--) : ?>
                                                <span class="fa fa-star"></span>
                                            <?php endfor; ?>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="rating-title"><?= JText::_('COM_PLANTS_YIELD')?></span>
                                        <div class="rating-value">
                                            <?php for($j = $plant->yield; $j >= 1; $j--) : ?>
                                                <span class="fa fa-star checked"></span>
                                            <?php endfor; ?>
                                            <?php for($j = 5 - $plant->yield; $j >= 1; $j--) : ?>
                                                <span class="fa fa-star"></span>
                                            <?php endfor; ?>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="rating-title"><?= JText::_('COM_PLANTS_EASY_CARE')?></span>
                                        <div class="rating-value">
                                            <?php for($j = $plant->easy_care; $j >= 1; $j--) : ?>
                                                <span class="fa fa-star checked"></span>
                                            <?php endfor; ?>
                                            <?php for($j = 5 - $plant->easy_care; $j >= 1; $j--) : ?>
                                                <span class="fa fa-star"></span>
                                            <?php endfor; ?>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="rating-title"><?= JText::_('COM_PLANTS_AUTHOR_RECOMMENDS')?></span>
                                        <div class="rating-value">
                                            <?php for($j = $plant->author_recomends; $j >= 1; $j--) : ?>
                                                <span class="fa fa-star checked"></span>
                                            <?php endfor; ?>
                                            <?php for($j = 5 - $plant->author_recomends; $j >= 1; $j--) : ?>
                                                <span class="fa fa-star"></span>
                                            <?php endfor; ?>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            <?php endif; ?>

            <?php if($this->counter > 12) : ?>
                <button id="lookmore" class="look-more-btn"><?= JText::_('COM_PLANTS_LOOK_MORE')?></button>
            <?php endif; ?>
        </main>
    </div>

    <input type="hidden" id="plants-counter" value="<?= $plants_counter ?>">
    <input type="hidden" id="segment" value="<?= $this->segment ?>">
    <input type="hidden" id="user_id" value="<?= $this->user->id ?>">

</main>

<script>
    jQuery(document).ready(function(){
        jQuery('.sharepage').on('click', function(){

            FB.ui({
                method: 'share',
                display: 'popup',
                href: '<?= JUri::getInstance() ?>'
            }, function(response){});
        });

        window.fbAsyncInit = function() {
            FB.init({
                appId            : '<?= $this->config->get('id') ?>',
                autoLogAppEvents : true,
                xfbml            : true,
                version          : 'v2.12'
            });
        };

        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = 'https://connect.facebook.net/en_US/sdk.js';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    });
</script>