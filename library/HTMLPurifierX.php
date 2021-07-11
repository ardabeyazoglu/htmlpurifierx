<?php

class HTMLPurifierX extends HTMLPurifier
{

    /**
     * Array of extra filter objects to run on HTML,
     * for backwards compatibility.
     * @type HTMLPurifier_Filter[]
     */
    protected $filters = array();

    /**
     * Single instance of HTML Purifier.
     * @type HTMLPurifier
     */
    protected static $instance;

    /**
     * Initializes the purifier.
     *
     * @param HTMLPurifier_Config|mixed $config Optional HTMLPurifier_Config object
     *                for all instances of the purifier, if omitted, a default
     *                configuration is supplied (which can be overridden on a
     *                per-use basis).
     *                The parameter can also be any type that
     *                HTMLPurifier_Config::create() supports.
     */
    public function __construct($config = null)
    {
        parent::__construct($config);

        $this->strategy = new HTMLPurifierX_Strategy_Core();
    }

    public function addFilter($filter)
    {
        trigger_error(
            'HTMLPurifier->addFilter() is deprecated, use configuration directives' .
            ' in the Filter namespace or Filter.Custom',
            E_USER_WARNING
        );
        $this->filters[] = $filter;
    }

    /**
     * Filters an HTML snippet/document to be XSS-free and standards-compliant.
     *
     * @param string $html String of HTML to purify
     * @param HTMLPurifier_Config $config Config object for this operation,
     *                if omitted, defaults to the config object specified during this
     *                object's construction. The parameter can also be any type
     *                that HTMLPurifier_Config::create() supports.
     *
     * @return string Purified HTML
     */
    public function purify($html, $config = null)
    {
        // :TODO: make the config merge in, instead of replace
        $config = $config ? HTMLPurifier_Config::create($config) : $this->config;

        // implementation is partially environment dependant, partially
        // configuration dependant
        $lexer = HTMLPurifier_Lexer::create($config);

        $context = new HTMLPurifier_Context();

        // setup HTML generator
        $this->generator = new HTMLPurifierX_Generator($config, $context);
        $context->register('Generator', $this->generator);

        // set up global context variables
        if ($config->get('Core.CollectErrors')) {
            // may get moved out if other facilities use it
            $language_factory = HTMLPurifier_LanguageFactory::instance();
            $language = $language_factory->create($config, $context);
            $context->register('Locale', $language);

            $error_collector = new HTMLPurifier_ErrorCollector($context);
            $context->register('ErrorCollector', $error_collector);
        }

        // setup id_accumulator context, necessary due to the fact that
        // AttrValidator can be called from many places
        $id_accumulator = HTMLPurifier_IDAccumulator::build($config, $context);
        $context->register('IDAccumulator', $id_accumulator);

        $html = HTMLPurifier_Encoder::convertToUTF8($html, $config, $context);

        // setup filters
        $filter_flags = $config->getBatch('Filter');
        $custom_filters = $filter_flags['Custom'];
        unset($filter_flags['Custom']);
        $filters = array();
        foreach ($filter_flags as $filter => $flag) {
            if (!$flag) {
                continue;
            }
            if (strpos($filter, '.') !== false) {
                continue;
            }
            $class = "HTMLPurifier_Filter_$filter";
            $filters[] = new $class;
        }
        foreach ($custom_filters as $filter) {
            // maybe "HTMLPurifier_Filter_$filter", but be consistent with AutoFormat
            $filters[] = $filter;
        }
        $filters = array_merge($filters, $this->filters);
        // maybe prepare(), but later

        for ($i = 0, $filter_size = count($filters); $i < $filter_size; $i++) {
            $html = $filters[$i]->preFilter($html, $config, $context);
        }

        // purified HTML
        $html =
            $this->generator->generateFromTokens(
            // list of tokens
                $this->strategy->execute(
                // list of un-purified tokens
                    $lexer->tokenizeHTML(
                    // un-purified HTML
                        $html,
                        $config,
                        $context
                    ),
                    $config,
                    $context
                )
            );

        for ($i = $filter_size - 1; $i >= 0; $i--) {
            $html = $filters[$i]->postFilter($html, $config, $context);
        }

        $html = HTMLPurifier_Encoder::convertFromUTF8($html, $config, $context);
        $this->context =& $context;
        return $html;
    }

}