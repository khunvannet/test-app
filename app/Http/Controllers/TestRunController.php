<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestRun;

class TestRunController extends Controller
{
   public function getAllRun(Request $request)
    {
        try {
            $pageIndex = $request->query('pageIndex', 1);
            $pageSize = $request->query('pageSize', 25);
            $searchQuery = $request->query('searchQuery', '');
            $projectId = $request->query('projectId', null);

            $query = TestRun::query();
            $query->where('active', 1);

            if (!empty($searchQuery)) {
                $query->where(function ($q) use ($searchQuery) {
                    $q->where('name', 'LIKE', '%' . $searchQuery . '%')
                    ->orWhere('code', 'LIKE', '%' . $searchQuery . '%');
                });
            }

            if (!empty($projectId)) {
                $query->where('projectId', $projectId);
            }

            $testRun = $query->paginate($pageSize, ['*'], 'page', $pageIndex);

            return response()->json([
                'results' => $testRun->items(),
                'param' => [
                    'pageIndex' => $testRun->currentPage(),
                    'pageSize' => $testRun->perPage(),
                    'totalCount' => $testRun->total(),
                    'searchQuery' => $searchQuery,
                    'projectId' => $projectId,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to retrieve Test Run: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve Test run'], 500);
        }
    }
     public function getCloseRun(Request $request)
    {
        try {
            $pageIndex = $request->query('pageIndex', 1);
            $pageSize = $request->query('pageSize', 25);
            $searchQuery = $request->query('searchQuery', '');
            $projectId = $request->query('projectId', null);

            $query = TestRun::query();
            $query->where('active', 0);

            if (!empty($searchQuery)) {
                $query->where(function ($q) use ($searchQuery) {
                    $q->where('name', 'LIKE', '%' . $searchQuery . '%')
                    ->orWhere('code', 'LIKE', '%' . $searchQuery . '%');
                });
            }

            if (!empty($projectId)) {
                $query->where('projectId', $projectId);
            }

            $testRun = $query->paginate($pageSize, ['*'], 'page', $pageIndex);

            return response()->json([
                'results' => $testRun->items(),
                'param' => [
                    'pageIndex' => $testRun->currentPage(),
                    'pageSize' => $testRun->perPage(),
                    'totalCount' => $testRun->total(),
                    'searchQuery' => $searchQuery,
                    'projectId' => $projectId,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to retrieve Test Run: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve Test run'], 500);
        }
    }




    public function getRunById($id)
    {
        try {
            $testRun = TestRun::find($id);
            if (!$testRun) {
                return response()->json(['error' => 'Test Run not found'], 404);
            }
            return response()->json($testRun);
        } catch (\Exception $e) {
            \Log::error('Failed to retrieve test run: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve test run'], 500);
        }
    }

   public function store(Request $request)
    {
        try {
          
            $lastTestRun = TestRun::orderBy('code', 'desc')->first();
            $nextCode = 'R0001';

            if ($lastTestRun) {
                $lastCodeNumber = intval(substr($lastTestRun->code, 1));
                $nextCodeNumber = $lastCodeNumber + 1;
                $nextCode = 'R' . str_pad($nextCodeNumber, 4, '0', STR_PAD_LEFT);
            }
            
            $testRun = new TestRun();
            $testRun->code = $nextCode; 
            $testRun->name = $request->name;
            $testRun->projectId = $request->projectId;
            $testRun->description = $request->description;
            $testRun->testcase = json_encode($request->testcase); 
            $testRun->active = $request->active;
            $testRun->save();

            return response()->json($testRun, 201);
        } catch (\Exception $e) {
            \Log::error('Failed to create test run: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create test run'], 500);
        }
    }
    public function update(Request $request, $id)
    {
        try {


            $testRun = TestRun::find($id);
            if (!$testRun) {
                return response()->json(['error' => 'Test Run not found'], 404);
            }

            $testRun->code = $request->code;
            $testRun->name = $request->name;
            $testRun->projectId = $request->projectId;
            $testRun->description = $request->description;
            $testRun->testcase = json_encode($request->testcase); 
            $testRun->active = $request->active;
            $testRun->save();

            return response()->json($testRun);
        } catch (\Exception $e) {
            \Log::error('Failed to update test run: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update test run'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $testRun = TestRun::find($id);
            if (!$testRun) {
                return response()->json(['error' => 'Test Run not found'], 404);
            }

            $testRun->delete();
            return response()->json(['message' => 'Test Run deleted successfully']);
        } catch (\Exception $e) {
            \Log::error('Failed to delete test run: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete test run'], 500);
        }
    }
    public function isExist(Request $request)
    {
       $name = $request->query('name');
        $exists = TestRun::where('name', $name)->exists();
        return response()->json(['exists' => $exists]);
    }
    public function closeRun(Request $request, $id)
    {
        try {
            $testRun = TestRun::find($id);
            if (!$testRun) {
                return response()->json(['error' => 'Test Run not found'], 404);
            }

            $testRun->active = false;
            $testRun->save();

            return response()->json(['message' => 'Test Run closed successfully']);
        } catch (\Exception $e) {
            \Log::error('Failed to close test run: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to close test run'], 500);
        }
    }
    public function activeRun(Request $request, $id)
    {
        try {
            $testRun = TestRun::find($id);
            if (!$testRun) {
                return response()->json(['error' => 'Test Run not found'], 404);
            }

            $testRun->active = true;
            $testRun->save();

            return response()->json(['message' => 'Test Run closed successfully']);
        } catch (\Exception $e) {
            \Log::error('Failed to close test run: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to close test run'], 500);
        }
    }

}