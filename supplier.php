<?php
add_action('add_meta_boxes', 'add_supplier_info_meta_box');
function add_supplier_info_meta_box() {
    add_meta_box(
        'supplier_info_meta_box',
        __('Supplier Information', 'divi'),
        'supplier_info_meta_box_callback',
        'product',
        'normal',
        'high'
    );
}

function supplier_info_meta_box_callback($post) {
    wp_nonce_field('save_supplier_info', 'supplier_info_nonce');

    $supplier_name = get_post_meta($post->ID, '_supplier_name', true);
    $supplier_address = get_post_meta($post->ID, '_supplier_address', true);
    $supplier_tel = get_post_meta($post->ID, '_supplier_tel', true);
    $supplier_email = get_post_meta($post->ID, '_supplier_email', true);
    $google_drive_link = get_post_meta($post->ID, '_google_drive_link', true); 

    
    echo '<label for="supplier_name">' . __('Supplier Name', 'divi') . '</label>';
    echo '<input type="text" id="supplier_name" name="supplier_name" value="' . esc_attr($supplier_name) . '" style="width:100%; margin-bottom:10px;" />';

    
    echo '<label for="supplier_address">' . __('Supplier Person', 'divi') . '</label>';
    echo '<textarea id="supplier_address" name="supplier_address" style="width:100%; margin-bottom:10px;">' . esc_textarea($supplier_address) . '</textarea>';

    
    echo '<label for="supplier_tel">' . __('Supplier Tel', 'divi') . '</label>';
    echo '<input type="text" id="supplier_tel" name="supplier_tel" value="' . esc_attr($supplier_tel) . '" style="width:100%; margin-bottom:10px;" />';

    
    echo '<label for="supplier_email">' . __('Supplier Email', 'divi') . '</label>';
    echo '<input type="email" id="supplier_email" name="supplier_email" value="' . esc_attr($supplier_email) . '" style="width:100%; margin-bottom:10px;" />';

    
    echo '<label for="google_drive_link">' . __('Google Drive Link', 'divi') . '</label>';
    echo '<input type="text" id="google_drive_link" name="google_drive_link" value="' . esc_attr($google_drive_link) . '" style="width:100%; margin-bottom:10px;" />';
}

// Save 
add_action('save_post', 'save_supplier_info_meta_box_data', 10, 1);
function save_supplier_info_meta_box_data($post_id) {
 
    if (!isset($_POST['supplier_info_nonce']) || !wp_verify_nonce($_POST['supplier_info_nonce'], 'save_supplier_info')) {
        error_log('Nonce verification failed.');
        return;
    }

    // Save supplier
    if (isset($_POST['supplier_name'])) {
        update_post_meta($post_id, '_supplier_name', sanitize_text_field($_POST['supplier_name']));
    }
    if (isset($_POST['supplier_address'])) {
        update_post_meta($post_id, '_supplier_address', sanitize_textarea_field($_POST['supplier_address']));
    }
    if (isset($_POST['supplier_tel'])) {
        update_post_meta($post_id, '_supplier_tel', sanitize_text_field($_POST['supplier_tel']));
    }
    if (isset($_POST['supplier_email'])) {
        update_post_meta($post_id, '_supplier_email', sanitize_email($_POST['supplier_email']));
    }

    if (isset($_POST['google_drive_link'])) {
        update_post_meta($post_id, '_google_drive_link', esc_url_raw($_POST['google_drive_link']));
    }
}

add_filter('woocommerce_product_tabs', 'add_supplier_tab');
function add_supplier_tab($tabs) {
    $tabs['supplier_tab'] = array(
        'title'    => __('Kontakt', 'divi'),
        'priority' => 50,
        'callback' => 'supplier_tab_content'
    );
    return $tabs;
}

// Display 
function supplier_tab_content() {
    $product_id = get_the_ID();

    echo '<p>' . esc_html(get_post_meta($product_id, '_supplier_name', true)) . '</p>';
    echo '<p>' . esc_html(get_post_meta($product_id, '_supplier_address', true)) . '</p>';
    
    $tel = get_post_meta($product_id, '_supplier_tel', true);
    if (!empty($tel)) {
        echo '<p><strong>' . __('Tel:', 'divi') . '</strong> ' . esc_html($tel) . '</p>';
    }
    
    $email = get_post_meta($product_id, '_supplier_email', true);
    if (!empty($email)) {
        echo '<p><strong>' . __('Email:', 'divi') . '</strong> ' . esc_html($email) . '</p>';
    }

    $google_drive_link = get_post_meta($product_id, '_google_drive_link', true);
    if (!empty($google_drive_link)) {
        echo '<h3>' . __('Viac kontaktov tu', 'divi') . '</h3>';
        echo '<a href="' . esc_url($google_drive_link) . '" class="button" target="_blank">' . __('Obchodný tím', 'divi') . '</a>';
    }
}
?>
