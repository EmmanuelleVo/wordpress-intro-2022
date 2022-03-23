<?php

abstract class BaseValidator
{
	protected $message;

	public function __construct($value) {
		$this->message = $this->handle($value);
	}

	abstract protected function handle($value):?string;

	public function hasError():bool {
		return !is_null($this->message);
	}

	public function getError():?string {
		return $this->message;
	}
}