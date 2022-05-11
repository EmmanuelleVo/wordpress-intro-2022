<?php get_header() ?>

    <main class="layout">
        <section class="layout__intro">
            <h2 class="layout__title"><?= __('Introduction', 'dw') ?></h2>
            <p class="layout__text"><?= __('Bienvenue', 'dw') ?>&nbsp;!</p>
        </section>

        <section class="layout__latest latest">
            <h2 class="latest__title"><?= __('Nos dernières nouvelles', 'dw') ?></h2>
            <div class="latest__container">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<?php dw_include('post', ['modifier' => 'index']); ?>
				<?php endwhile; else : ?>
                    <p><?= __('Pas de post', 'dw') ?></p>
				<?php endif; ?>
            </div>
        </section>

        <section class="layout__trips trips">
            <h2 class="trips__title"><?= __('Mes derniers voyages', 'dw') ?></h2>
            <a href="<?= get_post_type_archive_link('trip') ?>" class="trips__all"><?= __('Voir tous les voyages', 'dw') ?></a>
            <div class="trips__container">
                <?php
                $trips = dw_get_trips(3);
                if (($trips/* = dw_get_trips(3)*/)->have_posts() ) : while ($trips->have_posts() ) : $trips->the_post(); ?>
	                <?php dw_include('trip', ['modifier' => 'index']); ?>
				<?php endwhile; else : ?>
                    <p class="trips__empty"><?= __('Il n’y a pas encore de voyage', 'dw') ?></p>
				<?php endif; ?>
            </div>
        </section>

    </main>

<?php get_footer() ?>