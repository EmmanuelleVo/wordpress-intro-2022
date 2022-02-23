<?php /* Template Name: Contact */ ?>
<?php get_header() ?>
	<main class="layout contact">
		<h2 class="layout__title"><?= get_the_title() ?></h2>
		<div class="contact__container">
			<?= the_content() ?>
		</div>
        <div class="contact__form">
            <!--do_shortcode('[contact-form-7 id="43" title="contact-form"]')-->
            <?= apply_filters('the_content', '[contact-form-7 id="43" title="contact-form"]') ?>
        </div>
	</main>
<?php get_footer() ?>