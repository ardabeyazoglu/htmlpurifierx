<?php

class HTMLPurifierX_Generator extends HTMLPurifier_Generator
{

    /**
     * Whether or not generator should produce XML output.
     * @type bool
     */
    protected $_xhtml = true;

    /**
     * Cache of HTMLDefinition during HTML output to determine whether or
     * not attributes should be minimized.
     * @type HTMLPurifier_HTMLDefinition
     */
    protected $_def;

    /**
     * Cache of %Output.SortAttr.
     * @type bool
     */
    protected $_sortAttr;

    /**
     * Cache of %Output.FixInnerHTML.
     * @type bool
     */
    protected $_innerHTMLFix;

    /**
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     */
    public function __construct($config, $context)
    {
        parent::__construct($config, $context);

        $this->_innerHTMLFix = $config->get('Output.FixInnerHTML');
        $this->_sortAttr = $config->get('Output.SortAttr');
        $this->_def = $config->getHTMLDefinition();
        $this->_xhtml = $this->_def->doctype->xml;
    }

    /**
     * Generates attribute declarations from attribute array.
     * @note This does not include the leading or trailing space.
     * @param array $assoc_array_of_attributes Attribute array
     * @param string $element Name of element attributes are for, used to check
     *        attribute minimization.
     * @return string Generated HTML fragment for insertion.
     */
    public function generateAttributes($assoc_array_of_attributes, $element = '')
    {
        $html = '';
        if ($this->_sortAttr) {
            ksort($assoc_array_of_attributes);
        }

        $allowNamespacedAttrs = $this->config->get('HTML.AllowNamespacedAttributes');

        foreach ($assoc_array_of_attributes as $key => $value) {
            if (!$this->_xhtml) {
                // Remove namespaced attributes
                if (!$allowNamespacedAttrs && strpos($key, ':') !== false) {
                    continue;
                }
                // Check if we should minimize the attribute: val="val" -> val
                if ($element && !empty($this->_def->info[$element]->attr[$key]->minimized)) {
                    $html .= $key . ' ';
                    continue;
                }
            }
            // Workaround for Internet Explorer innerHTML bug.
            // Essentially, Internet Explorer, when calculating
            // innerHTML, omits quotes if there are no instances of
            // angled brackets, quotes or spaces.  However, when parsing
            // HTML (for example, when you assign to innerHTML), it
            // treats backticks as quotes.  Thus,
            //      <img alt="``" />
            // becomes
            //      <img alt=`` />
            // becomes
            //      <img alt='' />
            // Fortunately, all we need to do is trigger an appropriate
            // quoting style, which we do by adding an extra space.
            // This also is consistent with the W3C spec, which states
            // that user agents may ignore leading or trailing
            // whitespace (in fact, most don't, at least for attributes
            // like alt, but an extra space at the end is barely
            // noticeable).  Still, we have a configuration knob for
            // this, since this transformation is not necesary if you
            // don't process user input with innerHTML or you don't plan
            // on supporting Internet Explorer.
            if ($this->_innerHTMLFix) {
                if (strpos($value, '`') !== false) {
                    // check if correct quoting style would not already be
                    // triggered
                    if (strcspn($value, '"\' <>') === strlen($value)) {
                        // protect!
                        $value .= ' ';
                    }
                }
            }
            $html .= $key.'="'.$this->escape($value).'" ';
        }
        return rtrim($html);
    }

}
