<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Main;

class MainController extends Controller
{
    public function getAllMain()
    {
        try {
            $main = Main::all();
            return response()->json($main);
        } catch (\Exception $e) {
            \Log::error('Failed to retrieve mains: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve mains'], 500);
        }
    }

    public function getMainById($id)
    {
        try {
            $main = Main::find($id);
            if (!$main) {
                return response()->json(['message' => 'Main not found'], 404);
            }
            return response()->json($main);
        } catch (\Exception $e) {
            \Log::error('Failed to retrieve main: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve main'], 500);
        }
    }

   

    public function store(Request $request)
    {
        try {
            $main = new Main();
            $main->name = $request->name;
            $main->projectId = $request->projectId;
            $main->save();

            return response()->json(['message' => 'Main created successfully', 'main' => $main], 201);
        } catch (\Exception $e) {
            \Log::error('Failed to create main: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create main'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $main = Main::findOrFail($id);
            if (!$main) {
                return response()->json(['error' => 'Main not found'], 404);
            }

            if ($request->has('name')) {
                $main->name = $request->name;
            }
            if ($request->has('projectId')) {
                $main->projectId = $request->projectId;
            }
            $main->save();

            return response()->json(['message' => 'Main updated successfully', 'main' => $main], 200);
        } catch (\Exception $e) {
            \Log::error('Failed to update main: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update main'], 500);
        }
    }

    public function delete($id)
    {
        try {
            $main = Main::findOrFail($id);
            $main->delete();
            return response()->json(['message' => 'Main deleted successfully']);
        } catch (\Exception $e) {
            \Log::error('Failed to delete main: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete main'], 500);
        }
    }

    public function isExist(Request $request){
        $name = $request->query('name');
        $exists = Main::where('name', $name)->exists();
        return response()->json(['exists' => $exists]);
    }
}