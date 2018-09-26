<?php defined('_JEXEC') or die; ?>

<form action="/index.php?option=com_plants&task=sender.sendMessage" method="post">

    <label for="email">
        <span>Email:</span>
        <input id="email" type="email" name="email" required />
    </label>

    <label for="email">
        <span>Message:</span>
        <textarea id="message" name="message" required ></textarea>
    </label>

    <input type="submit">

</form>
