<?php
/**
 * Template Name: 인사말 (about-greeting)
 * 슬러그 'greeting' 인 페이지에 자동 적용.
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
			array(
				'label' => __( '인사말', 'doduri' ),
			),
		),
		'tabs'       => array(
			array(
				'key'   => 'greeting',
				'label' => __( '인사말', 'doduri' ),
				'url'   => home_url( '/greeting/' ),
			),
			array(
				'key'   => 'doctor',
				'label' => __( '의료진소개', 'doduri' ),
				'url'   => home_url( '/doctor/' ),
			),
			array(
				'key'   => 'facility',
				'label' => __( '시설소개', 'doduri' ),
				'url'   => home_url( '/facility/' ),
			),
		),
		'active_tab' => 'greeting',
	)
);
get_template_part( 'template-parts/sub-page-header' );

// 인사말 텍스트 — ACF 옵션 → 기본값
$greet_label  = doduri_option( 'greet_label', 'Doduri Animal Clinic' );
$greet_name   = doduri_option( 'greet_name_html', "안녕하세요.\n도두리동물병원 원장\n[김한민]입니다." );
$greet_thanks = doduri_option( 'greet_thanks', '감사합니다.' );
$greet_sign   = doduri_option( 'greet_sign', '도두리동물병원 원장 김한민 드림' );

$greet_body = doduri_option(
	'greet_body',
	"도두리동물병원을 찾아주신 보호자 여러분께 진심으로 감사드립니다.\n\n저는 임상 현장에서 다양한 진료 경험을 쌓아오며, 보다 정확하고 안정적인 진료의 중요성을 깊이 느껴왔습니다. 이러한 경험을 바탕으로, 지역에서도 믿고 맡길 수 있는 의료를 제공하고자 도두리동물병원을 운영하고 있습니다.\n\n저희 병원은 멀리 가지 않아도 편하게 찾을 수 있는, 그리고 언제든 부담 없이 상담하고 진료받을 수 있는 '주치의 같은 병원'을 지향합니다.\n\n특히 나이가 들면서 자주 나타나는 다양한 내과 질환에 대해, 아이의 상태에 맞는 꾸준한 관리와 세심한 진료를 통해 오랫동안 건강한 삶을 이어갈 수 있도록 돕고자 합니다.\n\n또한 상위 진료 경험을 바탕으로, 보다 전문적인 진료를 지역 병원에서도 부담 없이 받을 수 있도록 노력하고 있습니다.\n\n앞으로도 도두리동물병원은 아이들과 보호자분들 곁에서 오래 함께할 수 있는, 믿을 수 있는 병원이 되겠습니다."
);

// 원장 사진 — ACF (image array 또는 URL) 우선, 없으면 테마의 director.jpg
$doctor_photo_url = '';
$photo_field      = doduri_option( 'doctor_photo', '' );
if ( is_array( $photo_field ) && ! empty( $photo_field['url'] ) ) {
	$doctor_photo_url = $photo_field['url'];
} elseif ( is_string( $photo_field ) && $photo_field ) {
	$doctor_photo_url = $photo_field;
} else {
	$doctor_photo_url = DODURI_THEME_URI . '/assets/images/doctor/doctor-greeting.jpg';
}
?>

<style>
/* ===== 인사말 레이아웃 ===== */
.greeting-wrap { display:flex; flex-direction:row; gap:52px; align-items:stretch; }
.greeting-img-wrap { flex:0 0 40%; max-width:40%; background:#c8c8c8; border-radius:8px; }
.greeting-img-wrap img { width:100%; height:100%; object-fit:cover; object-position:center top; display:block; border-radius:8px; }
.greeting-no-photo { flex:1; background:#e8dfd3; border-radius:8px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:12px; }
.greeting-no-photo i { font-size:64px; color:var(--primary-light); opacity:0.5; }
.greeting-no-photo span { font-size:13px; color:var(--gray); letter-spacing:0.5px; }
.greeting-content { flex:1; min-width:0; max-height:680px; overflow-y:auto; padding-right:10px; scrollbar-width:thin; scrollbar-color:#d0cbc4 transparent; }
.greeting-content::-webkit-scrollbar { width:4px; }
.greeting-content::-webkit-scrollbar-thumb { background:#d0cbc4; border-radius:2px; }
.greeting-content::-webkit-scrollbar-track { background:transparent; }
.greeting-label { font-size:11px; font-weight:600; letter-spacing:3px; text-transform:uppercase; color:var(--primary); margin-bottom:18px; }
.greeting-name { font-size:clamp(20px,2.4vw,30px); font-weight:800; line-height:1.5; color:var(--dark); }
.greeting-name em { font-style:normal; color:var(--primary); }
.greeting-rule { width:40px; height:2px; background:var(--primary); margin:20px 0 22px; }
.greeting-body { font-size:14px; line-height:1.9; color:#5a5149; }
.greeting-body p + p { margin-top:12px; }
.greeting-close { margin-top:14px; }
.greeting-thanks { font-size:14px; font-weight:400; color:#5a5149; margin-bottom:4px; }
.greeting-sign { font-size:14px; color:var(--gray); }
@media (max-width:900px){
	.greeting-wrap { flex-direction:column; gap:36px; }
	.greeting-img-wrap { flex:none; max-width:100%; width:100%; position:static; }
	.greeting-img-wrap img { width:100%; height:auto; }
	.greeting-content { max-height:none; overflow-y:visible; padding-right:0; }
}
</style>

<main>
	<section class="section">
		<div class="container">

			<div class="section-header">
				<p class="section-tag"><?php esc_html_e( '인사말', 'doduri' ); ?></p>
			</div>

			<div class="greeting-wrap">

				<div class="greeting-img-wrap">
					<?php if ( $doctor_photo_url ) : ?>
						<img src="<?php echo esc_url( $doctor_photo_url ); ?>" alt="<?php esc_attr_e( '원장 사진', 'doduri' ); ?>" />
					<?php else : ?>
						<div class="greeting-no-photo">
							<i class="fas fa-user"></i>
							<span><?php esc_html_e( '사진 준비중', 'doduri' ); ?></span>
						</div>
					<?php endif; ?>
				</div>

				<div class="greeting-content">
					<p class="greeting-label"><?php echo esc_html( $greet_label ); ?></p>
					<h2 class="greeting-name">
						<?php
						// [텍스트] 형태를 <em>으로 변환
						$name_html = esc_html( $greet_name );
						$name_html = preg_replace( '/\[(.+?)\]/', '<em>$1</em>', $name_html );
						$name_html = nl2br( $name_html );
						echo $name_html; // phpcs:ignore — 이미 esc_html 처리됨, em/br 만 추가됨
						?>
					</h2>
					<div class="greeting-rule"></div>

					<div class="greeting-body">
						<?php
						// 빈 줄로 단락 분리, [[강조]] 를 <strong>으로 변환
						$paras = preg_split( '/\n\s*\n/', trim( $greet_body ) );
						foreach ( $paras as $p ) {
							$p_html = esc_html( $p );
							$p_html = preg_replace( '/\[\[(.+?)\]\]/', '<strong>$1</strong>', $p_html );
							$p_html = nl2br( $p_html );
							echo '<p>' . $p_html . '</p>'; // phpcs:ignore
						}
						?>
					</div>

					<div class="greeting-close">
						<p class="greeting-thanks"><?php echo esc_html( $greet_thanks ); ?></p>
						<p class="greeting-sign"><?php echo esc_html( $greet_sign ); ?></p>
					</div>
				</div>

			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
