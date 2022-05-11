<?php get_header(); ?>
    <main class="layout">
        <section class="layout__trips trips">
            <h2 class="trips__title"><?= __('Tous mes voyages', 'dw'); ?></h2>
            <nav class="trips__filters">
                <h3 class="sro"><?= __('Filtrer les résultats', 'dw'); ?></h3>
				<?php foreach(get_terms(['taxonomy' => 'country', 'hide_empty' => true]) as $term) : ?>
                    <a href="?country=<?= $term->slug; ?>"><?= $term->name; ?></a>
				<?php endforeach; ?>
            </nav>
            <div class="trips__container">
				<?php
				if(have_posts()): while(have_posts()): the_post();
					include(__DIR__ . '/partials/trip.php');
				endwhile; else: ?>
                    <p class="trips__empty"><?= __('Il n’y a pas de voyages à vous raconter...', 'dw'); ?></p>
				<?php endif; ?>
            </div>
        </section>
    </main>
<?php get_footer(); ?>