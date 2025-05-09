<?php
if ( ! $providers || 0 === count( $providers ) ) {
	return;
}

wp_enqueue_style( 'login-me-now-main' );
wp_enqueue_script( 'login-me-now-main' );
?>

<div id="wp-login-login-me-now-buttons">

	<?php if ( 'before' === $display_position ): ?>
		<div style="text-align: center; margin: 10px 0;">
			<?php esc_html_e( 'Or', 'login-me-now' ); ?>
		</div>
	<?php endif; ?>

	<?php foreach ( $providers as $provider ): ?>
		<?php echo $provider::get_button(); ?>
	<?php endforeach; ?>

	<?php if ( 'after' === $display_position ): ?>
		<div style="text-align: center; margin: 10px 0;">
			<?php esc_html_e( 'Or', 'login-me-now' ); ?>
		</div>
	<?php endif; ?>

</div>