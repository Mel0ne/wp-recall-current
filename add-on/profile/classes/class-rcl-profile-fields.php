<?php

class Rcl_Profile_Fields_Manager extends Rcl_Fields_Manager {
	function __construct() {
		global $wpdb;

		add_filter( 'rcl_field_options', array( $this, 'edit_field_options' ), 10, 3 );

		parent::__construct( 'profile', array(
			'option_name'	 => 'rcl_profile_fields',
			'empty_field'	 => false,
			'structure_edit' => false,
			'meta_delete'	 => array(
				$wpdb->usermeta => 'meta_key'
			),
			'default_fields' => apply_filters( 'rcl_default_profile_fields', array(
				array(
					'slug'	 => 'first_name',
					'title'	 => __( 'Firstname', 'wp-recall' ),
					'icon'	 => 'fa-user',
					'type'	 => 'text'
				),
				array(
					'slug'	 => 'last_name',
					'title'	 => __( 'Surname', 'wp-recall' ),
					'icon'	 => 'fa-user',
					'type'	 => 'text'
				),
				array(
					'slug'	 => 'display_name',
					'title'	 => __( 'Name to be displayed', 'wp-recall' ),
					'icon'	 => 'fa-user',
					'type'	 => 'text'
				),
				array(
					'slug'	 => 'user_url',
					'title'	 => __( 'Website', 'wp-recall' ),
					'icon'	 => 'fa-link',
					'type'	 => 'url'
				),
				array(
					'slug'	 => 'description',
					'title'	 => __( 'Status', 'wp-recall' ),
					'icon'	 => 'fa-comment',
					'type'	 => 'textarea'
				),
				array(
					'slug'	 => 'birthday',
					'title'	 => __( 'Birthday', 'wp-recall' ),
					'icon'	 => 'fa-birthday-cake',
					'type'	 => 'date'
				) )
			),
			'field_options'	 => apply_filters( 'rcl_profile_field_options', array(
				array(
					'slug'	 => 'notice',
					'type'	 => 'textarea',
					'title'	 => __( 'field description', 'wp-recall' )
				),
				array(
					'slug'	 => 'required',
					'type'	 => 'radio',
					'title'	 => __( 'required field', 'wp-recall' ),
					'values' => array( __( 'No', 'wp-recall' ), __( 'Yes', 'wp-recall' ) )
				),
				array(
					'slug'	 => 'public_value',
					'type'	 => 'radio',
					'title'	 => __( 'show the content to other users', 'wp-recall' ),
					'values' => array( __( 'No', 'wp-recall' ), __( 'Yes', 'wp-recall' ) )
				),
				array(
					'slug'	 => 'admin',
					'type'	 => 'radio',
					'title'	 => __( 'can be changed only by the site administration', 'wp-recall' ),
					'values' => array( __( 'No', 'wp-recall' ), __( 'Yes', 'wp-recall' ) )
				),
				array(
					'slug'	 => 'filter',
					'type'	 => 'radio',
					'title'	 => __( 'Filter users by this field', 'wp-recall' ),
					'values' => array( __( 'No', 'wp-recall' ), __( 'Yes', 'wp-recall' ) )
				)
			) )
		) );

		$this->setup_default_fields();
	}

	function edit_field_options( $options, $field, $manager_id ) {

		if ( ! $field->id || $manager_id != $this->manager_id )
			return $options;

		$defaultFields = array(
			'first_name',
			'last_name',
			'display_name',
			'url',
			'description'
		);

		if ( in_array( $field->id, $defaultFields ) ) {
			unset( $options['filter'] );
			unset( $options['public_value'] );
		} else if ( in_array( $field->type, array( 'editor', 'uploader' ) ) ) {
			unset( $options['filter'] );
		}

		if ( $field->type == 'uploader' ) {
			unset( $options['filter'] );
			unset( $options['required'] );
			unset( $options['admin'] );
		}

		return $options;
	}

	function get_manager_options_form_fields() {

		$fields = array(
			'users_page_rcl' => array(
				'type'		 => 'custom',
				'title'		 => __( 'Users page', 'wp-recall' ),
				'notice'	 => __( 'This page is required to filter users by value of profile fields', 'wp-recall' ),
				'content'	 => wp_dropdown_pages( array(
					'selected'			 => rcl_get_option( 'users_page_rcl' ),
					'name'				 => 'users_page_rcl',
					'show_option_none'	 => __( 'Not selected', 'wp-recall' ),
					'echo'				 => 0
					)
				)
			) );

		return $fields;
	}

}
