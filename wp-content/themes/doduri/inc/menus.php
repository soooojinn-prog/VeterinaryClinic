<?php
/**
 * 메뉴 출력 헬퍼 — 워크 메뉴가 등록되지 않았을 때의 폴백 메뉴 포함.
 *
 * @package Doduri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PC 데스크톱 주메뉴 출력.
 * 워드프레스 메뉴가 'primary' 위치에 할당되지 않았으면 정적 폴백 메뉴 출력.
 */
function doduri_render_primary_menu() {
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'nav-list',
				'fallback_cb'    => false,
				'depth'          => 2,
			)
		);
		return;
	}

	// 폴백: 기존 정적 사이트 구조와 동일
	?>
	<ul class="nav-list">
		<li class="nav-item has-dropdown" data-page="about">
			<a href="<?php echo esc_url( home_url( '/greeting/' ) ); ?>"><?php esc_html_e( '병원소개', 'doduri' ); ?></a>
			<ul class="dropdown">
				<li><a href="<?php echo esc_url( home_url( '/greeting/' ) ); ?>"><?php esc_html_e( '인사말', 'doduri' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/doctor/' ) ); ?>"><?php esc_html_e( '의료진소개', 'doduri' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/facility/' ) ); ?>"><?php esc_html_e( '시설소개', 'doduri' ); ?></a></li>
			</ul>
		</li>
		<li class="nav-item has-dropdown" data-page="service">
			<a href="<?php echo esc_url( home_url( '/service-subject/' ) ); ?>"><?php esc_html_e( '진료안내', 'doduri' ); ?></a>
			<ul class="dropdown">
				<li><a href="<?php echo esc_url( home_url( '/service-subject/' ) ); ?>"><?php esc_html_e( '진료과목', 'doduri' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/location/#hours' ) ); ?>"><?php esc_html_e( '진료시간', 'doduri' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/location/' ) ); ?>"><?php esc_html_e( '오시는 길', 'doduri' ); ?></a></li>
			</ul>
		</li>
		<li class="nav-item has-dropdown" data-page="community">
			<a href="<?php echo esc_url( home_url( '/story/' ) ); ?>"><?php esc_html_e( '커뮤니티', 'doduri' ); ?></a>
			<ul class="dropdown">
				<li><a href="<?php echo esc_url( home_url( '/story/' ) ); ?>"><?php esc_html_e( '병원이야기', 'doduri' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/qna/' ) ); ?>"><?php esc_html_e( 'Q&A', 'doduri' ); ?></a></li>
			</ul>
		</li>
	</ul>
	<?php
}

/**
 * 모바일 메뉴 출력.
 */
function doduri_render_mobile_menu() {
	if ( has_nav_menu( 'mobile' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'mobile',
				'container'      => false,
				'menu_class'     => '',
				'items_wrap'     => '<ul>%3$s</ul>',
				'fallback_cb'    => false,
				'depth'          => 2,
			)
		);
		return;
	}

	// 폴백: 정적 사이트와 동일 구조
	?>
	<ul>
		<li>
			<a href="<?php echo esc_url( home_url( '/greeting/' ) ); ?>" class="mobile-link mobile-parent"><?php esc_html_e( '병원소개', 'doduri' ); ?></a>
			<ul class="mobile-sub">
				<li><a href="<?php echo esc_url( home_url( '/greeting/' ) ); ?>" class="mobile-link"><?php esc_html_e( '인사말', 'doduri' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/doctor/' ) ); ?>" class="mobile-link"><?php esc_html_e( '의료진소개', 'doduri' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/facility/' ) ); ?>" class="mobile-link"><?php esc_html_e( '시설소개', 'doduri' ); ?></a></li>
			</ul>
		</li>
		<li>
			<a href="<?php echo esc_url( home_url( '/service-subject/' ) ); ?>" class="mobile-link mobile-parent"><?php esc_html_e( '진료안내', 'doduri' ); ?></a>
			<ul class="mobile-sub">
				<li><a href="<?php echo esc_url( home_url( '/service-subject/' ) ); ?>" class="mobile-link"><?php esc_html_e( '진료과목', 'doduri' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/location/' ) ); ?>" class="mobile-link"><?php esc_html_e( '진료시간/오시는 길', 'doduri' ); ?></a></li>
			</ul>
		</li>
		<li>
			<a href="<?php echo esc_url( home_url( '/story/' ) ); ?>" class="mobile-link mobile-parent"><?php esc_html_e( '커뮤니티', 'doduri' ); ?></a>
			<ul class="mobile-sub">
				<li><a href="<?php echo esc_url( home_url( '/story/' ) ); ?>" class="mobile-link"><?php esc_html_e( '병원이야기', 'doduri' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/qna/' ) ); ?>" class="mobile-link"><?php esc_html_e( 'Q&A', 'doduri' ); ?></a></li>
			</ul>
		</li>
	</ul>
	<?php
}
