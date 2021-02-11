<?php

namespace Grav\Plugin\Shortcodes;

use Grav\Plugin\SVGIconsPlugin;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class SVGIconShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('svg-icon', function(ShortcodeInterface $sc) {

            // Get shortcode content and parameters
            $icon = $sc->getParameter('icon', $sc->getParameter('svg-icon', $this->getBbCode($sc)));
            $set = $sc->getParameter('set', 'tabler');
            $classes = $sc->getParameter('class', 'svg-icon inline-block align-middle');
            $path = str_replace('|', '/', $set) . "/" . $icon . ".svg";

            $svg = SVGIconsPlugin::svgIconFunction($path, $classes);

            if ($svg) return $svg;

            return '';
        });
    }
}