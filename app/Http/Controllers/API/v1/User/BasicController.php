<?php

namespace App\Http\Controllers\API\v1\User;

use App\Models\Blog;
use App\Models\Recipe;
use App\Models\MoodQuote;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\RecipeResource;
use Illuminate\Support\Facades\DB;

class BasicController extends Controller
{


    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json($user);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'dob' => 'required|date',
            'user_activity_id' => 'required|integer|exists:user_activities,id',
            'user_goal_id' => 'required|integer|exists:user_goals,id',
            'weight' => 'required|numeric',
            'height' => 'required|numeric',
            'medical_conditions' => 'nullable|array',
            'medical_conditions.*' => 'nullable|exists:medical_conditions,id',
            'gender' => 'required|in:male,female,other|string',
        ]);

        $user = $request->user();


        DB::beginTransaction();

        $user->update($request->only([
            'name',
        ]));

        $user->user_data()->create($request->only([
            'dob',
            'user_activity_id',
            'user_goal_id',
            'weight',
            'height',
            'gender',
        ]));


        if ($request->has('medical_conditions')) {
            $user->medicalConditions()->sync($request->medical_conditions);
        }

        DB::commit();


        return response()->json($user);
    }



    public function quotes(Request $request)
    {
        $quote = MoodQuote::inRandomOrder()->first();
        return response()->json($quote);
    }

    public function recipes()
    {
        $recipes = Recipe::with([
            'foods' => ['ingredients', 'images'],
            'compositions',
            'tags',
        ])->get();

        return RecipeResource::collection($recipes);
    }


    public function blogs()
    {
        $blogs = Blog::with([
            'tags',
            'images',
            'dietician',
        ])->get();


        return response()->json($blogs);
    }

    public function testimonials()
    {
        $testimonials = Testimonial::all();

        return response()->json($testimonials);
    }

    public function metadata()
    {
        $data = [
            'user_activities' => DB::table('user_activities')->get(),
            'user_goals' => DB::table('user_goals')->get(),
            'medical_conditions' => DB::table('medical_conditions')->get(),
        ];

        return response()->json($data);
    }
}