<?php
/**
 * Doduri Animal Clinic — functions.php
 *
 * 테마 부트스트랩 진입점. 실제 로직은 inc/ 하위로 분리.
 *
 * @package Doduri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // 직접 접근 차단
}

// 테마 디렉토리 상수
if ( ! defined( 'DODURI_THEME_DIR' ) ) {
	define( 'DODURI_THEME_DIR', get_template_directory() );
}
if ( ! defined( 'DODURI_THEME_URI' ) ) {
	define( 'DODURI_THEME_URI', get_template_directory_uri() );
}
if ( ! defined( 'DODURI_VERSION' ) ) {
	define( 'DODURI_VERSION', '0.1.1' );
}

// 모듈 로드
require_once DODURI_THEME_DIR . '/inc/theme-setup.php';
require_once DODURI_THEME_DIR . '/inc/enqueue.php';
require_once DODURI_THEME_DIR . '/inc/menus.php';
require_once DODURI_THEME_DIR . '/inc/acf-options.php';
require_once DODURI_THEME_DIR . '/inc/customizer.php';
