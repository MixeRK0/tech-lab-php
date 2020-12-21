<?php

namespace modules\analysis\frontend;

use modules\core\base\RestFullModule;

class analysis extends RestFullModule
{
    public $module = \modules\analysis\analysis::class;

    public $controllerNamespace = 'modules\analysis\frontend\controllers';
}
