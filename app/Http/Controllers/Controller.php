<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      x={
 *          "logo": {
 *              "url": "https://via.placeholder.com/190x90.png?text=L5-Swagger"
 *          }
 *      },
 *      title="Example OpenApi",
 *      termsOfService="http://swagger.io/terms/",
 *      description="Example App OpenApi description",
 *      @OA\Contact(
 *          email="ibukunoreofe@gmail.com"
 *      ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * ),
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer"
 * ),
 * @OA\ExternalDocumentation(
 *       description="Find out more about different properties type including enums",
 *       url="https://github.com/zircote/swagger-php/blob/master/Examples/petstore-3.0/Petstore.php"
 *   ),
 * @OA\Tag(
 *      name="Books",
 *      description="Everything about your books",
 *      @OA\ExternalDocumentation(
 *          description="Find out more",
 *          url="http://swagger.io"
 *      )
 *  ),
 * @OA\Tag(
 *      name="Authentication",
 *      description="Authenticate your app using Sanctum token",
 *  )
 * @OA\Server(
 *      description="Local Secure API",
 *      url="./../api"
 *  ),
 * @OA\Server(
 *       description="Local insecure API",
 *       url="http://svr2.scadware.com:14080/api"
 *   )
 *
 *
 *
 *   SWAGGER DOES NOT AUTO COPY TOKEN. YOU HAVE TO MANUALLY COPY IT TO THE AUTH
 *
 *   Note that if no server is provided, it automatically uses the laravel base url that called the documentation without the /api. So, all path needs to include /api on their own
 *   Best option is to build the paths to start with api and let it pick the base host url itself without specifying the OA Server derivative at all. It doesn't support laravel derivatives like {{}} in comments
 *   termsOfService is optional as well as some other annotations above
 *   Check how you can add multiple tags here to group some collections together
 *   https://github.com/zircote/swagger-php/blob/master/Examples/petstore-3.0/Petstore.php
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
