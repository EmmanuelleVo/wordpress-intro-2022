<?php get_header() ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<main class="layout single-trip">
	<h2 class="layout__title"><?= get_the_title() ?></h2>
    <figure class="single-trip__title">
	    <?= get_the_post_thumbnail( null, 'large', [
		    'class' => 'trip__thumb',
	    ] ) ?>
    </figure>
    <div class="single-trip__container">
        <?= the_content() ?>
    </div>
    <aside class="single-trip__details">
        <h3 class="single-trip__subtitle"><?= __('Détails du voyage', 'dw') ?></h3>
        <dl class="single-trip__definitions">
            <dt class="single-trip__label"><?= __('Date de départ', 'dw') ?></dt>
            <dd class="single-trip__data">
                <time class="single-trip__date" datetime="<?= date('c', strtotime(get_field('departure_date', false, false))) ?>">
		            <?= ucfirst(date_i18n('l, j F Y', strtotime(get_field('departure_date', false, false)))) ?>
                </time>
            </dd>
            <dt class="single-trip__label"><?= __('Date de retour', 'dw') ?></dt>
            <?php if( get_field( 'return_date' )): ?>
            <dd class="single-trip__data">
                <time class="single-trip__date" datetime="<?= date('c', strtotime(get_field('departure_date', false, false))) ?>">
		            <?= ucwords(date_i18n('l, j F Y', strtotime(get_field('return_date', false, false)))) ?>
                </time>
            <?php else: ?>
                <span class="date__empty"><?= __('Il n’y a pas encore de date de retour de prévue pour le moment', 'dw') ?></span>
            <?php endif; ?>
            </dd>
        </dl>
    </aside>
</main>
<?php endwhile; endif; ?>
<?php get_footer() ?>
