<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= get_bloginfo( 'name' ) ?></title>
    <link rel="stylesheet" href="<?= dw_mix( 'css/style.css' ) ?>" type="text/css">
    <script src="<?= dw_mix( 'js/script.js' ) ?>" type="text/javascript"></script>
    <?php wp_head(); ?>
</head>
<body data-translations="">
<header class="header">
    <div class="header__wrapper">
        <h1 class="header__title"><?= get_bloginfo( 'name' ); ?></h1>
        <p class="header__tagline"><?= get_bloginfo( 'description' ); ?></p>

        <nav class="header__nav nav">
            <h2 class="nav__title"><?= __('Navigation principale', 'dw') ?></h2>
			<?php /*wp_nav_menu(['theme_location' => 'primary',
                           'menu_class' => 'nav__links',
                           'menu_id' => 'navigation',
                           'container_class'=> 'nav__container',
                           'walker' => new PrimaryMenuWalker(),

        ]) */ ?>
            <ul class="nav__container">
				<?php foreach ( dw_get_menu_items( 'primary' ) as $link ): ?>
                    <li class="<?= $link->getBemClasses( 'nav__item' ) ?>">
                        <a href="<?= $link->url ?>" class="nav__link"><?= $link->label ?></a>
						<?php if ( $link->hasSubItems() ): ?>
                            <ul class="nav__subitems">
								<?php foreach ( $link->subitems as $sub ): ?>
                                    <li class="<?= $link->getBemClasses( 'nav__subitem' ) ?>">
                                        <a href="<?= $sub->url ?>" class="nav__link"><?= $sub->label ?></a>
                                    </li>
								<?php endforeach; ?>
                            </ul>
						<?php endif; ?>
                    </li>
				<?php endforeach; ?>
            </ul>
            <div class="nav__languages">
                <?php foreach(pll_the_languages(['raw' => true]) as $code => $locale): ?>

                    <a lang="<?= $locale['locale'] ?>" hreflang="<?= $locale['locale'] ?>" href="<?= $locale['url'] ?>" title="<?= $locale['name'] ?>" class="nav__locale"><?= strtoupper($code) ?></a>
                <?php endforeach; ?>
            </div>
        </nav>
        <form class="header__search search" method="get" action="<?= get_home_url() ?>" role="search">
            <div class="search__container">
                <label for="header_search" class="search__label"><?= __('Recherche', 'dw') ?> :</label>
                <input type="text" name="s" id="header_search" class="search__input" value="<?= get_search_query() ?>">
                <input type="submit" class="search__button" value="<?= __('Rechercher', 'dw') ?>">
            </div>
        </form>
    </div>
</header>
