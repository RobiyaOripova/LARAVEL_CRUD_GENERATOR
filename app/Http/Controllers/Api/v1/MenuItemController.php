<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\MenuItemRequest\MenuItemCreateRequest;
use App\Http\Requests\MenuItemRequest\MenuItemUpdateRequest;
use App\Http\Interfaces\MenuItemInterface;
use App\Http\Resources\MenuItemResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\MenuItem;


/**
 * @group MenuItem
 *
 */
class MenuItemController extends Controller
{
    public MenuItemInterface $menuItemRepository;

    public function __construct(MenuItemInterface $menuItemRepository)
    {
        $this->menuItemRepository = $menuItemRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/v1/menu_items",
     *      operationId="getMenuItemList",
     *      tags={"MenuItem"},
     *      summary="Get list of menu_items",
     *      description="Returns list of menu_items",
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
        return MenuItemResource::collection($this->menuItemRepository->index($request)->paginate($request->get("per_page")));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/admin/menu_items",
     *      operationId="getAdminMenuItemList",
     *      tags={"MenuItem"},
     *      summary="Get list of menu_items",
     *      description="Returns list of menu_items",
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
        return MenuItemResource::collection($this->menuItemRepository->adminIndex($request)->paginate($request->get("per_page")));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/menu_items/{id}",
     *      operationId="getMenuItemById",
     *      tags={"MenuItem"},
     *      summary="Get menuItem information",
     *      description="Returns menuItem data",
     *      @OA\Parameter(
     *          name="id",
     *          description="MenuItem id",
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


    public function show(Request $request, MenuItem $menuItem)
    {
        return new  MenuItemResource($this->menuItemRepository->show($request, $menuItem)->first());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/admin/menu_items",
     *      operationId="createMenuItem",
     *      tags={"MenuItem"},
     *      summary="Create new menuItem",
     *      description="Returns menuItem data",
     *      security={ {"bearerAuth": {} }},
     *
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *		  
	 *  			@OA\Property(property="menu_id",type="integer"),
	 *  			@OA\Property(property="title",type="string"),
	 *  			@OA\Property(property="url",type="string"),
	 *  			@OA\Property(property="file_id",type="integer"),
	 *  			@OA\Property(property="sort",type="integer"),
	 *  			@OA\Property(property="menu_item_parent_id",type="integer"),
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

    public function create(MenuItemCreateRequest $createRequest)
    {
        return successResponse("created successfully", $this->menuItemRepository->create($createRequest));
    }

    /**
     * @OA\Put(
     *      path="/api/v1/admin/menu_items/{id}",
     *      operationId="updateMenuItem",
     *      tags={"MenuItem"},
     *      summary="Update existing menuItem",
     *      description="Returns updated menuItem data",
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
	 *  			@OA\Property(property="menu_id",type="integer"),
	 *  			@OA\Property(property="title",type="string"),
	 *  			@OA\Property(property="url",type="string"),
	 *  			@OA\Property(property="file_id",type="integer"),
	 *  			@OA\Property(property="sort",type="integer"),
	 *  			@OA\Property(property="menu_item_parent_id",type="integer"),
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

    public function update(MenuItemUpdateRequest $updateRequest, MenuItem $menuItem)
    {
        return successResponse("updated successfully", $this->menuItemRepository->update($updateRequest, $menuItem));
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/admin/menu_items/{id}",
     *      operationId="deleteMenuItem",
     *      tags={"MenuItem"},
     *      summary="Delete existing menuItem",
     *      description="Deletes a record and returns no content",
     *      security={ {"bearerAuth": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="MenuItem id",
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

    public function destroy(MenuItem $menuItem): JsonResponse
    {
        return successResponse("deleted successfully", $this->menuItemRepository->delete($menuItem));
    }
}
