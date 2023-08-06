<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\MenuRequest\MenuCreateRequest;
use App\Http\Requests\MenuRequest\MenuUpdateRequest;
use App\Http\Interfaces\MenuInterface;
use App\Http\Resources\MenuResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\Menu;


/**
 * @group Menu
 *
 */
class MenuController extends Controller
{
    public MenuInterface $menuRepository;

    public function __construct(MenuInterface $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/v1/menu",
     *      operationId="getMenuList",
     *      tags={"Menu"},
     *      summary="Get list of menu",
     *      description="Returns list of menu",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */

    public function index(Request $request)
    {
        return MenuResource::collection($this->menuRepository->index($request)->paginate($request->get("per_page")));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/admin/menu",
     *      operationId="getAdminMenuList",
     *      tags={"Menu"},
     *      summary="Get list of menu",
     *      description="Returns list of menu",
     *      security={ {"bearerAuth": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */

    public function adminIndex(Request $request)
    {
        return MenuResource::collection($this->menuRepository->adminIndex($request)->paginate($request->get("per_page")));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/menu/{id}",
     *      operationId="getMenuById",
     *      tags={"Menu"},
     *      summary="Get menu information",
     *      description="Returns menu data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Menu id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */


    public function show(Request $request, Menu $menu)
    {
        return new  MenuResource($this->menuRepository->show($request, $menu)->first());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/admin/menu",
     *      operationId="createMenu",
     *      tags={"Menu"},
     *      summary="Create new menu",
     *      description="Returns menu data",
     *      security={ {"bearerAuth": {} }},
     *
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *		  
	 *  			@OA\Property(property="title",type="string"),
	 *  			@OA\Property(property="alias",type="string"),
	 *  			@OA\Property(property="type",type="integer"),
	 *  			@OA\Property(property="lang",type="integer"),
	 *  			@OA\Property(property="lang_hash",type="string"),
	 *  			@OA\Property(property="status",type="integer"),
     *
     * )
     * ),
     *
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    public function create(MenuCreateRequest $createRequest)
    {
        return successResponse("created successfully", $this->menuRepository->create($createRequest));
    }

    /**
     * @OA\Put(
     *      path="/api/v1/admin/menu/{id}",
     *      operationId="updateMenu",
     *      tags={"Menu"},
     *      summary="Update existing menu",
     *      description="Returns updated menu data",
     *      security={ {"bearerAuth": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *		  
	 *  			@OA\Property(property="title",type="string"),
	 *  			@OA\Property(property="alias",type="string"),
	 *  			@OA\Property(property="type",type="integer"),
	 *  			@OA\Property(property="lang",type="integer"),
	 *  			@OA\Property(property="lang_hash",type="string"),
	 *  			@OA\Property(property="status",type="integer"),
     *
     * )
     * ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */

    public function update(MenuUpdateRequest $updateRequest, Menu $menu)
    {
        return successResponse("updated successfully", $this->menuRepository->update($updateRequest, $menu));
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/admin/menu/{id}",
     *      operationId="deleteMenu",
     *      tags={"Menu"},
     *      summary="Delete existing menu",
     *      description="Deletes a record and returns no content",
     *      security={ {"bearerAuth": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="Menu id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */

    public function destroy(Menu $menu): JsonResponse
    {
        return successResponse("deleted successfully", $this->menuRepository->delete($menu));
    }
}
