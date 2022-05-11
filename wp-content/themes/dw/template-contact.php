<?php /* Template Name: template-contact */ ?>
<?php get_header() ?>
    <main class="layout contact">
        <h2 class="layout__title"><?= get_the_title() ?></h2>
        <div class="contact__container">
			<?= the_content() ?>
        </div>
        <?php if(! isset($_SESSION['feedback_contact_form']) || !$_SESSION['feedback_contact_form']['success']): ?>
        <form class="contact__form" action="<?= get_home_url() ?>/wp-admin/admin-post.php" method="post">
            <?php if(isset($_SESSION['feedback_contact_form']) && !$_SESSION['feedback_contact_form']['success']): ?>
                <p class="form__errors"><?= __('Ce formulaire contient des erreurs, merci de les corriger.', 'dw') ?></p>
            <?php endif;?>
            <div class="form__field">
                <label for="firstname" class="form__label"><?= __('Votre prénom', 'dw') ?></label>
                <input type="text" name="firstname" id="firstname" class="form__input"
                       value="<?= dw_get_contact_field_value('firstname') ?>">
                <?= dw_get_contact_field_error('firstname') ?>
            </div>
            <div class="form__field">
                <label for="lastname" class="form__label"><?= __('Votre nom', 'dw') ?></label>
                <input type="text" name="lastname" id="lastname" class="form__input"
                       value="<?= dw_get_contact_field_value('lastname') ?>">
	            <?= dw_get_contact_field_error('lastname') ?>
            </div>
            <div class="form__field">
                <label for="email" class="form__label"><?= __('Votre email', 'dw') ?></label>
                <input type="email" name="email" id="email" class="form__input"
                       value="<?= dw_get_contact_field_value('email') ?>">
	            <?= dw_get_contact_field_error('email') ?>
            </div>
            <div class="form__field">
                <label for="phone" class="form__label"><?= __('Votre numéro de téléphone', 'dw') ?></label>
                <input type="tel" name="phone" id="phone" class="form__input" value="<?= dw_get_contact_field_value('phone') ?>">
            </div>
            <div class="form__field">
                <label for="message" class="form__label"><?= __('Votre message', 'dw') ?></label>
                <textarea name="message" id="message" cols="30" rows="10"><?= dw_get_contact_field_value('message') ?></textarea>
	            <?= dw_get_contact_field_error('message') ?>
            </div>
            <div class="form__field">
                <label for="rules" class="form__checkbox">
                    <input type="checkbox" name="rules" id="rules" class="form__checker" value="1">
                    <span class="form__checklabel">J'ai lu et j'accepte les <a href="#">conditions générales d'utilisation</a></span>
                    <span class="form__checklabel"><?= str_replace(':conditions',
		                    '<a href="#">' . __('conditions générales d’utilisation', 'dw') . '</a>',
                            __('J’ai lu et j’accepte les :conditions', 'dw')); ?></a></span>
                </label>
	            <?= dw_get_contact_field_error('rules') ?>
            </div>
            <div class="form__actions">
                <input type="hidden" name="action" value="submit_contact_form">
                <?php wp_nonce_field('nonce_check_contact_form') ?>
                <button type="submit" class="form__button"><?= __('Envoyer', 'dw') ?></button>
            </div>
        </form>
        <?php else: ?>
            <p class="form__feedback"><?= __('Merci de nous avoir contacté. À bientôt', 'dw') ?> !</p>
        <?php endif; ?>
    </main>
<?php get_footer();

unset($_SESSION['feedback_contact_form'])
?>