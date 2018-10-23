<?php
/**
 * Explodify plugin for Craft CMS 3.x
 *
 * Plugin for exploding the contents of an uploaded ZIP file
 *
 * @link      http://ournameismud.co.uk/
 * @copyright Copyright (c) 2018 @cole007
 */

namespace ournameismud\explodify\variables;

use ournameismud\explodify\Explodify;

use Craft;
use craft\helpers\App;
use craft\base\Element;
use craft\elements\Asset;
use craft\elements\Entry;
use craft\helpers\ElementHelper;

/**
 * @author    @cole007
 * @package   Explodify
 * @since     1.0.0
 */
class ExplodifyVariable
{
    // Public Methods
    // =========================================================================
    private function returnElements($path) 
    {
        $response = [];
        $pathArray = explode('/',$path);
        $folder = array_pop($pathArray);
        $path = join($pathArray,'/') . '/' . ElementHelper::createSlug($folder);
        if ($handle = opendir($path)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    switch($entry) {
                        case 'skin.js':
                        $response['skin'] = $path . '/' . $entry;
                        break;
                        case 'pano.xml':
                        $response['xml'] = $path . '/' . $entry;
                        break;
                        case 'pano2vr_player.js':
                        $response['player'] = $path . '/' . $entry;
                        break;
                    }                    
                }
            }
            $response['root'] = $path . '/';
            // return $response;
            // echo "Directory handle: $handle\n";
        }
        return $response;
    }
    /**
     * @param null $optional
     * @return string
     */
    public function getAssets($entry_id, $field)
    {
        
        // echo $optional;
        $ids = json_decode($field);
        
        $entry = Craft::$app->entries->getEntryById($entry_id);
        $slug = $entry->slug;
        
        $response = [];
        
        foreach ($ids AS $id) {
            $asset = Craft::$app->getAssets()->getAssetById($id);
            $title = $asset->title;
            $path = 'resources/explodify/' . $slug.  '/' . $title;
            $response[] = $this->returnElements($path);
        }
        return $response;
    }
}
