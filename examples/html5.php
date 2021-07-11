<?php

ini_set("display_errors", 1);
require_once __DIR__ . "/../vendor/autoload.php";

$config = HTMLPurifierX_Config::createDefault();

$config->set('Cache.SerializerPath', sys_get_temp_dir());
$config->set('Cache.DefinitionImpl', null);

$config->set('AutoFormat.RemoveEmpty', false);

// custom config
$config->set('HTML.AllowDataAttributes', true);
$config->set('HTML.AllowNamespacedAttributes', true);
$config->set('HTML.AllowCustomElements', true);
$config->set('HTML.AllowCustomElementsRegex', "/^x-/");

$config->set('HTML.DefinitionID', "HTML5_TEST");

$config = HTMLPurifier_HTML5Config::inherit($config);

$html = <<<HTML
<div class="custom-components" data-ref="myDiv">
    <x-panel data-ref="customPanel">
        <x-body data-ref="customPanelBody">
            <x-button class="custom-btn" data-value="1" on:click="alert('...')">Custom Button</x-button>
        </x-body>
    </x-panel>
    <section>
        <article data-id="12345">
            test article
            <x-button class="custom-btn" data-value="1" on:click="alert('...')">Custom Button Inside HTML5 Node</x-button>
        </article>
    </section>
</div>
HTML;


$purifier = new HTMLPurifierX($config);
$html = $purifier->purify($html);

echo $html;