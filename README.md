jVectorMap widget for Yii2 framework
=================

## Description

For more information please visit [jVectorMap Home Page](http://jvectormap.com/) 

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/). 

To install, either run

```
$ php composer.phar require conquer/jvectormap "*"
```
or add

```
"conquer/jvectormap": "*"
```

to the ```require``` section of your `composer.json` file.

## Usage

```php
use conquer\jvectormap\JVectorMapWidget;

<?= JVectorMapWidget::widget([
    'map'=>'world_mill_en',
]); ?>

```

## License

**conquer/jvectormap** is released under the MIT License. See the bundled `LICENSE.md` for details.