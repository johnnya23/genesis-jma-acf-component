<?php
/*
Plugin Name: JMA Advanced Custom Fields Components
Description: Updated for Theme ver 2.2 This plugin creates an accordions and tabs from Advanced Custom Fields flexible content field
Version: 1.1
Author: John Antonacci
Author URI: http://cleansupersites.com
License: GPL2
*/

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
    wp_add_inline_script('adjust_site_js', "jQuery(document).ready(function($) {
        $('.panel-heading > a').click(function(){
            console.log($(this));
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

function jma_comp_setup_objs()
{
    $return = array();
    if (have_rows('components')) {
        while (have_rows('components')) {
            the_row();
            $row = get_row(true);
            $row_id = $row['comp_id'];
            $row_type = $row['acf_fc_layout'];
            $class = 'JMAComponent' . $row_type;
            $return[$row_id]  = new $class($row);
        }
    }
    return $return;
}

function jma_comp_css()
{
    if (!(jma_detect_accordion_shortcode() && have_rows('components'))) {
        return;
    }
    $comp_objs = jma_comp_setup_objs();
    $print = '';

    foreach ($comp_objs as $comp_obj) {
        $print .= $comp_obj->css();
    }
    $print .= '
    .jma-tabbed .nav li a {
    white-space: nowrap;
    }
    .jma-accordion.panel-group .panel+.panel {
    margin-top: 1px;
    }
    .jma-tabbed .nav>li.active>a {
    cursor: default;
    }
    @media(min-width:992px){
    .tabs-left.tb-tabs-framed > .tab-content {
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
    .tabs-left >.nav-tabs>li.active {
    border-right-color: #ffffff;
    }


    .tabs-left {
        position: relative;
    }
    .tabs-left.tb-tabs-framed.tab-arrows > .tab-content {
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
        wp_add_inline_style('themeblvd-theme', apply_filters('jumpstart_ent_css_output', $print));
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

        if (false !== strpos($class, 'JMAComp')) {
            $return[] = $class;
        }
    }
    return $return;
}


function jma_comp_filter($dynamic_styles)
{
    $comp_classes = get_comp_classes();
    if (is_array($comp_classes)) {
        foreach ($comp_classes as $comp_class) {
            $dynamic_styles = array_merge($dynamic_styles, $comp_class::css_filter());
        }
    }

    return $dynamic_styles;
}
add_filter('dynamic_styles_filter', 'jma_comp_filter');



function acf_component_shortcode($atts = array())
{
    if (!have_rows('components')) {//returns if acf not active
        return;
    }
    extract(shortcode_atts(array(
        'id' => '',
        ), $atts));
    ob_start();
    $comps = jma_comp_setup_objs();
    $this_comp = $comps[$id];
    echo $this_comp->markup();
    $x = ob_get_contents();
    ob_end_clean();

    return $x;
}
add_shortcode('acf_component', 'acf_component_shortcode');
