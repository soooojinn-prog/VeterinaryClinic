<?php
/**
 * Template Name: 진료시간 / 오시는 길 (service-location)
 * 슬러그 'location' 페이지에 적용.
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
		'bg'         => doduri_option( 'sub_service_bg', DODURI_THEME_URI . '/assets/images/facility/facility5.png' ),
		'title'      => __( '진료안내', 'doduri' ),
		'subtitle'   => __( '도두리동물병원의 진료 서비스를 안내합니다', 'doduri' ),
		'crumbs'     => array(
			array(
				'label' => __( '진료안내', 'doduri' ),
				'url'   => home_url( '/service-subject/' ),
			),
			array( 'label' => __( '진료시간 / 오시는 길', 'doduri' ) ),
		),
		'tabs'       => array(
			array( 'key' => 'subject',  'label' => __( '진료과목', 'doduri' ),                'url' => home_url( '/service-subject/' ) ),
			array( 'key' => 'location', 'label' => __( '진료시간 / 오시는 길', 'doduri' ), 'url' => home_url( '/location/' ) ),
		),
		'active_tab' => 'location',
	)
);
get_template_part( 'template-parts/sub-page-header' );

$info = doduri_site_info();

// 지오코딩 주소(지도용) — 시·도, 구, 도로명만
$geocode_query = doduri_option( 'location_geocode_query', '인천 계양구 용종로 2' );

// 진료시간
$hours_open    = doduri_option( 'hours_open', '오전 10시 ~ 오후 7시' );
$hours_break   = doduri_option( 'hours_break', '오후 1시 ~ 오후 2시' );
$hours_closed  = doduri_option( 'hours_closed_notice', '※ 목요일, 일요일은 휴진입니다.' );

// 교통수단 안내
$bus_info     = doduri_option( 'access_bus', '계산중학교 / 부평동초등학교 / 대동아파트 정류장 하차 (도보 5분 이내)' );
$parking_info = doduri_option( 'access_parking', '건물 지하주차장 이용' );

// 지도 앱 링크
$naver_map_url = doduri_option( 'naver_map_url', 'https://naver.me/5nhPYsQU' );
$kakao_map_url = doduri_option( 'kakao_map_url', 'https://map.kakao.com/link/search/도두리동물병원' );
$kakao_channel = ! empty( $info['kakao'] ) ? $info['kakao'] : 'http://pf.kakao.com/_lwlTX';
?>

<style>
.location-wrap { display:flex; gap:28px; border-radius:16px; overflow:hidden; box-shadow:0 4px 30px rgba(0,0,0,0.08); align-items:stretch; background:#fff; padding:28px; }
.location-map { flex:0 0 55%; min-height:520px; background:#e8eaed; position:relative; overflow:hidden; align-self:stretch; border-radius:12px; }
.location-info { flex:1; background:#fff; padding:0; display:flex; flex-direction:column; justify-content:space-between; gap:0; }
.location-title { font-size:17px; font-weight:800; color:var(--dark); display:flex; align-items:center; gap:8px; margin-bottom:10px; }
.location-title i { color:var(--primary); font-size:16px; }
.location-address { font-size:13px; color:#5a5149; line-height:1.6; margin-bottom:18px; }
.location-access { list-style:none; padding:0; margin:0 0 18px; display:flex; flex-direction:column; gap:8px; }
.location-access li { display:flex; align-items:flex-start; gap:10px; font-size:13px; color:#5a5149; line-height:1.6; }
.location-access li i { color:var(--primary); font-size:13px; margin-top:2px; flex-shrink:0; width:14px; text-align:center; }
.access-label { font-weight:700; color:var(--dark); white-space:nowrap; min-width:36px; }
.location-map-btns { display:flex; gap:8px; margin-bottom:22px; }
.map-btn { display:inline-flex; align-items:center; gap:6px; padding:9px 18px; border-radius:6px; font-size:13px; font-weight:700; cursor:pointer; transition:filter 0.2s; text-decoration:none; }
.map-btn:hover { filter:brightness(0.92); }
.map-btn.naver { background:#03C75A; color:#fff; }
.map-btn.kakao { background:#FEE500; color:#3c1e1e; }
.map-btn.kakao-ch { background:#FEE500; color:#3c1e1e; }
.location-divider { border:none; border-top:1px solid #ece6dc; margin:16px 0; }
#hours { scroll-margin-top: 100px; }
.loc-hours h4, .loc-contact h4 { font-size:13px; font-weight:800; color:var(--dark); margin-bottom:8px; }
.loc-hours-rows { display:flex; flex-direction:column; gap:4px; margin-bottom:6px; }
.loc-hours-row { display:flex; gap:16px; font-size:13px; color:#5a5149; }
.loc-hours-row .lh-label { font-weight:600; color:var(--dark); white-space:nowrap; min-width:52px; }
.loc-notice { font-size:12px; color:var(--primary); font-weight:600; }
.loc-contact p { font-size:14px; color:#5a5149; margin:0; }
@media (max-width:900px){
	.location-wrap { flex-direction:column; padding:20px; gap:20px; }
	.location-map { flex:none; min-height:260px; }
	.location-info { padding:0; }
}
</style>

<main>
	<section id="location" class="section">
		<div class="container">
			<div class="section-header">
				<p class="section-tag"><?php esc_html_e( '진료시간 / 오시는길', 'doduri' ); ?></p>
			</div>

			<div class="location-wrap">

				<div class="location-map">
					<div id="naver-map" style="width:100%;height:100%;"></div>
				</div>

				<div class="location-info">

					<h3 class="location-title">
						<i class="fas fa-paw"></i>
						<?php echo esc_html( $info['name_ko'] ); ?> <?php esc_html_e( '오시는 길', 'doduri' ); ?>
					</h3>
					<p class="location-address"><?php echo esc_html( $info['address'] ); ?></p>

					<ul class="location-access">
						<li>
							<i class="fas fa-bus"></i>
							<span class="access-label"><?php esc_html_e( '버스', 'doduri' ); ?></span>
							<span><?php echo esc_html( $bus_info ); ?></span>
						</li>
						<li>
							<i class="fas fa-car"></i>
							<span class="access-label"><?php esc_html_e( '주차', 'doduri' ); ?></span>
							<span><?php echo esc_html( $parking_info ); ?></span>
						</li>
					</ul>

					<div class="location-map-btns">
						<a href="<?php echo esc_url( $naver_map_url ); ?>" target="_blank" rel="noopener" class="map-btn naver">
							<?php esc_html_e( '네이버 지도', 'doduri' ); ?>
						</a>
						<a href="<?php echo esc_url( $kakao_map_url ); ?>" target="_blank" rel="noopener" class="map-btn kakao">
							<?php esc_html_e( '카카오 지도', 'doduri' ); ?>
						</a>
						<a href="<?php echo esc_url( $kakao_channel ); ?>" target="_blank" rel="noopener" class="map-btn kakao-ch">
							<?php esc_html_e( '카톡 채널', 'doduri' ); ?>
						</a>
					</div>

					<hr class="location-divider" />

					<div class="loc-hours" id="hours">
						<h4><?php esc_html_e( '진료시간', 'doduri' ); ?></h4>
						<div class="loc-hours-rows">
							<div class="loc-hours-row">
								<span class="lh-label"><?php esc_html_e( '진료시간', 'doduri' ); ?></span>
								<span><?php echo esc_html( $hours_open ); ?></span>
							</div>
							<div class="loc-hours-row">
								<span class="lh-label"><?php esc_html_e( '휴게시간', 'doduri' ); ?></span>
								<span><?php echo esc_html( $hours_break ); ?></span>
							</div>
						</div>
						<p class="loc-notice"><?php echo esc_html( $hours_closed ); ?></p>
					</div>

					<hr class="location-divider" />

					<div class="loc-contact">
						<h4><?php esc_html_e( '전화번호', 'doduri' ); ?></h4>
						<p><a href="<?php echo esc_url( $info['phone_link'] ); ?>" style="color:inherit;text-decoration:none;"><?php echo esc_html( $info['phone'] ); ?></a></p>
					</div>

					<hr class="location-divider" />

					<div class="loc-contact">
						<h4><?php esc_html_e( 'E-mail', 'doduri' ); ?></h4>
						<p><?php echo esc_html( $info['email'] ); ?></p>
					</div>

				</div>
			</div>
		</div>
	</section>
</main>

<script>
(function () {
	function initMap() {
		if (typeof naver === 'undefined' || !naver.maps || !naver.maps.Service) {
			return;
		}
		var query = <?php echo wp_json_encode( $geocode_query ); ?>;
		naver.maps.Service.geocode({ query: query }, function (status, response) {
			var lat, lng;
			if (status === naver.maps.Service.Status.OK && response.v2.addresses.length > 0) {
				lat = parseFloat(response.v2.addresses[0].y);
				lng = parseFloat(response.v2.addresses[0].x);
			} else {
				lat = 37.5372;
				lng = 126.7222;
			}

			var position = new naver.maps.LatLng(lat, lng);
			var map = new naver.maps.Map('naver-map', { center: position, zoom: 17 });
			var marker = new naver.maps.Marker({ position: position, map: map });
			var infoWindow = new naver.maps.InfoWindow({
				content: <?php echo wp_json_encode( '<div style="padding:8px 12px;font-size:13px;font-weight:700;white-space:nowrap;">' . $info['name_ko'] . '</div>' ); ?>
			});
			infoWindow.open(map, marker);
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initMap);
	} else {
		initMap();
	}
})();
</script>

<?php get_footer(); ?>
