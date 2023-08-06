<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\BannerInterface;
use App\Http\Requests\BannerRequest\BannerCreateRequest;
use App\Http\Requests\BannerRequest\BannerUpdateRequest;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Banner
 */
class BannerController extends Controller
{
    public BannerInterface $bannerRepository;

    public function __construct(BannerInterface $bannerRepository)
    {
        $this->bannerRepository = $bannerRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/v1/banners",
     *      operationId="getBannerList",
     *      tags={"Banner"},
     *      summary="Get list of banners",
     *      description="Returns list of banners",
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
        return BannerResource::collection($this->bannerRepository->index($request)->paginate($request->get('per_page')));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/admin/banners",
     *      operationId="getAdminBannerList",
     *      tags={"Banner"},
     *      summary="Get list of banners",
     *      description="Returns list of banners",
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
        return BannerResource::collection($this->bannerRepository->adminIndex($request)->paginate($request->get('per_page')));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/banners/{id}",
     *      operationId="getBannerById",
     *      tags={"Banner"},
     *      summary="Get banner information",
     *      description="Returns banner data",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Banner id",
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
    public function show(Request $request, Banner $banner)
    {
        return new BannerResource($this->bannerRepository->show($request, $banner)->first());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/admin/banners",
     *      operationId="createBanner",
     *      tags={"Banner"},
     *      summary="Create new banner",
     *      description="Returns banner data",
     *      security={ {"bearerAuth": {} }},
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(
     *
     *  			@OA\Property(property="title_uz",type="string"),
     *  			@OA\Property(property="title_ru",type="string"),
     *  			@OA\Property(property="title_en",type="string"),
     *  			@OA\Property(property="description_uz",type="string"),
     *  			@OA\Property(property="description_ru",type="string"),
     *  			@OA\Property(property="description_en",type="string"),
     *  			@OA\Property(property="url",type="string"),
     *  			@OA\Property(property="viewed",type="integer"),
     *  			@OA\Property(property="file_id",type="integer"),
     *  			@OA\Property(property="sort",type="integer"),
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
    public function create(BannerCreateRequest $createRequest)
    {
        return successResponse('created successfully', $this->bannerRepository->create($createRequest));
    }

    /**
     * @OA\Put(
     *      path="/api/v1/admin/banners/{id}",
     *      operationId="updateBanner",
     *      tags={"Banner"},
     *      summary="Update existing banner",
     *      description="Returns updated banner data",
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
     *  			@OA\Property(property="title_uz",type="string"),
     *  			@OA\Property(property="title_ru",type="string"),
     *  			@OA\Property(property="title_en",type="string"),
     *  			@OA\Property(property="description_uz",type="string"),
     *  			@OA\Property(property="description_ru",type="string"),
     *  			@OA\Property(property="description_en",type="string"),
     *  			@OA\Property(property="url",type="string"),
     *  			@OA\Property(property="viewed",type="integer"),
     *  			@OA\Property(property="file_id",type="integer"),
     *  			@OA\Property(property="sort",type="integer"),
     *  			@OA\Property(property="status",type="integer"),
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
    public function update(BannerUpdateRequest $updateRequest, Banner $banner)
    {
        return successResponse('updated successfully', $this->bannerRepository->update($updateRequest, $banner));
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/admin/banners/{id}",
     *      operationId="deleteBanner",
     *      tags={"Banner"},
     *      summary="Delete existing banner",
     *      description="Deletes a record and returns no content",
     *      security={ {"bearerAuth": {} }},
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Banner id",
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
    public function destroy(Banner $banner): JsonResponse
    {
        return successResponse('deleted successfully', $this->bannerRepository->delete($banner));
    }
}
