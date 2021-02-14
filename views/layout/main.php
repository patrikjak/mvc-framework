<?php
use app\core\Application;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <title>PHP MVC Framework</title>
</head>
<body>

<ul class="nav">
    <li class="nav-item">
        <a class="nav-link" href="/">Home</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/contact">Contact</a>
    </li>
    <?php if (Application::isGuest()): ?>
    <li class="nav-item">
        <a class="nav-link" href="/login">Login</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/register">Register</a>
    </li>
    <?php else: ?>
        <li class="nav-item">
            <a class="nav-link" href="/logout"><?= Application::$app->user->getName(); ?> - Log out</a>
        </li>
    <?php endif; ?>
</ul>

<div class="container">
    <?php if (Application::$app->session->getFlash('success')): ?>
    <div class="alert alert-success">
        <?= Application::$app->session->getFlash('success'); ?>
    </div>
    <?php endif; ?>
    {{content}}
</div>


</body>
</html>
