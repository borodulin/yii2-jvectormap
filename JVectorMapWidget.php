<?php
/**
 * @link https://github.com/borodulin/yii2-jvectormap
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-jvectormap/blob/master/LICENSE
 */

namespace conquer\jvectormap;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * jVectorMap widget
 *
 * ```php
 * <?= JVectorMapWidget::widget([
 *     'map' => 'world_mill_en',
 *     'htmlOptions' => ['style' => 'height: 500px'],
 *     'options' => ['backgroundColor' => '#fff'],
 * ]) ?>
 * ```
 *
 * @link https://jvectormap.com/documentation/javascript-api/jvm-map/
 * @author Andrey Borodulin
 */
class JVectorMapWidget extends Widget
{
    /**
     * Map name, e.g. `world_mill_en` (see JVectorMapAsset::availableMaps())
     * @var string
     */
    public $map;

    /**
     * jVectorMap options; use yii\web\JsExpression for callbacks such as `onRegionTipShow`
     * @link https://jvectormap.com/documentation/javascript-api/jvm-map/
     * @var array
     */
    public $options = [];

    /**
     * Placeholder tag
     * @var string
     */
    public $tag = 'div';

    /**
     * HTML attributes of the placeholder
     * @var array
     */
    public $htmlOptions = [];

    /**
     * Height of the placeholder unless set by its style or CSS classes (the map fills the placeholder,
     * so it is not visible with zero height). Null disables it.
     * @var string|null
     */
    public $defaultHeight = '400px';

    /**
     * @var string jVectorMap map name
     */
    private $_mapName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (empty($this->htmlOptions['id'])) {
            $this->htmlOptions['id'] = $this->getId();
        }
        if ($this->map) {
            // reports an unknown map right here, not when the page is rendered
            $this->_mapName = JVectorMapAsset::registerMap($this->map);
        }
        echo Html::beginTag($this->tag, $this->htmlOptions);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $view = $this->getView();
        JVectorMapAsset::register($view);

        $id = (string)$this->htmlOptions['id'];
        if ($this->defaultHeight !== null) {
            // :where() has no specificity, so any height from CSS classes or the style attribute wins
            $cssId = preg_replace_callback('/[^A-Za-z0-9_-]/u', function ($m) {
                return '\\' . dechex(mb_ord($m[0], 'UTF-8')) . ' ';
            }, $id);
            $view->registerCss(':where([id="' . $cssId . '"]){height:'
                . preg_replace('/[^\w.%() -]/', '', $this->defaultHeight) . '}');
        }

        $options = (array)$this->options;
        if ($this->_mapName !== null) {
            $options['map'] = $this->_mapName;
        }
        $view->registerJs('jQuery(document.getElementById(' . Json::htmlEncode($id) . ')).vectorMap('
            . (empty($options) ? '{}' : Json::htmlEncode($options)) . ');');

        return Html::endTag($this->tag);
    }
}
