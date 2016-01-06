<!DOCTYPE html>

<html>

    <head>

        <link href="/css/bootstrap.min.css" rel="stylesheet"/>
        <link href="/css/bootstrap-theme.min.css" rel="stylesheet"/>
        <link href="/css/styles.css" rel="stylesheet"/>

        <?php if (isset($title)): ?>
            <title>CS50x Project: <?= htmlspecialchars($title) ?></title>
        <?php else: ?>
            <title>BFF</title>
        <?php endif ?>

        <script src="/js/jquery-1.11.1.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script src="/js/scripts.js"></script>

    </head>

    <body>

        <div class="container">
            <div id="top">
                <a href="/"><img alt="Best Fuel Finder!" src="/img/bff.gif"/></a>
            </div>
            <div id="middle">
