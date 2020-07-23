<?php
/*
Plugin Name: JMA Advanced Custom Fields Components
Description: Updated for Genesis
Version: 1.0
Author: John Antonacci
Author URI: http://cleansupersites.com
License: GPL2
*/

function jma_acf_plugin_activate()
{
    /* activation code here */
}
register_activation_hook(__FILE__, 'jma_acf_plugin_activate');

function jma_acf_admin_notice()
{
    echo '<div class="notice notice-error is-dismissible">
             <p>The Genesis ACF Components plugin REQUIRES Genesis Bootstrap plugin</p>
         </div>';
}
function jma_acf_check_for_plugin()
{
    if (!is_plugin_active('jma-bootstrap-genesis/jma-bootstrap-genesis.php')) {
        add_action('admin_notices', 'jma_acf_admin_notice');
        return null;
    }
}
add_action('admin_init', 'jma_acf_check_for_plugin');

function jma_detect_accordion_shortcode()
{
    global $post;
    $return = false;
    $pattern = get_shortcode_regex();

    if (preg_match_all('/'. $pattern .'/s', $post->post_content, $matches)
        && array_key_exists(2, $matches)
        && in_array('acf_component', $matches[2])) {
        $return = true;
    }
    return $return;
}
add_action('wp', 'jma_detect_accordion_shortcode');/* accordion shortcode */

function jma_acf_comp_scripts()
{
    wp_add_inline_script('JMA_GBS_custom_js', "jQuery(document).ready(function($) {
        $('.panel-heading > a').click(function(){
$([document.documentElement, document.body]).animate({
                        scrollTop: $(this).offset().top
                    }, 300);
});
});");
}
function jma_comp_redirect()
{
    add_action('wp_enqueue_scripts', 'jma_acf_comp_scripts');
}
//add_action('template_redirect', 'jma_comp_redirect');

function jma_acf_layout_title($title, $field, $layout, $i)
{
    if ($value = get_sub_field('comp_id')) {
        return $title . ' - ' . $value;
    } else {
        foreach ($layout['sub_fields'] as $sub) {
            if ($sub['name'] == 'comp_id') {
                $key = $sub['key'];
                if (array_key_exists($i, $field['value']) && $value = $field['value'][$i][$key]) {
                    return $title . ' - ' . $value;
                }
            }
        }
    }
    return $title;
}
add_filter('acf/fields/flexible_content/layout_title', 'jma_acf_layout_title', 10, 4);


spl_autoload_register('jma_component_autoloader');
function jma_component_autoloader($class_name)
{
    if (false !== strpos($class_name, 'JMAComp')) {
        $classes_dir = realpath(plugin_dir_path(__FILE__));
        $class_file = $class_name . '.php';
        require_once $classes_dir . DIRECTORY_SEPARATOR . $class_file;
    }
}
//$row[$row_type . '_comp_id'] != $id
function jma_comp_setup_obj($id, $post_id)
{
    $return = array();
    if (have_rows('components')) {
        while (have_rows('components')) {
            the_row();
            $row_id = '';
            $row = get_row(false);

            $row_type = $row['acf_fc_layout'];
            if ($row_type == 'Tabbed') {
                if (isset($row['tabbed_comp_id'])) {
                    $row_id = $row['tabbed_comp_id'];
                }
            } else {
                if (isset($row['accordion_comp_id'])) {
                    $row_id = $row['accordion_comp_id'];
                }
            }
            if ($row_id && $row_id == $id) {
                $class = 'JMAComponent' . $row_type;
                $return  = new $class($row, $row_id, $post_id);
                break;
            }
        }
    }
    return $return;
}

