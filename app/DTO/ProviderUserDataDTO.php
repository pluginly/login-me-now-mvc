<?php

namespace LoginMeNow\App\DTO;

class ProviderUserDataDTO extends DTO {
	private int $user_id;
	private string $user_name;
	private string $user_email;
	private string $user_display_name;
}