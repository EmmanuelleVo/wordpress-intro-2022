<?php get_header() ?>

<main class="layout">
	<section class="results">
		<h2 class="results__title"><?= __('Les articles correspondant à votre recherche', 'dw') ?></h2>
		<div class="results__container">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php dw_include('post', ['modifier' => 'search']); ?>
			<?php endwhile; else : ?>
				<p class="results__empty"><?= __('Il n’y a pas de résultat pour votre recherche', 'dw') ?></p>
			<?php endif; ?>
		</div>
	</section>

	<section class="results">
		<h2 class="results__title"><?= __('Les récits correspondant à votre recherche<', 'dw') ?>/h2>
		<div class="results__container">
	<?php if (($trips = dw_get_trips(3, get_search_query()))->have_posts() ) : while ($trips->have_posts() ) : $trips->the_post(); ?>
		<?php dw_include('trip', ['modifier' => 'search']); ?>
	<?php endwhile; else : ?>
        <p class="trips__empty"><?= __('Il n’y a pas encore de voyage', 'dw') ?></p>
	<?php endif; ?>
		</div>
	</section>
</main>

<?php get_footer() ?>