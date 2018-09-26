<?php
    defined('_JEXEC') or die;
?>
<main class="profilePage">

    <section class="container">
        <div class="profile-main-info">
            <div class="profile-img-wrap">
                <img src="/<?= $this->post->photo ?>" alt="profile avatar">
            </div>

            <div class="profile-text-info">
                <div class="profile-name-wrap">
                    <h3 class="profile-name"><?= $this->post->title ?></h3>

                </div>
                <p><?= $this->post->post ?></p>
            </div>
        </div>
    </section>

</main>