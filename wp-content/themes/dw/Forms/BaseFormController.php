<?php

abstract class BaseFormController // abstract : jamais faire un new BaseFormController() + les class qui extends doivent utiliser les méthodes
{
	protected $data = [];

	public function __construct(array $data)
	{
		$this->data = $data;

		$this->verifyNonce();
		$this->sanitizeData();
		$this->validateData();
		$this->handle();
		$this->redirectWithSuccess();
		exit;
	}

	protected function verifyNonce()
	{
		// Vérification du nonce //

		$nonce = $this->data['_wpnonce'] ?? null; // null si undefined

		if (!$nonce || !wp_verify_nonce( $nonce, $this->getNonceKey() ) ) {
			die('Unauthorized access.');
		}

	}

	abstract protected function getNonceKey():string;

	protected function sanitizeData()
	{
		foreach ($this->getSanitizableAttributes() as $attribute => $sanitizer) {
			$sanitizer = new $sanitizer($this->data[$attribute] ?? null);

			$this->data[$attribute] = $sanitizer->getSanitizedValue();
		}
	}

	abstract protected function getSanitizableAttributes():array;

	protected function validateData()
	{
		$errors = [];

		foreach ($this->getValidatableAttributes() as $attribute => $validators) {
			$errors = $this->validateAttribute( $errors, $attribute, $validators );
		}

		if($errors){
			$this->redirectWithErrors($errors);
			exit; // stop l'exécution du code
		}
	}

	abstract protected function redirectWithErrors($errors);

	protected function validateAttribute($errors, $attribute, $validators )
	{
		foreach ($validators as $validator) {
			$validator = new $validator($this->data[$attribute] ?? null);

			if(!$validator->hasError()) continue;

			$errors[$attribute] = $validator->getError();

			break; // quand on a une erreur, on arrête la boucle
		}
		return $errors;
	}

	abstract protected function getValidatableAttributes():array;

	abstract protected function handle();

	abstract protected function redirectWithSuccess();


}