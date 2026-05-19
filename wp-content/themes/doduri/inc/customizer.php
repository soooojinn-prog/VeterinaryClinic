<?php
/**
 * 워드프레스 Customizer 설정 — 플러그인 없이 테마 옵션 관리.
 *
 * 관리자 > 외모 > 사용자 정의하기 에서 이미지를 업로드/교체할 수 있다.
 *
 * @package Doduri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function doduri_customizer_register( $wp_customize ) {

	/* ── 섹션 ── */
	$wp_customize->add_section(
		'doduri_clinic',
		array(
			'title'    => '진료비 안내 이미지 설정',
			'priority' => 30,
		)
	);

	/* ── 진료비 안내 이미지 PC ── */
	$wp_customize->add_setting(
		'fee_guide_image_pc',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'fee_guide_image_pc',
			array(
				'label'       => '진료비 안내 이미지 (PC)',
				'description' => 'PC에서 표시되는 이미지 (1장)',
				'section'     => 'doduri_clinic',
				'mime_type'   => 'image',
			)
		)
	);

	/* ── 진료비 안내 이미지 1 ── */
	$wp_customize->add_setting(
		'fee_guide_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'fee_guide_image',
			array(
				'label'       => '진료비 안내 이미지 ①',
				'description' => '모바일에서 첫 번째로 표시되는 이미지',
				'section'     => 'doduri_clinic',
				'mime_type'   => 'image',
			)
		)
	);

	/* ── 진료비 안내 이미지 2 ── */
	$wp_customize->add_setting(
		'fee_guide_image_2',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'fee_guide_image_2',
			array(
				'label'       => '진료비 안내 이미지 ②',
				'description' => '모바일에서 두 번째로 표시되는 이미지 (선택)',
				'section'     => 'doduri_clinic',
				'mime_type'   => 'image',
			)
		)
	);
}
add_action( 'customize_register', 'doduri_customizer_register' );
