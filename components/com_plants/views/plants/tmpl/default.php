<?php defined( '_JEXEC' ) or die; ?>

<main class="myPlants">
    <header class="sub-header">
        <div class="container">
            <?php if($this->userData->juser_id == $this->currentUser->id) : ?>
                <h1 class="page-title"><?= JText::_('COM_PLANTS_MY_PLANTS') ?></h1>
            <?php else : ?>
                <h1 class="page-title"><?= $this->userData->first_name ?><?= JText::_('COM_PLANTS_S_PLANTS') ?></h1>
            <?php endif; ?>
            <ul class="filters-wrap">
                <?php foreach ($this->categories as $category) : ?>
                    <li class="filter-item">
                        <?php if($this->userData->juser_id == $this->currentUser->id) : ?>
                            <a href="/plants/<?= $category->alias ?>">
                        <?php else : ?>
                            <a href="/plants/<?= $this->userData->juser_id . '/' . $category->alias?>">
                        <?php endif; ?>
                            <?php if($this->currentCategory == $category->alias) : ?>
                                <span class="active-category"><?= $category->title ?></span>
                            <?php else : ?>
                                <span><?= $category->title ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php if($this->userData->juser_id == $this->currentUser->id) : ?>
            <div class="add-new-plant">
                <p><?= JText::_('COM_PLANTS_ADD_PLANTS') ?></p>
                <a href="/add">
                    <div class="add-plant-icon">
                        <span class="plus-icon"></span>
                        <span class="plus-icon"></span>
                    </div>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </header>

    <div class="container my-plants-wrap">

        <?php if( !empty($this->items) ) : ?>
            <?php foreach($this->items as $item) : ?>
                <div class="my-plant-item">
                    <div class="my-plant-mobile-controls">
                        <div class="facebook-share">
                            <?= JText::_('COM_PLANTS_SHARE_TO') ?>
                            <a>
                                <img src="<?= JUri::base() . 'templates/vg/img/fb-icon.png' ?>" alt="facebook logo" class="sharebtn" data-link="<?= $item->id ?>">
                            </a>
                        </div>
                        <div class="icons-wrap">
                            <a href="/edit/<?= $item->id ?>"><i class="fa fa-cog" aria-hidden="true"></i></a>
                            <a href="/index.php?option=com_plants&task=plant.deleteplant&id=<?= $item->id ?>&<?= JSession::getFormToken() ?>=1"><i class="fa fa-trash" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <a href="/plant?id=<?= $item->id ?>">
                        <?php if($item->src) : ?>
                            <div class="my-plant-img" style="background-image: url('<?= '/images/plants/wide_' . $item->src ?>')"></div>
                        <?php else : ?>
                            <div class="my-plant-img" style="background-image: url('/images/plants/wide_cover.png')"></div>
                        <?php endif; ?>
                    </a>
                    <div class="my-plant-info">
                        <div class="upper-info">
                            <div class="main-descr">
                                <h2 class="plant-title"><?= $item->sort_name ?></h2>
                                <p class="plant-location"><?= $item->city ?></p>
                                <p class="plant-descr">
                                    <?php
                                        if(strlen($item->description) > 200) {
                                            $descr = substr($item->description, 0,200) . '...';
                                            echo nl2br($descr);
                                        } else {
                                            echo nl2br($item->description);
                                        }
                                    ?>

                                </p>
                            </div>
                            <div class="secondary-info">
                                <div class="facebook-share">
                                    <a>
                                        <img src="<?= JUri::base() . 'templates/vg/img/fb-icon.png' ?>" alt="facebook logo" class="sharebtn" data-link="<?= $item->id ?>">
                                    </a>
                                </div>
                                <a href="/plant?id=<?= $item->id ?>"><img src="<?= JUri::base() . 'templates/vg/img/msg-icon.png' ?>" alt="message icon" class="message-icon"></a>
                                <?php if($this->userData->juser_id == $this->currentUser->id) : ?>
                                    <a href="/edit/<?= $item->id ?>"><i class="fa fa-cog" aria-hidden="true"></i></a>
                                    <a href="/index.php?option=com_plants&task=plant.deleteplant&id=<?= $item->id ?>&<?= JSession::getFormToken() ?>=1"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="bottom-info">
                            <div class="plant-pricing">
                                <?php if( (float)$item->price ) : ?>
                                <div class="plant-info-item">
                                    <span class="plant-item-title"><?= JText::_('COM_PLANTS_PRICE') ?></span>
                                    <span class="plant-item-value"><?= $item->price ?>€</span>
                                </div>
                                <?php endif; ?>

                                <?php if(strtotime($item->planting_date) > 0) : ?>
                                <div class="plant-info-item">
                                    <span class="plant-item-title"><?= JText::_('COM_PLANTS_PLANTING') ?></span>
                                    <span class="plant-item-value"><?= date('d-m-Y', strtotime($item->planting_date)) ?></span>
                                </div>
                                <?php endif; ?>

                                <?php if(strtotime($item->transplantation_date) > 0) : ?>
                                <div class="plant-info-item">
                                    <span class="plant-item-title"><?= JText::_('COM_PLANTS_TRANSPLANTATION') ?></span>
                                    <span class="plant-item-value"><?= date('d-m-Y', strtotime($item->transplantation_date)) ?></span>
                                </div>
                                <?php endif; ?>

                                <?php if($item->manufactured) : ?>
                                <div class="plant-info-item">
                                    <span class="plant-item-title"><?= JText::_('COM_PLANTS_SEED_PRODUCER') ?></span>
                                    <span class="plant-item-value"><?= $item->manufactured ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="plant-rating">
                                <div class="plant-info-item">
                                    <span class="plant-item-title"><?= JText::_('COM_PLANTS_GERMINABILITY') ?></span>
                                    <span class="plant-item-value rating-stars">
                                        <?php for($i = $item->germinability; $i >= 1; $i--) : ?>
                                            <span class="fa fa-star checked"></span>
                                        <?php endfor; ?>
                                        <?php for($i = 5 - $item->germinability; $i >= 1; $i--) : ?>
                                            <span class="fa fa-star"></span>
                                        <?php endfor; ?>
                                    </span>
                                </div>
                                <div class="plant-info-item">
                                    <span class="plant-item-title"><?= JText::_('COM_PLANTS_YIELD') ?></span>
                                    <span class="plant-item-value rating-stars">
                                        <?php for($i = $item->yield; $i >= 1; $i--) : ?>
                                            <span class="fa fa-star checked"></span>
                                        <?php endfor; ?>
                                        <?php for($i = 5 - $item->yield; $i >= 1; $i--) : ?>
                                            <span class="fa fa-star"></span>
                                        <?php endfor; ?>
                                    </span>
                                </div>
                                <div class="plant-info-item">
                                    <span class="plant-item-title"><?= JText::_('COM_PLANTS_EASY_CARE') ?></span>
                                    <span class="plant-item-value rating-stars">
                                        <?php for($i = $item->easy_care; $i >= 1; $i--) : ?>
                                            <span class="fa fa-star checked"></span>
                                        <?php endfor; ?>
                                        <?php for($i = 5 - $item->easy_care; $i >= 1; $i--) : ?>
                                            <span class="fa fa-star"></span>
                                        <?php endfor; ?>
                                    </span>
                                </div>
                                <div class="plant-info-item">
                                    <span class="plant-item-title"><?= JText::_('COM_PLANTS_AUTHOR_RECOMMENDS') ?></span>
                                    <span class="plant-item-value rating-stars">
                                        <?php for($i = $item->author_recomends; $i >= 1; $i--) : ?>
                                            <span class="fa fa-star checked"></span>
                                        <?php endfor; ?>
                                        <?php for($i = 5 - $item->author_recomends; $i >= 1; $i--) : ?>
                                            <span class="fa fa-star"></span>
                                        <?php endfor; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</main>

<script>
    jQuery(document).ready(function(){

        jQuery('.fa-trash').on('click', function(){
            if (!confirm('Are you sure?'))
                return false;
        });


        jQuery('.sharebtn').on('click', function(){

            var plantId = jQuery(this).data('link');

            FB.ui({
                method: 'share',
                display: 'popup',
                href: '<?= JUri::base() ?>plant?id=' + plantId
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
    })
</script>
