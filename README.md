HTMLPurifierX
=============

HTMLPurifierX is a small wrapper around original [ezyang/htmlpurifier](https://github.com/ezyang/htmlpurifier) library, providing a few more customization options. It is also compatible with [xemlock/htmlpurifier-html5](https://github.com/xemlock/htmlpurifier-html5).

It extends configuration options to allow following features that can't be accomplished otherwise, because htmlpurifier follows a design principle based on whitelisting single elements and attributes. 

- Allow all data-* attributes on elements, since they are harmless and used very often.

        $configObject->set("HTML.AllowDataAttributes", true);

- Allow namespaced attributes like "xxx:yyyyyy" on elements, since they are used in modern frameworks.

        $configObject->set("HTML.AllowNamespacedAttributes", true);

- Allow all custom element names that satisy a regex, since they are widely used in web components and modern frameworks. A common use case would be to allow all elements with a user defined prefix.

        $configObject->set("HTML.AllowCustomElements", true);
        // default "": means allow all custom elements.
        $configObject->set("HTML.AllowCustomElementsRegex", "/^x-/");
        
        
Check [examples](examples/) to see how it works.

## Installation

    composer require ardabeyazoglu/htmlpurifierx
    
This will also install original ezyang/htmlpurifier library if not already installed.

## Why not fork ?

The customizations made here might be against the design principles of original library. It is easier and faster to provide them as an extra layer. 

## License

LGPL (same as the original library)