<?php
/**
 * Created by PhpStorm.
 * User: 80053521
 * Date: 2015/4/20
 * Time: 17:22
 */

namespace fbi\xhprof;

use fbi\xhprof\controllers\DefaultController;
use fbi\xhprof\lib\Helper;
use fbi\xhprof\lib\XHProfRuns_Default;
use logger\Logger;
use yii\base\Application;
use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * @var null Name
     */
    public $name = null;

    /**
     * if user hasn't set a directory location,
     * we use the xhprof.output_dir ini setting
     * if specified, else we default to the directory
     * in which the error_log file resides.
     * @var null
     */
    public $dir = '@runtime/xhprof';

    public function init()
    {
        parent::init();
        if ($this->dir) {
            $this->dir = \Yii::getAlias($this->dir);
        } elseif ($dir = ini_set('xhprof.output_dir')) {
            $this->dir = $dir;
        } else {
            $this->dir = \Yii::getAlias('@runtime/xhprof');
        }
        !is_dir($this->dir) and mkdir($this->dir, 0777, true);
    }

    public function bootstrap($app)
    {
        $this->name = is_null($this->name) ? \Yii::$app->id : $this->name;
        $this->name = preg_replace("/[^\w\.\-]/", '', $this->name);
        $app->on(Application::EVENT_BEFORE_REQUEST, function () {
            xhprof_enable(XHPROF_FLAGS_NO_BUILTINS | XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);
            register_shutdown_function([$this, 'record']);
        });
    }

    public function record()
    {
        $xhprof_data = xhprof_disable();
        if (!\Yii::$app->requestedAction->controller instanceof DefaultController
        ) {
            $xhprof_runs = new XHProfRuns_Default($this->dir);
            $xhprof_runs->save_run($xhprof_data, $this->name);
        }
    }
}