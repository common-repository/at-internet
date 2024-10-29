<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class ATInternet_SmartTag_Tracking_Internal_Search {

	/**
	 * Prefix.
	 * @var 	string
	 * @access  private
	 * @since 	1.0.0
	 */
	private $prefix = 'ati_';

	/**
	 * Keyword.
	 * @var 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public $keyword;

	/**
	 * Page number.
	 * @var 	int
	 * @access  public
	 * @since 	1.0.0
	 */
	public $resultPageNumber = 0;

	public function __construct() {}

	/**
	 * Build search result.
	 * @access  public
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_internal_search() {
		global $wp_query;
		// First page
		$page_number = ( $wp_query->found_posts > 0 ) ? 1 : 0;
		// Next pages
		if( $wp_query->query_vars['paged'] > 1 ) $page_number = $wp_query->query_vars['paged'];
		$this->keyword = get_search_query();
		$this->resultPageNumber = $page_number;
	}

}
