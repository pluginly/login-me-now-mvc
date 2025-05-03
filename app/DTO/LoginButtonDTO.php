<?php

namespace LoginMeNow\App\DTO;

class LoginButtonDTO extends DTO {
	private string $class;
	private string $icon;
	private string $label;
	private string $modal_behavior;
	private string $modal_html;

	public function set_class( string $class ) {
		$this->class = $class;

		return $this;
	}

	public function get_class() {
		return $this->class;
	}

	public function set_icon( string $icon ) {
		$this->icon = $icon;

		return $this;
	}

	public function get_icon() {
		return $this->icon;
	}

	public function set_label( string $label ) {
		$this->label = $label;

		return $this;
	}

	public function get_label() {
		return $this->label;
	}

	public function set_modal_behavior( string $modal_behavior ) {
		$this->modal_behavior = $modal_behavior;

		return $this;
	}

	public function get_modal_behavior() {
		return $this->modal_behavior;
	}

	public function set_modal_html( string $modal_html ) {
		$this->modal_html = $modal_html;

		return $this;
	}

	public function get_modal_html() {
		return $this->modal_html;
	}
}