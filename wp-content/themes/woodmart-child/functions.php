<?php

/**
 * Enqueue script and styles for child theme
 */
function woodmart_child_enqueue_styles()
{
	wp_enqueue_style(
		'child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array('woodmart-style'),
		woodmart_get_theme_info('Version')
	);

	wp_enqueue_style( 'select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), '4.1.0-rc.0');

	wp_enqueue_style(
		'font-gilroy',
		get_stylesheet_directory_uri() . '/assets/fonts/Gilroy/stylesheet.css',
		array(),
		'1.0.0'
	);
}
add_action('wp_enqueue_scripts', 'woodmart_child_enqueue_styles', 10010);


add_action('wp_enqueue_scripts', 'mcm_enqueue_scripts');
function mcm_enqueue_scripts()
{

	// *** SCRIPT
	wp_enqueue_script(
		'mcm-custom-scripts',
		get_stylesheet_directory_uri() . '/main.js',
		array(),
		'1.2',
		false
	);

	wp_enqueue_script('slick-js', get_stylesheet_directory_uri() . '/slick.js', array('jquery'), '', true);
	wp_enqueue_style('slick-css', get_stylesheet_directory_uri() . '/slick.css');
	//Add the Select2 CSS file

	//Add the Select2 JavaScript file
	wp_enqueue_script( 'select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', 'jquery', '4.1.0-rc.0');

	//Add a JavaScript file to initialize the Select2 elements
	wp_enqueue_script( 'select2-init', '/wp-content/plugins/select-2-tutorial/select2-init.js', 'jquery', '4.1.0-rc.0');
	wp_localize_script('mcm-custom-scripts', 'MCM', array(
		'ajax_url' 		=> admin_url('admin-ajax.php'),
		'nonce'         => wp_create_nonce('mcm_nonce'),
		'page_url'      => get_site_url(),
		'theme_url'     => get_stylesheet_directory_uri(),
	));
}


add_filter('woocommerce_product_tabs', 'remove_product_tabs', 9999);

function remove_product_tabs($tabs)
{

	unset($tabs['description']);
	unset($tabs['additional_information']);
	unset($tabs['reviews']);

	return $tabs;
}



function shortcoder_mostrar_garantia()
{
	return "<p><strong>Garantía</strong>" . get_field('garantia') . "</p>";
}
add_shortcode('garantia', 'shortcoder_mostrar_garantia');



add_shortcode('envio', 'shortcoder_mostrar_envio');

function shortcoder_mostrar_envio()
{
	return "<p><strong>Envío </strong>a" . get_field('envio') . "</p>";
}


add_shortcode('mantenimiento', 'shortcoder_mostrar_mantenimiento');

function shortcoder_mostrar_mantenimiento()
{
	return "<p><strong>Mantenimiento " . get_field('mantenimiento') . "</strong></p>";
}

/*****************************************************************************************/
add_filter('wpseo_show_reading_time', '__return_false');


// Agregar el campo checkbox y el campo descuento al formulario del producto
add_action('woocommerce_after_add_to_cart_button', 'add_discount_checkbox');

function add_discount_checkbox()
{
	$descuento = get_field('discount');
	if (isset($descuento)){
		echo '<div class="woocommerce-verificacion">
			<label for="cbx" class="cbx">
			<div class="checkmark">
			<input type="checkbox" id="cbx" name="verificacion" value="1">
			<div class="flip">
			<div class="front"></div>
			<div class="back">
			<svg viewBox="0 0 16 14" height="14" width="16">
			<path d="M2 8.5L6 12.5L14 1.5"></path>
			</svg>
			</div>
			</div>
			</div>
			<span>Quiero un descuento adicional por mi batería vieja</span>
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M174.7 45.1C192.2 17 223 0 256 0s63.8 17 81.3 45.1l38.6 61.7 27-15.6c8.4-4.9 18.9-4.2 26.6 1.7s11.1 15.9 8.6 25.3l-23.4 87.4c-3.4 12.8-16.6 20.4-29.4 17l-87.4-23.4c-9.4-2.5-16.3-10.4-17.6-20s3.4-19.1 11.8-23.9l28.4-16.4L283 79c-5.8-9.3-16-15-27-15s-21.2 5.7-27 15l-17.5 28c-9.2 14.8-28.6 19.5-43.6 10.5c-15.3-9.2-20.2-29.2-10.7-44.4l17.5-28zM429.5 251.9c15-9 34.4-4.3 43.6 10.5l24.4 39.1c9.4 15.1 14.4 32.4 14.6 50.2c.3 53.1-42.7 96.4-95.8 96.4L320 448v32c0 9.7-5.8 18.5-14.8 22.2s-19.3 1.7-26.2-5.2l-64-64c-9.4-9.4-9.4-24.6 0-33.9l64-64c6.9-6.9 17.2-8.9 26.2-5.2s14.8 12.5 14.8 22.2v32l96.2 0c17.6 0 31.9-14.4 31.8-32c0-5.9-1.7-11.7-4.8-16.7l-24.4-39.1c-9.5-15.2-4.7-35.2 10.7-44.4zm-364.6-31L36 204.2c-8.4-4.9-13.1-14.3-11.8-23.9s8.2-17.5 17.6-20l87.4-23.4c12.8-3.4 26 4.2 29.4 17L182 241.2c2.5 9.4-.9 19.3-8.6 25.3s-18.2 6.6-26.6 1.7l-26.5-15.3L68.8 335.3c-3.1 5-4.8 10.8-4.8 16.7c-.1 17.6 14.2 32 31.8 32l32.2 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-32.2 0C42.7 448-.3 404.8 0 351.6c.1-17.8 5.1-35.1 14.6-50.2l50.3-80.5z"/></svg>
			</label>
			</div>';
		echo '<div class="woocommerce-descuento">';

		// Obtener el valor del campo personalizado "descuento" creado con ACF
		$descuento = get_field('discount');

		echo '<input type="hidden" name="descuento" id="descuento" value="' . esc_attr($descuento) . '" />';
		echo '</div>';
	}
}

// *************************************************************************************************
// Agrega este código a tu archivo functions.php de tu tema o en un plugin personalizado

// Función para aplicar descuentos a productos específicos
function aplicar_descuento_a_productos($cart) {
	if (is_admin() && !defined('DOING_AJAX')) {
		return;
	}

	foreach ($cart->get_cart() as $item_key => $item) {
		$producto_id = $item['product_id'];
		$descuento = 50;
		// 		$precio_actual = floatval($item['data']->get_price());
		$precio_actual = floatval(wc_get_price_including_tax($item['data']));
		$nuevo_precio = $precio_actual - get_field('discount',$producto_id);
		$item['data']->set_price($nuevo_precio);
	}
}

// add_action('woocommerce_calculate_fees', 'aplicar_descuento_a_productos', 10, 1);


// Mostrar el descuento en el carrito y el checkout
function mostrar_descuento_en_carrito_y_checkout($item_data, $cart_item) {
	if (array_key_exists('descuento', $cart_item)) {
		$descuento = get_field('discount',$cart_item['product_id']);
		$item_data[] = array(
			'key'   => '<strong>Descuento por reciclaje</strong>',
			'value' => "Baterías <strong>x " . $cart_item['quantity'].'</strong>',
		);
		// 	. '<br>' . "<strong>Precio normal: </strong> $" .($cart_item['data']->get_price()+$descuento)
	}
	$zonas_envio = WC_Shipping_Zones::get_zone(5);
	$locations = $zonas_envio->get_zone_locations();

	$x = array_filter($locations,'zone_filter');
	if (count($x)!=0){
		return $item_data;
	}
}

// Descuento en carrito
add_filter('woocommerce_get_item_data', 'mostrar_descuento_en_carrito_y_checkout', 10, 2);


/** ******************************************************************************
 * 
 * Cambiar precio pagando con tarjeta de credito/debito
 * 
 */

// Actualizar precio en el carrito con el precio con tarjeta
function check_payment_method_card( $cart ) {
	global $text;
	global $items;
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return;
	}

	$chosen_payment_method = WC()->session->get('chosen_payment_method');
	if($chosen_payment_method == 'payphone' || $chosen_payment_method == 'pg_woocommerce') {
		foreach ( $cart->get_cart() as $cart_item) {
			$price_with_card = get_field('price_card', $cart_item['product_id']);
			if ( !empty( $price_with_card ) ) {
				if (array_key_exists('descuento', $cart_item)) {
					$descuento = get_field('discount',$cart_item['product_id']);
// 					$cart_item['data']->set_price( $price_with_card - floatval($descuento));
				}else{
					$cart_item['data']->set_price( $price_with_card );

				}
			}
		}
	}else{
		foreach ( $cart->get_cart() as $cart_item) {
			$price_with_card = $cart_item['data']->get_price();
			if ( !empty( $price_with_card ) ) {
				if (array_key_exists('descuento', $cart_item)) {
					$descuento = get_field('discount',$cart_item['product_id']);
					$cart_item['data']->set_price( $price_with_card - floatval($descuento));
				}else{
					$cart_item['data']->set_price( $price_with_card );

				}
// 				$cart_item['data']->set_price( $price_with_card );
			}
		}
	}
	
	

}
add_action( 'woocommerce_before_calculate_totals', 'check_payment_method_card' );

