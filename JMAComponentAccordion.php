<?php
class JMAComponentAccordion extends JMAComponent
{
    public function markup()
    {
        $content = $this->content;
        $accordion_array = $content['tabs_content'];
        if (!(is_array($accordion_array) && count($accordion_array))) {//returns if $accordion_array not useful
            return;
        }
        $return = '<div ';
        $return .= 'id="' . $content['comp_id'] . '" ';
        $return .= 'class="tb-accordion panel-group jma-component jma-' . strtolower($content['acf_fc_layout']) . ' ' . $content['custom_class'] . '"';
        $return .= '>';
        foreach ($accordion_array as $i => $accordion_pair) {
            if (!$accordion_pair['hide']) {
                $trigger = $in = '';
                if ($content['open'] && !$i) {
                    $trigger = ' active-trigger';
                    $in = ' in';
                }

                $return .= '<div class="tb-toggle panel panel-default">';// panel-default
            $return .= '<div class="panel-heading">';//panel-heading
            $return .= '<a class="accordion-cat panel-title' . $trigger . '" data-toggle="collapse" data-parent="#accordion" href="#collapse' . $content['comp_id'] . $i . '">';
                $return .= '<i class="fas fa-angle-right icon-show switch-me"></i><i class="fas fa-angle-down icon-hide switch-me"></i>' . $accordion_pair['tab'];
                $return .= '</a>';
                $return .= '</div><!--panel-heading-->';
                $return .= '<div id="collapse' . $content['comp_id'] . $i . '" class="panel-collapse collapse' . $in . '"><div>';
                foreach ($accordion_pair['contents'] as $element) {
                    switch ($element['acf_fc_layout']) {
                    case 'text_content':
                        $return .= apply_filters('the_content', $element['content']);
                        break;
                    case 'song':
                        $song_ob = $element['song'];
                        if ($element['w_title']) {
                            $return .= '<h3>' . apply_filters('the_title', $song_ob->post_title) . '</h3>';
                        }
                        $return .= apply_filters('the_content', $song_ob->post_content);
                        break;
                }
                }

                $return .= '</div></div></div><!--panel-default-->';
            }
        }
        $return .= '</div><!--panel-group-->';
        return $return;
    }

    public function css()
    {
        $content = $this->content;
        $group_class = '#' . $this->id . '.jma-component.jma-accordion';
        if ($content['inactive_bg']) {
            $return = $group_class . '.panel-group .panel-default>.panel-heading a {
            background-color: ' . $content['inactive_bg'] . ';
            border-color: #cccccc;
        }';
        }
        if ($content['inactive_text']) {
            $return .= $group_class . '.panel-group .panel-default>.panel-heading a {
            color: ' . $content['inactive_text'] . ';
        }';
        }
        if ($content['active_bg']) {
            $return .=  $group_class . '.panel-group .panel-default>.panel-heading a.active-trigger {
            background-color: ' . $content['active_bg'] . ';
        }';
        }
        if ($content['active_text']) {
            $return .=  $group_class . '.panel-group .panel-default>.panel-heading a.active-trigger {
            color: ' . $content['active_text'] . ';
        }';
        }

        return $return;
    }

    public static function css_filter()
    {
        $group_class = '.jma-component.jma-accordion';
        $jma_spec_options = jma_get_theme_values();//echo '<pre>';print_r($jma_spec_options);echo '</pre>';

        $dynamic_styles['compacc'] =  array($group_class . '.panel-group .panel-default>.panel-heading a',
            array('background-color', $jma_spec_options['footer_background_color']),
            array('border-color', $jma_spec_options['footer_font_color']),
            array('color', $jma_spec_options['footer_font_color'])
        );
        $dynamic_styles['compacc05'] =  array($group_class . '.panel-group .panel-default>.panel-heading a:hover',
            array('opacity', '0.9')
        );
        $dynamic_styles['compacc07'] =  array($group_class . '.panel-group .panel-default>.panel-heading a.active-trigger:hover',
            array('opacity', '1')
        );
        $dynamic_styles['compacc10'] =  array($group_class . '.panel-group .tb-toggle.panel-default>.panel-heading .panel-title.active-trigger',
            array('background-color', $jma_spec_options['footer_font_color']),
        );
        $dynamic_styles['compacc15'] =  array($group_class . '.panel-group .panel a.active-trigger',
            array('color', $jma_spec_options['footer_background_color']),
        );
        $dynamic_styles['compacc20'] =  array('.jma-component.jma-accordion .panel-collapse > div',
            array('padding', '20px'),
        );
        return $dynamic_styles;
    }
}
