<?php

namespace LoginMeNow\App\DTO;

class ProviderListenersDTO extends DTO {
	private bool $authenticated;
	private string $error_message;
	private string $redirection_url;

	public function set_authenticated( bool $authenticated ) {
		$this->authenticated = $authenticated;

		return $this;
	}

	public function is_authenticated() {
		return $this->authenticated;
	}

	public function set_error_message( string $error_message ) {
		$this->error_message = $error_message;

		return $this;
	}

	public function get_error_message() {
		return $this->error_message;
	}

	public function set_redirection_url( string $redirection_url ) {
		$this->redirection_url = $redirection_url;

		return $this;
	}

	public function get_redirection_url() {
		return $this->redirection_url;
	}
}