add_filter('woocommerce_add_cart_item_data', 'add_item_data_cart', 10, 3);

function add_item_data_cart($cart_item_data, $product_id, $variation_id) {
	if (isset($_POST['descuento']) && isset($_POST['verificacion']) && $_POST['verificacion'] === '1') {
		$descuento = sanitize_text_field($_POST['descuento']);
		$product_key = WC()->cart->generate_cart_id($product_id);
		$cart_item_key = WC()->cart->find_product_in_cart($product_key);

		if ($cart_item_key) {
			WC()->cart->remove_cart_item($cart_item_key);
		}

		$cart_item_data['descuento'] = $descuento;
	}
	return $cart_item_data;
}

$shipping_address = array();
function zone_filter($zone){
	global $shipping_address;
	$shipping_address = array(
		'country'   => WC()->customer->get_shipping_country(),
		'state'     => WC()->customer->get_shipping_state(),
		'postcode'  => WC()->customer->get_shipping_postcode(),
		'city'      => WC()->customer->get_shipping_city(),
		'address'   => WC()->customer->get_shipping_address(),
	);
	$aux = "EC:".$shipping_address['state'];
	return strcmp($aux, $zone->code) == 0;
}
$text="";
$items="";
function agregar_descuento_general() {
	global $text;
	global $items;
	$descuento = 0;
	$quantity=0;
	$total_carrito = WC()->cart->subtotal;
	foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
		if (array_key_exists('descuento', $cart_item)) {
			$descuento += get_field('discount',$cart_item['product_id']) * $cart_item['quantity'];
			$quantity += $cart_item['quantity'];
		}
	}

	$zonas_envio = WC_Shipping_Zones::get_zone(5);
	$locations = $zonas_envio->get_zone_locations();

	$x = array_filter($locations,'zone_filter');
	if (count($x)!=0){
		$tax_rates = WC_Tax::get_rates_for_tax_class( 'standard' );
		$tax_rate = reset( $tax_rates );


		$descuento_or = $descuento / ( 1 + ( floatval($tax_rate->tax_rate) / 100 ) );
		if($descuento!=0){
			$text=$descuento;
			$items=$quantity;

			add_action( 'woocommerce_cart_totals_before_order_total', 'agregar_informacion_personalizada_en_totales_carrito' );
add_action( 'woocommerce_review_order_before_order_total', 'agregar_informacion_personalizada_en_totales_carrito' );
			
		}

		// 		WC()->cart->add_fee('Descuento por reciclaje x'. $quantity  , -$descuento_or,true, 'standard');
	}
}
add_action('woocommerce_cart_calculate_fees', 'agregar_descuento_general');


