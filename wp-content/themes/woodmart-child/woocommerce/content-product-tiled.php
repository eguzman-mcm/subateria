<?php
global $product;


do_action('woocommerce_before_shop_loop_item');
add_action('woocommerce_template_loop_price', 'woocommerce_template_loop_price', 10);
add_action('woocommerce_template_loop_rating', 'woocommerce_template_loop_rating', 5);
add_action('woocommerce_template_single_excerpt', 'woocommerce_template_single_excerpt', 20);
add_action('woocommerce_product_description_tab', 'woocommerce_product_description_tab');
add_action('woocommerce_show_product_loop_sale_flash','woocommerce_show_product_loop_sale_flash',10);
add_action('woodmart_template_loop_product_thumbnail','woodmart_template_loop_product_thumbnail',10);
?>


<div class="product-wrapper product-container">

	<?php do_action('woocommerce_show_product_loop_sale_flash')?>

	<div class="product-element-top">


		<div class="prod-title">
			<?php do_action('woocommerce_shop_loop_item_title'); ?>
		</div>

<!-- 		<div class="prod-desc" style="min-height: 45px;"> -->
<!-- 		/*  do_action('woocommerce_template_single_excerpt'); */ -->
<!-- 		</div> -->

		<a href="<?php echo esc_url(get_permalink()); ?>" class="product-image-link">
			<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woodmart_template_loop_product_thumbnail - 10
			 */

				do_action('woodmart_template_loop_product_thumbnail');
			?>
		</a>
		<?php woodmart_hover_image(); ?>
		<div class="woodmart-buttons wd-pos-r-t">
			<div class="woodmart-add-btn wd-action-btn wd-style-icon wd-add-cart-btn"><?php do_action('woocommerce_after_shop_loop_item'); ?></div>
			<?php woodmart_quick_view_btn(get_the_ID()); ?>
			<?php woodmart_add_to_compare_loop_btn(); ?>
			<?php do_action('woodmart_product_action_buttons'); ?>
		</div>
		<?php woodmart_quick_shop_wrapper(); ?>
		
	</div>

	<div class="product-element-bottom">
		<?php
			/* 
			woodmart_product_categories();
			woodmart_product_brands_links();
			*/
		?>
		<?php
		 /* echo woodmart_swatches_list(); */
		?>
		<?php
			/**
			 * woocommerce_after_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			/* do_action('woocommerce_template_loop_rating'); */
		?>
		

		<?php
			/* if (woodmart_loop_prop('progress_bar')) :
				woodmart_stock_progress_bar();
			endif */ 
		?>

		<?php 
			/* if (woodmart_loop_prop('timer')) :
				woodmart_product_sale_countdown();
			endif */ 
		?>
		
		<div class="woodmart-add-btn view-prod">
			<a href="<?php echo esc_url(get_permalink()); ?>" class="button wp-element-button product_type_simple add_to_cart_button ajax_add_to_cart add-to-cart-loop loading" rel="nofollow">
				<span>Ver producto</span></a>
		</div> 
	</div> 

</div>