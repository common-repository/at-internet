<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class ATInternet_SmartTag_Tracking {

	/**
	 * Current post ID.
	 * @var 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public $post_id;

	/**
	 * Prefix for settings/custom fields.
	 * @var 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public $prefix;

	/**
	 * Is eCommerce activated?
	 * @var 	bool
	 * @access  public
	 * @since 	1.0.0
	 */
	public $ec;

	/**
	 * Screen type.
	 * @var 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public $screen_type;

	public function __construct ( $post_id = '0', $prefix = 'ati_', $ec = false ) {

		global $wp_query;

		$this->post_id = $post_id;
		$this->prefix = $prefix;

		if( is_singular() ) {
			$this->screen_type = 'post';
			$this->post_id = get_the_ID();
		}
		if( is_search() ) $this->screen_type = 'search';
		if( is_archive() ) $this->screen_type = 'archive';
		if( is_home() ) {
			$this->screen_type = 'home';
			$this->post_id = get_option('page_on_front');
		}

		// eCommerce part
		$conf_ec = ( get_option( $this->prefix . 'use_ecommerce' ) === 'on' ) ? 1 : 0;
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && $conf_ec ) {
			$ec = true;

			if( is_shop() ) $this->screen_type = 'shop' && $this->post_id = get_option( 'woocommerce_shop_page_id' );
			if( is_product_category() ) $this->screen_type = 'product_cat';
			if( is_product() ) $this->screen_type = 'product';
			if( is_cart() ) $this->screen_type = 'cart' && $this->post_id = get_option( 'woocommerce_cart_page_id' );
			if( is_checkout() ) $this->screen_type = 'checkout' && $this->post_id = get_option( 'woocommerce_checkout_page_id' );
			if( is_account_page() ) $this->screen_type = 'account' && $this->post_id = get_option( 'woocommerce_myaccount_page_id' );

		}

		$the_tracker = new ATInternet_SmartTag_Tracking_JS_Builder();

		$the_tracker->configuration = $this->get_tracker_configuration();
		$the_tracker->add( 'page', $this->get_tree_structure( get_option( $this->prefix . 'label_type', 'structure' ) ) );

		if( $this->screen_type == 'search' ) $the_tracker->add( 'internal_search', $this->get_internal_search() );

		$conf_async = ( get_option( $this->prefix . 'async' ) === 'on' ) ? 1 : 0;
		// Display tracking code
		$the_tracker->display($conf_async);

	}

	/**
	 * Get tracker configuration from settings.
	 * @access  public
	 * @since   1.0.0
	 * @return  ATInternet_SmartTag_Tracking_Configuration Object
	 */
	public function get_tracker_configuration() {
		// Collect tracker configuration set
		$tracker_config = new ATInternet_SmartTag_Tracking_Configuration();
		$ati_config = array(
			'domain' => $this->prefix . 'domain',
			'log' => $this->prefix . 'log',
			'logSSL' => $this->prefix . 'logssl',
			'site' => $this->prefix . 'sitenumber',
      'secure' => $this->prefix . 'secure',
      'pixelPath' => $this->prefix . 'pixel'
		);
		foreach( $ati_config as $config => $name ) {
			$config_value = get_option( $name, '' );
			if( $config_value !== '' ) $tracker_config->$config = $config_value;
		}

		return $tracker_config;
	}

	/**
	 * Get page tree structure.
	 * @access  public
	 * @since   1.0.0
	 * @return  ATInternet_SmartTag_Tracking_Tree_Structure Object
	 */
	public function get_tree_structure( $tree_type ) {

		$tree_structure = new ATInternet_SmartTag_Tracking_Tree_Structure();
		$tree_structure->get_the_tree_structure( $this->post_id, $tree_type, $this->screen_type );

		return (object) array_filter( (array) $tree_structure );
	}

	/**
	 * Get internal search.
	 * @access  public
	 * @since   1.0.0
	 * @return  ATInternet_SmartTag_Tracking_Internal_Search Object
	 */
	public function get_internal_search() {

		$internal_search = new ATInternet_SmartTag_Tracking_Internal_Search();

		$internal_search->get_internal_search();

		//return (object) array_filter((array) $internal_search);
		return (object) $internal_search;
	}

}
