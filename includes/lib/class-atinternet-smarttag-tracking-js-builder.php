<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class ATInternet_SmartTag_Tracking_JS_Builder {

	/**
	 * Tracker name.
	 * @var 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public $tracker_name = "attag";

	/**
	 * Tracker configuration.
	 * @var 	ATInternet_SmartTag_Tracking_Configuration
	 * @access  public
	 * @since 	1.0.0
	 */
	public $configuration;

	/**
	 * Page tree structure.
	 * @var 	ATInternet_SmartTag_Tracking_Tree_Structure
	 * @access  public
	 * @since 	1.0.0
	 */
	public $tree_structure;

	/**
	 * Internal search info.
	 * @var 	ATInternet_SmartTag_Tracking_Internal_Search
	 * @access  public
	 * @since 	1.0.0
	 */
	public $internal_search;

	/**
	 * Functionnalities to track.
	 * @var 	array
	 * @access  public
	 * @since 	1.0.0
	 */
	public $functionnalities = array();

	public function __construct () {}

	/**
	 * Tracker initialisation.
	 * @access  public
	 * @since   1.0.0
	 * @return  boolean
	 */
	public function add( $name, $value ) {
		switch ( $name ) {
			case 'page':
				$this->tree_structure = $value;
				$this->functionnalities[] = 'set_page';
				return true;
				break;

			case 'internal_search':
				$this->internal_search = $value;
				$this->functionnalities[] = 'set_internal_search';
				return true;
				break;

			default:
				return false;
				break;
		}
	}

	/**
	 * Tracker initialisation.
	 * @access  private
	 * @since   1.0.0
	 * @return  string
	 */
	private function tracker_init() {
		return 'var ' . $this->tracker_name . ' = new ATInternet.Tracker.Tag(' . json_encode( $this->configuration ) . ');';
	}
	
	/**
	 * Tracker initialisation.
	 * @access  private
	 * @since   1.0.0
	 * @return  string
	 */
	private function tracker_init_async() {
		return 'window.' . $this->tracker_name . ' = new window.ATInternet.Tracker.Tag(' . json_encode( $this->configuration ) . ');';
	}

	/**
	 * Set page tree structure.
	 * @access  private
	 * @since   1.0.0
	 * @return  string
	 */
	private function set_page() {
		return $this->tracker_name . '.page.set(' . json_encode( $this->tree_structure ) . ');';
	}

	/**
	 * Set internal search.
	 * @access  private
	 * @since   1.0.0
	 * @return  string
	 */
	private function set_internal_search() {
		return $this->tracker_name . '.internalSearch.set(' . json_encode( $this->internal_search ) . ');';
	}

	/**
	 * Dispatch tracker.
	 * @access  private
	 * @since   1.0.0
	 * @return  string
	 */
	private function tracker_dispatch() {
		return $this->tracker_name . '.dispatch();';
	}

	/**
	 * Display the full tracker.
	 * @access  public
	 * @since   1.0.0
	 * @return  string
	 */
	public function display($async) {
		if($async) echo $this->get_tracker_async();
		else echo $this->get_tracker();
	}

	/**
	 * Get the full tracker.
	 * @access  public
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_tracker() {
		$the_tracker = "<script type=\"text/javascript\">";
		$the_tracker .= "\n  " . $this->tracker_init();
		foreach ( $this->functionnalities as $functionnality ) {
			$the_tracker .= "\n  " . $this->$functionnality();
		}
		$the_tracker .= "\n  " . $this->tracker_dispatch();
		$the_tracker .= "\n</script>";

		return $the_tracker;
	}
	
	/**
	 * Get the full tracker.
	 * @access  public
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_tracker_async() {
		$the_tracker = "<script type=\"text/javascript\">";
		$the_tracker .= "\nwindow.ATInternet = {";
		$the_tracker .= "\n  onTrackerLoad:function(){";
		$the_tracker .= "\n    " . $this->tracker_init_async();
		foreach ( $this->functionnalities as $functionnality ) {
			$the_tracker .= "\n    " . $this->$functionnality();
		}
		$the_tracker .= "\n    " . $this->tracker_dispatch();
		$the_tracker .= "\n  }";
		$the_tracker .= "\n}";
		$the_tracker .= "\n</script>";

		return $the_tracker;
	}

}
