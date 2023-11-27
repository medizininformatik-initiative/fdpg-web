<?php
/**
 * User first name merge tag
 *
 * Requirements:
 * - Trigger property `user_object` or any other passed as
 * `property_name` parameter. Must be an object, preferabely WP_User
 *
 * @package notification
 */

namespace BracketSpace\Notification\Defaults\MergeTag\User;

use BracketSpace\Notification\Defaults\MergeTag\StringTag;

/**
 * User first name merge tag class
 */
class UserFirstName extends StringTag {
	/**
	 * Merge tag constructor
	 *
	 * @since 5.0.0
	 * @param array $params merge tag configuration params.
	 */
	public function __construct( $params = [] ) {

		$this->set_trigger_prop( $params['property_name'] ?? 'user_object' );

		$args = wp_parse_args(
			$params,
			[
				'slug'        => 'user_first_name',
				'name'        => __( 'User first name', 'notification' ),
				'description' => __( 'John', 'notification' ),
				'example'     => true,
				'group'       => __( 'User', 'notification' ),
				'resolver'    => function ( $trigger ) {
					return $trigger->{ $this->get_trigger_prop() }->first_name;
				},
			]
		);

		parent::__construct( $args );

	}

}
