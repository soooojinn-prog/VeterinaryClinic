<?php
/**
 * 검색 폼.
 *
 * @package Doduri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="s"><?php esc_html_e( '검색:', 'doduri' ); ?></label>
	<input type="search" id="s" class="search-field" placeholder="<?php esc_attr_e( '검색어 입력...', 'doduri' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" />
	<button type="submit" class="search-submit"><i class="fas fa-search"></i></button>
</form>
