<?php
class JMAComponent
{
    public $content;
    public $id;


    public function __construct($content)
    {
        $this->content = $content;
        $this->id = $content['comp_id'];
    }

    public function css()
    {
        $return = '';
        return $return;
    }

    public static function css_filter()
    {
        $return = array();
        return $return;
    }
}
