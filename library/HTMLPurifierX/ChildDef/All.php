<?php

class HTMLPurifierX_ChildDef_All extends HTMLPurifier_ChildDef
{
    /**
     * @type string
     */
    public $type = 'all';

    /**
     * @param HTMLPurifier_Node[] $children
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return bool
     */
    public function validateChildren($children, $config, $context)
    {
        return true;
    }
}
