<?php
/**
 * Explodify plugin for Craft CMS 3.x
 *
 * Plugin for exploding the contents of an uploaded ZIP file
 *
 * @link      http://ournameismud.co.uk/
 * @copyright Copyright (c) 2018 @cole007
 */

namespace ournameismud\explodify\models;

use ournameismud\explodify\Explodify;

use Craft;
use craft\base\Model;

/**
 * @author    @cole007
 * @package   Explodify
 * @since     1.0.0
 */
class Resource extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $someAttribute = 'Some Default';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['someAttribute', 'string'],
            ['someAttribute', 'default', 'value' => 'Some Default'],
        ];
    }
}
