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
				<form action="<?php echo esc_url( Kmetric_Admin::get_page_url() ); ?>" method="post">
					<?php wp_nonce_field( Kmetric_Admin::NONCE ) ?>
					<input type="hidden" name="action" value="enter-product-id">
					<p style="width: 100%; display: flex; flex-wrap: nowrap; box-sizing: border-box;">
						<input id="product-id" name="product-id" type="text" size="15" value="" class="regular-text code" style="flex-grow: 1; margin-right: 1rem;">
						<input type="submit" name="submit" id="submit" class="kmetric-button" value="<?php esc_attr_e( 'Activate', 'kmetric' );?>">
					</p>
				</form>
			</div>
		</div>
	</div>
</div>