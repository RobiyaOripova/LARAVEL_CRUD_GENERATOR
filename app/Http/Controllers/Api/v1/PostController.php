<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest\PostCreateRequest;
use App\Http\Requests\PostRequest\PostUpdateRequest;
use App\Http\Interfaces\PostInterface;
use App\Http\Resources\PostResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\Post;


/**
 * @group Post
 *
 */
class PostController extends Controller
{
    public PostInterface $postRepository;

    public function __construct(PostInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/v1/post",
     *      operationId="getPostList",
     *      tags={"Post"},
     *      summary="Get list of post",
     *      description="Returns list of post",
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
        return PostResource::collection($this->postRepository->index($request)->paginate($request->get("per_page")));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/admin/post",
     *      operationId="getAdminPostList",
     *      tags={"Post"},
     *      summary="Get list of post",
     *      description="Returns list of post",
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
        return PostResource::collection($this->postRepository->adminIndex($request)->paginate($request->get("per_page")));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/post/{id}",
     *      operationId="getPostById",
     *      tags={"Post"},
     *      summary="Get post information",
     *      description="Returns post data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Post id",
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


    public function show(Request $request, Post $post)
    {
        return new  PostResource($this->postRepository->show($request, $post)->first());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/admin/post",
     *      operationId="createPost",
     *      tags={"Post"},
     *      summary="Create new post",
     *      description="Returns post data",
     *      security={ {"bearerAuth": {} }},
     *
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *		  
	 *  			@OA\Property(property="title",type="string"),
	 *  			@OA\Property(property="description",type="string"),
	 *  			@OA\Property(property="content",type="string"),
	 *  			@OA\Property(property="slug",type="string"),
	 *  			@OA\Property(property="popular",type="integer"),
	 *  			@OA\Property(property="type",type="integer"),
	 *  			@OA\Property(property="file_id",type="integer"),
	 *  			@OA\Property(property="document_ids",type="string"),
	 *  			@OA\Property(property="category_ids",type="string"),
	 *  			@OA\Property(property="video_id",type="integer"),
	 *  			@OA\Property(property="top",type="integer"),
	 *  			@OA\Property(property="views",type="integer"),
	 *  			@OA\Property(property="published_at",type="datetime"),
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

    public function create(PostCreateRequest $createRequest)
    {
        return successResponse("created successfully", $this->postRepository->create($createRequest));
    }

    /**
     * @OA\Put(
     *      path="/api/v1/admin/post/{id}",
     *      operationId="updatePost",
     *      tags={"Post"},
     *      summary="Update existing post",
     *      description="Returns updated post data",
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
	 *  			@OA\Property(property="description",type="string"),
	 *  			@OA\Property(property="content",type="string"),
	 *  			@OA\Property(property="slug",type="string"),
	 *  			@OA\Property(property="popular",type="integer"),
	 *  			@OA\Property(property="type",type="integer"),
	 *  			@OA\Property(property="file_id",type="integer"),
	 *  			@OA\Property(property="document_ids",type="string"),
	 *  			@OA\Property(property="category_ids",type="string"),
	 *  			@OA\Property(property="video_id",type="integer"),
	 *  			@OA\Property(property="top",type="integer"),
	 *  			@OA\Property(property="views",type="integer"),
	 *  			@OA\Property(property="published_at",type="datetime"),
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

    public function update(PostUpdateRequest $updateRequest, Post $post)
    {
        return successResponse("updated successfully", $this->postRepository->update($updateRequest, $post));
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/admin/post/{id}",
     *      operationId="deletePost",
     *      tags={"Post"},
     *      summary="Delete existing post",
     *      description="Deletes a record and returns no content",
     *      security={ {"bearerAuth": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="Post id",
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

    public function destroy(Post $post): JsonResponse
    {
        return successResponse("deleted successfully", $this->postRepository->delete($post));
    }
}
