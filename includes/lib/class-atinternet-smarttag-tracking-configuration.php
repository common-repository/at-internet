<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class ATInternet_SmartTag_Tracking_Configuration {

	/**
	 * Collect domain.
	 * @var 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public $domain = "xiti.com";

	/**
	 * Collect log.
	 * @var 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public $log = null;

	/**
	 * Secured collect log.
	 * @var 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public $logSSL = null;

	/**
	 * Site number.
	 * @var 	int
	 * @access  public
	 * @since 	1.0.0
	 */
	public $site = null;

	/**
	 * SSL only?
	 * @var 	boolean
	 * @access  public
	 * @since 	1.0.0
	 */
	public $secure = false;

	public function __construct () {}

}
