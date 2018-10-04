<?php
/**
 * Explodify plugin for Craft CMS 3.x
 *
 * Plugin for exploding the contents of an uploaded ZIP file
 *
 * @link      http://ournameismud.co.uk/
 * @copyright Copyright (c) 2018 @cole007
 */

namespace ournameismud\explodify;

use ournameismud\explodify\variables\ExplodifyVariable;
use ournameismud\explodify\models\Settings;
use ournameismud\explodify\fields\Resource as ResourceField;

use Craft;
use craft\base\Plugin;
use craft\base\ElementInterface;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\services\Fields;
use craft\web\twig\variables\CraftVariable;
use craft\events\RegisterComponentTypesEvent;

use yii\base\Event;

/**
 * Class Explodify
 *
 * @author    @cole007
 * @package   Explodify
 * @since     1.0.0
 *
 * @property  ResourceService $resource
 */
class Explodify extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var Explodify
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.5';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    
    public function init()
    {
        parent::init();
        self::$plugin = $this;
        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = ResourceField::class;
            }
        );

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('explodify', ExplodifyVariable::class);
            }
        );        

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'explodify',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'explodify/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
