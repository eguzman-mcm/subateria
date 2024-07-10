<?php

/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

$product_images_attr = $product_image_summary_class = '';

$product_images_class      = woodmart_product_images_class();
$product_summary_class     = woodmart_product_summary_class();
$single_product_class      = woodmart_single_product_class();
$content_class             = woodmart_get_content_class();
$product_design         = woodmart_product_design();
$breadcrumbs_position     = woodmart_get_opt('single_breadcrumbs_position');
$image_width             = woodmart_get_opt('single_product_style');
$full_height_sidebar    = woodmart_get_opt('full_height_sidebar');
$page_layout            = woodmart_get_opt('single_product_layout');
$tabs_location             = woodmart_get_opt('product_tabs_location');
$reviews_location         = woodmart_get_opt('reviews_location');


add_action('woocommerce_template_single_title', 'woocommerce_template_single_title', 5);
add_action('woocommerce_template_single_rating', 'woocommerce_template_single_rating', 10);
add_action('woocommerce_template_single_price', 'woocommerce_template_single_price', 10);
add_action('woocommerce_template_single_excerpt', 'woocommerce_template_single_excerpt', 20);
add_action('woocommerce_template_single_add_to_cart', 'woocommerce_template_single_add_to_cart', 30);
add_action('woocommerce_template_single_meta', 'woocommerce_template_single_meta', 40);
add_action('woocommerce_template_single_sharing', 'woodmart_product_share_buttons', 50);

function atributos($product)
{
	$product_attributes = array();

	// Display weight and dimensions before attribute list.
	$display_dimensions = apply_filters('wc_product_enable_dimensions_display', $product->has_weight() || $product->has_dimensions());

	if ($display_dimensions && $product->has_weight()) {
		$product_attributes['weight'] = array(
			'label' => __('Weight', 'woocommerce'),
			'value' => wc_format_weight($product->get_weight()),
		);
	}

	if ($display_dimensions && $product->has_dimensions()) {
		$product_attributes['dimensions'] = array(
			'label' => __('Dimensions', 'woocommerce'),
			'value' => wc_format_dimensions($product->get_dimensions(false)),
		);
	}

	// Add product attributes to list.
	$attributes = array_filter($product->get_attributes(), 'wc_attributes_array_filter_visible');

	foreach ($attributes as $attribute) {
		$values = array();

		if ($attribute->is_taxonomy()) {
			$attribute_taxonomy = $attribute->get_taxonomy_object();
			$attribute_values   = wc_get_product_terms($product->get_id(), $attribute->get_name(), array('fields' => 'all'));

			foreach ($attribute_values as $attribute_value) {
				$value_name = esc_html($attribute_value->name);

				if ($attribute_taxonomy->attribute_public) {
					$values[] = '<a href="' . esc_url(get_term_link($attribute_value->term_id, $attribute->get_name())) . '" rel="tag">' . $value_name . '</a>';
				} else {
					$values[] = $value_name;
				}
			}
		} else {
			$values = $attribute->get_options();

			foreach ($values as &$value) {
				$value = make_clickable(esc_html($value));
			}
		}

		$product_attributes['attribute_' . sanitize_title_with_dashes($attribute->get_name())] = array(
			'label' => wc_attribute_label($attribute->get_name()),
			'value' => apply_filters('woocommerce_attribute', wpautop(wptexturize(implode(', ', $values))), $attribute, $values),
		);
	}

	/**
     * Hook: woocommerce_display_product_attributes.
     *
     * @since 3.6.0.
     * @param array $product_attributes Array of atributes to display; label, value.
     * @param WC_Product $product Showing attributes for this product.
     */
	$product_attributes = apply_filters('woocommerce_display_product_attributes', $product_attributes, $product);

	return $product_attributes;
}

//Full width image layout
if ($image_width == 5) {
	$product_images_class .= ' vc_row vc_row-fluid vc_row-no-padding';
	$product_images_attr = 'data-vc-full-width="true" data-vc-full-width-init="true" data-vc-stretch-content="true"';
}

$container_summary = $container_class = $full_height_sidebar_container = 'container';

if ($full_height_sidebar && $page_layout != 'full-width') {
	$single_product_class[] = $content_class;
	$container_summary = 'container-none';
	$container_class = 'container-none';
	$product_image_summary_class = 'col-lg-12 col-md-12 col-12';
} else {
	$product_image_summary_class = $content_class;
}

if (woodmart_get_opt('single_full_width')) {
	$container_summary = 'container-fluid';
	$full_height_sidebar_container = 'container-fluid';
}

?>

<?php if ((($product_design == 'alt' && ($breadcrumbs_position == 'default' || empty($breadcrumbs_position))) || $breadcrumbs_position == 'below_header') && (woodmart_get_opt('product_page_breadcrumbs', '1') || woodmart_get_opt('products_nav'))) : ?>
<div class="single-breadcrumbs-wrapper">
	<div class="container">
		<?php if (woodmart_get_opt('product_page_breadcrumbs', '1')) : ?>
		<?php woodmart_current_breadcrumbs('shop'); ?>
		<?php endif; ?>

		<?php if (woodmart_get_opt('products_nav')) : ?>
		<?php woodmart_products_nav(); ?>
		<?php endif ?>
	</div>
