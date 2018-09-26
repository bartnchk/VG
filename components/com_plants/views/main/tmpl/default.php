<main class="indexPage">

    <section class="pictureDownload" style="background-image: url('/templates/vg/img/main-page.jpg')">
        <div class="container">
            <div class="our_circle_subscription">
                <div class="upload-wrapper">
                    <div class="txt_download"><?= JText::_('TAKE_A_PICTURE_UPLOAD') ?></div>
                    <div class="our_icons">
                        <div class="our_three_icons d-flex justify-content-between">
                            <div class="bg_circle">
                                <img src="<?= JUri::base() ?>templates/vg/img/group.png" alt="">
                            </div>
                            <div class="bg_circle">
                                <img src="<?= JUri::base() ?>templates/vg/img/pictures.png" alt="">
                            </div>
                            <div class="bg_circle">
                                <img src="<?= JUri::base() ?>templates/vg/img/stars.png" alt="">
                            </div>
                        </div>
                        <div class="block_plus">
                            <a href="/add">
                                <div class="bg_circle">
                                    <img class="img_plus" src="<?= JUri::base() ?>templates/vg/img/plus.png" alt="">
                                </div>
                                <div class="txt_add">Add plants</div>
                            </a>
                        </div>
                    </div>
                </div>
                <form class="subscription" method="post" action="" enctype="multipart/form-data">
                    <div class="bg_subscription">
                        <div class="form-group">
                            <label for="exampleInputEmail1" class="txt_newsletter">Newsletter subscription</label>
                            <input type="email" class="form-control" aria-describedby="emailHelp" placeholder="Email" value="" id="email1" required>
                        </div>
                        <p class="bg_subscription-descr"><?= JText::_('NEWSLETTER_PRIVACY_POLICY') ?></p>
                        <button type="button" class="btn btn-green" value="Subscribe" id="subscribe1">Subscribe</button>
                    </div>
<!--                    --><?php //if($this->user->guest) : ?>
<!--                        <a href="/signup" class="btn btn-pink btn-lg mt-4">Sign Up / Sign In</a>-->
<!--                    --><?php //endif; ?>
                </form>
            </div>
        </div>
    </section>

    <section class="plantOfTheDay">
        <div class="container">
            <div class="line_break">Plant of the day</div>
            <div class="row">
                <?php if( !empty($this->dayPlant) ) : ?>

                    <div class="mainArticle col-md-6 d-flex align-items-center">
                        <h2 class="asideText">Plant of the day</h2>
                        <a href="/plant?id=<?= $this->dayPlant->id ?>" class="articleCardLink">
                            <div class="articleCard" style="background-image: url('/images/plants/wide_<?= $this->dayPlant->photo ?>')">
                                <div class="title_small d-flex flex-column my-auto">
                                    <div class="card_title"><?= $this->dayPlant->sort_name ?></div>
                                    <div class="small"><?= $this->dayPlant->city ?></div>
                                </div>
                            </div>
                        </a>
                        <?php if(!$this->user->guest) : ?>
                            <a href="/profile?id=<?= $this->dayPlant->user_id ?>" class="img_text">
                                <?php if($this->dayPlant->user_photo) : ?>
                                    <img class="user_img mr-3" src="/images/user_photos/<?= $this->dayPlant->user_photo ?>">
                                <?php else : ?>
                                    <img class="user_img mr-3" src="/images/user_photos/user.svg">
                                <?php endif; ?>

                                <div class="text"><?= $this->dayPlant->user_name . ' ' . $this->dayPlant->user_last_name?></div>
                            </a>
                        <?php else : ?>
                            <span class="img_text">
                                <?php if($this->dayPlant->user_photo) : ?>
                                    <img class="user_img mr-3" src="/images/user_photos/<?= $this->dayPlant->user_photo ?>">
                                <?php else : ?>
                                    <img class="user_img mr-3" src="/images/user_photos/user.svg">
                                <?php endif; ?>

                                <div class="text"><?= $this->dayPlant->user_name . ' ' . $this->dayPlant->user_last_name?></div>
                            </span>
                        <?php endif; ?>
                    </div>

                <?php endif; ?>

                <div class="articles list-group list-group-flush col-md-6 d-flex flex-wrap align-contant-between justify-content-center">

                    <?php foreach ($this->posts as $post) : ?>
                        <a href="<?= '/catalog' . getParams($post) ?>" class="article list-group-item list-group-item-action d-flex align-items-center justify-content-between">
                            <div class="article_text">
                                <div class="title"><?= $post->title ?></div>
                            </div>
                            <div class="imgWrap">
                                <img class="img-fluid" src="/<?= $post->photo ?>">
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="catalogSection">
        <div class="container">
            <div class="txt_catalog text-center"><?= JText::_('COM_PLANTS_CATALOG') ?></div>
            <div class="catalog row">
                <h2 class="asideText"><?= JText::_('COM_PLANTS_CATALOG') ?></h2>
                <?php foreach($this->catalog as $item) : ?>
                    <a href="/catalog/<?= $item->alias ?>" class="catalogPhoto" style="background-image: url('/<?= $item->photo ?>')"></a>
                <?php endforeach; ?>
                <a href="/catalog" class="btn catalogPhoto sample_bg_orange"><?= JText::_('COM_PLANTS_LEARN_MORE') ?></a>
            </div>
        </div>
    </section>

    <section>
        <form class="subscription mobile">
            <div class="bg_subscription">
                <div class="form-group">
                    <label for="exampleInputEmail1" class="txt_newsletter"><?= JText::_('NEWSLETTER_SUBSCRIPTION') ?></label>
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email">
                </div>
                <p class="bg_subscription-descr"><?= JText::_('NEWSLETTER_PRIVACY_POLICY') ?></p>
                <button type="submit" class="btn btn-green" id="subscribe-mobile"><?= JText::_('COM_PLANTS_SUBMIT') ?></button>
        </form>
    </section>

