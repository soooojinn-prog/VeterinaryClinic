<?php
/**
 * 공통 헤더.
 *
 * @package Doduri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="scrollProgress" class="scroll-progress"></div>

<!-- ===== HEADER ===== -->
<header id="header">
	<div class="header-inner">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">
			<div class="logo-icon">
				<?php
				if ( has_custom_logo() ) {
					$logo_id  = get_theme_mod( 'custom_logo' );
					$logo_src = wp_get_attachment_image_src( $logo_id, 'full' );
					if ( $logo_src ) {
						printf(
							'<img src="%1$s" alt="%2$s" />',
							esc_url( $logo_src[0] ),
							esc_attr( get_bloginfo( 'name' ) )
						);
					}
				} else {
					printf(
						'<img src="%1$s" alt="%2$s" />',
						esc_url( DODURI_THEME_URI . '/assets/images/logo/doduri-symbol.png' ),
						esc_attr__( '도두리동물병원 로고', 'doduri' )
					);
				}
				?>
			</div>
			<div class="logo-text">
				<?php $info = doduri_site_info(); ?>
				<span class="logo-main"><?php echo esc_html( $info['name_ko'] ); ?></span>
				<span class="logo-sub"><?php echo esc_html( $info['name_en'] ); ?></span>
			</div>
		</a>

		<nav class="nav-desktop">
			<?php doduri_render_primary_menu(); ?>
		</nav>

		<div class="header-actions">
			<button class="hamburger" id="hamburger" aria-label="<?php esc_attr_e( '메뉴', 'doduri' ); ?>">
				<span></span><span></span><span></span>
			</button>
		</div>
	</div>
</header>

<!-- ===== MOBILE NAV ===== -->
<div class="mobile-nav" id="mobileNav">
	<button class="mobile-nav-close" id="mobileClose"><i class="fas fa-times"></i></button>
	<?php doduri_render_mobile_menu(); ?>
</div>
<div class="mobile-overlay" id="mobileOverlay"></div>
