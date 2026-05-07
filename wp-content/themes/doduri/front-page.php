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
$hero_slides      = doduri_hero_slide_urls(); // 항상 1개 이상 반환 (fallback: facility1~5.png)
$hero_tag         = doduri_option( 'home_hero_tag', '도두리동물병원' );
$hero_title_line1 = doduri_option( 'home_hero_title_line1', '우리 동네 평생 건강 지킴이,' );
$hero_title_em    = doduri_option( 'home_hero_title_em', '도두리동물병원' );
$hero_desc        = doduri_option( 'home_hero_desc', "임상 경험을 바탕으로 지역에서도 믿고 맡길 수 있는\n주치의 같은 병원을 지향합니다." );
?>

<!-- ===== HERO ===== -->
<section id="hero">
	<div class="hero-slides">
		<?php foreach ( $hero_slides as $idx => $img_url ) : ?>
			<div class="hero-slide<?php echo 0 === $idx ? ' active' : ''; ?>" data-index="<?php echo (int) $idx; ?>">
				<div class="hero-bg" style="background-image: url('<?php echo esc_url( $img_url ); ?>')"></div>
				<div class="hero-overlay"></div>
				<?php if ( 0 === $idx ) : ?>
					<div class="hero-content">
						<p class="hero-tag"><?php echo esc_html( $hero_tag ); ?></p>
						<h1>
							<?php echo esc_html( $hero_title_line1 ); ?><br>
							<em><?php echo esc_html( $hero_title_em ); ?></em>
						</h1>
						<p class="hero-desc"><?php echo nl2br( esc_html( $hero_desc ) ); ?></p>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>

	<?php if ( count( $hero_slides ) > 1 ) : ?>
		<div class="hero-dots">
			<?php foreach ( $hero_slides as $idx => $img_url ) : ?>
				<button type="button" class="dot<?php echo 0 === $idx ? ' active' : ''; ?>" data-index="<?php echo (int) $idx; ?>" aria-label="<?php /* translators: %d: 슬라이드 번호 */ printf( esc_attr__( '슬라이드 %d', 'doduri' ), (int) ( $idx + 1 ) ); ?>"></button>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<div class="hero-scroll-hint">
		<span><?php esc_html_e( '스크롤', 'doduri' ); ?></span>
		<i class="fas fa-chevron-down"></i>
	</div>
</section>

<!-- ===== MAIN CONTENT ===== -->
<main>

	<!-- 진료과목 3개 클리닉 미니카드 -->
	<section class="home-clinics section">
		<div class="container">
			<div class="section-header">
				<p class="section-tag"><?php esc_html_e( '진료안내', 'doduri' ); ?></p>
				<h2 class="section-title"><?php esc_html_e( '도두리의 3개 클리닉', 'doduri' ); ?></h2>
			</div>
			<ul class="home-clinic-grid">
				<li>
					<i class="fas fa-stethoscope"></i>
					<h3><?php esc_html_e( '건강검진 클리닉', 'doduri' ); ?></h3>
					<p><?php esc_html_e( '체계적인 건강검진으로 질병을 조기에 발견합니다.', 'doduri' ); ?></p>
				</li>
				<li>
					<i class="fas fa-heartbeat"></i>
					<h3><?php esc_html_e( '내과 클리닉', 'doduri' ); ?></h3>
					<p><?php esc_html_e( '내과 질환의 정확한 진단과 맞춤 치료.', 'doduri' ); ?></p>
				</li>
				<li>
					<i class="fas fa-user-md"></i>
					<h3><?php esc_html_e( '순환기·시니어 클리닉', 'doduri' ); ?></h3>
					<p><?php esc_html_e( '심장 질환과 고령 동물 전문 진료.', 'doduri' ); ?></p>
				</li>
			</ul>
			<div class="home-clinic-more">
				<a href="<?php echo esc_url( home_url( '/service-subject/' ) ); ?>" class="btn btn-outline btn-sm"><?php esc_html_e( '진료과목 자세히 보기', 'doduri' ); ?></a>
			</div>
		</div>
	</section>

	<!-- 공지사항 미리보기 -->
	<?php get_template_part( 'template-parts/home-notices' ); ?>

	<!-- 진료시간 / 오시는길 요약 -->
	<?php
	$info_for_home  = doduri_site_info();
	$hours_open_h   = doduri_option( 'hours_open', '오전 10시 ~ 오후 7시' );
	$hours_break_h  = doduri_option( 'hours_break', '오후 1시 ~ 오후 2시' );
	$hours_closed_h = doduri_option( 'hours_closed_notice', '※ 목요일, 일요일은 휴진입니다.' );
	$kakao_url_h    = ! empty( $info_for_home['kakao'] ) ? $info_for_home['kakao'] : 'http://pf.kakao.com/_lwlTX';
	$naver_url_h    = doduri_option( 'naver_map_url', 'https://naver.me/5nhPYsQU' );
	?>
	<section class="home-summary section">
		<div class="container">
			<div class="home-summary-grid">

				<div class="home-summary-card">
					<h3><i class="far fa-clock"></i> <?php esc_html_e( '진료시간', 'doduri' ); ?></h3>
					<dl>
						<dt><?php esc_html_e( '진료', 'doduri' ); ?></dt><dd><?php echo esc_html( $hours_open_h ); ?></dd>
						<dt><?php esc_html_e( '휴게', 'doduri' ); ?></dt><dd><?php echo esc_html( $hours_break_h ); ?></dd>
					</dl>
					<p class="home-summary-notice"><?php echo esc_html( $hours_closed_h ); ?></p>
				</div>

				<div class="home-summary-card">
					<h3><i class="fas fa-map-marker-alt"></i> <?php esc_html_e( '오시는 길', 'doduri' ); ?></h3>
					<p class="home-summary-addr"><?php echo esc_html( $info_for_home['address'] ); ?></p>
					<p class="home-summary-tel"><a href="<?php echo esc_url( $info_for_home['phone_link'] ); ?>"><?php echo esc_html( $info_for_home['phone'] ); ?></a></p>
					<div class="home-summary-btns">
						<a href="<?php echo esc_url( $naver_url_h ); ?>" target="_blank" rel="noopener" class="btn btn-sm"><?php esc_html_e( '네이버 지도', 'doduri' ); ?></a>
						<a href="<?php echo esc_url( $kakao_url_h ); ?>" target="_blank" rel="noopener" class="btn btn-sm"><?php esc_html_e( '카톡 채널', 'doduri' ); ?></a>
					</div>
				</div>

			</div>
		</div>
	</section>

</main>

<?php get_footer(); ?>
