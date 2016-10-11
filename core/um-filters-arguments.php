<?php

	/***
	***	@conditional logout form
	***/
	add_filter('um_shortcode_args_filter', 'um_display_logout_form', 99);
	function um_display_logout_form( $args ) {
		global $ultimatemember;

		if ( is_user_logged_in() && isset( $args['mode'] ) && $args['mode'] == 'login' ) {
			
			if ( get_current_user_id() != um_user('ID' ) ) {
				um_fetch_user( get_current_user_id() );
			}
			
			$args['template'] = 'logout';
		
		}
		
		return $args;
		
	}
	
	/***
	***	@filter for shortcode args
	***/
	add_filter('um_shortcode_args_filter', 'um_shortcode_args_filter', 99);
	function um_shortcode_args_filter( $args ) {
		global $ultimatemember;

		if ($ultimatemember->shortcodes->message_mode == true) {
			$args['template'] = 'message';

			if (isset($_REQUEST['uid'])) {
				um_fetch_user( $_REQUEST['uid'] );

				$ultimatemember->shortcodes->custom_message = um_user( um_user('status')  . '_message' );


				um_reset_user();
			} else {
				if (isset($_REQUEST['message']) && $_REQUEST['message'] == 'checkmail')
					$ultimatemember->shortcodes->custom_message = $ultimatemember->query->get_meta_value('_um_checkmail_message', null, __('Thank you for registering. Before you can login we need you to activate your account by clicking the activation link in the email we just sent you.','ultimatemember'));
				else
					$ultimatemember->shortcodes->custom_message = 'Error displaying message';
			}
		}
		
		foreach( $args as $k => $v ) {
			if ( $ultimatemember->validation->is_serialized( $args[$k] ) ) {
				if ( !empty( $args[$k] ) ) {
					$args[$k] = unserialize( $args[$k] );
				}
			}
		}
		
		return $args;
		
	}