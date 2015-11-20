<?php
/**
 * @link https://github.com/borodulin/yii2-jvectormap
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-jvectormap/blob/master/LICENSE
 */

namespace conquer\jvectormap;

/**
 * @link http://jvectormap.com/
 */
class JVectorMapAsset extends \yii\web\AssetBundle
{
    private static $_maps=[];
    
    public $css = [
            'jquery-jvectormap-2.0.2.css',
    ];
    
    public $js = [
            'jquery-jvectormap-2.0.2.min.js',
    ];

    public $depends = [
            'yii\web\JqueryAsset',
    ];
    
    public function init()
    {
        $this->sourcePath = dirname(__FILE__) . '\assets';
        parent::init();
    }
    
    public function registerAssetFiles($view)
    {
        foreach (self::$_maps as $map){
            $this->js[] = "maps/jquery-jvectormap-$map.js";
        }
        parent::registerAssetFiles($view);
    }
    
    public static function registerMap($map)
    {
        self::$_maps[$map] = $map;
    } 
}