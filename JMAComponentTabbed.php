<?php
class JMAComponentTabbed extends JMAComponent
{
    public function markup()
    {
        $content = $this->content;
        $tabbed_array = $content['tabs_content'];
        if (!(is_array($tabbed_array) && count($tabbed_array))) {//reutns if $tabbed_array not useful
            return;
        }
        $wrap_cl = '';
        $ul_pill_cl = 'tabs';
        if ($content['display'] != 'tabs') {
            $wrap_cl .= 'tb-tabs-pills';
            $ul_pill_cl = 'pills';
        }
        if ($content['display'] == 'arrows') {
            $wrap_cl .= ' tabs-left tab-arrows';
        } elseif ($content['alignment'] == 'left') {
            $wrap_cl .= ' tabs-left';
        }

        $return = '<div ';
        $return .= 'id="' . $content['comp_id'] . '" ';
        $return .= 'class="' . $content['custom_class'] . ' tb-tabs tabbable tb-tabs-framed ' . $wrap_cl . ' jma-component jma-' . strtolower($content['acf_fc_layout']) . '"';
        $return .= '>';
        $tabs = '<ul class="nav nav-' . $ul_pill_cl . '">';
        $tab_content = '<div class="tab-content dark">';
        foreach ($tabbed_array as $i => $tabbed_pair) {
            if (!$tabbed_pair['hide']) {
                $active =  '';
                if (!$i) {
                    $active =  'active';
                }
                $tabs .= '<li class="' . preg_replace("/(\W)+/", "", strtolower($tabbed_pair['tab'])) . ' ' . $active . '">';
                $tabs .= '<a href="#tab_' . $content['comp_id'] . $i . '" data-toggle="tab" title="Title #1">';
                $tabs .= $tabbed_pair['tab'];
                $tabs .= '</a>';
                $tabs .= '</li>';


                $tab_content .= '<div id="tab_' . $content['comp_id'] . $i . '" class="' . preg_replace("/(\W)+/", "", strtolower($tabbed_pair['tab'])) . ' tab-pane entry-content fade in clearfix ' . $active . '">';

                $tab_content .= apply_filters('the_content', $tabbed_pair['content']);
                $tab_content .= '</div><!--tab-pane-->';
            }
        }
        $tabs .= '</ul><!--nav-tabs-->';
        $tab_content .= '</div><!--tab-content-->';
        $return .=  $tabs . $tab_content;
        $return .= '</div><!--tabbable-->';
        return $return;
    }

    public function css()
    {
        $return = '';
        $content = $this->content;
        $group_class = '#' . $this->id . '.jma-component.jma-tabbed';
        if ($content['inactive_bg']) {
            $return = $group_class . ' .nav>li>a {
            background-color: ' . $content['inactive_bg'] . ';
        }';
        }
        if ($content['inactive_text']) {
            $return .= $group_class . ' .nav>li>a {
            color: ' . $content['inactive_text'] . ';
        }';
        }
        if ($content['active_bg']) {
            $return .=  $group_class . ' .nav-pills>li.active>a {
            background-color: ' . $content['active_bg'] . '!important;
        }';
            $return .=  $group_class . '.tabs-left.tab-arrows .nav-pills>li.active:after {
            border-color: transparent transparent transparent ' . $content['active_bg'] . '
        }';
        }
        if ($content['active_text']) {
            $return .=  $group_class . ' .nav>li.active>a {
            color: ' . $content['active_text'] . ';
        }';
        }

        return $return;
    }

    public static function css_filter()
    {
        $group_class = '.jma-component.jma-tabbed';
        $jma_spec_options = jma_get_theme_values();//echo '<pre>';print_r($jma_spec_options);echo '</pre>';


        $dynamic_styles['comptab'] =  array( 'min-992@' . $group_class . '.tabs-left.tab-arrows > .nav-pills>li.active:after',
            array('content', '\'\''),
            array('right', '-25px'),
            array('top', ' 0'),
            array('width', ' 0'),
            array('height', ' 0'),
            array('position', 'absolute'),
            array('border-style', 'solid'),
            array('border-width', '22px 0 22px 25px'),
            array('border-color', 'transparent transparent transparent ' . $jma_spec_options['footer_font_color']),
            /*array('border', $jma_spec_options['footer_background_color']),*/
        );

        $dynamic_styles['comptab05'] =  array($group_class . ' > .nav>li>a',
            array('background-color', $jma_spec_options['footer_background_color']),
            array('color', $jma_spec_options['footer_font_color']),
        );

        $dynamic_styles['comptab07'] =  array($group_class . ' > .nav-tabs>li.active>a',
            array('background-color', 'inherit!important'),
            array('color', 'inherit'),
        );

        $dynamic_styles['comptab10'] =  array($group_class . '.tabs-left.tab-arrows > .nav-pills>li>a',
            array('border-radius', ' 0'),
        );

        $dynamic_styles['comptab15'] =  array($group_class . ' > .nav-pills>li.active>a',
            array('background-color', $jma_spec_options['footer_font_color'] . '!important'),
            array('color', $jma_spec_options['footer_background_color']),
        );
        $dynamic_styles['comptab20'] =  array($group_class . ' > .nav>li>a:hover',
            array('opacity', '0.9'),
        );
        $dynamic_styles['comptab25'] =  array($group_class . ' > .nav>li.active>a:hover',
            array('opacity', '1'),
        );

        return $dynamic_styles;
    }
}
