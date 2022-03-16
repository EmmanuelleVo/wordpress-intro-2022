<?php /* Template Name: Contact */ ?>
<?php get_header() ?>
    <main class="layout contact">
        <h2 class="layout__title"><?= get_the_title() ?></h2>
        <div class="contact__container">
			<?= the_content() ?>
        </div>
        <form class="contact__form" action="<?= get_home_url() ?>/wp-admin/admin-post.php" method="post">
            <div class="form__field">
                <label for="firstName" class="form__label">Votre prénom</label>
                <input type="text" name="firstName" id="firstName" class="form__input">
            </div>
            <div class="form__field">
                <label for="lastName" class="form__label">Votre nom</label>
                <input type="text" name="lastName" id="lastName" class="form__input">
            </div>
            <div class="form__field">
                <label for="email" class="form__label">Votre email</label>
                <input type="email" name="email" id="email" class="form__input">
            </div>
            <div class="form__field">
                <label for="phone" class="form__label">Votre numéro de téléphone</label>
                <input type="tel" name="phone" id="phone" class="form__input">
            </div>
            <div class="form__field">
                <label for="message" class="form__label">Votre message</label>
                <textarea name="message" id="message" cols="30" rows="10"></textarea>
            </div>
            <div class="form__field">
                <label for="rules" class="form__checkbox">
                    <input type="checkbox" name="rules" id="rules" class="form__checker" value="1">
                    <span class="form__checklabel">J'ai lu et j'accepte les <a href="#">conditions générales d'utilisation</a></span>
                </label>
            </div>
            <div class="form__actions">
                <input type="hidden" name="action" value="submit_contact_form">
                <?php wp_nonce_field('nonce_check_contact_form') ?>
                <button type="submit" class="form__button">Envoyer</button>
            </div>
        </form>
    </main>
<?php get_footer() ?>