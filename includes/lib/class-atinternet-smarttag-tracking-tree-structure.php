<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class ATInternet_SmartTag_Tracking_Tree_Structure {

	/**
	 * Prefix.
	 * @var 	string
	 * @access  private
	 * @since 	1.0.0
	 */
	private $prefix = 'ati_';

	/**
	 * Post ID.
	 * @var 	string
	 * @access  private
	 * @since 	1.0.0
	 */
	private $post_id;

	/**
	 * Tree type.
	 * @var 	string
	 * @access  private
	 * @since 	1.0.0
	 */
	private $tree_type;

	/**
	 * Screen type.
	 * @var 	string
	 * @access  private
	 * @since 	1.0.0
	 */
	private $screen_type;

	/**
	 * Level 2.
	 * @var 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public $level2;

	/**
	 * Chapter 1.
	 * @var 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public $chapter1;

	/**
	 * Chapter 2.
	 * @var 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public $chapter2;

	/**
	 * Chapter 3.
	 * @var 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public $chapter3;

	/**
	 * Name.
	 * @var 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public $name;

	public function __construct() {}

	/**
	 * Build search result tree structure.
	 * @access  public
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_the_tree_structure( $post_id, $tree_type, $screen_type ) {
		$this->post_id = $post_id;
		$this->tree_type = $tree_type;
		$this->screen_type = $screen_type;

		switch ( $screen_type ) {
			case 'post':
			case 'product':
				$this->post_tree();
				break;
			case 'shop':
				$this->shop_tree();
				break;
			case 'search':
				$this->search_tree();
				break;
			case 'archive':
				$this->archive_tree();
				break;
			case 'home':
				$this->home_tree();
				break;

			default:
				break;
		}

		$this->get_level2();
	}

	/**
	 * Make categories to chapters structure.
	 * @access  private
	 * @since   1.0.0
	 * @return  string
	 */
	private function get_categories() {
		$categories = array();
		if( $this->screen_type == 'post' ) $categories = get_the_terms( $this->post_id, 'category' ) ?: array();
		if( $this->screen_type == 'product' ) $categories = get_the_terms( $this->post_id, 'product_cat' )  ?: array();

		$categories = array_values( $categories );

		foreach ( array_keys( $categories ) as $key ) {
				_make_cat_compat( $categories[$key] );
		}

		return $categories;
	}

	/**
	 * Get page's level 2.
	 * @access  private
	 * @since   1.0.0
	 * @return  int
	 */
	private function get_level2() {
		if( $post_level2 = get_post_meta( $this->post_id, $this->prefix . 'level2', true ) ) {
			$this->level2 = $post_level2;
		} else {
			$this->level2 = get_option( $this->prefix . 'level2', '' );
		}

		return $this->level2;
	}

	/**
	 * Build search result tree structure.
	 * @access  public
	 * @since   1.0.0
	 * @return  string
	 */
	public function search_tree() {
		if( ( $name = sanitize_title( get_option( $this->prefix . 'search_label', '' ) ) ) != '' ) $this->name = $name;
		else $this->post_tree();
	}

	/**
	 * Build shop tree structure.
	 * @access  public
	 * @since   1.0.0
	 * @return  string
	 */
	public function shop_tree() {
		if( ( $name = sanitize_title( get_option( $this->prefix . 'shop_label', '' ) ) ) != '' ) $this->name = $name;
		else $this->post_tree();
	}

	/**
	 * Build archive tree structure.
	 * @access  public
	 * @since   1.0.0
	 * @return  string
	 */
	public function archive_tree() {
		if( ( $name = sanitize_title( get_the_archive_title() ) ) != '' ) $this->name = $name;
		else $this->post_tree();
	}

	/**
	 * Build home tree structure.
	 * @access  public
	 * @since   1.0.0
	 * @return  string
	 */
	public function home_tree() {
		if( ( $name = sanitize_title( get_option( $this->prefix . 'home_label', '' ) ) ) != '' ) $this->name = $name;
		else $this->post_tree();
	}

	/**
	 * Build post tree structure.
	 * @access  public
	 * @since   1.0.0
	 * @return  string
	 */
	public function post_tree() {
		switch ( $this->tree_type ) {
			case 'url':
				$tree_structure['name'] = '';
				break;

			case 'custom':
				$tree_structure = array();
				$chapter1 = get_post_meta( $this->post_id, $this->prefix . 'chapter1', true );
				$chapter2 = get_post_meta( $this->post_id, $this->prefix . 'chapter2', true );
				$chapter3 = get_post_meta( $this->post_id, $this->prefix . 'chapter3', true );
				$name = get_post_meta ($this->post_id, $this->prefix . 'name', true );
				if( !empty( $chapter1 ) ) $this->chapter1 = sanitize_title( $chapter1 );
				if( !empty( $chapter2 ) ) $this->chapter2 = sanitize_title( $chapter2 );
				if( !empty( $chapter3 ) ) $this->chapter3 = sanitize_title( $chapter3 );
				if( !empty( $name ) ) $this->name = sanitize_title( $name );
				break;

			case 'structure':
			default:
				$page_title = sanitize_title( get_the_title( $this->post_id ) );
				$categories = $this->get_categories();

				if($categories){
					$chap = explode( "|", get_category_parents( $categories[0]->cat_ID, false, '|' ) );
				}

				$tree_structure = array();
				if( !empty( $chap[0] ) ) $this->chapter1 = sanitize_title( $chap[0] );
				if( !empty( $chap[1] ) ) $this->chapter2 = sanitize_title( $chap[1] );
				if( !empty( $chap[2] ) ) $this->chapter3 = sanitize_title( $chap[2] );
				$this->name = sanitize_title( $page_title );
				break;
		}
		
	}

}
