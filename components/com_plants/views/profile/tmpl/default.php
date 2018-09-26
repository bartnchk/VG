<?php
    defined('_JEXEC') or die;

    if($this->user->birthday)
        $age = floor((time() - strtotime($this->user->birthday)) / 31556926);
?>
<main class="profilePage">

    <!-- profile heading with info -->
    <section class="container">
        <?php if( ($this->user->created_at == $this->user->updated_at) && ($this->currentUserId == $this->user->juser_id) ) : ?>
            <div class="create-block-wrap">
                <a href="/settings" class="create-block create-block-profile">
                          <span class="create-block__img-wrap">
                            <img src="/templates/vg/img/add_gardner.jpg" alt="" class="create-block__img">
                          </span>
                    <span class="create-block__descr"><?= JText::_('COM_PLANTS_COMPLETE_YOUR_PROFILE') ?></span>
                </a>
            </div>
        <?php else : ?>
        <div class="profile-main-info">
            <div class="profile-img-wrap">
                <?php if($this->user->photo) : ?>
                    <img src="<?= JUri::base() . 'images/user_photos/' . $this->user->photo?>" alt="profile avatar">
                <?php else : ?>
                    <img src="/images/user_photos/user.svg" alt="profile avatar">
                <?php endif; ?>
                <div class="mobile-plants-amount"><?= $this->counter ?><?= $this->counter == 1 ? ' plant' : ' plants' ?></div>
            </div>

            <div class="profile-text-info">
                <div class="profile-name-wrap">
                    <h3 class="profile-name"><?= $this->user->first_name . ' ' . $this->user->last_name ?></h3>
                    <span class="plants-amount"><?= $this->counter ?><?= $this->counter == 1 ? ' plant' : ' plants' ?></span>
                </div>
                <p class="profile-lvl">
                    <?php
                        switch ($this->user->experience) {
                            case 'beg':
                                echo 'Beginner';
                                break;
                            case 'exp':
                                echo 'Experienced';
                                break;
                            case 'adv':
                                echo 'Advanced';
                                break;
                        }
                    ?>
                </p>
                <?php if( isset($age) ) : ?>
                    <p class="profile-age"><?= $age ?> <?= JText::_('COM_PLANTS_YEARS') ?></p>
                <?php endif; ?>

                <?php if($this->user->city) : ?>
                    <p class="profile-location"><?= $this->user->city ?></p>
                <?php endif; ?>

                <?php if($this->user->about_me) : ?>
                    <p class="profile-descr"><?= $this->user->about_me ?></p>
                <?php endif; ?>
            </div>


        </div>
        <?php endif; ?>
    </section>

    <!-- section with my plants -->
    <?php if( !empty($this->categories) ) : ?>
        <section class="my-plants-section">
        <?php if($this->currentUserId != $this->user->juser_id) : ?>
            <h2 class="mobile-title"><?= $this->user->first_name . '\'s' ?> <?= JText::_('COM_PLANTS_PLANTS') ?></h2>
        <?php else : ?>
            <h2 class="mobile-title"><?= JText::_('COM_PLANTS_MY_PLANTS') ?></h2>
        <?php endif; ?>
        <div class="container">
            <?php if($this->currentUserId != $this->user->juser_id) : ?>
                <h2 class="asideText"><?= $this->user->first_name . '\'s' ?> <?= JText::_('COM_PLANTS_PLANTS') ?></h2>
            <?php else : ?>
                <h2 class="asideText"><?= JText::_('COM_PLANTS_MY_PLANTS') ?></h2>
            <?php endif; ?>
            <div class="row">
            <?php $href = ($this->user->juser_id == $this->currentUserId) ? 'plants' : 'plants/' . $this->user->juser_id; ?>
            <?php foreach ($this->categories as $category) : ?>

                <a href="/<?= $href ?>/<?= $category->alias ?>" class="my-plants-item">
                    <div class="my-plants-item-container" style="background-image: url('/<?= $category->photo ?>');">
                        <h4 class="category-title"><?= $category->title ?></h4>
                    </div>
                </a>
            <?php endforeach; ?>

            <?php if($this->best_plant) : ?>
            <a href="/plant?id=<?= $this->best_plant->id ?>" class="my-plants-item">
                <div class="my-plants-item-container best-plant" style="background-image: url(<?= JUri::base() . 'images/plants/wide_' . $this->best_plant->photo ?>);">
                    <div class="best-plant-wrap">
                    <p class="title"><?= JText::_('COM_PLANTS_BEST_PLANTS') ?></p>
                    <p class="title"><?= $this->best_plant->sort_name ?></p>
                    <span class="location"><?= $this->best_plant->manufactured ?></span>
                    <ul class="plant-rating">
                        <li class="rating-item">
                            <div class="rating-value">
                                <?php for($i = $this->best_plant->germinability; $i >= 1; $i--) : ?>
                                    <i class="fa fa-star checked"></i>
                                <?php endfor; ?>
                                <?php for($i = 5 - $this->best_plant->germinability; $i >= 1; $i--) : ?>
                                    <i class="fa fa-star"></i>
                                <?php endfor; ?>
                            </div>
                        </li>
                        <li class="rating-item">
                            <div class="rating-value">
                                <?php for($i = $this->best_plant->yield; $i >= 1; $i--) : ?>
                                    <i class="fa fa-star checked"></i>
                                <?php endfor; ?>
                                <?php for($i = 5 - $this->best_plant->yield; $i >= 1; $i--) : ?>
                                    <i class="fa fa-star"></i>
                                <?php endfor; ?>
                            </div>
                        </li>
                        <li class="rating-item">
                            <div class="rating-value">
                                <?php for($i = $this->best_plant->easy_care; $i >= 1; $i--) : ?>
                                    <i class="fa fa-star checked"></i>
                                <?php endfor; ?>
                                <?php for($i = 5 - $this->best_plant->easy_care; $i >= 1; $i--) : ?>
                                    <i class="fa fa-star"></i>
                                <?php endfor; ?>
                            </div>
                        </li>
                        <li class="rating-item">
                            <div class="rating-value">
                                <?php for($i = $this->best_plant->author_recomends; $i >= 1; $i--) : ?>
                                    <i class="fa fa-star checked"></i>
                                <?php endfor; ?>
                                <?php for($i = 5 - $this->best_plant->author_recomends; $i >= 1; $i--) : ?>
                                    <i class="fa fa-star"></i>
                                <?php endfor; ?>
                            </div>
                        </li>
                    </ul>
                </div>
                </div>
            </a>
            <?php endif; ?>
            </div>
        </div>
    </section>
    <?php elseif($this->currentUserId == $this->user->juser_id) : ?>
        <section class="container">
            <div class="create-block-wrap">
                <a href="/add" class="create-block create-block-plant">
                              <span class="create-block__img-wrap">
                                <img src="/templates/vg/img/add_plant.jpg" alt="" class="create-block__img">
                              </span>
                    <span class="create-block__descr"><?= JText::_('COM_PLANTS_CREATE_YOUR_FIRST_PLANT') ?></span>
                </a>
            </div>
        </section>
    <?php endif; ?>

    <?php if( !empty($this->plants) ) : ?>
    <section class="container catalog">
        <h2 class="mobile-title"><?= JText::_('COM_PLANTS_MY_PLANTS') ?></h2>
        <div class="catalog-wrap">
            <h2 class="asideText"><?= JText::_('COM_PLANTS_CATALOG') ?></h2>
            <div class="plants-list">
                <?php foreach ($this->plants as $plant) : ?>
                    <div class="plant">
                        <a href="/plant?id=<?= $plant->id ?>" class="plant-content" style="background-image: url('/images/plants/<?= $plant->src ?>')"></a>
                    </div>
                <?php endforeach; ?>
                <div class="plant">
                    <a class="plant-content learn-more" href="/plants/<?= $this->user->juser_id ?>"><?= JText::_('COM_PLANTS_LEARN') ?><br/> <?= JText::_('COM_PLANTS_MORE') ?></a>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

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