function jma_comp_css()
{
    if (!(jma_detect_accordion_shortcode() && have_rows('components'))) {
        return;
    }
    $print = '
    .jma-component {
        margin-bottom: 20px;
    }
    .jma-component.accordion .card-header, .jma-component.accordion .card {
        padding: 0;
        border-radius: 0
    }
    .jma-component.accordion button {
        text-decoration: none;
        display: block;
        width: 100%;
        text-align: left;
        padding: 0.75rem 1.25rem
    }
    .jma-component.accordion button i {
        margin-right: 3px;
    }
    .jma-component.accordion button:not(.collapsed) i {
        transform: rotate(90deg);
        -webkit-transform: rotate(90deg);
    }
    .jma-component.tabbed .nav-link {
        margin-right: 1px
    }
    .jma-component.tabbed .nav-link:not(.active) {
        border-width:0
    }
    .jma-tabbed .nav li a {
    white-space: nowrap;
    }
    .jma-component.tabbed .nav .nav-link.active {
        background:#ffffff
    }
    .jma-component.tabbed .tab-pane {
        background: #ffffff;
        padding: 15px;
    }
    .nav.nav-pills {
        margin-bottom: 3px
    }
    .jma-component.tabbed .nav>li>a.active {
    cursor: default;
    }
    @media(min-width:992px){
        .tabs-left.jma-component.tabbed, .tabs-left.jma-component.tabbed .nav, .tabs-left.jma-component.tabbed, .tabs-left.jma-component.tabbed .nav * {
            display: block!important
        }
        .tabs-left.jma-component.tabbed > .tab-content {
            border-top-width: 1px;
            margin-left: 189px;
        }
        .tabs-left > .nav-tabs {
            width: 170px;
            float: left;
        }
        .tabs-left > .nav-pills {
            width: 185px;
            float: left;
        }
        .tabs-left > .nav-tabs>li {
            border-left-width: 1px;
            float: none;
        }
        .tabs-left > .nav-pills>li {
            margin-top:1px;
            border-width: 0;
            float: none;
        }


        .tabs-left {
            position: relative;
        }
        .tabs-left.tab-arrows > .tab-content {
            border-top-width: 1px;
            margin-left: 189px;
        }
        .tabs-left.tab-arrows > .nav-pills>li {
            margin-left:0;
            width: 155px;
            position: relative;
            -webkit-transition: all 0.3s; /* Safari */
            transition: all 0.3s;
        }
        .tabs-left.tab-arrows > .nav-pills>li.active, .tabs-left.tab-arrows > .nav-pills {
            width: 170px;
        }
    }
    @media(min-width:767px) and (max-width:920px){
        .tabs-left > .nav-pills>li>a {
            padding-left: 8px;
            padding-right: 8px
        }
            .nav-pills>li+li {
            margin-left: 1px;
        }
    }
    .jma-tabbed .tab-content  {
        overflow: hidden; /* allow clears to work correctly within this element */
    }';
    if ($print) {
        if (function_exists('jma_gbs_minifyCss')) {
            $print = jma_gbs_minifyCss($print);
        }
        wp_add_inline_style('JMA_GBS_combined_css', apply_filters('jma_comp_css_filter', $print));
    }
}
add_action('wp_enqueue_scripts', 'jma_comp_css', 99);






function get_comp_classes()
{
    $return = array();
    foreach (scandir(plugin_dir_path(__FILE__)) as $file) {
        // get the file name of the current file without the extension
        // which is essentially the class name
        $class = basename($file, '.php');

        if (false !== strpos($class, 'JMAComponent')) {
            $return[] = $class;
        }
    }
    return $return;
}
new JMACompPostTypeSelector();

function jma_afc_css_array($css, $mods)
{
    $comp_classes = get_comp_classes();
    if (is_array($comp_classes)) {
        foreach ($comp_classes as $comp_class) {
            $css = array_merge($css, $comp_class::css_filter($mods));
        }
    }

    return $css;
}
add_filter('jma_gbs_css_array', 'jma_afc_css_array', 10, 2);



function acf_component_shortcode($atts = array())
{
    if (!have_rows('components')) {//returns if acf not active
        return;
    }
    extract(shortcode_atts(array(
        'id' => '',
        ), $atts));

    global $post;
    $comp = jma_comp_setup_obj($id, $post->ID);

    $x = '';
    if (isset($comp) && is_object($comp)) {
        ob_start();
        $comp->css();


        $transient_name = 'jma_acf_shortcode_markup'.$post->ID.$id;
        $x = get_transient($transient_name);
        if (false === $x) {
            echo $comp->markup();
            $x = ob_get_contents();
            set_transient($transient_name, $x);
        }

        ob_end_clean();
    }
    return $x;
}
add_shortcode('acf_component', 'acf_component_shortcode');


if (function_exists('acf_add_local_field_group')) {
    require('jma-acf-groups.php');
}

//jma_acf_shortcode_css
function jma_acf_save_post($id)
{
    global $wpdb;
    $css = '%jma_acf_shortcode_css' . $id . '%';
    $markup = '%jma_acf_shortcode_markup' . $id . '%';
    $plugin_options = $wpdb->get_results("SELECT option_name FROM $wpdb->options WHERE option_name LIKE '$css' OR option_name LIKE '$markup'");

    foreach ($plugin_options as $option) {
        delete_option($option->option_name);
    }
}
add_action('save_post', 'jma_acf_save_post');
