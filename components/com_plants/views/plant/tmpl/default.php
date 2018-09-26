<?php

defined('_JEXEC') or die;

$ts = strtotime($this->plant->planting_date);
if( $ts > 0 )
    $planting_date = date('d-m-Y', $ts);
else
    $planting_date = '';

$ts = strtotime($this->plant->transplantation_date);
if( $ts > 0 )
    $transplantation_date = date('d-m-Y', $ts);
else
    $transplantation_date = '';


$document = JFactory::getDocument();
$document->setMetaData( 'og:url', JUri::getInstance() );
$document->setMetaData( 'og:type', 'website' );
$document->setMetaData( 'og:title', $document->getTitle() );
//$document->setMetaData( 'og:description', 'tagcontent' );

if( isset($this->photos[0]->src) )
    $document->setMetaData( 'og:image', JUri::base() . 'images/plants/wide_' . $this->photos[0]->src );

?>



<main class="plantPage <?= $this->currentUser ? ' auth' : ''?>" style="background-image: url('/templates/vg/img/plant_page_bg.jpg')">
    <div class="container">
        <div class="bread-crumbs-wrap">
            <div class="bread-crumb first">
                <?php if($this->currentUser && $this->plant->user_id == $this->currentUser->juser_id) : ?>
                    <?= JText::_('COM_PLANTS_MY_PLANTS') ?>
                <?php elseif($this->currentUser->guest) : ?>
                    <a href="/signup"><?= $this->plant->first_name . "'s plants" ?></a>
                <?php else : ?>
                    <a href="/plants/<?= $this->plant->user_id ?>"><?= $this->plant->first_name . "'s plants" ?></a>
                <?php endif; ?>
            </div>


            <div class="bread-crumb">
                <?php if($this->currentUser->guest) : ?>
                    <a href="/signup"><?= $this->plant->category ?></a>
                <?php else : ?>
                    <a href="/plants/<?= $this->plant->user_id . '/' . $this->plant->category_alias ?>"><?= $this->plant->category ?></a>
                <?php endif; ?>
            </div>
            <div class="bread-crumb"><?= $this->plant->sort_name ?></div>
        </div>
        <div class="plant-content">
            <div class="plant-info-wrap">
                <div class="plant-images-wrap">

                    <?php if($this->currentUser && $this->plant->user_id == $this->currentUser->juser_id) : ?>
                        <div class="slider-controls">
                            <a href="/edit/<?= $this->plant->id ?>">
                                <i class="fa fa-cog" aria-hidden="true"></i>
                            </a>
                            <a href="/index.php?option=com_plants&task=plant.deletePlant&id=<?= $this->plant->id ?>&<?= JSession::getFormToken() ?>=1">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="plant-images-slider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1}'>
                        <?php foreach ($this->photos as $photo) : ?>
                            <div class="slider-item">
                                <img src="<?= '/images/plants/wide_' . $photo->src ?>" alt="plant image">
                            </div>
                        <?php endforeach; ?>

                        <?php if($this->plant->seeds_photo) : ?>
                        <div class="slider-item">
                            <img src="/images/seeds_photo/<?= $this->plant->seeds_photo ?>" alt="plant image">
                        </div>
                        <?php endif; ?>

                        <?php if($this->plant->barcode_photo) : ?>
                            <div class="slider-item">
                                <img src="/images/barcodes_photo/<?= $this->plant->barcode_photo ?>" alt="plant image">
                            </div>
                        <?php endif; ?>

                        <?php if(empty($this->photos) && !$this->plant->seeds_photo && !$this->plant->barcode_photo) : ?>
                            <div class="slider-item">
                                <img src="/images/plants/wide_cover.png" alt="plant image">
                            </div>
                        <?php endif ?>
                    </div>
                </div>
                <div class="plant-info">
                    <div class="commets-block">
                        <span><?= count($this->comments) ?></span>
                        <i class="fa fa-comment" aria-hidden="true"></i>
                    </div>
                    <div class="info-left-part">
                        <h2 class="plant-title">
                            <?= $this->plant->sort_name ?>
                        </h2>
                        <p class="plant-location">
                            <?= $this->plant->city ?>
                        </p>
                        <p class="plant-descr">
                            <?= nl2br($this->plant->description) ?>
                        </p>
                        <div class="plant-basic-info-wrapper">
                            <div class="plant-basic-info">

                                <?php if($this->plant->type) : ?>
                                    <div class="basic-info-item">
                                        <span class="info-item-title"><?= JText::_('COM_PLANTS_PLANT_TYPE') ?></span>
                                        <span class="info-item-value"><?= $this->plant->type ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if( (float)$this->plant->price ) : ?>
                                    <div class="basic-info-item">
                                        <span class="info-item-title"><?= JText::_('COM_PLANTS_PRICE') ?></span>
                                        <span class="info-item-value"><?= $this->plant->price ?> €</span>
                                    </div>
                                <?php endif; ?>

                                <?php if($this->plant->purchased) : ?>
                                    <div class="basic-info-item">
                                        <span class="info-item-title"><?= JText::_('COM_PLANTS_PURCHASED') ?></span>
                                        <span class="info-item-value"><?= $this->plant->purchased ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if($planting_date) : ?>
                                    <div class="basic-info-item">
                                        <span class="info-item-title"><?= JText::_('COM_PLANTS_PLANTING') ?></span>
                                        <span class="info-item-value"><?= $planting_date ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if($this->plant->preseeding == 0) : ?>
                                    <div class="basic-info-item">
                                        <span class="info-item-title"><?= JText::_('COM_PLANTS_PRESEEDING') ?></span>
                                        <span class="info-item-value"><?= JText::_('COM_PLANTS_NO') ?></span>
                                    </div>
                                <?php elseif ($this->plant->preseeding == 1) : ?>
                                    <div class="basic-info-item">
                                        <span class="info-item-title"><?= JText::_('COM_PLANTS_PRESEEDING') ?></span>
                                        <span class="info-item-value"><?= JText::_('COM_PLANTS_YES') ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if($transplantation_date) : ?>
                                    <div class="basic-info-item">
                                        <span class="info-item-title"><?= JText::_('COM_PLANTS_TRANSPLANTATION') ?></span>
                                        <span class="info-item-value"><?= $transplantation_date ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if($this->plant->manufactured) : ?>
                                    <div class="basic-info-item">
                                        <span class="info-item-title"><?= JText::_('COM_PLANTS_SEED_PRODUCER') ?></span>
                                        <span class="info-item-value"><?= $this->plant->manufactured ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if($this->plant->barcode) : ?>
                                    <div class="basic-info-item">
                                        <span class="info-item-title"><?= JText::_('COM_PLANTS_BARCODE') ?></span>
                                        <span class="info-item-value"><?= $this->plant->barcode ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php foreach ($this->plant->custom_fields as $field) : ?>
                                    <?php if($this->plant->manufactured) : ?>
                                        <div class="basic-info-item">
                                            <span class="info-item-title"><?= $field->name?></span>
                                            <span class="info-item-value"><?= $field->value ?></span>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="info-right-part">
                        <ul class="plant-rating">
                            <?php if($this->plant->seeds_in_package) : ?>
                                <li class="rating-item">
                                    <span class="rating-title"><?= JText::_('COM_PLANTS_SEEDS_IN_PACKAGE') ?></span>
                                    <span class="rating-value">
                                        <?php
                                            switch($this->plant->seeds_in_package) {
                                                case 1:
                                                    echo JText::_('COM_PLANTS_TOO_LESS');
                                                    break;
                                                case 2:
                                                    echo JText::_('COM_PLANTS_NORMAL');
                                                    break;
                                                case 3:
                                                    echo JText::_('COM_PLANTS_MANY');
                                                    break;
                                            }
                                        ?>
                                    </span>
                                </li>
                            <?php endif; ?>
                            <li class="rating-item">
                                <span class="rating-title"><?= JText::_('COM_PLANTS_SEED_GERMINABILITY') ?></span>
                                <span class="rating-value">
                                    <?php for($i = $this->plant->germinability; $i >= 1; $i--) : ?>
                                        <span class="fa fa-star checked"></span>
                                    <?php endfor; ?>
                                    <?php for($i = 5 - $this->plant->germinability; $i >= 1; $i--) : ?>
                                        <span class="fa fa-star"></span>
                                    <?php endfor; ?>
                                </span>
                            </li>
                            <li class="rating-item">
                                <span class="rating-title"><?= JText::_('COM_PLANTS_SEED_YIELD') ?></span>
                                <span class="rating-value">
                                    <?php for($i = $this->plant->yield; $i >= 1; $i--) : ?>
                                        <span class="fa fa-star checked"></span>
                                    <?php endfor; ?>
                                    <?php for($i = 5 - $this->plant->yield; $i >= 1; $i--) : ?>
                                        <span class="fa fa-star"></span>
                                    <?php endfor; ?>
                              </span>
                            </li>
                            <li class="rating-item">
                                <span class="rating-title"><?= JText::_('COM_PLANTS_EASY_CARE') ?></span>
                                <span class="rating-value">
                                    <?php for($i = $this->plant->easy_care; $i >= 1; $i--) : ?>
                                        <span class="fa fa-star checked"></span>
                                    <?php endfor; ?>
                                    <?php for($i = 5 - $this->plant->easy_care; $i >= 1; $i--) : ?>
                                        <span class="fa fa-star"></span>
                                    <?php endfor; ?>
                              </span>
                            </li>
                            <li class="rating-item">
                                <span class="rating-title"><?= JText::_('COM_PLANTS_AUTHOR_RECOMMENDS') ?></span>
                                <span class="rating-value">
                                    <?php for($i = $this->plant->author_recomends; $i >= 1; $i--) : ?>
                                        <span class="fa fa-star checked"></span>
                                    <?php endfor; ?>
                                    <?php for($i = 5 - $this->plant->author_recomends; $i >= 1; $i--) : ?>
                                        <span class="fa fa-star"></span>
                                    <?php endfor; ?>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php if($this->currentUser) : ?>
            <a class="big-green-btn" href="/catalog/<?= $this->plant->category_alias ?>">
                <div><?= JText::_('COM_PLANTS_SIMILAR_PLANTS') ?></div>
            </a>
            <?php endif; ?>
            <div class="comments-section">
                <?php if(!$this->currentUser->guest) : ?>
                    <div class="new-comment">
                        <div class="author-pick">
                            <?php if($this->currentUser->photo) : ?>
                                <img src="<?= JUri::base() . 'images/user_photos/' . $this->currentUser->photo ?>" alt="author avatar">
                            <?php else : ?>
                                <img src="/images/user_photos/user.svg" alt="author avatar">
                            <?php endif; ?>
                        </div>
                        <form method="post" action="" enctype="multipart/form-data" class="form-group">
                            <input name="comment" type="text" class="form-control" placeholder="<?= JText::_('COM_PLANTS_ADD_A_COMMENT') ?>" required>
                            <button type="submit"><i class="fa fa-check" aria-hidden="true"></i></button>
                            <input type="hidden" name="task" value="plant.sendComment"/>
                            <?= JHtml::_( 'form.token' ); ?>
                        </form>
                    </div>
                <?php endif; ?>
                <?php if($this->comments) : ?>
                    <div class="comments-list">
                        <?php foreach ($this->comments as $comment) : ?>
                        <div class="comment-item">
                            <div class="author-info-wrap">
                                <div class="author-pick">
                                    <?php if($comment->user_photo) : ?>
                                        <img style="height: 50px" src="<?= JUri::base() . 'images/user_photos/' . $comment->user_photo ?>" alt="author avatar">
                                    <?php else : ?>
                                        <img style="height: 50px" src="/images/user_photos/user.svg" alt="author avatar">
                                    <?php endif; ?>
                                </div>
                                <div class="author-info">
                                    <p class="name"><?= $comment->first_name ?></p>
                                    <div class="author-sub-info">
                                        <span><?= $comment->created_at ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="comment-text">
                                <?= $comment->comment ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if($this->currentUser->guest) : ?>
                        <a class="big-green-btn" href="/signup" class="signupButton">
                            <div><?= JText::_('COM_PLANTS_REGISTRATION') ?></div>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script>
    jQuery(document).ready(function(){

        jQuery('.fa-trash-o').on('click', function(){
            if (!confirm('<?= JText::_('COM_PLANTS_ARE_YOU_SURE') ?>'))
                return false;
        });
    })
</script>
