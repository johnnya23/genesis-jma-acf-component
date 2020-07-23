<?php
class JMAComponentAccordion extends JMAComponent
{
    public function markup()
    {
        $content = $this->content;
        $accordion_array = $content['accordion_tabs_content'];
        if (!(is_array($accordion_array) && count($accordion_array))) {//returns if $accordion_array not useful
            return;
        }
        $return = '<div ';
        $return .= 'id="' . $content['accordion_comp_id'] . '" ';
        $return .= 'class="accordion panel-group jma-component jma-' . strtolower($content['acf_fc_layout']) . ' ' . $content['custom_class'] . '"';
        $return .= '>';
        foreach ($accordion_array as $i => $accordion_pair) {
            if (!$accordion_pair['hide']) {
                $in = '';
                $trigger = 'collapsed';
                $aria_expanded = 'false';
                if ($content['open'] && !$i) {
                    $trigger = '';
                    $aria_expanded = 'true';
                    $in = ' show';
                }

                $return .= '<div class="card panel panel-default">';// panel-default
                $return .= '<div id="' . $content['accordion_comp_id'] . $i . '" class="card-header">';//panel-header
                $return .= '<button class="panel-title btn btn-link ' . $trigger . '" data-toggle="collapse" data-target="#collapse' . $content['accordion_comp_id'] . $i . '" aria-expanded="' . $aria_expanded . '" aria-controls="collapse' . $content['accordion_comp_id'] . $i . '"">';
                $return .= '<i class="fas fa-angle-right"></i>' . $accordion_pair['accordion_tab'];
                $return .= '</button>';
                $return .= '</div><!--panel-header-->';
                $return .= '<div id="collapse' . $content['accordion_comp_id'] . $i . '" class="collapse' . $in . '" data-parent="#' . $content['accordion_comp_id'] . '" aria-labelledby="' . $content['accordion_comp_id'] . $i . '""><div class="card-body">';
                foreach ($accordion_pair['accordion_contents'] as $element) {
                    switch ($element['acf_fc_layout']) {
                    case 'text_content':
                        $return .= apply_filters('the_content', $element['accordion_content']);
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

    public static function css_filter($mods = array())
    {
        $group_class = '.jma-component.accordion';

        $dynamic_styles[] =  array(
            'selector' => $group_class . ' .card-header button',
            'background-color'=> $mods['footer_font_color'],
            'border-color'=> $mods['footer_bg_color'],
        );
        $dynamic_styles[] =  array(
            'selector' => $group_class . ' .card-header button',
            'color'=> $mods['footer_bg_color'],
        );
        $dynamic_styles[] =  array(
            'selector' => $group_class . ' .card-header:hover',
            'opacity'=> '0.85'
        );

        $dynamic_styles[] =  array(
            'selector' => $group_class . ' .card-header button.collapsed',
            'background-color'=> $mods['footer_bg_color'],
            'border-color'=> $mods['footer_font_color'],
        );
        $dynamic_styles[] =  array(
            'selector' => $group_class . ' .card-header button.collapsed',
            'color'=> $mods['footer_font_color'],
        );
        /*$dynamic_styles[] =  array(
            'selector' => $group_class . '.panel-group .panel-default>.panel-heading button.active-trigger:hover',
            'opacity'=> '1'
        );
        $dynamic_styles[] =  array(
            'selector' => $group_class . '.panel-group .tb-toggle.panel-default>.panel-heading .panel-title.active-trigger',
            'background-color'=> $mods['footer_font_color'],
        );
        $dynamic_styles[] =  array(
            'selector' => $group_class . '.panel-group .panel button.active-trigger',
            'color'=> $mods['footer_bg_color'],
        );*/
        $dynamic_styles[] =  array(
            'selector' => '.jma-component.accordion .panel-collapse > div',
            'padding'=> '20px',
        );
        return $dynamic_styles;
    }
}
