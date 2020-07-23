<?php


//the settings page
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' 	=> 'Component Locations',
        'menu_title'	=> 'Components',
        'menu_slug' 	=> 'jma-acf-location',
        'capability'	=> 'edit_posts',
        'redirect'		=> false
    ));
}


function acf_post_group_options()
{
    $args = array(
        'public'                => true,
        'exclude_from_search'   => false,
        '_builtin'              => false
    );
    $output = 'names'; // names or objects, note names is the default
    $operator = 'and'; // 'and' or 'or'
$post_types = get_post_types($args/*, $output, $operator*/);
    array_unshift($post_types, "page", "post");

    /* general settings page */
    acf_add_local_field_group(array(
        'key' => 'group_5c72a2077eb8e',
        'title' => 'Defaults',
        'fields' => array(
            array(
                'key' => 'acffield_5c72a7ec47a2cyyy',
                'label' => 'Location',
                'name' => 'jma_acf_location',
                'type' => 'post_object',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'post_type' => $post_types,
                'taxonomy' => '',
                'allow_null' => 1,
                'multiple' => 1,
                'return_format' => 'object',
                'ui' => 1,
            ),
            array(
                'key' => 'acffield_5c72ae5531046',
                'label' => 'Post Type',
                'name' => 'jma_acf_post_type',
                'type' => 'comp_post_type_selector',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'select_type' => 'Checkboxes',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'jma-acf-location',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
    ));
}
add_action('acf/init', 'acf_post_group_options', 998);

function acf_slider_options()
{
    /* pull types from settings page to determine visibility on pages/posts */
    //$posts = get_acffield('location', 'option');
    $types = get_field('jma_acf_post_type', 'option');
    $posts = get_field('jma_acf_location', 'option');
    $jma_acf_default_class = get_field('jma_acf_default_class', 'option');
    $location = array();
    if (is_array($types)) {
        foreach ($types as $type) {
            $location[] = array(
                array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => $type
            )
        );
        }
    }
    if (is_array($posts)) {
        foreach ($posts as $post) {
            $param = $post->post_type == 'post'?'post':'page';
            $location[] = array(
                array(
                'param' => $param,
                'operator' => '==',
                'value' => $post->ID
            )
        );
        }
    }


    acf_add_local_field_group(array(
    'key' => 'group_57e7318d8e3a0',
    'title' => 'ACF Components',
    'fields' => array(
        array(
            'key' => 'components',
            'label' => 'Components',
            'name' => 'components',
            'type' => 'flexible_content',
            'instructions' => 'Add components, then insert [acf_component id=\'yourcompid\'] where you want the component to appear.',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'layouts' => array(
                array(
                    'key' => 'Accordion',
                    'name' => 'Accordion',
                    'label' => 'Accordion',
                    'display' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'accordion_comp_id',
                            'label' => 'Component Id',
                            'name' => 'comp_id',
                            'type' => 'text',
                            'instructions' => 'The value for yourcompid in shortcode [acf_component id=\'yourcompid\'] where you want the component to appear.',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '33',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                        ),
                        array(
                            'key' => 'accordion_custom_class',
                            'label' => 'Custom Class',
                            'name' => 'custom_class',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '33',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                        ),
                        array(
                            'key' => 'accordion_open',
                            'label' => 'Open First Panel',
                            'name' => 'open',
                            'type' => 'radio',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '33',
                                'class' => '',
                                'id' => '',
                            ),
                            'layout' => 'horizontal',
                            'choices' => array(
                                0 => 'No',
                                1 => 'Yes',
                            ),
                            'default_value' => '',
                            'other_choice' => 0,
                            'save_other_choice' => 0,
                            'allow_null' => 0,
                            'return_format' => 'value',
                        ),
                        array(
                            'key' => 'accordion_inactive_bg',
                            'label' => 'Inactive Background',
                            'name' => 'inactive_bg',
                            'type' => 'color_picker',
                            'instructions' => 'if blank will match footer bg',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '25',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                        ),
                        array(
                            'key' => 'accordion_inactive_text',
                            'label' => 'Inactive Text',
                            'name' => 'inactive_text',
                            'type' => 'color_picker',
                            'instructions' => 'if blank will match footer text',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '25',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                        ),
                        array(
                            'key' => 'accordion_active_bg',
                            'label' => 'Active Background',
                            'name' => 'active_bg',
                            'type' => 'color_picker',
                            'instructions' => 'if blank will match footer text',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '25',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                        ),
                        array(
                            'key' => 'accordion_active_text',
                            'label' => 'Active Text',
                            'name' => 'active_text',
                            'type' => 'color_picker',
                            'instructions' => 'if blank will match body text',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '25',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                        ),
                        array(
                            'key' => 'accordion_tabs_content',
                            'label' => 'Tabs/Content',
                            'name' => 'tabs_content',
                            'type' => 'repeater',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'collapsed' => 'field_58628d0e3816b',
                            'min' => 0,
                            'max' => 0,
                            'layout' => 'table',
                            'button_label' => '',
                            'sub_fields' => array(
                                array(
                                    'key' => 'accordion_hide',
                                    'label' => 'hide',
                                    'name' => 'hide',
                                    'type' => 'checkbox',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '6',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'choices' => array(
                                        'hide' => 'hide',
                                    ),
                                    'allow_custom' => 0,
                                    'default_value' => false,
                                    'layout' => 'vertical',
                                    'toggle' => 0,
                                    'return_format' => 'value',
                                    'save_custom' => 0,
                                ),
                                array(
                                    'key' => 'accordion_tab',
                                    'label' => 'Tab',
                                    'name' => 'tab',
                                    'type' => 'textarea',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '33',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'default_value' => '',
                                    'new_lines' => '',
                                    'maxlength' => '',
                                    'placeholder' => '',
                                    'rows' => 3,
                                ),
                                array(
                                    'key' => 'accordion_contents',
                                    'label' => 'Contents',
                                    'name' => 'contents',
                                    'type' => 'flexible_content',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '60',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'layouts' => array(
                                        'layout_5d44df7bb935a' => array(
                                            'key' => 'accordion_text_content',
                                            'name' => 'text_content',
                                            'label' => 'Text Content',
                                            'display' => 'block',
                                            'sub_fields' => array(
                                                array(
                                                    'key' => 'accordion_content',
                                                    'label' => 'Content',
                                                    'name' => 'content',
                                                    'type' => 'wysiwyg',
                                                    'instructions' => '',
                                                    'required' => 0,
                                                    'conditional_logic' => 0,
                                                    'wrapper' => array(
                                                        'width' => '',
                                                        'class' => '',
                                                        'id' => '',
                                                    ),
                                                    'default_value' => '',
                                                    'tabs' => 'text',
                                                    'media_upload' => 0,
                                                    'toolbar' => 'full',
                                                    'delay' => 0,
                                                ),
                                            ),
                                            'min' => '',
                                            'max' => '',
                                        ),
                                        'layout_5d44e1ab7554c' => array(
                                            'key' => 'accordion_song',
                                            'name' => 'song',
                                            'label' => 'Song',
                                            'display' => 'block',
                                            'sub_fields' => array(
                                                array(
                                                    'key' => 'accordion_w_title',
                                                    'label' => 'w/ Title?',
                                                    'name' => 'w_title',
                                                    'type' => 'checkbox',
                                                    'instructions' => '',
                                                    'required' => 0,
                                                    'conditional_logic' => 0,
                                                    'wrapper' => array(
                                                        'width' => '15',
                                                        'class' => '',
                                                        'id' => '',
                                                    ),
                                                    'choices' => array(
                                                        1 => 'Yes',
                                                    ),
                                                    'allow_custom' => 0,
                                                    'default_value' => false,
                                                    'layout' => 'vertical',
                                                    'toggle' => 0,
                                                    'return_format' => 'value',
                                                    'save_custom' => 0,
                                                ),
                                                array(
                                                    'key' => 'accordion_song_post',
                                                    'label' => 'Song',
                                                    'name' => 'song',
                                                    'type' => 'post_object',
                                                    'instructions' => '',
                                                    'required' => 0,
                                                    'conditional_logic' => 0,
                                                    'wrapper' => array(
                                                        'width' => '84',
                                                        'class' => '',
                                                        'id' => '',
                                                    ),
                                                    'post_type' => array(
                                                        0 => 'songs',
                                                    ),
                                                    'taxonomy' => '',
                                                    'allow_null' => 0,
                                                    'multiple' => 0,
                                                    'return_format' => 'object',
                                                    'ui' => 1,
                                                ),
                                            ),
                                            'min' => '',
                                            'max' => '',
                                        ),
                                    ),
                                    'button_label' => 'Text or Song',
                                    'min' => 1,
                                    'max' => '',
                                ),
                            ),
                        ),
                    ),
                    'min' => '',
                    'max' => '',
                ),
                array(
                    'key' => 'Tabbed',
                    'name' => 'Tabbed',
                    'label' => 'Tabbed',
                    'display' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'tabbed_custom_class',
                            'label' => 'Custom Class',
                            'name' => 'custom_class',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '15',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                        ),
                        array(
                            'key' => 'tabbed_alignment',
                            'label' => 'Alignment',
                            'name' => 'alignment',
                            'type' => 'radio',
                            'instructions' => 'Put the tabs on the top or left side of content penels',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '35',
                                'class' => '',
                                'id' => '',
                            ),
                            'layout' => 'horizontal',
                            'choices' => array(
                                'top' => 'Top',
                                'left' => 'Left',
                            ),
                            'default_value' => 'top',
                            'other_choice' => 0,
                            'save_other_choice' => 0,
                            'allow_null' => 0,
                            'return_format' => 'value',
                        ),
                        array(
                            'key' => 'tabbed_display',
                            'label' => 'Display',
                            'name' => 'display',
                            'type' => 'radio',
                            'instructions' => 'Arrows are animated tabs with pointed end on active panel.',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '35',
                                'class' => '',
                                'id' => '',
                            ),
                            'layout' => 'horizontal',
                            'choices' => array(
                                'tabs' => 'Tabs',
                                'pills' => 'Pills',
                                'arrows' => 'Arrows',
                            ),
                            'default_value' => 'tabs',
                            'other_choice' => 0,
                            'save_other_choice' => 0,
                            'allow_null' => 0,
                            'return_format' => 'value',
                        ),
                        array(
                            'key' => 'tabbed_inactive_bg',
                            'label' => 'Inactive Background',
                            'name' => 'inactive_bg',
                            'type' => 'color_picker',
                            'instructions' => 'if blank will match footer bg',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '25',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                        ),
                        array(
                            'key' => 'tabbed_inactive_text',
                            'label' => 'Inactive Text',
                            'name' => 'inactive_text',
                            'type' => 'color_picker',
                            'instructions' => 'if blank will match footer text',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '25',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                        ),
                        array(
                            'key' => 'tabbed_active_bg',
                            'label' => 'Active Background',
                            'name' => 'active_bg',
                            'type' => 'color_picker',
                            'instructions' => 'if blank will match footer text',
                            'required' => 0,
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_58628eaa38172',
                                        'operator' => '!=',
                                        'value' => 'tabs',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '25',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                        ),
                        array(
                            'key' => 'tabbed_active_text',
                            'label' => 'Active Text',
                            'name' => 'active_text',
                            'type' => 'color_picker',
                            'instructions' => 'if blank will match body text (footer bg for arrows)',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '25',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                        ),
                        array(
                            'key' => 'tabbed_comp_id',
                            'label' => 'Component Id',
                            'name' => 'comp_id',
                            'type' => 'text',
                            'instructions' => 'The value for yourcompid in shortcode [acf_component id=\'yourcompid\'] where you want the component to appear.',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '15',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                        ),
                        array(
                            'key' => 'tabbed_tabs_content',
                            'label' => 'Tabs/Content',
                            'name' => 'content',
                            'type' => 'repeater',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'collapsed' => 'field_58628da73816f',
                            'min' => 0,
                            'max' => 0,
                            'layout' => 'table',
                            'button_label' => '',
                            'sub_fields' => array(
                                array(
                                    'key' => 'tabbed_hide',
                                    'label' => 'Hide',
                                    'name' => 'hide',
                                    'type' => 'checkbox',
                                    'instructions' => 'YOU CAN\'T HIDE THE FIRST TAB',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '6',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'choices' => array(
                                        'hide' => 'hide',
                                    ),
                                    'allow_custom' => 0,
                                    'default_value' => false,
                                    'layout' => 'vertical',
                                    'toggle' => 0,
                                    'return_format' => 'value',
                                    'save_custom' => 0,
                                ),
                                array(
                                    'key' => 'tabbed_tab',
                                    'label' => 'Tab',
                                    'name' => 'tab',
                                    'type' => 'textarea',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '20',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'default_value' => '',
                                    'new_lines' => '',
                                    'maxlength' => '',
                                    'placeholder' => '',
                                    'rows' => 3,
                                ),
                                array(
                                    'key' => 'tabbed_content',
                                    'label' => 'Content',
                                    'name' => 'content',
                                    'type' => 'wysiwyg',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '74',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'default_value' => '',
                                    'tabs' => 'all',
                                    'toolbar' => 'full',
                                    'media_upload' => 1,
                                    'delay' => 0,
                                ),
                            ),
                        ),
                    ),
                    'min' => '',
                    'max' => '',
                ),
            ),
            'min' => '',
            'max' => '',
            'button_label' => 'Add Component',
        ),
    ),
    'location' => $location,
    'menu_order' => 0,
    'position' => 'acf_after_title',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
));
}
add_action('acf/init', 'acf_slider_options', 999);
