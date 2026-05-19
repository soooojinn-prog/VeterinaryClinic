<?php
/**
 * Template Name: 의료진소개 (about-doctor)
 * 슬러그 'doctor' 페이지에 적용.
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
		'bg'         => doduri_option( 'sub_about_bg', DODURI_THEME_URI . '/assets/images/facility/facility1.png' ),
		'title'      => __( '병원소개', 'doduri' ),
		'subtitle'   => __( '도두리동물병원을 소개합니다', 'doduri' ),
		'crumbs'     => array(
			array(
				'label' => __( '병원소개', 'doduri' ),
				'url'   => home_url( '/greeting/' ),
			),
			array( 'label' => __( '의료진소개', 'doduri' ) ),
		),
		'tabs'       => array(
			array( 'key' => 'greeting', 'label' => __( '인사말', 'doduri' ), 'url' => home_url( '/greeting/' ) ),
			array( 'key' => 'doctor',   'label' => __( '의료진소개', 'doduri' ), 'url' => home_url( '/doctor/' ) ),
			array( 'key' => 'facility', 'label' => __( '시설소개', 'doduri' ), 'url' => home_url( '/facility/' ) ),
		),
		'active_tab' => 'doctor',
	)
);
get_template_part( 'template-parts/sub-page-header' );

$doctor_name = doduri_option( 'doctor_name', '김한민 원장' );

// 그룹별 항목 (학력/경력/학회/학술활동) — ACF 가 없으면 정적 기본값.
// ACF 에서는 repeater 로 구현 가능하지만, fallback 은 단순 배열로.
$doctor_groups = doduri_option( 'doctor_groups', null );
if ( ! is_array( $doctor_groups ) || empty( $doctor_groups ) ) {
	$doctor_groups = array(
		array(
			'items' => array(
				'전남대학교 수의과대학 수의학과 졸업',
			),
		),
		array(
			'items' => array(
				'前) 부산 H 동물메디컬센터 진료수의사',
				'前) 평택 B 동물의료센터 진료수의사',
				'前) 거제 G 동물메디컬센터 원장',
				'前) 인천 D 동물병원 부원장',
				'前) 부천 B 동물전문의료센터 진료과장',
				'前) 인천 A 동물의료센터 부원장',
			),
		),
		array(
			'items' => array(
				'대한수의사회 정회원',
			),
		),
		array(
			'items' => array(
				'세계소동물수의사회 (2011)',
				'부산수의컨퍼런스 (2018, 2019)',
				'영남수의컨퍼런스 (2019, 2021)',
				'경기북부수의컨퍼런스 (2024, 2025)',
				'인천수의컨퍼런스 (2025)',
			),
		),
	);
}

$doctor_photo_url = '';
$photo_field      = doduri_option( 'doctor_photo', '' );
if ( is_array( $photo_field ) && ! empty( $photo_field['url'] ) ) {
	$doctor_photo_url = $photo_field['url'];
} elseif ( is_string( $photo_field ) && $photo_field ) {
	$doctor_photo_url = $photo_field;
} else {
	$doctor_photo_url = DODURI_THEME_URI . '/assets/images/doctor/director.jpg';
}
?>

<style>
.doctor-card { display:flex; flex-direction:row; gap:56px; align-items:stretch; background:var(--bg-light); border-radius:20px; padding:52px 60px; }
.doctor-photo-wrap { flex:0 0 38%; max-width:38%; background:#c8c8c8; border-radius:8px; }
.doctor-photo-wrap img { width:100%; height:100%; object-fit:cover; object-position:center top; display:block; border-radius:8px; }
.doctor-no-photo { flex:1; min-height:240px; background:#e8dfd3; border-radius:8px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:10px; }
.doctor-no-photo i { font-size:52px; color:var(--primary-light); opacity:0.55; }
.doctor-no-photo span { font-size:12px; color:var(--gray); letter-spacing:0.5px; }
.doctor-detail { flex:1; min-width:0; }
.doctor-name { font-size:20px; font-weight:800; color:var(--dark); padding-bottom:18px; margin-bottom:0; border-bottom:1px solid #d8cfc4; }
.doctor-groups { display:flex; flex-direction:column; }
.doctor-group { padding:18px 0; border-bottom:1px solid #ece6dc; }
.doctor-group:last-child { border-bottom:none; padding-bottom:0; }
.doctor-group ul { list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:7px; }
.doctor-group ul li { font-size:14px; color:#5a5149; line-height:1.7; padding-left:16px; position:relative; }
.doctor-group ul li::before { content:'·'; position:absolute; left:2px; color:var(--primary); font-weight:900; font-size:17px; line-height:1.5; }
@media (max-width:900px){
	.doctor-card { flex-direction:column; gap:36px; padding:0; background:transparent; border-radius:0; }
	.doctor-photo-wrap { flex:none; width:100%; max-width:100%; }
	.doctor-photo-wrap img { height:auto; width:100%; }
	.doctor-no-photo { min-height:200px; }
}
</style>

<main>
	<section class="section">
		<div class="container">

			<div class="section-header">
				<p class="section-tag"><?php esc_html_e( '의료진 소개', 'doduri' ); ?></p>
			</div>

			<div class="doctor-card">

				<div class="doctor-photo-wrap">
					<?php if ( $doctor_photo_url ) : ?>
						<img src="<?php echo esc_url( $doctor_photo_url ); ?>" alt="<?php echo esc_attr( $doctor_name ); ?>" />
					<?php else : ?>
						<div class="doctor-no-photo">
							<i class="fas fa-user"></i>
							<span><?php esc_html_e( '사진 준비중', 'doduri' ); ?></span>
						</div>
					<?php endif; ?>
				</div>

				<div class="doctor-detail">
					<h3 class="doctor-name"><?php echo esc_html( $doctor_name ); ?></h3>

					<div class="doctor-groups">
						<?php foreach ( $doctor_groups as $group ) :
							if ( empty( $group['items'] ) || ! is_array( $group['items'] ) ) {
								continue;
							} ?>
							<div class="doctor-group">
								<ul>
									<?php foreach ( $group['items'] as $item ) :
										// item 이 배열({ text: ... })인 경우와 문자열인 경우 모두 처리
										$txt = is_array( $item ) ? ( isset( $item['text'] ) ? $item['text'] : '' ) : $item;
										if ( $txt === '' ) continue; ?>
										<li><?php echo esc_html( $txt ); ?></li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endforeach; ?>
					</div>

				</div>

			</div>

		</div>
	</section>
</main>

<?php get_footer(); ?>
