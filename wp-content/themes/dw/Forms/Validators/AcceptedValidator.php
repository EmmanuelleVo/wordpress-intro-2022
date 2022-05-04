<?php

class AcceptedValidator extends BaseValidator {

	protected function handle( $value ): ?string {
		if( $value !== '1') {
			return __('Veuillez cocher la case ci-dessus pour continuer.', 'dw');
		}

		return null;
	}
}