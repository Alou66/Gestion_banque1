<?php

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="API Laravel Banking",
 *         version="1.0.0",
 *         description="Documentation Swagger de l’API Laravel"
 *     ),
 *     @OA\Server(
 *         url="http://127.0.0.1:8000",
 *         description="Serveur local"
 *     )
 * )
 */

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
