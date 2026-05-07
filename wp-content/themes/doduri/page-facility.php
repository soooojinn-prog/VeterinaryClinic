<?php
/**
 * Template Name: 시설소개 (about-facility)
 * 슬러그 'facility' 페이지에 적용.
 *
 * @package Doduri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

set_query_var(
	'doduri_sub_args',
	array(
		'bg'         => doduri_option( 'sub_about_bg', 'https://images.unsplash.com/photo-1576201836106-db1758fd1c97?w=1600&q=80' ),
		'title'      => __( '병원소개', 'doduri' ),
		'subtitle'   => __( '도두리동물병원을 소개합니다', 'doduri' ),
		'crumbs'     => array(
			array(
				'label' => __( '병원소개', 'doduri' ),
				'url'   => home_url( '/greeting/' ),
			),
			array( 'label' => __( '시설소개', 'doduri' ) ),
		),
		'tabs'       => array(
			array( 'key' => 'greeting', 'label' => __( '인사말', 'doduri' ), 'url' => home_url( '/greeting/' ) ),
			array( 'key' => 'doctor',   'label' => __( '의료진소개', 'doduri' ), 'url' => home_url( '/doctor/' ) ),
			array( 'key' => 'facility', 'label' => __( '시설소개', 'doduri' ), 'url' => home_url( '/facility/' ) ),
		),
		'active_tab' => 'facility',
	)
);
get_template_part( 'template-parts/sub-page-header' );

// 시설 이미지 — ACF 갤러리(image array) 또는 fallback 으로 테마의 facility1~4.jpg
$gallery = doduri_option( 'facility_gallery', null );
$images  = array();

if ( is_array( $gallery ) && ! empty( $gallery ) ) {
	foreach ( $gallery as $g ) {
		if ( is_array( $g ) && ! empty( $g['url'] ) ) {
			$images[] = array(
				'url' => $g['url'],
				'alt' => isset( $g['alt'] ) ? $g['alt'] : '',
			);
		} elseif ( is_string( $g ) ) {
			$images[] = array( 'url' => $g, 'alt' => '' );
		}
	}
}

if ( empty( $images ) ) {
	for ( $i = 1; $i <= 5; $i++ ) {
		$images[] = array(
			'url' => DODURI_THEME_URI . '/assets/images/facility/facility' . $i . '.png',
			'alt' => sprintf( __( '병원 시설 %d', 'doduri' ), $i ),
		);
	}
}

$total = count( $images );
?>

<main>
	<section class="section facility-section">
		<div class="container">
			<div class="page-section-heading">
				<h2><?php esc_html_e( '시설소개', 'doduri' ); ?></h2>
			</div>

			<div class="facility-slider">

				<div class="facility-slider-main">
					<button class="slider-btn slider-prev" aria-label="<?php esc_attr_e( '이전 이미지', 'doduri' ); ?>">
						<i class="fas fa-chevron-left"></i>
					</button>

					<div class="slider-track">
						<?php foreach ( $images as $idx => $img ) :
							$active = ( $idx === 0 ) ? ' active' : ''; ?>
							<div class="slider-slide<?php echo esc_attr( $active ); ?>">
								<img src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php echo esc_attr( $img['alt'] ); ?>" />
							</div>
						<?php endforeach; ?>
					</div>

					<button class="slider-btn slider-next" aria-label="<?php esc_attr_e( '다음 이미지', 'doduri' ); ?>">
						<i class="fas fa-chevron-right"></i>
					</button>

					<div class="slider-counter">
						<span class="slider-current">1</span> / <span class="slider-total"><?php echo esc_html( $total ); ?></span>
					</div>
				</div>

				<div class="facility-thumbs">
					<?php foreach ( $images as $idx => $img ) :
						$active = ( $idx === 0 ) ? ' active' : ''; ?>
						<div class="facility-thumb<?php echo esc_attr( $active ); ?>" data-index="<?php echo esc_attr( $idx ); ?>">
							<img src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php printf( esc_attr__( '시설 썸네일 %d', 'doduri' ), $idx + 1 ); ?>" />
						</div>
					<?php endforeach; ?>
				</div>

			</div>
		</div>
	</section>
</main>

<!-- 라이트박스 -->
<div id="facilityLightbox" class="facility-lightbox" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( '시설 이미지 확대', 'doduri' ); ?>">
	<button class="lightbox-close" aria-label="<?php esc_attr_e( '닫기', 'doduri' ); ?>"><i class="fas fa-times"></i></button>
	<button class="lightbox-prev"  aria-label="<?php esc_attr_e( '이전', 'doduri' ); ?>"><i class="fas fa-chevron-left"></i></button>
	<div class="lightbox-img-wrap">
		<img id="lightboxImg" src="" alt="" />
	</div>
	<button class="lightbox-next"  aria-label="<?php esc_attr_e( '다음', 'doduri' ); ?>"><i class="fas fa-chevron-right"></i></button>
	<div class="lightbox-counter">
		<span id="lightboxCurrent">1</span> / <span id="lightboxTotal"><?php echo esc_html( $total ); ?></span>
	</div>
</div>

<?php get_footer(); ?>
