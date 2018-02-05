<?php
/**
 * Explodify plugin for Craft CMS 3.x
 *
 * Plugin to save 
 *
 * @link      http://ournameismud.co.uk/
 * @copyright Copyright (c) 2018 @cole007
 */

namespace ournameismud\explodify\assetbundles\Explodify;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    @cole007
 * @package   Explodify
 * @since     1.0.0
 */
class ExplodifyAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@ournameismud/explodify/assetbundles/explodify/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/Explodify.js',
        ];

        $this->css = [
            'css/Explodify.css',
        ];

        parent::init();
    }
}
