<?php
/**
 * Explodify plugin for Craft CMS 3.x
 *
 * Plugin for exploding the contents of an uploaded ZIP file
 *
 * @link      http://ournameismud.co.uk/
 * @copyright Copyright (c) 2018 @cole007
 */

namespace ournameismud\explodify\assetbundles\resourcefield;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    @cole007
 * @package   Explodify
 * @since     1.0.0
 */
class ResourceFieldAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@ournameismud/explodify/assetbundles/resourcefield/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/Resource.js',
        ];

        $this->css = [
            'css/Resource.css',
        ];

        parent::init();
    }
}
