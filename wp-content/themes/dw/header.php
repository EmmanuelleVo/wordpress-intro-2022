<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= get_bloginfo('name') ?></title>
    <link rel="stylesheet" href="<?= dw_mix('css/style.css') ?>" type="text/css">
    <script src="<?= dw_mix('js/script.js') ?>" type="text/javascript"></script>
</head>
<body>
<header class="header">
    <h1 class="header__title"><?= get_bloginfo('name'); ?></h1>
    <p class="header__tagline"><?= get_bloginfo('description'); ?></p>

    <nav class="header__nav nav">
        <h2 class="nav__title">Navigation principale</h2>
        <?php /*wp_nav_menu(['theme_location' => 'primary',
                           'menu_class' => 'nav__links',
                           'menu_id' => 'navigation',
                           'container_class'=> 'nav__container',
                           'walker' => new PrimaryMenuWalker(),

        ]) */?>
        <ul class="nav__container">
            <?php foreach (dw_get_menu_items('primary') as $link): ?>
            <li class="<?= $link->getBemClasses('nav__item') ?>">
                <a href="<?= $link->url ?>" class="nav__link"><?= $link->label ?></a>
                <?php if($link->hasSubItems()): ?>
                <ul class="nav__subitems">
	                <?php foreach ($link->subitems as $sub): ?>
                    <li class="<?= $link->getBemClasses('nav__subitem') ?>">
                        <a href="<?= $sub->url ?>" class="nav__link"><?= $sub->label ?></a>
                    </li>
	                <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </li>
	        <?php endforeach; ?>
        </ul>
    </nav>
    <form class="header__search search" method="get" action="<?= get_home_url() ?>" role="search">
        <div class="search__container">
            <label for="header_search" class="search__label">Recherche :</label>
            <input type="text" name="s" id="header_search" class="search__input" value="<?= get_search_query() ?>">
            <input type="submit" class="search__button" value="Rechercher">
        </div>
    </form>
</header>
