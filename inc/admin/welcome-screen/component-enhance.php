<?php
/**
 * Welcome screen enhance template
 *
 * @package localshop
 */

?>
<?php

/**
 * Get the extension data
 */
$localshop_admin 	= new Localshop_Admin();
$extensions 		= $localshop_admin::get_localshop_product_data( 'http://d3t0oesq8995hv.cloudfront.net/localshop-extensions.json', 'localshop_extensions' );
$child_themes 		= $localshop_admin::get_localshop_product_data( 'http://d3t0oesq8995hv.cloudfront.net/localshop-child-themes.json', 'localshop_child_themes' );
?>

	<div class="boxed enhance">
		<h2><?php printf( esc_html__( 'Enhance %s', 'localshop' ), 'Localshop' ); ?></h2>
		<p>
			<?php printf( esc_html__( 'Take a look at our range of extensions that extend and enhance %s functionality.', 'localshop' ), 'Localshop\'s' ); ?>
		</p>

		<ul class="extensions">
			<?php
			if ( $extensions ) {
				foreach ( $extensions as $extension ) {
					foreach ( $extension as $product ) {
						$price 	= $product->price;
						$title 	= str_replace( 'Localshop', '', $product->title );

						if ( '&#36;0.00' != $price ) {
							echo '<li><a href="' . esc_url( $product->link ) . '">' . esc_attr( $title ) . ' - <span class="price">' . esc_attr( $product->price ) . '</span></a><p>' . wp_kses_post( $product->excerpt ) . '</p></li>';
						}
					}
				}
			} else {
				echo '<div class="localshop-notice">' . esc_attr__( 'We\'re currently unable to retrieve these products. Please double check your internet connection or try back later.', 'localshop' ) . '</div>';
			}
			?>
		</ul>

		<div class="more-button">
			<a href="http://www.woothemes.com/product-category/localshop-extensions?utm_source=product&utm_medium=upsell&utm_campaign=localshopaddons" class="button button-primary">
				<?php printf( esc_html__( 'View all %s extensions &rarr;', 'localshop' ), 'Localshop' ); ?>
			</a>
		</div>
	</div>

	<div class="boxed child-themes">
		<h2><?php esc_html_e( 'Child themes', 'localshop' ); ?></h2>
		<p><?php printf( esc_html__( 'Take a look at our range of child themes for %s that allow you to easily change the look of your store to suit a specific industry.', 'localshop' ), 'Localshop' ); ?></p>

		<?php
		if ( $child_themes ) {
			foreach ( $child_themes as $child_theme ) {
				foreach ( $child_theme as $product ) {
					$price 				= $product->price;

					if ( '&#36;0.00' == $price ) {
						$price = __( 'Free!', 'localshop' );
					}

					$link 				= $product->link;
					$image 				= $product->image;
					$excerpt			= $product->excerpt;
					$title 				= $product->title; ?>

					<div class="child-theme">
						<a href="<?php echo esc_url( $link ); ?>">
							<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>" />
							<span class="child-theme-title"><?php echo esc_attr( $title ); ?></span>
							<span class="price"><?php echo esc_attr( $price ); ?></span>
						</a>
					</div>
				<?php
				}
			}
		} else {
			echo '<div class="localshop-notice">' . esc_attr__( 'We\'re currently unable to retrieve these products. Please double check your internet connection or try back later.', 'localshop' ) . '</div>';
		}
		?>
		<div class="more-button">
			<a href="http://www.woothemes.com/product-category/themes/localshop-child-theme-themes?utm_source=product&utm_medium=upsell&utm_campaign=localshopaddons" class="button button-primary">
				<?php printf( esc_html__( 'View all %s child themes &rarr;', 'localshop' ), 'Localshop' ); ?>
			</a>
		</div>
	</div>

	<div class="boxed free-plugins">
		<h2><?php esc_html_e( 'Install free plugins', 'localshop' ); ?></h2>
		<p>
			<?php echo sprintf( esc_html__( 'There are a number of free plugins available for %s on the WordPress.org %splugin repository%s. Here\'s a few we made earlier:', 'localshop' ), 'Localshop', '<a href="https://wordpress.org/plugins/search.php?q=localshop">', '</a>' ); ?>
		</p>
		<ul class="extensions">
			<?php
			if ( $extensions ) {
				foreach ( $extensions as $extension ) {
					foreach ( $extension as $product ) {
						$price            = $product->price;
						$lower_case_title = strtolower( str_replace( ' ', '-', $product->title ) );
						$title            = str_replace( 'Localshop', '', $product->title );

						if ( '&#36;0.00' == $price ) {
							echo '<li><a class="thickbox" href="' . esc_url( wp_nonce_url( self_admin_url( 'plugin-install.php?tab=plugin-information&plugin=' . esc_attr( $lower_case_title ) . '&TB_iframe=true&width=744&height=800' ), 'install-plugin_' . esc_attr( $lower_case_title ) ) ) . '">' . esc_attr( $title ) . ' - <span class="price">' . esc_attr__( 'Free!', 'localshop' ) . '</span></a><p>' . wp_kses_post( $product->excerpt ) . '</p></li>';
						}
					}
				}
			} else {
				echo '<div class="localshop-notice">' . esc_attr__( 'We\'re currently unable to retrieve these products. Please double check your internet connection or try back later.', 'localshop' ) . '</div>';
			}
			?>
		</ul>
	</div>
