<?php
/**
 * @link https://github.com/borodulin/yii2-jvectormap
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-jvectormap/blob/master/LICENSE
 */
namespace conquer\jvectormap;

use yii\helpers\Html;
use yii\helpers\Json;
/**
 * @link http://jvectormap.com/
 */
class JVectorMapWidget extends \yii\base\Widget
{

	/**
	 * Map name
	 * @var string
	 */
	public $map;
	
	/**
	 * General Map options
	 * @link http://jvectormap.com/documentation/javascript-api/jvm-map/
	 * @var array()
	 */
	public $options;
	
	/**
	 * Html attributes of placeholder
	 * @var array()
	 */
	public $htmlOptions;
	
	/**
	 * Initializes the widget.
	 * If you override this method, make sure you call the parent implementation first.
	 */
	public function init()
	{
		parent::init();
		JVectorMapAsset::register($this->view);
		if($this->map)
			JVectorMapAsset::registerMap($this->map);
	}
	
	/**
	 * @inheritdoc
	 */
	public function run()
	{
		$view = $this->getView();
		$htmlOptions=$this->htmlOptions;
		if(empty($htmlOptions['id']))
			$htmlOptions['id'] = $this->getId();
		$options=$this->options;
		if($this->map)
			$options['map']=str_replace('-', '_', $this->map);
		$options=Json::encode($options);
		$view->registerJs("console.log(jvm.Map.maps,'$this->map'); jQuery('#{$htmlOptions['id']}').vectorMap($options);");
		return Html::tag('div','',$htmlOptions);
	}
}