<?php
class JMAComponentTabbed extends JMAComponent
{
    public function markup()
    {
        $content = $this->content;
        $tabbed_array = $content['tabbed_tabs_content'];
        if (!(is_array($tabbed_array) && count($tabbed_array))) {//reutns if $tabbed_array not useful
            return;
        }
        $wrap_cl = '';
        $ul_pill_cl = 'tabs';
        if ($content['tabbed_display'] != 'tabs') {
            $wrap_cl .= 'tb-tabs-pills';
            $ul_pill_cl = 'pills';
        }
        if ($content['tabbed_display'] == 'arrows') {
            $wrap_cl .= ' tabs-left tab-arrows';
        } elseif ($content['tabbed_alignment'] == 'left') {
            $wrap_cl .= ' tabs-left';
        }

        $return = '<div ';
        $return .= 'id="' . $content['tabbed_comp_id'] . '" ';
        $return .= 'class="' . $content['tabbed_custom_class'] . ' ' . $wrap_cl . ' jma-component tabbed jma-' . $content['tabbed_comp_id'] . '"';
        $return .= '>';
        $tabs = '<ul class="nav nav-' . $ul_pill_cl . '">';
        $tab_content .= '<div class="tab-content">';
        foreach ($tabbed_array as $i => $tabbed_pair) {
            if (!$tabbed_pair['hide']) {
                $active =  '';
                $active_tab =  '';
                if (!$i) {
                    $active_tab =  ' active';
                    $active =  ' show active';
                }
                $tabs .= '<li class="nav-item ' . preg_replace("/(\W)+/", "", strtolower($tabbed_pair['tabbed_tab'])) . '">';
                $tabs .= '<a class="nav-link' . $active_tab . '" href="#tab_' . $content['tabbed_comp_id'] . $i . '" data-toggle="tab" title="Title #1">';
                $tabs .= $tabbed_pair['tabbed_tab'];
                $tabs .= '</a>';
                $tabs .= '</li>';


                $tab_content .= '<div id="tab_' . $content['tabbed_comp_id'] . $i . '" class="tab-pane fade clearfix' . $active . '">';

                $tab_content .= apply_filters('the_content', $tabbed_pair['tabbed_content']);
                $tab_content .= '</div><!--tab-pane-->';
            }
        }
        $tab_content .= '</div><!--tab-content-->';
        $tabs .= '</ul><!--nav-tabs-->';
        $return .=  $tabs . $tab_content;
        $return .= '</div><!--tabbable-->';
        return $return;
    }

    public static function css_filter($mods = array())
    {
        $group_class = '.jma-component.tabbed';
        //echo '<pre>';print_r($mods);echo '</pre>';


        $dynamic_styles[] =  array(
            'selector' =>  $group_class . '.tabs-left.tab-arrows > .nav-pills>li a.active:after',
            'query' => '(min-width:992px)',
            'content'=> '\'\'',
            'right'=> '-40px',
            'top'=> ' 0',
            'width'=> ' 0',
            'height'=> ' 0',
            'position'=> 'absolute',
            'border-style'=> 'solid',
            'border-width'=> '20px 0 20px 25px',
            'border-color'=> 'transparent transparent transparent ' . $mods['footer_font_color']
        );

        $dynamic_styles[] =  array(
            'selector' => $group_class . ' > .nav>li>a',
            'background-color'=> $mods['footer_bg_color'],
            'color'=> $mods['footer_font_color'],
        );

        $dynamic_styles[] =  array(
            'selector' => $group_class . ' > .nav-tabs>li a.active',
            /*'background-color'=> 'inherit!important',*/
            'color'=> 'inherit',
        );

        $dynamic_styles[] =  array(
            'selector' => $group_class . '.tabs-left.tab-arrows > .nav-pills>li>a',
            'border-radius'=> ' 0',
        );

        $dynamic_styles[] =  array(
            'selector' => $group_class . '.tabs-left.tab-arrows > .nav-pills>li>a.active',
            'width' => '170px',
        );

        $dynamic_styles[] =  array(
            'selector' => $group_class . ' > .nav-pills>li a.active',
            'background-color'=> $mods['footer_font_color'] . '!important',
            'color'=> $mods['footer_bg_color'],
        );
        $dynamic_styles[] =  array(
            'selector' => $group_class . ' > .nav>li>a:hover',
            'opacity' => '0.85',
        );
        $dynamic_styles[] =  array(
            'selector' => $group_class . ' > .nav>li>a.active:hover',
            'opacity'=> '1',
        );

        return $dynamic_styles;
    }
}
