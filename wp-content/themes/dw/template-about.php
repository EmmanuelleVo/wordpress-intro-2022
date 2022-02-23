<?php /* Template Name: About */ ?>
<?php get_header() ?>
<main class="layout about">
	<h2 class="layout__title"><?= get_the_title() ?></h2>
	<div class="about__container">
		<?= the_content() ?>
	</div>
</main>
<?php get_footer() ?>
