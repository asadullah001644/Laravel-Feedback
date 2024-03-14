<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedback = Feedback::paginate(10);
        return response()->json($feedback, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|in:bug,feature,request,improvement',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $feedback = Feedback::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'user_id' => auth()->user()->id, // Assuming you have authentication set up
        ]);

        return response()->json($feedback, 201);
    }

    public function show($id)
    {
        $feedback = Feedback::findOrFail($id);
        return response()->json($feedback, 200);
    }

    public function update(Request $request, $id)
    {
        $feedback = Feedback::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|in:bug,feature,request,improvement',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $feedback->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
        ]);

        return response()->json($feedback, 200);
    }

    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();
        return response()->json(['message' => 'Feedback deleted successfully'], 200);
    }
}
