<?php
class JMAComponent
{
    public $content;
    public $id;
    public $post_id;


    public function __construct($content, $id, $post_id)
    {
        $this->content = $content;
        $this->id = $id;
        $this->post_id = $post_id;
    }

    public function css()
    {
        add_action('wp_enqueue_scripts', $this->jma_comp_shortcode_css(), 99);
    }

    public static function css_filter()
    {
        $return = array();
        return $return;
    }

    public function jma_comp_shortcode_css()
    {
        $return = '';
        $transient_name = 'jma_acf_shortcode_css'.$this->post_id.$this->id;
        $return = get_transient($transient_name);
        if (false === $return) {
            // It wasn't there, so regenerate the data and save the transient
            $content = $this->content;
            if ($content['acf_fc_layout'] == 'Tabbed') {
                $group_class = '#' . $this->id . '.jma-component.tabbed';
                if ($content['tabbed_inactive_bg']) {
                    $return .= $group_class . ' .nav>li>a:not(.active) {
                        background-color: ' . $content['tabbed_inactive_bg'] . ';
                    }';
                }
                if ($content['tabbed_inactive_text']) {
                    $return .= $group_class . ' .nav>li>a:not(.active) {
                        color: ' . $content['tabbed_inactive_text'] . ';
                    }';
                }
                if ($content['tabbed_active_bg']) {
                    $return .=  $group_class . ' .nav-pills>li a.active {
                        background-color: ' . $content['tabbed_active_bg'] . '!important;
                    }';
                    $return .=  $group_class . '.tabs-left.tab-arrows .nav-pills>li a.active:after {
                        border-color: transparent transparent transparent ' . $content['tabbed_active_bg'] . '
                    }';
                }
                if ($content['tabbed_active_text']) {
                    $return .=  $group_class . ' .nav>li.active>a {
                        color: ' . $content['tabbed_active_text'] . ';
                    }';
                }
            } elseif ($content['acf_fc_layout'] == 'Accordion') {
                $content = $this->content;
                $group_class = '#' . $this->id . '.jma-component.accordion';
                if ($content['accordion_inactive_bg']) {
                    $return = $group_class . ' .card-header button.collapsed {
                        background-color: ' . $content['accordion_inactive_bg'] . ';
                        border-color: #cccccc;
                    }';
                }
                if ($content['accordion_inactive_text']) {
                    $return .= $group_class . ' .card-header button.collapsed {
                        color: ' . $content['accordion_inactive_text'] . ';
                    }';
                }
                if ($content['accordion_active_bg']) {
                    $return .=  $group_class . ' .card-header button:not(.collapsed) {
                        background-color: ' . $content['accordion_active_bg'] . ';
                    }';
                }
                if ($content['accordion_active_text']) {
                    $return .=  $group_class . ' .card-header button:not(.collapsed) {
                        color: ' . $content['accordion_active_text'] . ';
                    }';
                }
            }
            set_transient($transient_name, $return);
        }





        if ($return) {
            if (function_exists('jma_gbs_minifyCss')) {
                $return = jma_gbs_minifyCss($return);
            }
            wp_register_style('jma_comp_shortcode_css', false, array('JMA_GBS_combined_css'));
            wp_enqueue_style('jma_comp_shortcode_css');
            wp_add_inline_style('jma_comp_shortcode_css', apply_filters('jma_comp_shortcode_css_filter', $return));
        }
    }
}
