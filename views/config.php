<div id="kmetric-plugin-container">
	<div class="kmetric-masthead">
		<div class="kmetric-masthead__inside-container">
			<div class="kmetric-masthead__logo-container">
				<img class="kmetric-masthead__logo" src="<?php echo esc_url( plugins_url( '../assets/images/logo.png', __FILE__ ) ); ?>" alt="Kmetric" />
			</div>
		</div>
	</div>
	<div class="kmetric-lower">
		<div class="kmetric-box">
			<h3><?php esc_html_e( 'Product ID', 'kmetric' ); ?></h3>
			<form method="post" action="options.php" class="kmetric-form">
				<?php settings_fields( 'kmetric-set-product-id' ); ?>
				<?php do_settings_sections( 'kmetric-set-product-id' ); ?>
				<p>
					<input id="wordpress_kmetric_product_id" name="wordpress_kmetric_product_id" type="text" size="15" value="<?php echo Kmetric::get_product_id(); ?>" class="regular-text code" style="display: block;width: 100%;padding: 0.5em;">
				</p>
				<?php submit_button( null, 'kmetric-button' ); ?>
			</form>
		</div>
	</div>
</div>