</div>
<?php endif ?>

<div class="container">
	<?php
	/**
     * Hook: woocommerce_before_single_product.
     */
	do_action('woocommerce_before_single_product');

		if (post_password_required()) {
			echo get_the_password_form();
			return;
		}

	?>
</div>

<?php if ($full_height_sidebar && $page_layout != 'full-width') echo '<div class="' . $full_height_sidebar_container . '"><div class="row full-height-sidebar-wrap">'; ?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class($single_product_class, $product); ?>>

	<div class="<?php echo esc_attr($container_summary); ?>">

		<?php
		/**
         * Hook: woodmart_before_single_product_summary_wrap.
         *
         * @hooked woocommerce_output_all_notices - 10
         */
		do_action('woodmart_before_single_product_summary_wrap');
		?>

		<div class="row product-image-summary-wrap">
			<div class="product-image-summary <?php echo esc_attr($product_image_summary_class); ?>">
				<div class="row product-image-summary-inner cont-prod-single">
					<div class="col-lg-4 col-12 <?php //echo esc_attr($product_images_class); ?> product-images" <?php echo !empty($product_images_attr) ? $product_images_attr : ''; ?>>
						<div class="product-images-inner">
							<?php
							/**
                             * woocommerce_before_single_product_summary hook
                             *
                             * @hooked woocommerce_show_product_sale_flash - 10
                             * @hooked woocommerce_show_product_images - 20
                             */
							do_action('woocommerce_before_single_product_summary');
							?>
						</div>
					</div>
					<?php if ($image_width == 5) : ?>
					<div class="vc_row-full-width"></div>
					<?php endif ?>
					<div class="<?php //echo esc_attr($product_summary_class); ?> col-lg-8 col-12  summary entry-summary">
						<div class="summary-inner">
							<?php //if ((($product_design == 'default' && ($breadcrumbs_position == 'default' || empty($breadcrumbs_position))) || $breadcrumbs_position == 'summary') && (woodmart_get_opt('product_page_breadcrumbs', '1') || woodmart_get_opt('products_nav'))) : 
							?>
							<!-- <div class="single-breadcrumbs-wrapper">
