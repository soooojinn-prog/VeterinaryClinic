<?php
/**
 * 프론트 페이지 (홈) — 정적 index.html 의 hero 영역을 PHP 로 이식.
 *
 * @package Doduri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// 히어로 영역 — ACF 옵션이 있으면 그것을, 없으면 정적 프로토타입의 기본값 사용
$hero_bg    = doduri_option( 'home_hero_bg', 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=1600&q=80' );
$hero_tag   = doduri_option( 'home_hero_tag', '도두리동물병원' );
$hero_title_line1 = doduri_option( 'home_hero_title_line1', '우리 동네 평생 건강 지킴이,' );
$hero_title_em    = doduri_option( 'home_hero_title_em', '도두리동물병원' );
$hero_desc  = doduri_option( 'home_hero_desc', "임상 경험을 바탕으로 지역에서도 믿고 맡길 수 있는\n주치의 같은 병원을 지향합니다." );
$hero_btn1_label = doduri_option( 'home_hero_btn1_label', '진료안내 보기' );
$hero_btn1_url   = doduri_option( 'home_hero_btn1_url', home_url( '/service-subject/' ) );
$hero_btn2_label = doduri_option( 'home_hero_btn2_label', '상담/예약' );
$hero_btn2_url   = doduri_option( 'home_hero_btn2_url', home_url( '/contact/' ) );
?>

<!-- ===== HERO ===== -->
<section id="hero">
	<div class="hero-slides">
		<div class="hero-slide active" data-index="0">
			<div class="hero-bg" style="background-image: url('<?php echo esc_url( $hero_bg ); ?>')"></div>
			<div class="hero-overlay"></div>
			<div class="hero-content">
				<p class="hero-tag"><?php echo esc_html( $hero_tag ); ?></p>
				<h1>
					<?php echo esc_html( $hero_title_line1 ); ?><br>
					<em><?php echo esc_html( $hero_title_em ); ?></em>
				</h1>
				<p class="hero-desc"><?php echo nl2br( esc_html( $hero_desc ) ); ?></p>
				<div class="hero-btns">
					<a href="<?php echo esc_url( $hero_btn1_url ); ?>" class="btn btn-primary"><?php echo esc_html( $hero_btn1_label ); ?></a>
					<a href="<?php echo esc_url( $hero_btn2_url ); ?>" class="btn btn-outline"><?php echo esc_html( $hero_btn2_label ); ?></a>
				</div>
			</div>
		</div>
	</div>
	<div class="hero-scroll-hint">
		<span><?php esc_html_e( '스크롤', 'doduri' ); ?></span>
		<i class="fas fa-chevron-down"></i>
	</div>
</section>

<!-- ===== MAIN CONTENT (추후 섹션 추가) ===== -->
<main>
	<?php
	// 관리자가 페이지 본문에 추가 콘텐츠를 작성한 경우(고정 페이지 ID 가 front 인 경우) 출력
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			$content = get_the_content();
			if ( ! empty( trim( $content ) ) ) {
				echo '<section class="section"><div class="container">';
				the_content();
				echo '</div></section>';
			}
		endwhile;
	endif;
	?>
</main>

<?php get_footer(); ?>
