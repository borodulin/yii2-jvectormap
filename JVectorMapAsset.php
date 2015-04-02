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
	// The files are not web directory accessible, therefore we need
	// to specify the sourcePath property. Notice the @bower alias used.
	// public $sourcePath = '@bower/jvectormap';
	
	private static $_maps=[];
	
	public $css=[
			'jquery-jvectormap-2.0.2.css',
	];
	
	public $js=[
			'jquery-jvectormap-2.0.2.min.js',
	];

	public function registerAssetFiles($view)
	{
		$this->sourcePath=dirname(__FILE__).\DIRECTORY_SEPARATOR.'assets';
		foreach (self::$_maps as $map){
			$this->js[]="maps/jquery-jvectormap-$map.js";
		}
		parent::init();
	}
	
	public static function registerMap($map)
	{
		self::$_maps[]=$map;
	} 
	
}