<?php

namespace LoginMeNow\App\DTO;

class ProviderSettingsFieldsDTO {
	private array $fields = [];

	public function set_fields( array $fields ) {
		$this->fields = $fields;

		return $this;
	}

	public function get_fields() {
		return $this->fields;
	}
}