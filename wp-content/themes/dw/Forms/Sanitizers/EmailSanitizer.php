<?php

class EmailSanitizer extends BaseSanitizer {

	public function getSanitizedValue() {
		return sanitize_email( $this->value ?? null );
	}
}