<?php
    defined('_JEXEC') or die();

    $user_id = JFactory::getUser()->id;

    $db = JFactory::getDbo();

    $query = $db->getQuery(true);
    $query->select('photo, first_name, last_name, experience');
    $query->from('#__z_users');
    $query->where('juser_id = ' . $user_id);

    $db->setQuery($query);
    $user = $db->loadObject();

    $params = \Joomla\CMS\Component\ComponentHelper::getParams('com_plants');

    $title = JFactory::getDocument()->getTitle();
    $description = 'description site';

    $lang = JFactory::getLanguage();
    $extension = 'com_plants';
    $base_dir = JPATH_SITE;
    $language_tag = $lang->getTag();
    $reload = true;
    $lang->load($extension, $base_dir, $language_tag, $reload);
?>


<!DOCTYPE html>
<html xmlns:jdoc="http://www.w3.org/1999/XSL/Transform">
<head>


    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-120474007-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-120474007-1');
    </script>


    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-MJNV5GN');</script>
    <!-- End Google Tag Manager -->


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700,900" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    <link rel="stylesheet" href="<?= JUri::base() ?>templates/vg/style/main.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker3.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="<?= JUri::base() ?>templates/vg/js/subscribe.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>

    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <jdoc:include type="head" />

</head>

<body>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MJNV5GN"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->



