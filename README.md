# jVectorMap widget for Yii2 framework

[![Latest Version](https://img.shields.io/packagist/v/conquer/jvectormap.svg)](https://packagist.org/packages/conquer/jvectormap)
[![Total Downloads](https://img.shields.io/packagist/dt/conquer/jvectormap.svg)](https://packagist.org/packages/conquer/jvectormap)
[![CI](https://github.com/borodulin/yii2-jvectormap/actions/workflows/ci.yml/badge.svg)](https://github.com/borodulin/yii2-jvectormap/actions/workflows/ci.yml)
[![License](https://img.shields.io/packagist/l/conquer/jvectormap.svg)](LICENSE)

## Description

[jVectorMap](https://jvectormap.com/) 2.0.3 widget with the bundled world, continent and country maps.

## Requirements

- PHP 7.4 or 8.x
- Yii 2.0
- ext-mbstring

## Installation

Install the extension via [Composer](https://getcomposer.org):

```bash
composer require conquer/jvectormap:^1.2
```

## Usage

```php
<?php
use conquer\jvectormap\JVectorMapWidget;
use yii\web\JsExpression;
?>

<?= JVectorMapWidget::widget([
    'map' => 'world_mill_en',
    // attributes of the container; the map fills it
    'htmlOptions' => ['style' => 'height: 500px'],
    // jVectorMap options: https://jvectormap.com/documentation/javascript-api/jvm-map/
    'options' => [
        'backgroundColor' => '#fff',
        'markers' => [
            ['latLng' => [55.75, 37.62], 'name' => 'Moscow'],
        ],
        'series' => [
            'regions' => [['values' => ['RU' => 100, 'US' => 50], 'scale' => ['#C8EEFF', '#0071A4']]],
        ],
        // callbacks
        'onRegionTipShow' => new JsExpression('function (e, tip, code) { tip.text(tip.text() + " (" + code + ")"); }'),
    ],
]) ?>
```

The container gets `height: 400px` unless its height is set by its `style` attribute or CSS classes
(`defaultHeight`, `null` disables it): with zero height the map is not visible.

Options are JSON-encoded with `</script>` escaped, so data from users (marker names, labels) can be passed safely.

### Maps

The map name may be written with `_` or `-` (`world_mill_en`, `world-mill-en`).
`JVectorMapAsset::availableMaps()` returns the list:

`ar_merc_en`, `ar_mill_en`, `at_merc_en`, `at_mill_en`, `au_merc_en`, `au_mill_en`, `be_merc_en`, `be_mill_en`, `ca_lcc_en`, `ca_merc_en`, `ca_mill_en`, `ch_merc_en`, `ch_mill_en`, `cn_merc_en`, `cn_mill_en`, `co_merc_en`, `co_mill_en`, `de_merc_en`, `de_mill_en`, `dk_merc_en`, `dk_mill_en`, `es_merc_en`, `es_mill_en`, `europe_merc_en`, `europe_mill_en`, `fr_merc_en`, `fr_mill_en`, `fr_regions_merc_en`, `fr_regions_mill_en`, `in_merc_en`, `in_mill_en`, `it_merc_en`, `it_mill_en`, `nl_merc_en`, `nl_mill_en`, `no_merc_en`, `no_mill_en`, `nz_merc_en`, `nz_mill_en`, `pl_merc_en`, `pl_mill_en`, `pt_merc_en`, `pt_mill_en`, `se_merc_en`, `se_mill_en`, `th_merc_en`, `th_mill_en`, `us_aea_en`, `us_lcc_en`, `us_merc_en`, `us_mill_en`, `ve_merc_en`, `ve_mill_en`, `world_merc_en`, `world_mill_en`, `za_merc_en`, `za_mill_en`

## Upgrading to 1.2

- `conquer/excanvas` is no longer a dependency: jVectorMap draws SVG (and VML in old IE) and does not use it.
  If you register `conquer\excanvas\ExcanvasAsset` yourself, require `conquer/excanvas`.
- An unknown map is reported by the widget (`InvalidConfigException` with the list of maps)
  instead of when the page is rendered.
- The container gets a default height of 400px (see `defaultHeight`).

## License

**conquer/jvectormap** is released under the MIT License. See the bundled [`LICENSE`](LICENSE) for details.
