<?php

namespace App\Http\Controllers;

use App\Models\UserPreference;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserPreferenceController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'preferred_sources'    => 'array|nullable',
            'preferred_categories' => 'array|nullable',
            'preferred_authors'    => 'array|nullable',
        ]);

        UserPreference::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'preferred_sources'    => $request->get('preferred_sources'),
                'preferred_categories' => $request->get('preferred_categories'),
                'preferred_authors'    => $request->get('preferred_authors'),
            ]
        );

        return response()->json(['message' => 'Preferences updated successfully.']);
    }
}
