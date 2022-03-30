<?php

class EmailSanitizer extends BaseSanitizer {

	public function getSanitizedValue() {
		return sanitize_text_field( $this->value ?? null );
	}
}