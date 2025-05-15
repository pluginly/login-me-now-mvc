<?php

use LoginMeNow\App\Repositories\SettingsRepository;
?>

<div class="lmn_magic_link_login">
	<a href="#" class="lmn_btn lmn_magic_link_login_button"><span class="svg-bg">
	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#ffffff" version="1.1" id="Capa_1" width="20px" height="20px" viewBox="0 0 53.727 53.727" xml:space="preserve">
		<g>
			<g>
				<path d="M25.044,24.134c0.848,0.431,2.305,0.289,3.055-0.297L49.16,7.399c-0.938-0.648-2.149-1.057-3.461-1.314    c0,0-12.807-0.539-20.843,0.242c-8.034,0.78-20.499,3.775-20.499,3.775C3.103,10.622,2,11.253,1.209,12.058L25.044,24.134z"/>
				<path d="M53.456,40.312c0.219-0.562,0.318-1.188,0.249-1.911l-2.717-27.96c-0.015-0.149-0.059-0.277-0.085-0.417l-16.9,13.191    L53.456,40.312z"/>
				<path d="M31.483,25.179l-1.451,1.134c-0.857,0.667-1.976,1.07-3.121,1.182c-1.145,0.11-2.318-0.068-3.287-0.56l-1.63-0.826    L5.856,47.077c0.657,0.263,1.38,0.47,2.155,0.629c0,0,13.446,0.434,20.83-0.365c8.272-0.848,20.511-3.651,20.511-3.651    c0.74-0.321,1.411-0.667,2.001-1.046L31.483,25.179z"/>
				<path d="M0.008,14.969c0.001,0.142-0.017,0.274-0.002,0.423l2.717,27.961c0.07,0.722,0.295,1.32,0.626,1.835l15.796-20.522    L0.008,14.969z"/>
			</g>
		</g>
	</svg>
		</span><span><?php echo esc_html( SettingsRepository::get('magic_link_login_button_text','Continue with magic link')); ?></span>
	</a>
	<div class="lmn_magic_link_login_bg"></div>

	<div class="lmn_magic_link_login_form">
		<div class="lmn_magic_link_login_form_header">
			<h2><?php echo esc_html( SettingsRepository::get( 'email_magic_link_title', 'Email Magic Link' ) ); ?></h2>
			<p><?php echo esc_html( SettingsRepository::get( 'email_magic_link_description', 'Enter your registered email address to receive a quick login link directly in your inbox.' ) ); ?></p>
		</div>
		<input type="email" name="lmn_email_address" id="lmn_email_address" autocomplete="email" placeholder="Email Address">
    	<a href="#" class="lmn_btn lmn_magic_link_login_send_link"><?php esc_html_e( 'Send Link', 'login-me-now' ); ?></a>
    	<a href="#" class="lmn_magic_link_login_close"><?php esc_html_e( 'Back to Login', 'login-me-now' ); ?></a>
	</div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const magicLinkButton = document.querySelector(".lmn_magic_link_login_button");
        const magicLinkForm = document.querySelector(".lmn_magic_link_login_form");
        const magicLinkBackground = document.querySelector(".lmn_magic_link_login_bg");
        const closeButtons = document.querySelectorAll(".lmn_magic_link_login_close");
        const sendLinkButton = document.querySelector(".lmn_magic_link_login_send_link");
        const messageBox = document.createElement("p");

        // Add message box for feedback
        messageBox.className = "lmn_message_box";
        messageBox.style.marginTop = "10px";
        messageBox.style.color = "#0073aa";
        magicLinkForm.appendChild(messageBox);

        // Show Magic Link Form
        magicLinkButton.addEventListener("click", function (e) {
            e.preventDefault();
            magicLinkForm.classList.add("active");
            magicLinkBackground.classList.add("active");
            messageBox.textContent = ""; // Clear any previous message
        });

        // Close Magic Link Form
        closeButtons.forEach((btn) => {
            btn.addEventListener("click", function (e) {
                e.preventDefault();
                magicLinkForm.classList.remove("active");
                magicLinkBackground.classList.remove("active");
                messageBox.textContent = ""; // Clear any previous message
            });
        });

        // Handle AJAX for "Send Link" button
        sendLinkButton.addEventListener("click", function (e) {
            e.preventDefault();
            const emailInput = document.getElementById("lmn_email_address");
            const email = emailInput.value;

            if (!email) {
                messageBox.textContent = "<?php esc_attr_e( 'Please enter your email address.', 'login-me-now' ); ?>";
                messageBox.style.color = "red";
                return;
            }

            // Show loading message
            messageBox.textContent = "<?php esc_attr_e( 'Sending magic link...', 'login-me-now' ); ?>";
            messageBox.style.color = "#0073aa";

            // AJAX request
            fetch("<?php echo get_rest_url( null, 'login-me-now/send-magic-link' ); ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: new URLSearchParams({
                    email: email,
					wpnonce: "<?php echo wp_create_nonce( 'lmn-magic-link-nonce' ) ?>"
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    console.log('data : ', data );
                    if (data.success) {
                        messageBox.textContent = data.data.message;
                        messageBox.style.color = "green";
                        emailInput.value = ""; // Clear email input
                    } else {
                        messageBox.textContent = data.data.message || "<?php esc_attr_e( 'An error occurred. Please try again.1', 'login-me-now' ); ?>";
                        messageBox.style.color = "red";
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    messageBox.textContent = "<?php esc_attr_e( 'An error occurred. Please try again.2', 'login-me-now' ); ?>";
                    messageBox.style.color = "red";
                });
        });
    });
</script>