// --------------------------------------------------------------------------------------------------




function agregar_informacion_personalizada_en_totales_carrito() {
	global $text;
	global $items;
	echo '<tr><th>Descuento por reciclaje x'.$items.'</th><td><span class="woocommerce-Price-amount amount">$'.number_format($text, 2, '.', '').'</span></td></tr>';
}




// --------------------------------------------------------------------------------------------------


add_action( 'wp_footer', 'event_change_payment_method_checkout_script' );
function event_change_payment_method_checkout_script() {
	if ( is_checkout()) : ?>
<script type="text/javascript">
	jQuery( function($){
		$('form.checkout').on('change', 'input[name="payment_method"]', function(){
			$(document.body).trigger('update_checkout');
		});
	});
</script>
<?php
	endif;
}

if ( ! function_exists( 'woodmart_wc_empty_cart_message' ) ) {
	/**
         * Show notice if cart is empty.
         *
         * @since 1.0.0
         */
	function woodmart_wc_empty_cart_message() {
		//                 woodmart_enqueue_inline_style( 'woo-page-empty-page' );

?>
<p class="cart-empty wd-empty-page wc-empty-cart-message">
	<?php echo wp_kses_post( apply_filters( 'wc_empty_cart_message', __( 'Your cart is currently empty.', 'woocommerce' ) ) ); ?>
</p>
<?php
	}

	add_action( 'woocommerce_cart_is_empty', 'woodmart_wc_empty_cart_message', 10 );
}


add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');

function custom_override_checkout_fields($fields) {
	// Elimina el campo del código postal (postcode)
	unset($fields['billing']['billing_postcode']);
	unset($fields['shipping']['shipping_postcode']);

	return $fields;
}
require_once 'shortcodes_filters/index.php';
// require_once 'checkout_field.php';