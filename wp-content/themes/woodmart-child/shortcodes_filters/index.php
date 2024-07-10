<?php

add_shortcode('filters_subateria', 'filters_subateria');
add_shortcode('filters_subateria_dropdown', 'filters_subateria_dropdown');


function filters_subateria()
{
    ob_start();
?>


    <div id="container-filter">
        <div id="loading-overlay">
            <div id="loading-spinner"></div>
        </div>
        <h2 class="wp-block-heading has-text-color" style="color:#dd1725;font-style:normal;font-weight:700">TIPO DE VEHICULO</h2>
        <div id="type">
            <?php //echo shortcode_vehicle_type(); 
            ?>
        </div>
        <h2 class="top-space wp-block-heading has-text-color" style="color:#dd1725;font-style:normal;font-weight:700">MARCA</h2>
        <div id="brand">
        </div>
        <h2 class="top-space wp-block-heading has-text-color" style="color:#dd1725;font-style:normal;font-weight:700">MODELO</h2>
        <div id="model">
        </div>
        <h2 class="top-space wp-block-heading has-text-color" style="color:#dd1725;font-style:normal;font-weight:700">AÑO</h2>
        <div id="year">
        </div>
        <div>
            <input type="button" id="button_search" value="Buscar">
            <button type="button">
                Limpiar
            </button>
        </div>
        <input type="hidden" name="post_type" value="product">
    </div>

<?php
    return ob_get_clean();
}

function filters_subateria_dropdown()
{
    ob_start();
?>
    <div id="container-filter-dropdown">
        <div id="loading-overlay">
            <div id="loading-spinner"></div>
        </div>
        <div class="dropdown" id="type-drop">
            <select id="select-type" style="width: 100%">
                <option value=''></option>
                <?php //echo get_cat() 
                ?>
            </select>
        </div>
        <div class="dropdown" id="brand-drop">
            <select id="select-brand" style="width: 100%">
                <option value=''></option>
            </select>
        </div>
        <div class="dropdown" id="model-drop">
            <select id="select-model" style="width: 100%">
                <option value=''></option>
            </select>
        </div>
        <div class="dropdown" id="year-drop">
            <select id="select-year" style="width: 100%">
                <option value=''></option>
            </select>
        </div>
        <div class="action-buttons home">

            <button type="button" id="clean_dropdown">
                Limpiar
            </button>
            <input type="button" id="search_dropdown" value="Buscar">
        </div>
    </div>
<?php
    return ob_get_clean();
}



// Shortcodes
require_once('subateria_filter.php');
