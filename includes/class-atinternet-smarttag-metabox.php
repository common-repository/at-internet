<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class ATInternet_metabox {
	private $screens = array(
		'post',
		'page',
	);
	private $fields = array();

	/**
	 * Class construct method. Adds actions to their respective WordPress hooks.
	 */
	public function __construct() {
		array_push( $this->fields,
			array(
				'id' => 'level2',
				'label' => __( 'Level 2', 'at-internet' ),
				'type' => 'text'
			)
		);
		if( get_option('ati_label_type', '') == 'custom') {
			array_push( $this->fields,
				array(
					'id' => 'chapter1',
					'label' => __( 'Chapter 1', 'at-internet' ),
					'type' => 'text'
				),
				array(
					'id' => 'chapter2',
					'label' => __( 'Chapter 2', 'at-internet' ),
					'type' => 'text'
				),
				array(
					'id' => 'chapter3',
					'label' => __( 'Chapter 3', 'at-internet' ),
					'type' => 'text'
				),
				array(
					'id' => 'name',
					'label' => __( 'Name', 'at-internet' ),
					'type' => 'text'
				)
			);
		}

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
	}

	/**
	 * Hooks into WordPress' add_meta_boxes function.
	 * Goes through screens (post types) and adds the meta box.
	 */
	public function add_meta_boxes() {
		foreach ( $this->screens as $screen ) {
			add_meta_box(
				'at-internet',
				__( 'AT Internet', 'at-internet' ),
				array( $this, 'add_meta_box_callback' ),
				$screen,
				'side',
				'high'
			);
		}
	}

	/**
	 * Generates the HTML for the meta box
	 *
	 * @param object $post WordPress post object
	 */
	public function add_meta_box_callback( $post ) {
		wp_nonce_field( 'at_internet_data', 'at_internet_nonce' );
		$this->generate_fields( $post );
	}

	/**
	 * Generates the field's HTML for the meta box.
	 */
	public function generate_fields( $post ) {
		$output = '';
		foreach ( $this->fields as $field ) {
			$label = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
			$db_value = get_post_meta( $post->ID, 'ati_' . $field['id'], true );
			switch ( $field['type'] ) {
				default:
					$input = sprintf(
						'<input id="%s" name="%s" type="%s" value="%s">',
						$field['id'],
						$field['id'],
						$field['type'],
						$db_value
					);
			}
			$output .= '<p>' . $label . '<br>' . $input . '</p>';
		}
		echo $output;
	}

	/**
	 * Hooks into WordPress' save_post function
	 */
	public function save_post( $post_id ) {
		if ( ! isset( $_POST['at_internet_nonce'] ) )
			return $post_id;

		$nonce = $_POST['at_internet_nonce'];
		if ( !wp_verify_nonce( $nonce, 'at_internet_data' ) )
			return $post_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		foreach ( $this->fields as $field ) {
			if ( isset( $_POST[ $field['id'] ] ) ) {
				switch ( $field['type'] ) {
					case 'email':
						$_POST[ $field['id'] ] = sanitize_email( $_POST[ $field['id'] ] );
						break;
					case 'text':
						$_POST[ $field['id'] ] = sanitize_text_field( $_POST[ $field['id'] ] );
						break;
				}
				update_post_meta( $post_id, 'ati_' . $field['id'], $_POST[ $field['id'] ] );
			} else if ( $field['type'] === 'checkbox' ) {
				update_post_meta( $post_id, 'ati_' . $field['id'], '0' );
			}
		}
	}
}
