<?php

add_action('wp_ajax_nopriv_subateria_filter', 'subateria_filter');
add_action('wp_ajax_subateria_filter', 'subateria_filter');

add_action('wp_ajax_nopriv_subateria_filter_dropdown', 'subateria_filter_dropdown');
add_action('wp_ajax_subateria_filter_dropdown', 'subateria_filter_dropdown');


add_action('wp_ajax_nopriv_get_query_categories', 'get_query_categories');
add_action('wp_ajax_get_query_categories', 'get_query_categories');

add_action('wp_ajax_nopriv_get_all', 'get_all');
add_action('wp_ajax_get_all', 'get_all');
function subateria_filter()
{
    $parent = $_POST['value'];
    $name = $_POST['name'];
    // $title = $_POST['title'];
    // echo $parent . ' PADRE';
    $html = "";
    $category = get_term_by('slug', $parent, 'product_cat');
    // var_dump($category);
    if ($category) {
        $args = array(
            'taxonomy'     => 'product_cat',
            'orderby'      => 'name',
            'show_count'   => 0,
            'pad_counts'   => 0,
            'hierarchical' => 0,
            'title_li'     => '',
            'hide_empty'   => 0,
            'parent'       => $category->term_id
        );
        $sub_cats_brand = get_categories($args);
        // $html = '<h2 class="wp-block-heading mob has-text-color" style="text-transform:uppercase;color:#dd1725;font-style:normal;font-weight:700">' . $title . '</h2><div class="wc-product-categories-list">';
        if ($sub_cats_brand) {
            $html .= '<ul class=" wc-block-product-categories-list wc-block-product-categories-list--depth-0">';
            foreach ($sub_cats_brand as $cat) {
                $html .= '<li class="wc-block-product-categories-list-item">';
                $html .= '<label class="radio-label"><input class="radio-btn" id="' . $cat->term_id . '" type="radio" name="' . $name . '" value="' . $cat->slug . '"/><span>' . $cat->name . '</span></label>';
                $html .= '</li>';
            }
            $html .= '</ul>';
            $html .= '</div>';
        } else {
            $html .= "<label>No existe elementos para la categoría " . $category->name . "</label>";
        }
    }
    echo $html;
    wp_die();
}


// function get_query_categories($root_category_slug = null,$query_categories = [])
// {
//     $root_category_slug = $root_category_slug == null ? get_query_var('product_cat'):$root_category_slug;
//     $root_category = get_term_by('slug', $root_category_slug, 'product_cat');
//     if ($root_category) {
//         $parent_id =  $root_category->parent;
//         $parent = get_term_by('term_id', $parent_id, 'product_cat');
//         if ($parent) {
//             array_push($query_categories, $parent);
//             get_query_categories($parent->slug,$query_categories);
//         }
//         var_dump($query_categories);
//     }
// }

function get_query_categories()
{
    $query_categories = [];
    $categories = [];
    $url = $_POST['url'];
    $query = strpos($url, "/product-category/");
    if ($query !== false) {
        $url = str_replace("/product-category/", "", $url);
        $categories = explode('/', $url);
        foreach ($categories as $cat) {
            $root_category = get_term_by('slug', $cat, 'product_cat');
            if ($root_category) {
                array_push($query_categories, $root_category);
            }
        }
    } else {
        $query_categories = false;
    }
    wp_send_json($query_categories);
}

function get_all()
{
  
    $categories = get_terms('product_cat', array(
        'hide_empty' => true, 
		'pad_counts' => true,
    ));

    // Construir el árbol jerárquico
    $categoryTree = build_category_tree($categories, 0);

    wp_send_json($categoryTree);
    // return $categoryTree;
}

function build_category_tree($categories, $parent = 0)
{
    $tree = array();

    foreach ($categories as $category) {
        if ($category->parent == $parent) {
            $children = build_category_tree($categories, $category->term_id);
            if ($children) {
                $category->children = $children;
            }
            $tree[] = $category;
        }
    }

    return $tree;
}

function shortcode_vehicle_type()
{
    // ob_start();
    $html = "";
    $args = array(
        'taxonomy'     => 'product_cat',
        'orderby'      => 'name',
        'show_count'   => 0,
        'pad_counts'   => 0,
        'hierarchical' => 0,
        'title_li'     => '',
        'hide_empty'   => 0
    );
    $all_categories = get_categories($args);
    // $all_categories =  get_all();
    $html .= '<h2 class="wp-block-heading mob has-text-color" style="color:#dd1725;font-style:normal;font-weight:700">TIPO DE VEHICULO</h2><div class="wc-product-categories-list">';
    $html .= '<ul class="wc-block-product-categories-list wc-block-product-categories-list--depth-0">';
    foreach ($all_categories as $cat) {
        if ($cat->parent == 0) {
            $html .= '<li class="wc-block-product-categories-list-item">';
            $html .= '<label class="radio-label" for="' . $cat->term_id . '">' . '<input class="radio-btn" id="' . $cat->term_id . '" type="radio" name="vehicule_type" value="' . $cat->slug . '"/><span>' . $cat->name . '</span></label>';
            $html .= '</li>';
        }
    }
    $html .= '</ul>';
    $html .= '</div>';
    return $html;
}



function subateria_filter_dropdown()
{
    $parent = $_POST['value'];
    $category = get_term_by('slug', $parent, 'product_cat');
    $args = array(
        'taxonomy'     => 'product_cat',
        'orderby'      => 'name',
        'show_count'   => 0,
        'pad_counts'   => 0,
        'hierarchical' => 0,
        'title_li'     => '',
        'hide_empty'   => 0,
        'parent'       => $category->term_id
    );
    $sub_cats_brand = get_categories($args);
    $html = "<option></option>";
    if ($sub_cats_brand) {
        foreach ($sub_cats_brand as $cat) {
            $html .= '<option value="' . $cat->slug . '">' . $cat->name . '</option>';
        }
    }
    echo $html;
}

function get_cat()
{
    $args = array(
        'taxonomy'     => 'product_cat',
        'orderby'      => 'name',
        'show_count'   => 0,
        'pad_counts'   => 0,
        'hierarchical' => 0,
        'title_li'     => '',
        'hide_empty'   => 0
    );
    $all_categories = get_categories($args);
    $html = "<option></option>";
    foreach ($all_categories as $cat) {
        if ($cat->category_parent == 0) {
            $html .= '<option value="' . $cat->slug . '">' . $cat->name . '</option>';
        }
    }
    return $html;
}
