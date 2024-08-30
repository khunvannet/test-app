<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testcase;

class TestCaseController extends Controller
{
  public function getAllTestCase(Request $request)
    {
        try {
            $pageIndex = $request->query('pageIndex', 1);
            $pageSize = $request->query('pageSize', 25);
            $searchQuery = $request->query('searchQuery', '');
            $mainId = $request->query('mainId', null);

            $query = Testcase::query();

            if (!empty($searchQuery)) {
                $query->where(function($q) use ($searchQuery) {
                    $q->where('name', 'LIKE', '%' . $searchQuery . '%')
                    ->orWhere('code', 'LIKE', '%' . $searchQuery . '%');
                });
            }

            if (!is_null($mainId)) {
                $query->where('mainId', $mainId);
            }

            $test = $query->paginate($pageSize, ['*'], 'page', $pageIndex);

            return response()->json([
                'results' => $test->items(),
                'param' => [
                    'pageIndex' => $test->currentPage(),
                    'pageSize' => $test->perPage(),
                    'totalCount' => $test->total(),
                    'searchQuery' => $searchQuery,
                    'mainId' => $mainId,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to retrieve Test Case: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve Test case'], 500);
        }
    }
     public function getTestCase()
    {
        try {
            $test = Testcase::all();
            return response()->json($test);
        }catch (\Exception $e) {
             \Log::error('Failed to retrieve Test Case: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve Test case'], 500);
        }
    }


    public function store(Request $request)
    {
        try {
            $lastTestCase = Testcase::orderBy('code', 'desc')->first();
            $nextCode = 'T0001';

            if ($lastTestCase) {
                $lastCodeNumber = intval(substr($lastTestCase->code, 1));
                $nextCodeNumber = $lastCodeNumber + 1;
                $nextCode = 'T' . str_pad($nextCodeNumber, 4, '0', STR_PAD_LEFT);
            }

            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $imageName = time() . '.' . $request->attachment->extension();
                $request->attachment->move(public_path('images'), $imageName);
                $attachmentPath = 'images/' . $imageName;
            }

            $testCase = new Testcase();
            $testCase->code = $nextCode;
            $testCase->name = $request->name;
            $testCase->description = $request->description;
            $testCase->notes = $request->notes;
            $testCase->attachment = $attachmentPath;
            $testCase->mainId = $request->mainId;

            $testCase->save();

            return response()->json($testCase, 201);
        } catch (\Exception $e) {
            \Log::error('Error creating test case: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json(['error' => 'Internal Server Error', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $test = Testcase::findOrFail($id);
            if (!$test) {
                return response()->json(['error' => 'Test case not found'], 404);
            }

            if ($request->has('code')) {
                $test->code = $request->code;
            }
            if ($request->has('name')) {
                $test->name = $request->name;
            }
            if ($request->has('description')) {
                $test->description = $request->description;
            }
            if ($request->has('notes')) {
                $test->notes = $request->notes;
            }
            if ($request->hasFile('attachment')) {
                $imageName = time() . '.' . $request->attachment->extension();
                $request->attachment->move(public_path('images'), $imageName);
                $test->attachment = 'images/' . $imageName;
            }
            if ($request->has('mainId')) {
                $test->mainId = $request->mainId;
            }

            $test->save();

            return response()->json($test, 200);
        } catch (\Exception $e) {
            \Log::error('Error updating test case: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json(['error' => 'Internal Server Error', 'message' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $test = Testcase::findOrFail($id);
            if (!$test) {
                return response()->json(['error' => 'Test case not found'], 404);
            }
            $test->delete();
            return response()->json(['message' => 'Test case deleted successfully']);
        } catch (\Exception $e) {
            \Log::error('Error deleting test case: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json(['error' => 'Internal Server Error', 'message' => $e->getMessage()], 500);
        }
    }

    public function isExist(Request $request)
    {
        $name = $request->query('name');
        $mainId = $request->query('mainId');

        $query = Testcase::where('name', $name);

        if ($mainId) {
            $query->where('mainId', $mainId);
        }

        $exists = $query->exists();
        return response()->json(['exists' => $exists]);
    }

}