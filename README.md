## CMB2 Field Address 

Just a simple, repeatable address field.  It's really just the snippet from [CMB2 Snippet Library](https://github.com/CMB2/CMB2-Snippet-Library) converted to a plugin. 

## Installation

Install CMB2 from the [WordPress plugin directory](https://wordpress.org/plugins/CMB2/).  Then install this as a regular plugin.

## Usage

1. Instantiate a metabox in the [usual way](https://github.com/CMB2/CMB2/wiki/Basic-Usage).  
2. Add the address field.

```
function cmb2_sample_metaboxes() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_yourprefix_';

	/**
	 * Initiate the metabox
	 */
	$cmb = new_cmb2_box( array(
		'id'            => 'address_metabox',
		'title'         => __( 'Address', 'cmb2' ),
		'object_types'  => array( 'page', ), // Post type
		'context'       => 'sidebar',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

	// Address field
	$cmb->add_field( array(
		'name'       => __( 'Address Field', 'cmb2' ),
		'desc'       => __( 'field description (optional)', 'cmb2' ),
		'id'         => $prefix . 'address',
		'type'       => 'address',
		// 'repeatable'      => true,
	) );
}
```

![Address field](https://github.com/scottsawyer/cmb2-field-address/raw/master/assets/images/screenshot-demo.wp-builder.net-2018.10.08-14-36-05.png "Example Address field")

To access your address:

```
$address = get_post_meta( get_the_ID(), $prefix . 'address', true );

print_r( $addrsss );

// Address array
Array
(
  [location] => Company Address
  [address-1] => 123 Sesame Street
  [address-2] => Suite 120
  [city] => Sesame
  [state] => PA
  [zip] => 12345
)
```
Or if it's a repeater:
```
print_r( $address );

// Array of Addresses
Array
(
  [0] => Array
      (
          [location] => Company Address
          [address-1] => 123 Sesame Street
          [address-2] => Suite 120
          [city] => Sesame
          [state] => PA
          [zip] => 12345
      )

)
```

