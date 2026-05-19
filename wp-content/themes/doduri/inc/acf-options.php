<?php
/**
 * ACF (Advanced Custom Fields) 옵션 페이지 + 헬퍼.
 *
 * ACF 미설치 환경에서도 테마가 깨지지 않도록 fallback 헬퍼를 제공한다.
 * 기본값은 정적 프로토타입의 텍스트(병원 정보, 전화번호, 이메일 등)를 그대로 사용.
 *
 * @package Doduri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ACF Pro 가 설치되어 있을 때 옵션 페이지 등록.
 */
function doduri_register_acf_options() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page(
			array(
				'page_title' => __( '도두리 사이트 옵션', 'doduri' ),
				'menu_title' => __( '사이트 옵션', 'doduri' ),
				'menu_slug'  => 'doduri-site-options',
				'capability' => 'edit_theme_options',
				'redirect'   => false,
				'icon_url'   => 'dashicons-admin-customizer',
			)
		);
	}
}
add_action( 'acf/init', 'doduri_register_acf_options' );

/**
 * ACF 옵션 값을 안전하게 읽어오는 헬퍼.
 *
 * @param string $key     ACF 필드 키.
 * @param mixed  $default ACF 가 없거나 값이 비어있을 때의 기본값.
 * @return mixed
 */
function doduri_option( $key, $default = '' ) {
	if ( function_exists( 'get_field' ) ) {
		$val = get_field( $key, 'option' );
		if ( ! empty( $val ) ) {
			return $val;
		}
	}
	return $default;
}

/**
 * 사이트 공통 정보 — 정적 프로토타입의 데이터를 기본값으로.
 */
function doduri_site_info() {
	return array(
		'name_ko'    => doduri_option( 'site_name_ko', '도두리동물병원' ),
		'name_en'    => doduri_option( 'site_name_en', 'Doduri Animal Clinic' ),
		'ceo'        => doduri_option( 'site_ceo', '김한민' ),
		'phone'      => doduri_option( 'site_phone', '032-545-7582' ),
		'phone_link' => doduri_option( 'site_phone_link', 'tel:032-545-7582' ),
		'address'    => doduri_option( 'site_address', '인천 계양구 용종로 2 계산프라자, 도두리동물병원' ),
		'email'      => doduri_option( 'site_email', 'doduri_ah@naver.com' ),
		'blog'       => doduri_option( 'site_blog_url', 'https://blog.naver.com/doduri_ah' ),
		'kakao'      => doduri_option( 'site_kakao_url', 'http://pf.kakao.com/_lwlTX' ), // 클라이언트에서 수령 후 옵션 페이지에서 입력
		'biz_no'     => doduri_option( 'site_biz_no', '409-26-52253' ),
	);
}

/**
 * 진료비 안내 이미지 URL — Customizer(외모 > 사용자 정의하기)에서 관리.
 *
 * 반환값: 이미지 URL 문자열 또는 빈 문자열.
 */
function doduri_fee_guide_image_url() {
	$attachment_id = (int) get_theme_mod( 'fee_guide_image', 0 );
	if ( $attachment_id ) {
		$src = wp_get_attachment_image_url( $attachment_id, 'full' );
		if ( $src ) {
			return $src;
		}
	}
	return '';
}

/**
 * 메인 히어로 슬라이드 이미지 URL 배열 — fallback: 시설 사진 5장.
 *
 * @return array<string>
 */
function doduri_hero_slide_urls() {
	$slides = doduri_option( 'home_hero_slides', array() );

	$urls = array();
	if ( is_array( $slides ) && ! empty( $slides ) ) {
		foreach ( $slides as $item ) {
			if ( is_array( $item ) && ! empty( $item['url'] ) ) {
				$urls[] = $item['url'];
			} elseif ( is_string( $item ) && $item !== '' ) {
				$urls[] = $item;
			}
		}
	}

	if ( ! empty( $urls ) ) {
		return $urls;
	}

	// fallback: 시설 사진 5장
	$fallback = array();
	for ( $i = 1; $i <= 5; $i++ ) {
		$fallback[] = DODURI_THEME_URI . '/assets/images/facility/facility' . $i . '.png';
	}
	return $fallback;
}
