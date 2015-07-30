#yii2-xhprof
xhprof for yii2
========================

Installation
------------
"repositories": [
	{
		"url": "https://github.com/mikolazp/yii2-xhprof.git",
		"type": "git"
	}
],

"require": {
        "mikolazp/yii2-xhprof": "dev-master"
    },


Usage
-----

In file main-local.php

```php

if(YII_ENV == 'dev'){
    $config['bootstrap'][] = 'xhprof';
    $config['modules']['xhprof'] = [
        'class'=>'fbi\xhprof\Module',
    ];
}
```

can find here: mysite.dev/xhprof
