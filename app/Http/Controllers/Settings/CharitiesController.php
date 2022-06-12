<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Helpers\AccountHelper;
use App\Http\Controllers\Controller;
use App\Traits\JsonRespondController;
use App\Models\Contact\CharityPreference;
use Illuminate\Support\Facades\Validator;

class CharitiesController extends Controller
{
    use JsonRespondController;

    /**
     * Display the personalization page.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $accountHasLimitations = AccountHelper::hasLimitations(auth()->user()->account);

        return view('settings.charities.index')
            ->withAccountHasLimitations($accountHasLimitations);
    }

    /**
     * Get all the contact field types.
     */
    public function userPreferences()
    {
        return auth()->user()->charities;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return string
     */
    public function storeCharityPreference(Request $request)
    {
        $user = auth()->user();
        $data = [
            'user_id' => $user->id,
            'charity_id' => $request->pivot ? $request->pivot['charity_id'] : $request->charity_id,
            'percent' => $request->pivot ? $request->pivot['percent'] : $request->percent
        ];

        Validator::make($request->all(), [
            'charity_id' => 'required|exists:charities,id',
            'percent' => 'nullable|integer|min:0|max:' . abs((100 - $user->userCharities()->sum('percent')) + $data['percent']),
        ])->validate();
        $result = $user->syncRelation([
            $data
        ], 'userCharities', null, [
            'charity_id' => $data['charity_id'],
            'user_id' => $user->id
        ]);
        return $user->charities()->where('charities.id', $request->charity_id)->first();
    }

    /**
     * Destroy the contact field type.
     */
    public function destroyCharityPreference(Request $request, $charityId)
    {
        $user = auth()->user();
        $user->userCharities()->where('charity_id', $charityId)->delete();
        return $this->respondObjectDeleted($charityId);
    }
}