<?php

defined('_JEXEC') or die;

$fb_id = $this->params->get('id');
$fb_url = JUri::getInstance() . '/fblogin';

$g_id = $this->params->get('google_id');
$g_url = JUri::getInstance() . '/glogin';

?>

<main class="signInPage">
    <!-- your code -->
    <div class="card">
        <div class="signUpIn text-right">
            <a href="#" class="btn btn-pink btn-sm ml-auto">Sign in</a>
            <a href="signup" class="btn btn-sm btn-pink inactive ml-2">Sign up</a>
        </div>
        <div class="our_form">
            <div class="title">Sign In</div>
            <div class="signWith d-flex justify-content-between mb-3">
                <a href="https://www.facebook.com/v2.11/dialog/oauth?client_id=<?= $fb_id ?>&redirect_uri=<?= $fb_url ?>&response_type=code&scope=public_profile,email" target="_blank" class="btn btn-lightBlue btn-lg">Sign in with Facebook</a>
                <a href="https://accounts.google.com/o/oauth2/auth?redirect_uri=<?= $g_url ?>&response_type=code&client_id=<?= $g_id ?>&scope=https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile" class="btn btn-blue btn-lg">Sign in with Google</a>
            </div>
            <form action="">
                <div class="input form-group">
                    <input type="email" class="form-control-lg w-100" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" name="username">
                </div>

                <div class="input form-group">
                    <input type="password" class="form-control-lg w-100" id="exampleInputPassword1" placeholder="Password" name="password">
                </div>

                <div class="form-check mt-3">
                    <label class="form-check-label d-flex justify-content-between">
                        <input class="form-check-input" type="checkbox" checked>
                        Remember me
                        <a href="#" class="forgotPswd">Forgot Password?</a>
                        <input type="hidden" name="task" value="login.signin">
                    </label>
                </div>

                <div class="kapcha"></div>

                <button type="submit" class="btn btn-green btn-lg btn-block mt-3">Sign In</button>
            </form>
        </div>
    </div>
</main>