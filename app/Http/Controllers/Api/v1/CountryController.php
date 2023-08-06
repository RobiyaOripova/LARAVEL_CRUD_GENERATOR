<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\CountryInterface;
use App\Http\Requests\CountryRequest\CountryCreateRequest;
use App\Http\Requests\CountryRequest\CountryUpdateRequest;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Country
 */
class CountryController extends Controller
{
    public CountryInterface $countryRepository;

    public function __construct(CountryInterface $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/v1/country",
     *      operationId="getCountryList",
     *      tags={"Country"},
     *      summary="Get list of country",
     *      description="Returns list of country",
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
        return CountryResource::collection($this->countryRepository->index($request)->paginate($request->get('per_page')));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/admin/country",
     *      operationId="getAdminCountryList",
     *      tags={"Country"},
     *      summary="Get list of country",
     *      description="Returns list of country",
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
        return CountryResource::collection($this->countryRepository->adminIndex($request)->paginate($request->get('per_page')));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/country/{id}",
     *      operationId="getCountryById",
     *      tags={"Country"},
     *      summary="Get country information",
     *      description="Returns country data",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Country id",
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
    public function show(Request $request, Country $country)
    {
        return new CountryResource($this->countryRepository->show($request, $country)->first());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/admin/country",
     *      operationId="createCountry",
     *      tags={"Country"},
     *      summary="Create new country",
     *      description="Returns country data",
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
     *  			@OA\Property(property="code",type="string"),
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
    public function create(CountryCreateRequest $createRequest)
    {
        return successResponse('created successfully', $this->countryRepository->create($createRequest));
    }

    /**
     * @OA\Put(
     *      path="/api/v1/admin/country/{id}",
     *      operationId="updateCountry",
     *      tags={"Country"},
     *      summary="Update existing country",
     *      description="Returns updated country data",
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
     *  			@OA\Property(property="code",type="string"),
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
    public function update(CountryUpdateRequest $updateRequest, Country $country)
    {
        return successResponse('updated successfully', $this->countryRepository->update($updateRequest, $country));
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/admin/country/{id}",
     *      operationId="deleteCountry",
     *      tags={"Country"},
     *      summary="Delete existing country",
     *      description="Deletes a record and returns no content",
     *      security={ {"bearerAuth": {} }},
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Country id",
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
    public function destroy(Country $country): JsonResponse
    {
        return successResponse('deleted successfully', $this->countryRepository->delete($country));
    }
}
