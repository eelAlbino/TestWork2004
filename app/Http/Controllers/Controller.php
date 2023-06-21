<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Exception;
use App\Exceptions\ApiException;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Проверка наличия необходимого разрешения у текущего пользователя
     * @param string $permissionCode    Код разрешения
     * @param $model                    Класс|объект модели, через которую производится проверка
     */
    protected function checkPermission(string $permissionCode, $model = null): void
    {
        try {
            $this->authorize($permissionCode, $model);
            
        } catch(Exception $exception) {
            throw (new ApiException(
                $exception->getMessage(),
                null,
                $exception
            ))->errorCode('have_not_permission');
        }
    }
}