<div class="wrapper">
    <div class="content">
        <header class="header">

            <form class="searchForm d-lg-none" action="/search">
                <button type="button" class="close" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="form-group">
                    <input type="text" class="form-control" aria-describedby="searchHelp" placeholder="<?= JText::_('COM_PLANTS_SEARCH') ?>" name="search_query">
                    <small id="searchHelp" class="form-text"><?= JText::_('COM_PLANTS_TEXT_OR_BRACODE')?></small>
                </div>
            </form>

            <nav class="navbar navbar-expand-lg navbar-dark">
                <a class="navbar-brand" href="/">
                    <img src="<?= JUri::base() ?>templates/vg/img/logo.png" alt="">
                </a>

                <i class="fa fa-search d-lg-none ml-auto mr-4" aria-hidden="true"></i>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#burgerMenu" aria-controls="burgerMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="burgerMenu">

                    <div class="list-group flex-lg-row">
                        <?php if($user_id) : ?>
                            <a href="" class="list-group-item list-group-item-action d-flex align-items-end d-lg-none">
                                <?php if($user->photo) : ?>
                                    <img src="/images/user_photos/<?= $user->photo ?>">
                                <?php else : ?>
                                    <img src="/images/user_photos/user.svg">
                                <?php endif; ?>

                                <div class="text">
                                    <div class="name"><?= $user->first_name ?></div>
                                    <div class="grade">
                                        <?php
                                            switch ($user->experience) {
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
                                    </div>
                                </div>
                            </a>
                        <?php endif; ?>
                            <a href="/catalog" class="list-group-item list-group-item-action"><?= JText::_('COM_PLANTS_CATALOG') ?></a>
                        <?php if($user_id) : ?>
                            <a href="/plants" class="list-group-item list-group-item-action"><?= JText::_('COM_PLANTS_MY_PLANTS') ?></a>
                        <?php endif; ?>
                        <?php if($user_id) : ?>
                            <a href="/profile" class="list-group-item list-group-item-action d-lg-none"><?= JText::_('COM_PLANTS_PROFILE') ?></a>
                            <a href="/settings" class="list-group-item list-group-item-action d-lg-none"><?= JText::_('COM_PLANTS_SETTINGS') ?></a>
                            <form action="/login?task=user.logout" method="post" class="user-logout-form">
                                <div class="list-group-item list-group-item-action d-flex d-lg-none">
                                    <button type="submit" class="btn btn-green">Exit</button>
                                    <input type="hidden" name="return" value="/">
                                    <?= JHtml::_('form.token'); ?>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>

                    <div class="signShareBtns d-flex d-lg-none justify-content-end py-2">

                        <a class="btn btn-pink sharepage"><?= JText::_('COM_PLANTS_SHARE_TO') ?> <img class="pl-1" src="/templates/vg/img/fb-circle.png" alt="fb-circle.png"></a>
                        <?php if(!$user_id) : ?>
                            <a href="/login" class="btn btn-green"><?= JText::_('COM_PLANTS_SIGNIN_SIGNUP') ?></a>
                        <?php endif; ?>
                    </div>

                    <form action="/search" class="d-none d-lg-flex form-inline ml-auto" method="post">
                        <input class="form-control" type="search" placeholder="Search" aria-label="Search" name="search_query">

                        <div class="shareTo ml-4 mr-2"><?= JText::_('COM_PLANTS_SHARE_TO') ?></div>

                        <a class="navbar-brand bg_brand mr-4 sharepage">
                            <div class="bgfa pl-3 pr-3">
                                <i class="fa fa-facebook" aria-hidden="true"></i>
                            </div>
                        </a>

                        <?php if($user_id) : ?>
                            <?php if($user->photo) : ?>
                                <span class="userPhoto"><img src="/images/user_photos/<?= $user->photo ?>"></span>
                            <?php else : ?>
                                <span class="userPhoto"><img src="/images/user_photos/user.svg"></span>
                            <?php endif; ?>
                        <?php else : ?>
                            <a href="/login" class="btn btn-outline-green"><?= JText::_('COM_PLANTS_SIGNIN_SIGNUP') ?></a>
                        <?php endif; ?>
                    </form>
                </div>
            </nav>

            <div class="drop_logo">
                <div class="list-group">
                    <a href="/profile" class="list-group-item list-group-item-action"><?= JText::_('COM_PLANTS_PROFILE') ?></a>
                    <a href="/settings" class="list-group-item list-group-item-action"><?= JText::_('COM_PLANTS_SETTINGS') ?></a>
                    <form action="/login?task=user.logout" method="post" class="user-logout-form">
                        <a id="user-logout" class="list-group-item list-group-item-action"><?= JText::_('COM_PLANTS_EXIT') ?></a>
                        <input type="hidden" name="return" value="/">
                        <?= JHtml::_('form.token'); ?>
                    </form>
                </div>
            </div>

        </header>
        <jdoc:include type="message" />
        <jdoc:include type="component" />
    </div>

    <div class="notification-coockie" style="display: none;">
        <div class="notification__text">
            <?= JText::_('COM_PLANTS_COOKIE_POLICY') ?>
        </div>
        <button class="notification__btn"></button>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="infoBlock about_us col-sm-12 col-md-12 col-lg-4 d-flex flex-column">
                    <div class="title"><?= JText::_('COM_PLANTS_ABOUT_US') ?></div>
                    <div class="text"><?= JText::_('COM_PLANTS_ABOUT_US_TEXT') ?></div>
                </div>

                <div class="infoBlock menu col-sm-12 col-md-12 col-lg-4 d-flex flex-column">
                    <div class="title"><?= JText::_('COM_PLANTS_MENU') ?></div>
                    <div class="text">

                        <jdoc:include type="modules" name="footermenu" style="none" />

                        <form class="newsletter-form d-none d-lg-block" id="subscribtion-form-footer">
                            <div class="form-group">
                                <label for="email2"><?= JText::_('NEWSLETTER_SUBSCRIPTION') ?></label>
                                    <input type="email" class="form-control my-2" placeholder="Email" value="" id="email2" required>
                                    <p class="bg_subscription-descr"><?= JText::_('NEWSLETTER_PRIVACY_POLICY') ?></p>
                                <button type="submit" class="btn btn-green w-100"><?= JText::_('COM_PLANTS_SUBSCRIBE') ?></button>
                            </div>
                        </form>

                    </div>
                </div>
                <div class="infoBlock contact_us col-sm-12 col-md-12 col-lg-4 d-flex flex-column">
                    <div class="title"><?= JText::_('COM_PLANTS_CONTACT_US') ?></div>
                    <div class="text">
                        <div class="address d-flex">
                            <div class="icon">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                            </div>
                            <div class="text ml-3"><?= $params->get('address') ?></div>
                        </div>
                        <div class="address d-flex">
                            <div class="icon">
                                <i class="fa fa-envelope-open-o" aria-hidden="true"></i>
                            </div>
                            <div class="text ml-3"><?= $params->get('email') ?></div>
                        </div>
                        <div class="address d-flex">
                            <div class="icon">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                            </div>
                            <div class="text ml-3">Tel. <?= $params->get('phone') ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="container">
            <div class="footer_bottom">
                <div class="text_img d-flex align-items-center">
                    <div class="text mr-3"><?= JText::_('COM_PLANTS_DEVELOPED_BY') ?></div>
                    <a href="http://zengineers.company/" target="_blank"><img class="logo_z" src="/templates/vg/img/zengineers-logo.svg" width="30px;" height="30px;"></a>
                </div>
            </div>
        </div>
    </footer>
</div>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script type="text/javascript" src="<?= JUri::base() ?>templates/vg/js/select2.min.js"></script>
<script type="text/javascript" src="<?= JUri::base() ?>templates/vg/js/index.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
<script src="<?= JUri::base() ?>templates/vg/js/jquery.ui.touch-punch.min.js"></script>



<script>
    jQuery(document).ready(function(){

        jQuery('#subscribtion-form-footer').on('submit', function() {

            var email = jQuery('#email2').val();

            if(email.trim() && validEmail(email))
                subscribe(email);
            else
            {
                alert('Fill the form correctly');
                return false;
            }

            jQuery('#email2').val('');
            return false;
        });

        jQuery('#user-logout').on('click', function(){
            jQuery('.user-logout-form').submit();
        })

        //set cookie
        jQuery('.notification__btn').on('click', function(){
            var CookieDate = new Date;
            CookieDate.setFullYear(CookieDate.getFullYear( ) + 1);
            document.cookie = 'cookie_notification=1; expires=' + CookieDate.toGMTString( ) + ';';
        });

        if( getCookie('cookie_notification') == 0 ) {
            jQuery('.notification-coockie').show();
        }

    });

    function validEmail(email)
    {
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email))
            return (true);

        return (false);
    }

    function getCookie(cname) {

        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');

        for(var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

</script>



</body>

</html>