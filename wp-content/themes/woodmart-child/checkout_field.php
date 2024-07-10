<?php



function vicode_custom_checkout_fields($fields)

{

    $fields['billing']['cedula'] = array(

        'type' => 'text',

        'label' => 'Cédula',

        'placeholder' => 'Por favor ingresa tu número de cédula',

        'required' => true,

        'class' => array('form-row-wide'),

        'priority' => 35, // Ajusta la prioridad para ubicar el campo

    );



    return $fields;
}

add_filter('woocommerce_checkout_fields', 'vicode_custom_checkout_fields');









// saving data

function vicodemedia_save_extra_checkout_fields($order_id, $posted)

{

    // don't forget appropriate sanitization

    if (isset($posted['cedula'])) {

        update_post_meta($order_id, '_cedula', sanitize_text_field($posted['cedula']));
        setcookie('_cedula', sanitize_text_field($posted['cedula']), time() + (86400 * 30), '/');
    }
}

add_action('woocommerce_checkout_update_order_meta', 'vicodemedia_save_extra_checkout_fields', 10, 2);



add_action('woocommerce_before_checkout_form', 'prefill_custom_checkout_field');

function prefill_custom_checkout_field()
{
    if (isset($_COOKIE['_cedula'])) {
        $_POST['cedula'] = $_COOKIE['_cedula'];
    }
}

// Función para validar el campo extra
function validate_custom_checkout_fields($posted) {
    if (!empty($posted['cedula']) && !preg_match('/^[0-9]+$/', $posted['cedula'])) {
        wc_add_notice(__('Por favor, introduce una cédula válida.'), 'error');
    }
}
add_action('woocommerce_checkout_process', 'validate_custom_checkout_fields');



// Display the Data to User

function vicodemedia_display_order_data($order_id)

{  ?>

    <p class="woocommerce-customer-details--email">
        <?php echo get_post_meta($order_id, '_cedula', true); ?>
    </p>


<?php }

add_action('woocommerce_thankyou', 'vicodemedia_display_order_data', 10);

add_action('woocommerce_view_order', 'vicodemedia_display_order_data', 20);







// display data on the Dashboard WC order details page

function vicodemedia_display_order_data_in_admin($order)

{  ?>
    <div class="order_data_column">
        <h4><?php _e('Información esencial', 'woocommerce'); ?></h4>
        <div class="address">
            <?php
            // Mostrar el campo personalizado
            echo '<p><strong>' . __('Cédula') . ':</strong>' . get_post_meta($order->id, '_cedula', true) . '</p>';
            ?>
        </div>
    </div>
<?php
}

add_action('woocommerce_admin_order_data_after_order_details', 'vicodemedia_display_order_data_in_admin');



function vicodemedia_save_extra_details($post_id, $post)

{

    update_post_meta($post_id, '_cedula', wc_clean($_POST['_cedula']));
}

// save data from admin

add_action('woocommerce_process_shop_order_meta', 'vicodemedia_save_extra_details', 45, 2);









// add the field to email template

function vicodemedia_email_order_meta_fields($fields, $sent_to_admin, $order)

{

    $fields['instagram'] = array(

        'label' => __('Cédula'),

        'value' => get_post_meta($order->id, '_cedula', true),

    );

    return $fields;
}

add_filter('woocommerce_email_order_meta_fields', 'vicodemedia_email_order_meta_fields', 10, 3);
