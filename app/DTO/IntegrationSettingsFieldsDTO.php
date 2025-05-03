<?php

namespace LoginMeNow\App\DTO;

class IntegrationSettingsFieldsDTO {
    private string $title;

    private string $color;

    private int $created_by;

    private int $id;

    private int $form_id;

    public function __construct( string $title, string $color, int $form_id, int $created_by ) {
        $this->title      = $title;
        $this->color      = $color;
        $this->form_id    = $form_id;
        $this->created_by = $created_by;
    }

    public function get_color() {
        return $this->color;
    }

    public function set_color( string $color ) {
        $this->color = $color;
    }

    public function get_title() {
        return $this->title;
    }

    public function set_title( string $title ) {
        $this->title = $title;
    }

    public function get_id() {
        return $this->id;
    }

    public function set_id( int $id ) {
        $this->id = $id;
    }

    public function get_form_id() {
        return $this->form_id;
    }

    public function set_form_id( int $form_id ) {
        $this->form_id = $form_id;
    }

    public function get_created_by() {
        return $this->created_by;
    }

    public function set_created_by( int $created_by ) {
        $this->created_by = $created_by;
    }
}