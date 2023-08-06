<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\CategoryInterface;
use App\Http\Requests\CategoryRequest\CategoryCreateRequest;
use App\Http\Requests\CategoryRequest\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Category
 */
class CategoryController extends Controller
{
    public CategoryInterface $categoryRepository;

    public function __construct(CategoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/v1/categories",
     *      operationId="getCategoryList",
     *      tags={"Category"},
     *      summary="Get list of categories",
     *      description="Returns list of categories",
     *
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
        return CategoryResource::collection($this->categoryRepository->index($request)->paginate($request->get('per_page')));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/admin/categories",
     *      operationId="getAdminCategoryList",
     *      tags={"Category"},
     *      summary="Get list of categories",
     *      description="Returns list of categories",
     *      security={ {"bearerAuth": {} }},
     *
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
        return CategoryResource::collection($this->categoryRepository->adminIndex($request)->paginate($request->get('per_page')));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/categories/{id}",
     *      operationId="getCategoryById",
     *      tags={"Category"},
     *      summary="Get category information",
     *      description="Returns category data",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Category id",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
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
    public function show(Request $request, Category $category)
    {
        return new CategoryResource($this->categoryRepository->show($request, $category)->first());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/admin/categories",
     *      operationId="createCategory",
     *      tags={"Category"},
     *      summary="Create new category",
     *      description="Returns category data",
     *      security={ {"bearerAuth": {} }},
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(
     *
     *  			@OA\Property(property="name_uz",type="string"),
     *  			@OA\Property(property="name_ru",type="string"),
     *  			@OA\Property(property="name_en",type="string"),
     *  			@OA\Property(property="status",type="integer"),
     *  			@OA\Property(property="type",type="integer"),
     *  			@OA\Property(property="is_special",type="integer"),
     *  			@OA\Property(property="sort",type="integer"),
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
    public function create(CategoryCreateRequest $createRequest)
    {
        return successResponse('created successfully', $this->categoryRepository->create($createRequest));
    }

    /**
     * @OA\Put(
     *      path="/api/v1/admin/categories/{id}",
     *      operationId="updateCategory",
     *      tags={"Category"},
     *      summary="Update existing category",
     *      description="Returns updated category data",
     *      security={ {"bearerAuth": {} }},
     *
     *      @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(
     *
     *  			@OA\Property(property="name_uz",type="string"),
     *  			@OA\Property(property="name_ru",type="string"),
     *  			@OA\Property(property="name_en",type="string"),
     *  			@OA\Property(property="status",type="integer"),
     *  			@OA\Property(property="type",type="integer"),
     *  			@OA\Property(property="is_special",type="integer"),
     *  			@OA\Property(property="sort",type="integer"),
     *
     * )
     * ),
     *
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
    public function update(CategoryUpdateRequest $updateRequest, Category $category)
    {
        return successResponse('updated successfully', $this->categoryRepository->update($updateRequest, $category));
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/admin/categories/{id}",
     *      operationId="deleteCategory",
     *      tags={"Category"},
     *      summary="Delete existing category",
     *      description="Deletes a record and returns no content",
     *      security={ {"bearerAuth": {} }},
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Category id",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *
     *          @OA\JsonContent()
     *       ),
     *
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
    public function destroy(Category $category): JsonResponse
    {
        return successResponse('deleted successfully', $this->categoryRepository->delete($category));
    }
}
