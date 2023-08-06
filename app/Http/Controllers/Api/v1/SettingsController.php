<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SettingsRequest\SettingsCreateRequest;
use App\Http\Requests\SettingsRequest\SettingsUpdateRequest;
use App\Http\Interfaces\SettingsInterface;
use App\Http\Resources\SettingsResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\Settings;


/**
 * @group Settings
 *
 */
class SettingsController extends Controller
{
    public SettingsInterface $settingsRepository;

    public function __construct(SettingsInterface $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/v1/settings",
     *      operationId="getSettingsList",
     *      tags={"Settings"},
     *      summary="Get list of settings",
     *      description="Returns list of settings",
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
        return SettingsResource::collection($this->settingsRepository->index($request)->paginate($request->get("per_page")));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/admin/settings",
     *      operationId="getAdminSettingsList",
     *      tags={"Settings"},
     *      summary="Get list of settings",
     *      description="Returns list of settings",
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
        return SettingsResource::collection($this->settingsRepository->adminIndex($request)->paginate($request->get("per_page")));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/settings/{id}",
     *      operationId="getSettingsById",
     *      tags={"Settings"},
     *      summary="Get settings information",
     *      description="Returns settings data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Settings id",
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


    public function show(Request $request, Settings $settings)
    {
        return new  SettingsResource($this->settingsRepository->show($request, $settings)->first());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/admin/settings",
     *      operationId="createSettings",
     *      tags={"Settings"},
     *      summary="Create new settings",
     *      description="Returns settings data",
     *      security={ {"bearerAuth": {} }},
     *
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *		  
	 *  			@OA\Property(property="name",type="string"),
	 *  			@OA\Property(property="value",type="string"),
	 *  			@OA\Property(property="file_id",type="integer"),
	 *  			@OA\Property(property="slug",type="string"),
	 *  			@OA\Property(property="link",type="string"),
	 *  			@OA\Property(property="alias",type="string"),
	 *  			@OA\Property(property="lang_hash",type="string"),
	 *  			@OA\Property(property="sort",type="integer"),
	 *  			@OA\Property(property="lang",type="integer"),
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

    public function create(SettingsCreateRequest $createRequest)
    {
        return successResponse("created successfully", $this->settingsRepository->create($createRequest));
    }

    /**
     * @OA\Put(
     *      path="/api/v1/admin/settings/{id}",
     *      operationId="updateSettings",
     *      tags={"Settings"},
     *      summary="Update existing settings",
     *      description="Returns updated settings data",
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
	 *  			@OA\Property(property="name",type="string"),
	 *  			@OA\Property(property="value",type="string"),
	 *  			@OA\Property(property="file_id",type="integer"),
	 *  			@OA\Property(property="slug",type="string"),
	 *  			@OA\Property(property="link",type="string"),
	 *  			@OA\Property(property="alias",type="string"),
	 *  			@OA\Property(property="lang_hash",type="string"),
	 *  			@OA\Property(property="sort",type="integer"),
	 *  			@OA\Property(property="lang",type="integer"),
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

    public function update(SettingsUpdateRequest $updateRequest, Settings $settings)
    {
        return successResponse("updated successfully", $this->settingsRepository->update($updateRequest, $settings));
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/admin/settings/{id}",
     *      operationId="deleteSettings",
     *      tags={"Settings"},
     *      summary="Delete existing settings",
     *      description="Deletes a record and returns no content",
     *      security={ {"bearerAuth": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="Settings id",
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

    public function destroy(Settings $settings): JsonResponse
    {
        return successResponse("deleted successfully", $this->settingsRepository->delete($settings));
    }
}
