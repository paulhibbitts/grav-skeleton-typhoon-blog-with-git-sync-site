<?php

namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Grav;
use Grav\Common\Plugin;
use Grav\Common\Twig\TwigExtension;
use Grav\Common\Utils;
use Twig\TwigFunction;

/**
 * Class SVGIconsPlugin
 *
 * @package Grav\Plugin
 */
class SVGIconsPlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized'        => [
                ['autoload', 100000], // TODO: Remove when plugin requires Grav >=1.7
                ['onPluginsInitialized', 0]
            ],
            'onTwigInitialized'           => ['onTwigInitialized', 0],
            'onShortcodeHandlers'         => ['onShortcodeHandlers', 0],
            'registerNextGenEditorPlugin' => ['registerNextGenEditorPluginShortcodes', 0],
        ];
    }

    /**
     * Composer autoload.
     *
     * @return ClassLoader
     */
    public function autoload(): ClassLoader
    {
        return require __DIR__ . '/vendor/autoload.php';
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        // Enable the main events we are interested in
        $this->enable([
            // Put your main events here
        ]);
    }

    // Access plugin events in this class
    public function onTwigInitialized()
    {
        $twig = $this->grav['twig'];

        $twig->twig()->addFunction(
            new TwigFunction('svg_icon', [$this, 'svgIconFunction'])
        );
    }

    /**
     * Initialize configuration
     */
    public function onShortcodeHandlers()
    {
        $this->grav['shortcode']->registerAllShortcodes(__DIR__ . '/classes/shortcodes');
    }

    public function registerNextGenEditorPluginShortcodes($event) {
        $plugins = $event['plugins'];

        $plugins['js'][]  = 'plugin://svg-icons/nextgen-editor/shortcodes/svg-icons.js';

        $event['plugins']  = $plugins;
        return $event;
    }

    public static function svgIconFunction($path, $classes = null)
    {
        $path = Grav::instance()['locator']->findResource('plugins://svg-icons/icons/' . $path, true, true);
        return TwigExtension::svgImageFunction($path, $classes);
    }

}
