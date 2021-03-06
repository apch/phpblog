<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
    <link rel="stylesheet" href="<?=APP_ROOT?>/content/styles.css" />
    <link rel="icon" href="<?=APP_ROOT?>/content/images/favicon.ico" />
    <script src="<?=APP_ROOT?>/content/scripts/jquery-3.0.0.min.js"></script>
    <script src="<?=APP_ROOT?>/content/scripts/blog-scripts.js"></script>
    <script src="<?=APP_ROOT?>/content/tinymce/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: 'textarea',  // change this value according to your HTML
            width: 600,
            height: 300,
            toolbar: 'undo redo | styleselect | bold italic | link image',
        });
    </script>
    <title><?php if (isset($this->title)) echo htmlspecialchars($this->title) ?></title>
</head>

<body>
<header>
    <a href="<?=APP_ROOT?>/"><img class="logo-style" src="<?=APP_ROOT?>/content/images/site-logo.png"></a>
    <a class="hover-effects" href="<?=APP_ROOT?>/">Home</a>
    <?php if ($this->isLoggedIn) : ?>
        <a class="hover-effects" href="<?=APP_ROOT?>/posts">Posts</a>
        <a class="hover-effects" href="<?=APP_ROOT?>/comments">Comments</a>
        <a class="hover-effects" href="<?=APP_ROOT?>/posts/create">Create Post</a>
        <a class="hover-effects" href="<?=APP_ROOT?>/categories">Categories</a>
        <a class="hover-effects" href="<?=APP_ROOT?>/users">Users</a>
    <?php else: ?>
        <a class="hover-effects" href="<?=APP_ROOT?>/users/login">Login</a>
        <a class="hover-effects" href="<?=APP_ROOT?>/users/register">Register</a>
    <?php endif; ?>

    <?php if ($this->isLoggedIn) : ?>
        <div id="logged-in-info">
            <span>

            <?php
            date_default_timezone_set('Europe/Sofia');
            $time = date("H");
            if ($time < "12") {
                echo "Good Мorning,";
            } else
            if ($time >= "12" && $time < "17") {
                echo "Good Afternoon,";
            } else
            if ($time >= "17" && $time < "19") {
                echo "Good Evening,";
            } else
            if ($time >= "19") {
                echo "Good Night,";
            }
            ?> 

            <b><?=htmlspecialchars($_SESSION['username']) .'!'?></b></span>
            <form method="post" action="<?=APP_ROOT?>/users/logout">
                <input class="btn" type="submit" value="Logout"/>
            </form>
        </div>
    <?php endif; ?>
</header>

<?php require_once('show-notify-messages.php'); ?>