<div class="single-breadcrumbs"> -->
							<?php //if (woodmart_get_opt('product_page_breadcrumbs', '1')) : 
							?>
							<?php //woodmart_current_breadcrumbs('shop'); 
							?>
							<?php //endif; 
							?>

							<?php // if (woodmart_get_opt('products_nav')) : 
							?>
							<?php //woodmart_products_nav(); 
							?>
							<?php //endif 
							?>
							<!-- </div> -->
							<!-- </div> -->
							<?php //endif 
							?>

							<?php
	/**
                                 * woocommerce_single_product_summary hook
                                 *
                                 * @hooked woocommerce_template_single_title - 5
                                 * @hooked woocommerce_template_single_rating - 10
                                 * @hooked woocommerce_template_single_price - 10
                                 * @hooked woocommerce_template_single_excerpt - 20
                                 * @hooked woocommerce_template_single_add_to_cart - 30
                                 * @hooked woocommerce_template_single_meta - 40
                                 * @hooked woocommerce_template_single_sharing - 50
                                 * $title = do_action('woocommerce_template_single_title');
                                 * $rating = do_action('woocommerce_template_single_rating');
                                 * $price = do_action('woocommerce_template_single_price');
                                 * $excerpt = do_action('woocommerce_template_single_excerpt');
                                 * $cart = do_action('woocommerce_template_single_add_to_cart');
                                 * $meta = do_action('woocommerce_template_single_meta');
                                 * $sharing = do_action('woocommerce_template_single_sharing');
                                 */
	$prod = atributos($product);
					$stock_status = $product->get_stock_status();
					$stock_cant = $product->get_stock_quantity();
					$sku = $product->get_sku();
					$arr = (!empty($prod) && $prod['attribute_pa_marca'] && $prod['attribute_pa_marca']['value']) ? $prod['attribute_pa_marca']['value'] : array();
					$cat_list = wc_get_product_category_list($product->get_id(), $sep = ",");
					$cat_arr = get_the_terms($product->get_id(), 'product_cat');
					$prod = explode(" ", $product->get_attribute('marca'));
							?>

							<div class="single-prod-cont">
								<div class="item1">
									<?php do_action('woocommerce_template_single_title') ?>
									<?php do_action('woocommerce_template_single_excerpt') ?>
									<div class="custom-fields-style">

										<p><img src="<?php echo content_url(); ?>/uploads/2023/03/proteger.webp" alt="gar"><strong>Garantía</strong> <?php the_field('garantia'); ?></p>
										<p><img src="<?php echo content_url(); ?>/uploads/2023/03/entrega.webp" alt="gar"><strong>Envío</strong> a <?php the_field('envio'); ?></p>
										<p><img src="<?php echo content_url(); ?>/uploads/2023/03/mantenimiento.webp" alt="gar"><strong>Mantenimiento</strong> <?php the_field('mantenimiento'); ?></p>
									</div>
									<div class="detail-prices">
										<p class="price-pvp">
											<strong><?= __('Precio P.V.P', 'subateria') ?>: </strong>
											<span class="woocommerce-Price-amount amount">
												<bdi>
													<span class="woocommerce-Price-currencySymbol">$</span>
													<?=  $product->get_regular_price() ?>
												</bdi>
											</span>
										</p>

										<p class="price-with-cash">
											<strong><?= __('Pago con efectivo', 'subateria') ?>: </strong>
											<span class="woocommerce-Price-amount amount">
												<bdi>
													<span class="woocommerce-Price-currencySymbol">$</span>
													<?=  $product->get_sale_price(); ?>
												</bdi>
											</span>
										</p>
										<?php
										$price_card = get_field('price_card', $product->get_id());
										error_log($price_card);
										if($price_card) { ?>
										<p class="price-with-card">
											<strong><?= __('Pago con tarjeta', 'subateria') ?>: </strong>
											<span class="woocommerce-Price-amount amount">
												<bdi>
													<span class="woocommerce-Price-currencySymbol">$</span>
													<?= $price_card ?>
												</bdi>
											</span>
										</p>
										<?php }
										?>
									</div>
									<?php do_action('woocommerce_template_single_add_to_cart'); ?>
									<div class="alert-rec">
									</div>
									<?php echo wc_get_product_tag_list($product->get_id(), ' ‎ | ‎ ', '<span class="tagged_as"> <strong>Tags: </strong> ' .  ' ', '</span>'); ?>

								</div>
								<div class="item2">
									<div class="subitem1">
										<div class="stock-wpp-cont">
											<p class="<?php echo ($stock_cant != 0 ? "style-agotado" : "style-stock"); ?>">
												<?php if ($stock_cant != 0) {
	echo "<span>Agotado</span>";
} else {
// 	echo "<span>" . $stock_cant . " en stock </span>";
	echo "<span>Disponible</span>";
} ?>
											</p>
											<a href="#ctc_chat" id="ctc_chat"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg></a>
										</div>
										<p class="sku-style">
											<span><strong>Código</strong></span>
											<span><?php echo $sku ?></span>
										</p>

										<div class="rating-style">
											<span><strong>Rating</strong></span>
											<?php do_action('woocommerce_template_single_rating'); ?>
										</div>

									</div>
									<div class="subitem2">
										<?php echo do_shortcode('[html_block id="609"]');
										?>
									</div>

									<div class="subitem3">
										<div class="cont-share-links"><strong>Compartir </strong><?php echo do_shortcode('[social_buttons type="follow"]') ?></div>
									</div>

								</div>
							</div>

						</div>
					</div>
				</div><!-- .summary -->
			</div>

			<?php
	if (!$full_height_sidebar) {
		/**
                 * woocommerce_sidebar hook
                 *
                 * @hooked woocommerce_get_sidebar - 10
                 */
		do_action('woocommerce_sidebar');
	}
			?>

		</div>

		<?php
		/**
         * woodmart_after_product_content hook
         *
         * @hooked woodmart_product_extra_content - 20
         */
		do_action('woodmart_after_product_content');
		?>

	</div>

	<?php if ($tabs_location != 'summary' || $reviews_location == 'separate') : ?>
	<div class="product-tabs-wrapper">
		<div class="<?php echo esc_attr($container_class); ?>">
			<div class="row">
				<div class="col-12 poduct-tabs-inner">
					<?php
					/**
                         * woocommerce_after_single_product_summary hook
                         *
                         * @hooked woocommerce_output_product_data_tabs - 10
                         * @hooked woocommerce_upsell_display - 15
                         * @hooked woocommerce_output_related_products - 20
                         */
					do_action('woocommerce_after_single_product_summary');
					?>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<?php do_action('woodmart_after_product_tabs'); ?>

	<div class="<?php echo esc_attr($container_class); ?> related-and-upsells">
		<?php
		/**
         * woodmart_woocommerce_after_sidebar hook
         *
         * @hooked woocommerce_upsell_display - 10
         * @hooked woocommerce_output_related_products - 20
         */
		do_action('woodmart_woocommerce_after_sidebar');
		?></div>

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action('woocommerce_after_single_product'); ?>

<?php
if ($full_height_sidebar && $page_layout != 'full-width') {
	/**
     * woocommerce_sidebar hook
     *
     * @hooked woocommerce_get_sidebar - 10
     */
	do_action('woocommerce_sidebar');
}
?>

<?php if ($full_height_sidebar && $page_layout != 'full-width') echo '</div></div>'; ?>