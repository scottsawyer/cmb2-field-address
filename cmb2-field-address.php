<?php
/**
 * @package	CMB2\Field_Address
 * @author 	scottsawyer
 * @copyright	Copyright (c) scottsawyer
 *
 * Plugin Name: CMB2 Field Type: Address
 * Plugin URI: https://github.com/scottsawyer/cmb2-field-address
 * Github Plugin URI: https://github.com/scottsawyer/cmb2-field-address
 * Description: CMB2 field type to create an address.
 * Version: 1.0
 * Author: scottsawyer
 * Author URI: https://www.scottsawyerconsulting.com
 * License: GPLv2+
 */

if ( !defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'CMB2_Field_Address' ) ) {
  /**
   * Class CMB2_Field_Address
   */
  class CMB2_Field_Address {
    
    /**
     * Current version number
     */
    const VERSION = '1.0.0';
	  /**
		 * List of states. To translate, pass array of states in the 'state_list' field param.
		 *
		 * @var array
		 */
	  protected static $state_list = ['AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','DC'=>'District Of Columbia','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming'];
    /**
     * Initialize the plugin
     */
    public function __construct() {
    	//add_filter( 'cmb2_render_class_address', [$this, 'class_name'] ) ;
      add_action( 'cmb2_render_address', [$this, 'render_address'], 10, 5 );
      add_filter( 'cmb2_sanitize_address', [$this, 'maybe_save_split_values'], 12, 4 );
      add_filter( 'cmb2_sanitize_address', [$this, 'sanitize'], 10, 5 );
      add_filter( 'cmb2_types_esc_address', [$this, 'escape'], 10, 4 );
    }    

    //public static function class_name() { return __CLASS__; }

		/**
		 * Handles outputting the address field.
		 */
    public static function render_address( $field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object ) {

    	$field_escaped_value = wp_parse_args( $field_escaped_value, [
    		'location'	=> '',
	    	'address-1' => '',
				'address-2' => '',
				'city'      => '',
				'state'     => '',
				'zip'       => '',
				]
			);

			$state_label = 'State';

			$state_list = self::$state_list;

				// Add the "label" option. Can override via the field text param
			$state_list = ['' => esc_html( 'Select a State' ) ] + $state_list;
			$state_options = '';
			foreach ( $state_list as $abrev => $state ) {
				$state_options .= '<option value="' . $abrev . '" ' . selected( $field_escaped_value['state'], $abrev, false ) . '>' . $state . '</option>';
			}

			?>
			<div><p><label for="<?= $field_type_object->_id( '_location', false ); ?>"><?= esc_html(  'Location' ); ?></label></p>
				<?= $field_type_object->input( [
					'type'	=> 'text',
					'name'  => $field_type_object->_name( '[location]' ),
					'id'    => $field_type_object->_id( '_location' ),
					'value' => $field_escaped_value['location'],
					'desc'  => '',
				] ); ?>
			</div>			
			<div><p><label for="<?= $field_type_object->_id( '_address_1', false ); ?>"><?= esc_html(  'Address 1' ); ?></label></p>
				<?= $field_type_object->input( [
					'type'	=> 'text',
					'name'  => $field_type_object->_name( '[address-1]' ),
					'id'    => $field_type_object->_id( '_address_1' ),
					'value' => $field_escaped_value['address-1'],
					'desc'  => '',
				] ); ?>
			</div>
			<div><p><label for="<?= $field_type_object->_id( '_address_2', false ); ?>'"><?= esc_html(  'Address 2' ); ?></label></p>
				<?= $field_type_object->input( [
					'type'	=> 'text',
					'name'  => $field_type_object->_name( '[address-2]' ),
					'id'    => $field_type_object->_id( '_address_2' ),
					'value' => $field_escaped_value['address-2'],
					'desc'  => '',
				] ); ?>
			</div>
			<div style="overflow: hidden;">
				<div class="alignleft"><p><label for="<?= $field_type_object->_id( '_city', false ); ?>'"><?= esc_html( 'City' ); ?></label></p>
					<?= $field_type_object->input( [
						'type'	=> 'text',
						'class' => 'cmb_text_small',
						'name'  => $field_type_object->_name( '[city]' ),
						'id'    => $field_type_object->_id( '_city' ),
						'value' => $field_escaped_value['city'],
						'desc'  => '',
					] ); ?>
				</div>
				<div class="alignleft"><p><label for="<?= $field_type_object->_id( '_state', false ); ?>'"><?= esc_html(  $state_label ); ?></label></p>
					
						<?= $field_type_object->select( [
							'name'    => $field_type_object->_name( '[state]' ),
							'id'      => $field_type_object->_id( '_state' ),
							'value' 	=> $field_escaped_value['state'],
							'options' => $state_options,
							'desc'    => '',
						] ); ?>
					
				</div>
				<div class="alignleft"><p><label for="<?= $field_type_object->_id( '_zip', false ); ?>'"><?= esc_html( 'Zip' ); ?></label></p>
					<?= $field_type_object->input( [
						'type'  => 'number',
						'class' => 'cmb_text_small',
						'name'  => $field_type_object->_name( '[zip]' ),
						'id'    => $field_type_object->_id( '_zip' ),
						'value' => $field_escaped_value['zip'],
						'desc'  => '',
					] ); ?>
				</div>
			</div>
			
			<?php
			$field_type_object->_desc( 'true' );
			
    }

    /**
		 * Optionally save the Address values into separate fields
		 */
		public static function maybe_save_split_values( $override_value, $value, $object_id, $field_args ) {
			if ( ! isset( $field_args['split_values'] ) || ! $field_args['split_values'] ) {
				// Don't do the override
				return $override_value;
			}
			$address_keys = ['address-1', 'address-2', 'city', 'state', 'zip'];
			foreach ( $address_keys as $key ) {
				if ( ! empty( $value[ $key ] ) ) {
					update_post_meta( $object_id, $field_args['id'] . 'addr_'. $key, sanitize_text_field( $value[ $key ] ) );
				}
			}
			remove_filter( 'cmb2_sanitize_address', [ $this, 'sanitize' ], 10, 5 );
			// Tell CMB2 we already did the update
			return true;
		}

		public static function sanitize( $check, $meta_value, $object_id, $field_args, $sanitize_object ) {
			// if not repeatable, bail out.
			if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] ) {
				return $check;
			}
			foreach ( $meta_value as $key => $val ) {
				$meta_value[ $key ] = array_filter( array_map( 'sanitize_text_field', $val ) );
			}
			return array_filter($meta_value);
		}


		public static function escape( $check, $meta_value, $field_args, $field_object ) {
			// if not repeatable, bail out.
			if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] ) {
				return $check;
			}
			foreach ( $meta_value as $key => $val ) {
				$meta_value[ $key ] = array_filter( array_map( 'esc_attr', $val ) );
			}
			return array_filter($meta_value);
		}
	}
	$cmb2_field_address = new CMB2_Field_Address();
}

?>