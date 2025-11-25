<?php
/**
 * VK Blocks - Query Block Extension
 *
 * @package vk-blocks
 */

/**
 * Add modified date order option to WordPress core Query block
 *
 * @param array    $query Array of query variables.
 * @param WP_Block $block The block object.
 * @param int      $page  Current page number, 1-based.
 * @return array Modified query variables.
 */
function vk_blocks_query_loop_block_query_vars( $query, $block, $page ) {
	// Only apply to core/query block
	if ( 'core/query' !== $block->name ) {
		return $query;
	}

	// Get the query attributes from the block
	$block_attrs      = $block->attributes;
	$query_attributes = isset( $block_attrs['query'] ) ? $block_attrs['query'] : array();

	// Set orderby parameter if specified
	if ( isset( $query_attributes['orderBy'] ) ) {
		$orderby_value = $query_attributes['orderBy'];
		// Map orderBy values to WordPress query parameters
		// Only include 'modified' as it's not yet available in core
		// Other options are handled by core Query block
		$orderby_mapping = array(
			'modified' => 'modified',
		);

		if ( isset( $orderby_mapping[ $orderby_value ] ) ) {
			$query['orderby'] = $orderby_mapping[ $orderby_value ];
		}
	}

	// Set order parameter if specified
	if ( isset( $query_attributes['order'] ) ) {
		$query['order'] = $query_attributes['order'];
	}

	// Ensure page parameter is preserved (required by WordPress filter)
	$query['paged'] = $page;

	return $query;
}
add_filter( 'query_loop_block_query_vars', 'vk_blocks_query_loop_block_query_vars', 10, 3 );
