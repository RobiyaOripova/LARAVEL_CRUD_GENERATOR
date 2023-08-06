<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PageRequest\PageCreateRequest;
use App\Http\Requests\PageRequest\PageUpdateRequest;
use App\Http\Interfaces\PageInterface;
use App\Http\Resources\PageResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\Page;


/**
 * @group Page
 *
 */
class PageController extends Controller
{
    public PageInterface $pageRepository;

    public function __construct(PageInterface $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/v1/page",
     *      operationId="getPageList",
     *      tags={"Page"},
     *      summary="Get list of page",
     *      description="Returns list of page",
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
        return PageResource::collection($this->pageRepository->index($request)->paginate($request->get("per_page")));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/admin/page",
     *      operationId="getAdminPageList",
     *      tags={"Page"},
     *      summary="Get list of page",
     *      description="Returns list of page",
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
        return PageResource::collection($this->pageRepository->adminIndex($request)->paginate($request->get("per_page")));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/page/{id}",
     *      operationId="getPageById",
     *      tags={"Page"},
     *      summary="Get page information",
     *      description="Returns page data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Page id",
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


    public function show(Request $request, Page $page)
    {
        return new  PageResource($this->pageRepository->show($request, $page)->first());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/admin/page",
     *      operationId="createPage",
     *      tags={"Page"},
     *      summary="Create new page",
     *      description="Returns page data",
     *      security={ {"bearerAuth": {} }},
     *
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *		  
	 *  			@OA\Property(property="title",type="string"),
	 *  			@OA\Property(property="slug",type="string"),
	 *  			@OA\Property(property="description",type="string"),
	 *  			@OA\Property(property="type",type="integer"),
	 *  			@OA\Property(property="file_id",type="integer"),
	 *  			@OA\Property(property="sort",type="integer"),
	 *  			@OA\Property(property="documents",type="string"),
	 *  			@OA\Property(property="anons",type="string"),
	 *  			@OA\Property(property="content",type="string"),
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

    public function create(PageCreateRequest $createRequest)
    {
        return successResponse("created successfully", $this->pageRepository->create($createRequest));
    }

    /**
     * @OA\Put(
     *      path="/api/v1/admin/page/{id}",
     *      operationId="updatePage",
     *      tags={"Page"},
     *      summary="Update existing page",
     *      description="Returns updated page data",
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
	 *  			@OA\Property(property="slug",type="string"),
	 *  			@OA\Property(property="description",type="string"),
	 *  			@OA\Property(property="type",type="integer"),
	 *  			@OA\Property(property="file_id",type="integer"),
	 *  			@OA\Property(property="sort",type="integer"),
	 *  			@OA\Property(property="documents",type="string"),
	 *  			@OA\Property(property="anons",type="string"),
	 *  			@OA\Property(property="content",type="string"),
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

    public function update(PageUpdateRequest $updateRequest, Page $page)
    {
        return successResponse("updated successfully", $this->pageRepository->update($updateRequest, $page));
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/admin/page/{id}",
     *      operationId="deletePage",
     *      tags={"Page"},
     *      summary="Delete existing page",
     *      description="Deletes a record and returns no content",
     *      security={ {"bearerAuth": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="Page id",
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

    public function destroy(Page $page): JsonResponse
    {
        return successResponse("deleted successfully", $this->pageRepository->delete($page));
    }
}
