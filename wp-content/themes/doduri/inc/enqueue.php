<?php
/**
 * 스타일/스크립트 등록.
 *
 * - Google Fonts (Noto Sans KR)
 * - Font Awesome 6.5.0 (CDN)
 * - 테마 메인 CSS (assets/css/style.css)
 * - 서브 페이지 CSS (assets/css/sub.css) — 서브 페이지에서만 enqueue
 * - bootstrap.js (app-ready 이벤트 발화)
 * - script.js (공통)
 * - sub.js (서브 페이지에서만)
 * - 네이버 지도 SDK (오시는 길 페이지에서만)
 *
 * @package Doduri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function doduri_enqueue_assets() {
	$ver = DODURI_VERSION;

	// ===== 외부 폰트/아이콘 =====
	wp_enqueue_style(
		'doduri-google-fonts',
		'https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;500;600;700;800&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'doduri-font-awesome',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css',
		array(),
		'6.5.0'
	);

	// ===== 테마 메인 스타일 =====
	wp_enqueue_style(
		'doduri-style-main',
		DODURI_THEME_URI . '/assets/css/style.css',
		array( 'doduri-google-fonts', 'doduri-font-awesome' ),
		$ver
	);

	// 테마 헤더(style.css 메타) — WP 호환을 위해 등록은 해두되 실제 디자인 영향 없음
	wp_enqueue_style(
		'doduri-theme-header',
		get_stylesheet_uri(),
		array( 'doduri-style-main' ),
		$ver
	);

	// 서브 페이지 CSS (프론트 페이지가 아닐 때만)
	if ( ! is_front_page() ) {
		wp_enqueue_style(
			'doduri-style-sub',
			DODURI_THEME_URI . '/assets/css/sub.css',
			array( 'doduri-style-main' ),
			$ver
		);
	}

	// ===== 스크립트 =====
	// bootstrap.js: app-ready 이벤트 발화 + active 메뉴 처리. script.js 보다 먼저 로드되어야 함.
	wp_enqueue_script(
		'doduri-bootstrap',
		DODURI_THEME_URI . '/assets/js/bootstrap.js',
		array(),
		$ver,
		true
	);

	wp_enqueue_script(
		'doduri-script',
		DODURI_THEME_URI . '/assets/js/script.js',
		array( 'doduri-bootstrap' ),
		$ver,
		true
	);

	// 서브 페이지 전용 JS
	if ( ! is_front_page() ) {
		wp_enqueue_script(
			'doduri-sub',
			DODURI_THEME_URI . '/assets/js/sub.js',
			array( 'doduri-script' ),
			$ver,
			true
		);
	}

	// 네이버 지도 SDK — 오시는 길 페이지에서만
	if ( is_page( 'location' ) ) {
		wp_enqueue_script(
			'doduri-naver-maps',
			'https://oapi.map.naver.com/openapi/v3/maps.js?ncpKeyId=goa90mkxm6&submodules=geocoder',
			array(),
			null,
			false // head 에 로드 (서브모듈 의존성 때문)
		);
	}

	// KBoard 오버라이드 CSS — 커뮤니티(병원이야기/치료 케이스) 페이지에서만
	if ( is_page( array( 'story', 'cases' ) ) ) {
		wp_enqueue_style(
			'doduri-kboard-override',
			DODURI_THEME_URI . '/assets/css/kboard-override.css',
			array( 'doduri-style-main' ),
			$ver
		);
	}
}
add_action( 'wp_enqueue_scripts', 'doduri_enqueue_assets' );

/**
 * preconnect 힌트(Google Fonts) — 약간의 폰트 로딩 단축.
 */
function doduri_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'doduri_resource_hints', 10, 2 );
