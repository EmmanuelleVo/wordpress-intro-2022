<?php get_header() ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <main class="layout single-post">
        <h2 class="layout__title"><?= get_the_title() ?></h2>
        <figure class="single-post__title">
			<?= get_the_post_thumbnail( null, 'large', [
				'class' => 'trip__thumb',
			] ) ?>
        </figure>
        <div class="single-post__container">
			<?= the_content() ?>
        </div>
    </main>
<?php endwhile; endif; ?>
<?php get_footer() ?>