<!--    <secton>-->
<!--        <div class="banner d-flex justify-content-around">-->
<!--            <a href="#"><img class="banner_img" src="--><?//= JUri::base() ?><!--templates/vg/img/banner.jpg" alt="Banner"> </a>-->
<!--        </div>-->
<!--    </secton>-->

    <section class="section-title">
        <div class="thin-line"></div>
        <div class="title-wrap">
            <?= JText::_('COM_PLANTS_LAST_ADDED') ?>
        </div>
    </section>

    <section class="product-slider-section">
        <div class="container product-slider-container">
            <div class="product-slider" data-slick='{"slidesToShow": 5, "slidesToScroll": 3}'>
                <?php foreach ($this->lastPlants as $item) : ?>
                    <a href="/plant?id=<?= $item->id ?>">
                        <div class="slider-item">
                            <?php if($item->photo) : ?>
                                <img src="/images/plants/<?= $item->photo ?>" alt="plant image">
                            <?php else : ?>
                                <img src="/images/plants/cover.png" alt="plant image">
                            <?php endif; ?>

                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php if( !empty($this->randomPlant) ) : ?>
        <section class="random-plant-section">
        <div class="container">
            <div class="random-plant-mobile-title text-center"><?= JText::_('COM_PLANTS_RANDOM_PLANT') ?></div>
            <div class="random-plant-wrap">
                <div class="asideText">
                    <?= JText::_('COM_PLANTS_RANDOM_PLANT') ?>
                </div>
                <div class="random-plant-heading">
                    <a class="imgWrapper" href="/plant?id=<?= $this->randomPlant->id ?>">
                        <?php if($this->randomPlant->photo) : ?>
                            <img class="plan-heading-image" src="/images/plants/wide_<?= $this->randomPlant->photo ?>" alt="plant image">
                        <?php else : ?>
                            <img class="plan-heading-image" src="/images/plants/wide_cover.png" alt="plant image">
                        <?php endif; ?>
                    </a>
                    <?php if(!$this->user->guest) : ?>
                        <a href="/profile?id=<?= $this->randomPlant->user_id ?>" class="author-wrap">
                            <?php if($this->randomPlant->user_photo) : ?>
                                <img class="plant-author-image" src="/images/user_photos/<?= $this->randomPlant->user_photo ?>" alt="author avatar">
                            <?php else : ?>
                                <img class="plant-author-image" src="/images/user_photos/user.svg" alt="author avatar">
                            <?php endif; ?>
                            <p class="author-name"><?= $this->randomPlant->user_first_name . ' ' . $this->randomPlant->user_last_name ?></p>
                        </a>
                    <?php else : ?>
                        <span class="author-wrap">
                            <?php if($this->randomPlant->user_photo) : ?>
                                <img class="plant-author-image" src="/images/user_photos/<?= $this->randomPlant->user_photo ?>" alt="author avatar">
                            <?php else : ?>
                                <img class="plant-author-image" src="/images/user_photos/user.svg" alt="author avatar">
                            <?php endif; ?>
                            <p class="author-name"><?= $this->randomPlant->user_first_name . ' ' . $this->randomPlant->user_last_name ?></p>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="random-plant-info">
                    <div class="plant-info-upper">
                        <div class="info-upper-details">
                            <h3 class="info-upper-title"><?= $this->randomPlant->sort_name ?></h3>
                            <p class="info-upper-location"><?= $this->randomPlant->city ?></p>
                            <p class="info-upper-descr"><?= $this->randomPlant->description ?></p>
                        </div>
                        <div class="plant-socials">
                            <div class="plant-socials-fb">
                                <a href="https://www.facebook.com/sharer/sharer.php?s=100&p[url]=<?= JUri::getInstance() ?>&p[images][0]=&p[title]=<?= $this->title ?>&" target="_blank" onclick="window.open(this.href,'targetWindow','toolbar=no,location=0,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=250'); return false">
                                    <img src="/templates/vg/img/fb-icon.png" alt="facebook icon" data-link="<?= $this->randomPlant->id ?>" class="sharebtn">
                                </a>
                                <?= JText::_('COM_PLANTS_SHARE_TO') ?>
                            </div>
                            <a href="/plant?id=<?= $this->randomPlant->id ?>"><img class="plant-socials-msg" src="/templates/vg/img/msg-icon.png" alt="message icon"></a>
                        </div>
                    </div>
                    <div class="plant-info-bottom">
                        <ul class="plant-info-list">
                            <li>
                                <span class="info-list-title">Price</span>
                                <span class="info-list-value"><?= $this->randomPlant->price ?>€</span>
                            </li>

                            <?php if(strtotime($this->randomPlant->planting_date) > 0) : ?>
                            <li>
                                <span class="info-list-title"><?= JText::_('COM_PLANTS_PLANTING') ?></span>
                                <span class="info-list-value">
                                    <?= date( 'd-m-Y', strtotime($this->randomPlant->planting_date) ) ?>
                                </span>
                            </li>
                            <?php endif; ?>

                            <?php if(strtotime($this->randomPlant->transplantation_date) > 0) : ?>
                            <li>
                                <span class="info-list-title"><?= JText::_('COM_PLANTS_TRANSPLANTATION') ?></span>
                                <span class="info-list-value">
                                    <?= date( 'd-m-Y', strtotime($this->randomPlant->transplantation_date) ) ?>
                                </span>
                            </li>
                            <?php endif; ?>

                            <li>
                                <span class="info-list-title"><?= JText::_('COM_PLANTS_SEED_PRODUCER') ?></span>
                                <span class="info-list-value"><?= $this->randomPlant->manufactured ?></span>
                            </li>
                        </ul>
                        <ul class="plant-rating">
                            <li>
                                <span class="rating-title"><?= JText::_('COM_PLANTS_SEED_GERMINABILITY') ?></span>
                                <div class="rating-value">
                                    <?php for($j = $this->randomPlant->germinability; $j >= 1; $j--) : ?>
                                        <span class="fa fa-star checked"></span>
                                    <?php endfor; ?>
                                    <?php for($j = 5 - $this->randomPlant->germinability; $j >= 1; $j--) : ?>
                                        <span class="fa fa-star"></span>
                                    <?php endfor; ?>
                                </div>
                            </li>
                            <li>
                                <span class="rating-title"><?= JText::_('COM_PLANTS_SEED_YIELD') ?></span>
                                <div class="rating-value">
                                    <?php for($j = $this->randomPlant->yield; $j >= 1; $j--) : ?>
                                        <span class="fa fa-star checked"></span>
                                    <?php endfor; ?>
                                    <?php for($j = 5 - $this->randomPlant->yield; $j >= 1; $j--) : ?>
                                        <span class="fa fa-star"></span>
                                    <?php endfor; ?>
                                </div>
                            </li>
                            <li>
                                <span class="rating-title"><?= JText::_('COM_PLANTS_EASY_CARE') ?></span>
                                <div class="rating-value">
                                    <?php for($j = $this->randomPlant->easy_care; $j >= 1; $j--) : ?>
                                        <span class="fa fa-star checked"></span>
                                    <?php endfor; ?>
                                    <?php for($j = 5 - $this->randomPlant->easy_care; $j >= 1; $j--) : ?>
                                        <span class="fa fa-star"></span>
                                    <?php endfor; ?>
                                </div>
                            </li>
                            <li>
                                <span class="rating-title"><?= JText::_('COM_PLANTS_AUTHOR_RECOMMENDS') ?></span>
                                <div class="rating-value">
                                    <?php for($j = $this->randomPlant->author_recomends; $j >= 1; $j--) : ?>
                                        <span class="fa fa-star checked"></span>
                                    <?php endfor; ?>
                                    <?php for($j = 5 - $this->randomPlant->author_recomends; $j >= 1; $j--) : ?>
                                        <span class="fa fa-star"></span>
                                    <?php endfor; ?>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <section class="section-title">
        <div class="thin-line"></div>
        <div class="title-wrap">
            <?= JText::_('COM_PLANTS_POPULAR') ?>
        </div>
    </section>

    <section class="popular-plants container">
        <?php foreach ($this->popularPlants as $item) : ?>
            <div class="popular-plant-item">
                <div class="plant-item-upper">
                    <?php if(!$this->user->guest) : ?>
                        <a href="/profile?id=<?= $item->user_id ?>" class="author-wrap">
                            <?php if($item->user_photo) : ?>
                                <img src="/images/user_photos/<?= $item->user_photo ?>" alt="author avatar">
                            <?php else : ?>
                                <img src="/images/user_photos/user.svg" alt="author avatar">
                            <?php endif; ?>
                            <p class="author-name"><?= $item->user_first_name . ' ' . $item->user_last_name ?></p>
                        </a>
                    <?php else : ?>
                        <span class="author-wrap">
                            <?php if($item->user_photo) : ?>
                                <img src="/images/user_photos/<?= $item->user_photo ?>" alt="author avatar">
                            <?php else : ?>
                                <img src="/images/user_photos/user.svg" alt="author avatar">
                            <?php endif; ?>
                            <p class="author-name"><?= $item->user_first_name . ' ' . $item->user_last_name ?></p>
                        </span>
                    <?php endif; ?>
                    <a href="/plant?id=<?= $item->id ?>">
                        <?php if($item->src) : ?>
                            <img class="popular-plant-image" src="/images/plants/<?= $item->src ?>" alt="plant image">
                        <?php else : ?>
                            <img class="popular-plant-image" src="/images/plants/cover.png" alt="plant image">
                        <?php endif; ?>
                    </a>
                </div>
                <div class="plant-item-bottom">
                <div class="plant-item-bottom-head">
                    <h3 class="popular-plant-title">
                        <?= $item->sort_name ?>
                    </h3>
                    <p class="plant-location"><?= $item->city ?></p>
                </div>
                <p class="popular-plant-descr">
                    <?= $item->description ?>
                </p>
                <ul class="plant-rating">
                    <li>
                        <span class="rating-title"><?= JText::_('COM_PLANTS_SEED_GERMINABILITY')?></span>
                        <div class="rating-value">
                            <?php for($j = $item->germinability; $j >= 1; $j--) : ?>
                                <span class="fa fa-star checked"></span>
                            <?php endfor; ?>
                            <?php for($j = 5 - $item->germinability; $j >= 1; $j--) : ?>
                                <span class="fa fa-star"></span>
                            <?php endfor; ?>
                        </div>
                    </li>
                    <li>
                        <span class="rating-title"><?= JText::_('COM_PLANTS_SEED_YIELD')?></span>
                        <div class="rating-value">
                            <?php for($j = $item->yield; $j >= 1; $j--) : ?>
                                <span class="fa fa-star checked"></span>
                            <?php endfor; ?>
                            <?php for($j = 5 - $item->yield; $j >= 1; $j--) : ?>
                                <span class="fa fa-star"></span>
                            <?php endfor; ?>
                        </div>
                    </li>
                    <li>
                        <span class="rating-title"><?= JText::_('COM_PLANTS_EASY_CARE')?></span>
                        <div class="rating-value">
                            <?php for($j = $item->easy_care; $j >= 1; $j--) : ?>
                                <span class="fa fa-star checked"></span>
                            <?php endfor; ?>
                            <?php for($j = 5 - $item->easy_care; $j >= 1; $j--) : ?>
                                <span class="fa fa-star"></span>
                            <?php endfor; ?>
                        </div>
                    </li>
                    <li>
                        <span class="rating-title"><?= JText::_('COM_PLANTS_AUTHOR_RECOMMENDS')?></span>
                        <div class="rating-value">
                            <?php for($j = $item->author_recomends; $j >= 1; $j--) : ?>
                                <span class="fa fa-star checked"></span>
                            <?php endfor; ?>
                            <?php for($j = 5 - $item->author_recomends; $j >= 1; $j--) : ?>
                                <span class="fa fa-star"></span>
                            <?php endfor; ?>
                        </div>
                    </li>
                </ul>
            </div>
            </div>
        <?php endforeach; ?>
    </section>
