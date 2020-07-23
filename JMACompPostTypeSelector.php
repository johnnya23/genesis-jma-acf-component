<?php


class JMACompPostTypeSelector
{

    /*
    *  Construct
    *
    *  @description:
    *  @since: 3.6
    *  @created: 1/04/13
    */

    public function __construct()
    {


        // version 5+
        add_action('acf/include_field_types', array($this, 'include_field_types'));
    }

    /*
    *  register_fields
    *
    *  @description:
    *  @since: 3.6
    *  @created: 1/04/13
    */

    public function include_field_types()
    {
        new JMACompPostTypeSelectorField();
    }
}
