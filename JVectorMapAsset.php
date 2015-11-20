<?php
/**
 * @link https://github.com/borodulin/yii2-jvectormap
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-jvectormap/blob/master/LICENSE
 */

namespace conquer\jvectormap;

use yii\base\InvalidConfigException;

/**
 * @link http://jvectormap.com/
 */
class JVectorMapAsset extends \yii\web\AssetBundle
{
    private static $_maps=[];
    
    public $css = [
            'jquery-jvectormap-2.0.3.css',
    ];
    
    public $js = [
            'jquery-jvectormap-2.0.3.min.js',
    ];

    public $depends = [
            'yii\web\JqueryAsset',
    ];
    
    public function init()
    {
        $this->sourcePath = dirname(__FILE__) . '/assets';
        parent::init();
    }
    
    public function registerAssetFiles($view)
    {
        foreach (self::$_maps as $map){
            $jsname = "maps/jquery-jvectormap-" . str_replace('_', '-', $map) . ".js";
            if (file_exists($this->sourcePath . "/" . $jsname)) {
                $this->js[] = $jsname;
            } else {
                throw new InvalidConfigException("Map {$map} is not found.");
            }
        }
        parent::registerAssetFiles($view);
    }
    
    public static function registerMap($map)
    {
        self::$_maps[$map] = $map;
    } 
}