</main>

<?php
    function getParams($post)
    {
        $search_params = '';
        $sign = '';

        if($post->category)
        {
            $search_params = '/' . $post->category;
        }

        switch ($post->rating)
        {
            case 0:
                return substr($search_params, 0, strlen($search_params) - 1);
            case 1:
                $sign = '>';
                break;
            case 2:
                $sign = '<';
                break;
            case 3:
                $sign = '';
        }

        if($post->germinability)
        {
            $search_params .= $search_params ? '&' : '?';
            $search_params .= $sign ? 'germinability=' . $sign : 'germinability=';
            $search_params .= $post->germinability;
        }

        if($post->yield)
        {
            $search_params .= $search_params ? '&' : '?';
            $search_params .= $sign ? 'yield=' . $sign : 'yield=';
            $search_params .= $post->yield;
        }

        if($post->easy_care)
        {
            $search_params .= $search_params ? '&' : '?';
            $search_params .= $sign ? 'easy_care=' . $sign : 'easy_care=';
            $search_params .= $post->easy_care;
        }

        if($post->author_recommends)
        {
            $search_params .= $search_params ? '&' : '?';
            $search_params .= $sign ? 'author_recommends=' . $sign : 'author_recommends=';
            $search_params .= $post->author_recommends;
        }

        if( !(strpos($search_params,'?') === 0) || strpos($search_params,'?') )
        {
            $position = strpos($search_params,'&');
            $search_params = substr_replace($search_params, '?', $position, 1);
        }


        return $search_params;
    }
?>

<script>
    jQuery(document).ready(function () {

        jQuery('#subscribe1').on('click', function() {

            var email = jQuery('#email1').val();

            if(email.trim() && validEmail(email))
                subscribe(email);
            else
            {
                alert('Fill the form correctly');
                return false;
            }

            jQuery('#email1').val('');
            return false;

        });


        jQuery('#subscribe-mobile').on('click', function(){

            var email = jQuery('#exampleInputEmail1').val();

            if(email.trim() && validEmail(email))
                subscribe(email);
            else
            {
                alert('Fill the form correctly');
                return false;
            }

            jQuery('#exampleInputEmail1').val('');
            return false;
        });

        function validEmail(email)
        {
            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email))
                return (true);

            return (false);
        }

        //facebook share button
        jQuery('.sharebtn').on('click', function(){

            var plantId = jQuery(this).data('link');

            FB.ui({
                method: 'share',
                display: 'popup',
                href: '<?= JUri::base() ?>plant?id=' + plantId
            }, function(response){});
        });

        jQuery('.sharepage').on('click', function(){

            FB.ui({
                method: 'share',
                display: 'popup',
                href: '<?= JUri::base() ?>'
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











