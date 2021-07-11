<?php

class HTMLPurifierX_ElementDef extends HTMLPurifier_ElementDef
{

    /**
     * @var bool is it custom html element
     */
    public $custom_element = false;

    public static function copyFrom (HTMLPurifier_ElementDef $def)
    {
        $xdef = new self();
        $xdef->autoclose = $def->autoclose;
        $xdef->child = $def->child;
        $xdef->excludes = $def->excludes;
        $xdef->attr = $def->attr;
        $xdef->attr_transform_pre = $def->attr_transform_pre;
        $xdef->attr_transform_post = $def->attr_transform_post;
        $xdef->required_attr = $def->required_attr;
        $xdef->descendants_are_inline = $def->descendants_are_inline;
        $xdef->formatting = $def->formatting;
        $xdef->standalone = $def->standalone;
        $xdef->wrap = $def->wrap;
        return $xdef;
    }

    public function setCustomElement($c)
    {
        $this->custom_element = !!$c;
    }

}
