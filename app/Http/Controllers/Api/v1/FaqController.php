<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\FaqInterface;
use App\Http\Requests\FaqRequest\FaqCreateRequest;
use App\Http\Requests\FaqRequest\FaqUpdateRequest;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Faq
 */
class FaqController extends Controller
{
    public FaqInterface $faqRepository;

    public function __construct(FaqInterface $faqRepository)
    {
        $this->faqRepository = $faqRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/v1/faq",
     *      operationId="getFaqList",
     *      tags={"Faq"},
     *      summary="Get list of faq",
     *      description="Returns list of faq",
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
        return FaqResource::collection($this->faqRepository->index($request)->paginate($request->get('per_page')));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/admin/faq",
     *      operationId="getAdminFaqList",
     *      tags={"Faq"},
     *      summary="Get list of faq",
     *      description="Returns list of faq",
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
        return FaqResource::collection($this->faqRepository->adminIndex($request)->paginate($request->get('per_page')));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/faq/{id}",
     *      operationId="getFaqById",
     *      tags={"Faq"},
     *      summary="Get faq information",
     *      description="Returns faq data",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Faq id",
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
    public function show(Request $request, Faq $faq)
    {
        return new FaqResource($this->faqRepository->show($request, $faq)->first());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/admin/faq",
     *      operationId="createFaq",
     *      tags={"Faq"},
     *      summary="Create new faq",
     *      description="Returns faq data",
     *      security={ {"bearerAuth": {} }},
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(
     *
     *  			@OA\Property(property="question",type="string"),
     *  			@OA\Property(property="answer",type="string"),
     *  			@OA\Property(property="sort",type="integer"),
     *  			@OA\Property(property="file_id",type="integer"),
     *  			@OA\Property(property="lang",type="integer"),
     *  			@OA\Property(property="lang_hash",type="string"),
     *  			@OA\Property(property="status",type="integer"),
     *  			@OA\Property(property="type",type="integer"),
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
    public function create(FaqCreateRequest $createRequest)
    {
        return successResponse('created successfully', $this->faqRepository->create($createRequest));
    }

    /**
     * @OA\Put(
     *      path="/api/v1/admin/faq/{id}",
     *      operationId="updateFaq",
     *      tags={"Faq"},
     *      summary="Update existing faq",
     *      description="Returns updated faq data",
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
     *  			@OA\Property(property="question",type="string"),
     *  			@OA\Property(property="answer",type="string"),
     *  			@OA\Property(property="sort",type="integer"),
     *  			@OA\Property(property="file_id",type="integer"),
     *  			@OA\Property(property="lang",type="integer"),
     *  			@OA\Property(property="lang_hash",type="string"),
     *  			@OA\Property(property="status",type="integer"),
     *  			@OA\Property(property="type",type="integer"),
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
    public function update(FaqUpdateRequest $updateRequest, Faq $faq)
    {
        return successResponse('updated successfully', $this->faqRepository->update($updateRequest, $faq));
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/admin/faq/{id}",
     *      operationId="deleteFaq",
     *      tags={"Faq"},
     *      summary="Delete existing faq",
     *      description="Deletes a record and returns no content",
     *      security={ {"bearerAuth": {} }},
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Faq id",
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
    public function destroy(Faq $faq): JsonResponse
    {
        return successResponse('deleted successfully', $this->faqRepository->delete($faq));
    }
}
