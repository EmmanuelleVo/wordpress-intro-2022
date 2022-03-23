<?php

class ContactFormController extends BaseFormController {

	protected function getNonceKey(): string {
		return 'nonce_check_contact_form';
	}

	protected function getSanitizableAttributes(): array {
		return [
			'firstname' => TextSanitizer::class,
			'lastname' => TextSanitizer::class,
			'email' => EmailSanitizer::class,
			'phone' => TextSanitizer::class,
			'message' => TextSanitizer::class,
			'rules' => TextSanitizer::class,
		];
	}

	protected function getValidatableAttributes(): array {
		return [
			'firstname' => [RequiredValidator::class],
			'lastname' => [RequiredValidator::class],
			'email' => [RequiredValidator::class, EmailValidator::class],
			'message' => [RequiredValidator::class],
			'rules' => [AcceptedValidator::class],
			];
	}

	protected function redirectWithErrors( $errors )
	{
		$_SESSION['feedback_contact_form'] = [
			'success' => false,
			'data' => $this->data,
			'errors' => $errors,
		];
		// status 302 = redirection temporaire (défault) et status 301 = redirection permanente
		return wp_safe_redirect(($this->data['_wp_http_referer'] ?? '') . '#contact', 302);
	}

	protected function handle() {
		// Traitement //
		// Stocker en DB
		$id = wp_insert_post( [
			'post_type'    => 'message',
			'post_title'   => 'Message de ' . $this->data['firstname'] . ' ' . $this->data['lastname'],
			'post_content' => $this->data['message'],
			'post_status'  => 'publish'
		] );
		// Envoyer un mail
		$content = 'Bonjour, un nouveau message de contact à été envoyé. <br>';
		$content .= 'Pour le visualiser : ' . get_edit_post_link( $id );

		wp_mail( get_bloginfo('admin_email'), 'Nouveau message', $content );
		// TODO : configurer serveur mail (ex: mailgun - plugin)
	}

	protected function redirectWithSuccess() {
		// Tout est OK : feedback positif
		$_SESSION['feedback_contact_form'] = [
			'success' => true,
		];
		return wp_safe_redirect($this->data['_wp_http_referer'] . '#contact', '302');
	}
}