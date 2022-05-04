<?php

class RequiredValidator extends BaseValidator {

	protected function handle( $value ): ?string {
		var_dump(filter_var( $value, FILTER_VALIDATE_EMAIL));
		if(is_null($value)
		   || $value === ''
		   || (is_array($value) && empty($value))
		) {
			return __('Ce champ ne peut pas être vide', 'dw');
		}

		return null;
	}
}