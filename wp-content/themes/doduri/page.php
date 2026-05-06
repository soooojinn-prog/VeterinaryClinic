<?php
/**
 * 일반 고정 페이지 템플릿.
 * 전용 page-{slug}.php 가 없는 경우의 기본 템플릿.
 *
 * @package Doduri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<!-- ===== 서브 페이지 헤더 ===== -->
<div class="sub-page-header">
	<?php
	$header_bg = get_the_post_thumbnail_url( get_the_ID(), 'full' );
	if ( ! $header_bg ) {
		$header_bg = 'https://images.unsplash.com/photo-1450778869180-41d0601e046e?w=1600&q=80';
	}
	?>
	<div class="sub-page-header-bg" style="background-image: url('<?php echo esc_url( $header_bg ); ?>')"></div>
	<div class="sub-page-header-overlay"></div>
	<div class="container sub-page-header-content">
		<h1><?php the_title(); ?></h1>
		<nav class="breadcrumb">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( '홈', 'doduri' ); ?></a>
			<i class="fas fa-chevron-right"></i>
			<span><?php the_title(); ?></span>
		</nav>
	</div>
</div>

<!-- ===== 콘텐츠 ===== -->
<main>
	<section class="section">
		<div class="container">
			<?php
			while ( have_posts() ) :
				the_post();
				the_content();
			endwhile;
			?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
