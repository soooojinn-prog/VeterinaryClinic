<?php
/**
 * 재사용 서브 페이지 헤더 + 탭 — set_query_var 로 args 전달.
 *
 * @package Doduri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = get_query_var( 'doduri_sub_args', array() );

$bg          = isset( $args['bg'] ) ? $args['bg'] : 'https://images.unsplash.com/photo-1576201836106-db1758fd1c97?w=1600&q=80';
$big_title   = isset( $args['title'] ) ? $args['title'] : '';
$subtitle    = isset( $args['subtitle'] ) ? $args['subtitle'] : '';
$crumbs      = isset( $args['crumbs'] ) && is_array( $args['crumbs'] ) ? $args['crumbs'] : array();
$tabs        = isset( $args['tabs'] ) && is_array( $args['tabs'] ) ? $args['tabs'] : array();
$active_tab  = isset( $args['active_tab'] ) ? $args['active_tab'] : '';
?>

<!-- ===== 서브 페이지 헤더 ===== -->
<div class="sub-page-header">
	<div class="sub-page-header-bg" style="background-image: url('<?php echo esc_url( $bg ); ?>')"></div>
	<div class="sub-page-header-overlay"></div>
	<div class="container sub-page-header-content">
		<h1><?php echo esc_html( $big_title ); ?></h1>
		<?php if ( $subtitle ) : ?>
			<p><?php echo esc_html( $subtitle ); ?></p>
		<?php endif; ?>
		<?php if ( ! empty( $crumbs ) ) : ?>
			<nav class="breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( '홈', 'doduri' ); ?></a>
				<?php foreach ( $crumbs as $i => $crumb ) : ?>
					<i class="fas fa-chevron-right"></i>
					<?php
					$is_last = ( count( $crumbs ) - 1 === $i );
					if ( $is_last || empty( $crumb['url'] ) ) {
						echo '<span>' . esc_html( $crumb['label'] ) . '</span>';
					} else {
						printf(
							'<a href="%1$s">%2$s</a>',
							esc_url( $crumb['url'] ),
							esc_html( $crumb['label'] )
						);
					}
					?>
				<?php endforeach; ?>
			</nav>
		<?php endif; ?>
	</div>
</div>

<?php if ( ! empty( $tabs ) ) : ?>
<!-- ===== 서브 탭 ===== -->
<div class="sub-tab-nav">
	<div class="container">
		<ul class="sub-tab-list">
			<?php foreach ( $tabs as $tab ) : ?>
				<?php $cls = ( ! empty( $tab['key'] ) && $tab['key'] === $active_tab ) ? ' active' : ''; ?>
				<li>
					<a href="<?php echo esc_url( $tab['url'] ); ?>" class="sub-tab-link<?php echo esc_attr( $cls ); ?>">
						<?php echo esc_html( $tab['label'] ); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<?php endif; ?>
