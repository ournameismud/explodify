<?php
/**
 * Explodify plugin for Craft CMS 3.x
 *
 * Plugin for exploding the contents of an uploaded ZIP file
 *
 * @link      http://ournameismud.co.uk/
 * @copyright Copyright (c) 2018 @cole007
 */

namespace ournameismud\explodify\fields;

use ournameismud\explodify\Explodify;
use ournameismud\explodify\assetbundles\resourcefield\ResourceFieldAsset;

use Craft;
use craft\helpers\App;
use craft\base\ElementInterface;
use craft\elements\Asset;
use craft\base\Field;
use craft\helpers\Db;
use craft\helpers\ElementHelper;
use craft\helpers\Json;
use yii\db\Schema;
// use yii\log\Logger;

/**
 * @author    @cole007
 * @package   Explodify
 * @since     1.0.0
 */
class Resource extends Field
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $volumes = [];

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('explodify', 'Explodify: Resource');
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = array_merge($rules, [            
        ]);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
        
        return $value;
    }

    /**
     * @inheritdoc
     */
    public function serializeValue($value, ElementInterface $element = null)
    {
        
        // get file id
        $id = $value[0];
        // get asset object
        $asset = Craft::$app->getAssets()->getAssetById($id);
        // get entry slug        
        $slug = $element->slug;
        
        // zip directory/destination
        $dest = 'resources/explodify/' . $slug . '/';

        // get temporary file path
        $tmpAsset = $asset->getCopyOfFile();
    
        $newDir = $dest . ElementHelper::createSlug($asset->title); 
        if (file_exists($newDir)) {
            return $value;
        } 

        // initiate zipArchive class
        // http://php.net/manual/en/ziparchive.extractto.php
        /*
        @carlcs
        if ($asset->tempFilePath) {
            $path = $asset->tempFilePath;
        } else {
            $path = $asset->getVolume()->path.DIRECTORY_SEPARATOR.$asset->getUri();
        }
        */        
        $zip = new \ZipArchive;
        App::maxPowerCaptain();
        // if open 
        if ($zip->open($tmpAsset) === TRUE) {
            // extract to path and close
            // http://php.net/manual/en/ziparchive.extractto.php
            $dirName = $zip->getNameIndex(0);
            $zip->extractTo($dest);
            $zip->close();              
            
            $rename = rename($dest . $dirName, $newDir);
            // $filename = $asset->filename          
            // need log here
            if ($rename) Craft::info('File unzipped and renamed successfully for entry ['.$slug.']');
            else Craft::info('File unzipped successfully but could not rename for entry ['.$slug.']');
            // Yii::getLogger()->log('', Logger::LEVEL_INFO, true);
            // static::getLogger()->log('File unzipped successfully', Logger::LEVEL_INFO);
            // Craft::dd('unzipped');
        } else {
            // need log here
            Craft::info('File unzipped successfully');
        }
        // FileHelper::removeFile($tmpAsset);
        // return parent::serializeValue($value, $element);
        return $value;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        $vols = Craft::$app->volumes->getAllVolumes();
        // Render the settings template
        return Craft::$app->getView()->renderTemplate(
            'explodify/_components/fields/Resource_settings',
            [
                'field' => $this,
                'volumes' => Asset::sources(),
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        // Register our asset bundle
        Craft::$app->getView()->registerAssetBundle(ResourceFieldAsset::class);
        // Get our id and namespace
        $id = Craft::$app->getView()->formatInputId($this->handle);
        $namespacedId = Craft::$app->getView()->namespaceInputId($id);

        // Variables to pass down to our field JavaScript to let it namespace properly
        $jsonVars = [
            'id' => $id,
            'name' => $this->handle,
            'namespace' => $namespacedId,
            'prefix' => Craft::$app->getView()->namespaceInputId(''),
            ];
        $jsonVars = Json::encode($jsonVars);
        Craft::$app->getView()->registerJs("$('#{$namespacedId}-field').ExplodifyResource(" . $jsonVars . ");");

        $elements = [];
        $decoded = json_decode($value);
        if ($decoded) {
            foreach ($decoded AS $id) {
                $elements = [Craft::$app->getAssets()->getAssetById($id)];
            }    
        }

        // Render the input template
        return Craft::$app->getView()->renderTemplate(
            'explodify/_components/fields/Resource_input',
            [
                'name' => $this->handle,
                'volumes' => $this->volumes,
                'value' => $value,
                'elements' => $elements,
                'field' => $this,
                'id' => $id,
                'namespacedId' => $namespacedId,
                'elementType' => Asset::class,
            ]
        );
    }
}
