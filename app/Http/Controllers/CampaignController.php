<?php

namespace App\Http\Controllers;

use App\Http\Resources\CampaignCollection;
use App\Http\Resources\CampaignResource;
use App\Models\Campaign;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CampaignController extends Controller
{
    /**
     * Return json response with campaign collection data
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function getListing(Request $request) : JsonResponse
    {
        $data = $this->validate($request, [
            'per_page' => 'optional|integer'
        ]);

        $campaigns = Campaign::paginate($data['per_page'] ?? 20);

        return response()->json([
            'status' => true,
            'data' => new CampaignCollection($campaigns)
        ]);
    }

    /**
     * Create or Update campaign data
     */
    public function save(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required|string',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after:date_from',
            'total_budget' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'daily_budget' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'image' => 'required|image',
        ]);

        $id = $request->get('id');
        $isNew = !empty($id);

        if ($isNew) {
            $campaign = new Campaign();
        } else {
            $campaign = Campaign::findOrFail($id);
        }

        $imageName = $data['name'] . '-' . time() . '.' . $request->image->extension();
        $data['image'] = $request->image->storeAs(public_path('images'), $imageName);

        $campaign->fill($data)->save();
        $code = $isNew ? 201 : 200;

        return response('', $code)->json([
            'status' => true,
            'data' => new CampaignResource($campaign)
        ]);
    }
}
