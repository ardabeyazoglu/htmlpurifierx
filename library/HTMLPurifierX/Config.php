<?php

class HTMLPurifierX_Config extends HTMLPurifier_Config
{

    const REVISION = 2021071101;

    /**
     * Constructor
     * @param HTMLPurifier_ConfigSchema $definition ConfigSchema that defines
     * what directives are allowed.
     * @param HTMLPurifier_PropertyList $parent
     */
    public function __construct($definition, $parent = null)
    {
        $definition->add("HTML.AllowDataAttributes", false, "bool", false);
        $definition->add("HTML.AllowNamespacedAttributes", false, "bool", false);
        $definition->add("HTML.AllowCustomElements", false, "bool", false);
        $definition->add("HTML.AllowCustomElementsRegex", "", "string", true);

        parent::__construct($definition, $parent);
    }

    /**
     * Convenience constructor that creates a config object based on a mixed var
     * @param mixed $config Variable that defines the state of the config
     *                      object. Can be: a HTMLPurifier_Config() object,
     *                      an array of directives based on loadArray(),
     *                      or a string filename of an ini file.
     * @param HTMLPurifier_ConfigSchema $schema Schema object
     * @return HTMLPurifier_Config Configured object
     */
    public static function create($config, $schema = null)
    {
        if ($config instanceof HTMLPurifier_Config) {
            // pass-through
            return $config;
        }
        if (!$schema) {
            $ret = HTMLPurifierX_Config::createDefault();
        } else {
            $ret = new HTMLPurifierX_Config($schema);
        }
        if (is_string($config)) {
            $ret->loadIni($config);
        } elseif (is_array($config)) $ret->loadArray($config);
        return $ret;
    }

    /**
     * Convenience constructor that creates a default configuration object.
     * @return HTMLPurifierX_Config default object.
     */
    public static function createDefault()
    {
        $definition = HTMLPurifier_ConfigSchema::instance();
        $config = new HTMLPurifierX_Config($definition);
        return $config;
    }

    public static function copyFrom(HTMLPurifier_Config $config){
        $xconfig = new self($config->def);
        return $xconfig;
    }

}
