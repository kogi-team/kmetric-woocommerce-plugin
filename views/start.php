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
			<h2><?php esc_html_e( 'How to get Kmetric', 'kmetric' ); ?></h2>
			<p><?php esc_html_e( 'Create your product here.', 'kmetric' ); ?> <a href="https://www.kmetric.io/register" target="_blank"><?php esc_html_e( 'Sign Up', 'kmetric' ); ?></a></p>
			<p><?php esc_html_e( 'After creating the product, we will give you [PRODUCT-ID], then you can copy and place into here to start tracking.', 'kmetric' ); ?></p>
			<p><img src="<?php echo esc_url( plugins_url( '../assets/images/getting-started.jpg', __FILE__ ) ); ?>"?></p>
		</div>
		<div class="kmetric-boxes">
			<div class="kmetric-box">
				<h3><?php esc_html_e( 'Product ID', 'kmetric' ); ?></h3>
				<form method="post" action="options.php" class="kmetric-form">
					<?php settings_fields( 'kmetric-set-product-id' ); ?>
					<?php do_settings_sections( 'kmetric-set-product-id' ); ?>
					<p>
						<input id="wordpress_kmetric_product_id" name="wordpress_kmetric_product_id" type="text" size="15" value="" class="regular-text code" style="display: block;width: 100%;padding: 0.5em;">
					</p>
					<?php submit_button( null, 'kmetric-button' ); ?>
				</form>
			</div>
		</div>
	</div>
</div>