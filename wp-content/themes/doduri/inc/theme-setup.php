<?php
/**
 * 테마 기본 셋업 — add_theme_support, register_nav_menus, 이미지 사이즈 등.
 *
 * @package Doduri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'doduri_theme_setup' ) ) {
	function doduri_theme_setup() {
		// <title> 자동 출력
		add_theme_support( 'title-tag' );

		// 대표 이미지(썸네일) 지원
		add_theme_support( 'post-thumbnails' );

		// HTML5 마크업
		add_theme_support(
			'html5',
			array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
		);

		// 사용자 지정 로고
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 80,
				'width'       => 80,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

		// 자동 RSS 링크
		add_theme_support( 'automatic-feed-links' );

		// 구텐베르크 너비 정렬
		add_theme_support( 'align-wide' );

		// 한국어 텍스트 도메인
		load_theme_textdomain( 'doduri', DODURI_THEME_DIR . '/languages' );

		// 메뉴 위치 등록
		register_nav_menus(
			array(
				'primary' => __( '주 메뉴 (PC 헤더)', 'doduri' ),
				'mobile'  => __( '모바일 메뉴', 'doduri' ),
			)
		);
	}
}
add_action( 'after_setup_theme', 'doduri_theme_setup' );

/**
 * body_class 에 페이지 그룹 클래스 추가 (page-about / page-service / page-community / page-home).
 * 활성 메뉴 표시는 bootstrap.js 가 이 body 클래스를 보고 처리한다.
 */
function doduri_body_classes( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'page-home';
		return $classes;
	}

	if ( is_page() ) {
		$slug = get_post_field( 'post_name', get_queried_object_id() );

		if ( in_array( $slug, array( 'greeting', 'doctor', 'facility' ), true ) ) {
			$classes[] = 'page-about';
		} elseif ( in_array( $slug, array( 'service-subject', 'location' ), true ) ) {
			$classes[] = 'page-service';
		} elseif ( in_array( $slug, array( 'story', 'notice', 'qna' ), true ) ) {
			$classes[] = 'page-community';
		}
	}

	return $classes;
}
add_filter( 'body_class', 'doduri_body_classes' );
