<?php

/**
 * Core strategy composed of the big four strategies.
 */
class HTMLPurifierX_Strategy_Core extends HTMLPurifier_Strategy_Composite
{
    public function __construct()
    {
        $this->strategies[] = new HTMLPurifierX_Strategy_RemoveForeignElements();
        $this->strategies[] = new HTMLPurifierX_Strategy_MakeWellFormed();
        $this->strategies[] = new HTMLPurifierX_Strategy_FixNesting();
        $this->strategies[] = new HTMLPurifierX_Strategy_ValidateAttributes();
    }
}
