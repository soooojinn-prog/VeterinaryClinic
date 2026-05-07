<?php
/**
 * Template Name: 진료과목 (service-subject)
 * 슬러그 'service-subject' 페이지에 적용.
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
		'bg'         => doduri_option( 'sub_service_bg', 'https://images.unsplash.com/photo-1548767797-d8c844163c4c?w=1600&q=80' ),
		'title'      => __( '진료안내', 'doduri' ),
		'subtitle'   => __( '도두리동물병원의 진료 서비스를 안내합니다', 'doduri' ),
		'crumbs'     => array(
			array(
				'label' => __( '진료안내', 'doduri' ),
				'url'   => home_url( '/service-subject/' ),
			),
			array( 'label' => __( '진료과목', 'doduri' ) ),
		),
		'tabs'       => array(
			array( 'key' => 'subject',  'label' => __( '진료과목', 'doduri' ),                'url' => home_url( '/service-subject/' ) ),
			array( 'key' => 'location', 'label' => __( '진료시간 / 오시는 길', 'doduri' ), 'url' => home_url( '/location/' ) ),
		),
		'active_tab' => 'subject',
	)
);
get_template_part( 'template-parts/sub-page-header' );

// 진료과목 항목 — ACF repeater 또는 정적 fallback
$services = doduri_option( 'service_items', null );
if ( ! is_array( $services ) || empty( $services ) ) {
	$services = array(
		array(
			'title'   => '건강검진 · 예방의학',
			'desc'    => "아이의 건강을 지키는 첫 번째 방법은 정기적인 검진과 예방입니다.\n작은 변화도 놓치지 않고, 질병이 생기기 전에 미리 파악하고 관리합니다.",
			'bullets' => array(
				'신체검사 / 혈액검사 / 요검사 / 분변검사',
				'영상검사 (X-ray, 초음파)',
				'예방접종 및 심장사상충 · 내외부 구충',
				'연령별 맞춤 건강검진 (성견·성묘 / 시니어)',
			),
			'closing' => '반려동물의 나이와 건강 상태에 맞는 검진 주기를 안내해 드립니다.',
		),
		array(
			'title'   => '내과질환 클리닉',
			'desc'    => "소화기, 호흡기, 비뇨기, 피부, 내분비 등 다양한 내과 질환을\n정확하게 진단하고, 아이의 상태에 맞게 치료합니다.",
			'bullets' => array(
				'소화기 질환 (구토, 설사, 식욕부진, 췌장염 등)',
				'호흡기 질환 (기침, 콧물, 호흡 이상 등)',
				'비뇨기 · 신장 질환 (혈뇨, 결석, 신부전 등)',
				'피부 · 귀 질환 / 내분비 질환 (쿠싱, 당뇨, 갑상선 등)',
			),
			'closing' => '빠른 진단과 적절한 치료로, 아이가 편안하게 회복할 수 있도록 돕겠습니다.',
		),
		array(
			'title'   => '순환기 · 시니어 클리닉',
			'desc'    => "나이가 들수록 심장 질환, 관절 문제, 만성 내과 질환이 늘어납니다.\n꾸준한 모니터링과 세심한 관리로 시니어 반려동물의 삶의 질을 지킵니다.",
			'bullets' => array(
				'심장 질환 (판막 질환, 심근병증 등) 진단 및 내과 관리',
				'심장 청진 · 혈압 측정 · 심전도 · 심장 초음파',
				'관절염 · 신경계 질환 관리',
				'만성 질환 (신부전, 당뇨, 갑상선 등) 장기 관리',
			),
			'closing' => '시니어 아이도 오랫동안 건강하고 행복한 일상을 보낼 수 있도록 함께하겠습니다.',
		),
	);
}

$principle_title = doduri_option( 'service_principle_title', '우리의 진료 원칙' );
$principle_body  = doduri_option(
	'service_principle_body',
	"저희는 모든 진료에서 보호자분께 충분한 설명과 선택지를 드리는 것을 기본으로 합니다.\n아이의 상태와 보호자의 상황을 함께 고려하여, 가장 현실적이고 최선의 방향을 함께 찾아가겠습니다."
);
?>

<style>
.service-list { display:flex; flex-direction:column; gap:48px; }
.service-item { display:flex; gap:40px; background:var(--bg-light); border-radius:20px; padding:48px 52px; align-items:flex-start; }
.service-num { flex:0 0 auto; width:56px; height:56px; border-radius:50%; background:var(--primary); color:#fff; font-size:20px; font-weight:800; display:flex; align-items:center; justify-content:center; margin-top:4px; }
.service-body { flex:1; min-width:0; }
.service-title { font-size:20px; font-weight:800; color:var(--dark); margin-bottom:14px; }
.service-desc { font-size:15px; line-height:1.9; color:#5a5149; margin-bottom:20px; }
.service-bullets { list-style:none; padding:0; margin:0 0 18px; display:flex; flex-direction:column; gap:8px; }
.service-bullets li { font-size:14px; color:#5a5149; line-height:1.7; padding-left:18px; position:relative; }
.service-bullets li::before { content:'·'; position:absolute; left:2px; color:var(--primary); font-weight:900; font-size:18px; line-height:1.5; }
.service-closing { font-size:14px; font-weight:600; color:var(--primary); line-height:1.8; padding-top:16px; border-top:1px solid #ece6dc; }
.service-principle { margin-top:56px; background:var(--primary); border-radius:20px; padding:48px 56px; text-align:center; }
.service-principle-title { font-size:15px; font-weight:700; letter-spacing:2px; color:rgba(255,255,255,0.75); margin-bottom:20px; text-transform:uppercase; }
.service-principle-body { font-size:clamp(16px,2vw,20px); font-weight:700; color:#fff; line-height:1.9; }
@media (max-width:768px){
	.service-item { flex-direction:column; gap:20px; padding:36px 28px; }
	.service-num { width:44px; height:44px; font-size:16px; }
	.service-principle { padding:36px 28px; }
}
</style>

<main>
	<section id="subject" class="section">
		<div class="container">
			<div class="section-header">
				<p class="section-tag"><?php esc_html_e( '진료과목', 'doduri' ); ?></p>
			</div>

			<div class="service-list">
				<?php foreach ( $services as $idx => $svc ) :
					$title   = isset( $svc['title'] ) ? $svc['title'] : '';
					$desc    = isset( $svc['desc'] ) ? $svc['desc'] : '';
					$bullets = isset( $svc['bullets'] ) && is_array( $svc['bullets'] ) ? $svc['bullets'] : array();
					$closing = isset( $svc['closing'] ) ? $svc['closing'] : '';
					?>
					<div class="service-item">
						<div class="service-num"><?php echo esc_html( $idx + 1 ); ?></div>
						<div class="service-body">
							<h3 class="service-title"><?php echo esc_html( $title ); ?></h3>
							<?php if ( $desc ) : ?>
								<p class="service-desc"><?php echo nl2br( esc_html( $desc ) ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $bullets ) ) : ?>
								<ul class="service-bullets">
									<?php foreach ( $bullets as $b ) :
										$bt = is_array( $b ) ? ( isset( $b['text'] ) ? $b['text'] : '' ) : $b;
										if ( $bt === '' ) continue; ?>
										<li><?php echo esc_html( $bt ); ?></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
							<?php if ( $closing ) : ?>
								<p class="service-closing"><?php echo esc_html( $closing ); ?></p>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="service-principle">
				<p class="service-principle-title"><?php echo esc_html( $principle_title ); ?></p>
				<p class="service-principle-body"><?php echo nl2br( esc_html( $principle_body ) ); ?></p>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
