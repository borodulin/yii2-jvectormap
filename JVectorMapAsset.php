<?php
/**
 * @link https://github.com/borodulin/yii2-jvectormap
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-jvectormap/blob/master/LICENSE
 */

namespace conquer\jvectormap;

use yii\base\InvalidConfigException;
use yii\web\AssetBundle;

/**
 * jVectorMap 2.0.3 with the bundled maps
 * @link https://jvectormap.com/
 * @author Andrey Borodulin
 */
class JVectorMapAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/assets';

    public $css = [
        'jquery-jvectormap-2.0.3.css',
    ];

    public $js = [
        'jquery-jvectormap-2.0.3.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    /**
     * @var array map name => map file, registered with registerMap()
     */
    private static $_maps = [];

    /**
     * @var array|null cache of availableMaps()
     */
    private static $_available;

    /**
     * @inheritdoc
     */
    public function registerAssetFiles($view)
    {
        foreach (self::$_maps as $file) {
            if (!in_array($file, $this->js, true)) {
                $this->js[] = $file;
            }
        }
        parent::registerAssetFiles($view);
    }

    /**
     * Registers a map to be loaded with the bundle.
     * @param string $map map name, e.g. `world_mill_en`, `world-mill-en` or `fr_regions_merc_en`
     * @return string the map name used by jVectorMap, e.g. `world_mill_en`
     * @throws InvalidConfigException if the map is not bundled
     */
    public static function registerMap($map)
    {
        $name = static::normalizeMapName($map);
        $maps = static::availableMaps();
        if ($name === null || !isset($maps[$name])) {
            throw new InvalidConfigException('Map "' . $map . '" is not found. Available maps: '
                . implode(', ', array_keys($maps)) . '.');
        }
        self::$_maps[$name] = $maps[$name];
        return $name;
    }

    /**
     * @param string $map map name in any of the supported spellings
     * @return string|null the jVectorMap map name, null if the name is invalid
     */
    public static function normalizeMapName($map)
    {
        if (!is_string($map) || !preg_match('/^[a-z0-9_-]+$/i', $map)) {
            return null;
        }
        return strtolower(str_replace('-', '_', $map));
    }

    /**
     * @return array jVectorMap map name => file relative to the asset directory,
     * e.g. `fr_regions_merc_en` => `maps/jquery-jvectormap-fr_regions-merc-en.js`
     */
    public static function availableMaps()
    {
        if (self::$_available === null) {
            self::$_available = [];
            foreach (glob(__DIR__ . '/assets/maps/jquery-jvectormap-*.js') as $path) {
                $file = basename($path);
                $name = str_replace('-', '_', substr($file, strlen('jquery-jvectormap-'), -3));
                self::$_available[$name] = 'maps/' . $file;
            }
            ksort(self::$_available);
        }
        return self::$_available;
    }
}
