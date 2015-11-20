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
     * @var array
     */
    public $options;

    /**
     * Placeholder tag
     * @var string
     */
    public $tag = 'div';
    
    /**
     * Html attributes of placeholder
     * @var array
     */
    public $htmlOptions = [];
    
    /**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        if (empty($htmlOptions['id'])) {
            $htmlOptions['id'] = $this->getId();
        }
        echo Html::beginTag($this->tag, $this->htmlOptions);
    }
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        echo Html::endTag($this->tag);

        $view = $this->getView();
        
        JVectorMapAsset::register($view);
        if ($this->map) {
            JVectorMapAsset::registerMap($this->map);
        }
        $options = $this->options;
        if ($this->map) {
            $options['map'] = str_replace('-', '_', $this->map);
        }
        $options = Json::encode($options);
        $view->registerJs("jQuery('#{$this->htmlOptions['id']}').vectorMap($options);");
    }
}