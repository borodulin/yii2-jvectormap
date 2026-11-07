<?php

namespace conquer\jvectormap\tests;

use conquer\jvectormap\JVectorMapAsset;
use conquer\jvectormap\JVectorMapWidget;
use PHPUnit\Framework\TestCase;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use yii\web\Application;
use yii\web\JsExpression;
use yii\web\View;

class JVectorMapWidgetTest extends TestCase
{
    private $assetPath;

    protected function setUp(): void
    {
        $this->assetPath = sys_get_temp_dir() . '/jvectormap-test-' . uniqid();
        mkdir($this->assetPath);
        new Application([
            'id' => 'test',
            'basePath' => __DIR__,
            'vendorPath' => dirname(__DIR__) . '/vendor',
            'aliases' => ['@bower' => '@vendor/bower-asset', '@npm' => '@vendor/npm-asset'],
            'components' => [
                'request' => ['cookieValidationKey' => 'test', 'scriptFile' => __DIR__ . '/index.php', 'scriptUrl' => '/index.php'],
                'assetManager' => ['basePath' => $this->assetPath, 'baseUrl' => '/assets'],
            ],
        ]);
        // the registry of maps is static
        (function () {
            self::$_maps = [];
        })->bindTo(null, JVectorMapAsset::class)();
    }

    protected function tearDown(): void
    {
        FileHelper::removeDirectory($this->assetPath);
        Yii::$app = null;
    }

    private function render(array $config)
    {
        $view = new View();
        Yii::$app->set('view', $view);
        $widget = JVectorMapWidget::widget($config);
        $html = $view->renderFile(__DIR__ . '/views/page.php');
        return [$widget, $html];
    }

    public function testWorldMap()
    {
        list($widget, $html) = $this->render(['map' => 'world_mill_en']);
        $this->assertSame('<div id="w0"></div>', $widget);
        $this->assertMatchesRegularExpression('#src="/assets/\w+/jquery-jvectormap-2\.0\.3\.min\.js"#', $html);
        $this->assertMatchesRegularExpression('#src="/assets/\w+/maps/jquery-jvectormap-world-mill-en\.js"#', $html);
        $this->assertStringContainsString('jQuery(document.getElementById("w0")).vectorMap({"map":"world_mill_en"});', $html);
        $this->assertStringContainsString(':where([id="w0"]){height:400px}', $html);
        $this->assertStringNotContainsString('excanvas', $html);
    }

    public function mapNameProvider()
    {
        return [
            ['world_mill_en', 'world_mill_en', 'world-mill-en'],
            ['world-mill-en', 'world_mill_en', 'world-mill-en'],
            ['fr_regions_merc_en', 'fr_regions_merc_en', 'fr_regions-merc-en'],
            ['fr_regions-mill-en', 'fr_regions_mill_en', 'fr_regions-mill-en'],
            ['us-aea-en', 'us_aea_en', 'us-aea-en'],
        ];
    }

    /**
     * @dataProvider mapNameProvider
     */
    public function testMapNames($map, $jsName, $file)
    {
        list(, $html) = $this->render(['map' => $map]);
        $this->assertStringContainsString("/maps/jquery-jvectormap-$file.js", $html);
        $this->assertStringContainsString('{"map":"' . $jsName . '"}', $html);
    }

    public function testUnknownMapIsReportedByWidget()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('Map "../../etc/passwd" is not found. Available maps: ar_merc_en');
        JVectorMapWidget::begin(['map' => '../../etc/passwd']);
    }

    public function testAvailableMaps()
    {
        $maps = JVectorMapAsset::availableMaps();
        $this->assertCount(57, $maps);
        $this->assertSame('maps/jquery-jvectormap-fr_regions-merc-en.js', $maps['fr_regions_merc_en']);
        foreach ($maps as $file) {
            $this->assertFileExists(dirname(__DIR__) . '/assets/' . $file);
        }
    }

    public function testOptionsAreEncoded()
    {
        list(, $html) = $this->render([
            'map' => 'world_mill_en',
            'htmlOptions' => ['id' => "we'ird</style>.id"],
            'options' => [
                'markers' => [['latLng' => [55.75, 37.6], 'name' => '</script><script>alert(1)</script>']],
                'onRegionTipShow' => new JsExpression('function (e, el, code) {}'),
            ],
        ]);
        $this->assertStringNotContainsString('<script>alert(1)', $html);
        $this->assertStringNotContainsString('</style>.id', $html);
        $this->assertStringContainsString('"onRegionTipShow":function (e, el, code) {}', $html);
        $this->assertStringContainsString('document.getElementById("we\\' . 'u0027ird\\' . 'u003C\/style\\' . 'u003E.id")', $html);
    }

    public function testDefaultHeightCanBeDisabled()
    {
        list(, $html) = $this->render(['map' => 'world_mill_en', 'defaultHeight' => null]);
        $this->assertStringNotContainsString(':where(', $html);
    }

    public function testEachMapIsLoadedOnce()
    {
        $view = new View();
        Yii::$app->set('view', $view);
        JVectorMapWidget::widget(['map' => 'world_mill_en']);
        JVectorMapWidget::widget(['map' => 'world-mill-en']);
        JVectorMapWidget::widget(['map' => 'europe_mill_en']);
        $html = $view->renderFile(__DIR__ . '/views/page.php');
        $this->assertSame(1, substr_count($html, 'jquery-jvectormap-world-mill-en.js'));
        $this->assertSame(1, substr_count($html, 'jquery-jvectormap-europe-mill-en.js'));
    }
}
