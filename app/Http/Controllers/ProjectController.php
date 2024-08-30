<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Log;
class ProjectController extends Controller
{
   public function getAllProject(Request $request)
    {
        try {
            $pageIndex = $request->query('pageIndex', 1);
            $pageSize = $request->query('pageSize', 25);
            $projects = Project::paginate($pageSize, ['*'], 'page', $pageIndex);
            return response()->json([
                'results' => $projects->items(),
                'param' => [
                    'pageIndex' => $projects->currentPage(),
                    'pageSize' => $projects->perPage(),
                    'totalCount' => $projects->total()
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to retrieve projects: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve projects'], 500);
        }
    }
  public function getSelectProject()
    {
        try {
            $projects = Project::all();
            return response()->json($projects);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve projects', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to retrieve projects'], 500);
        }
    }
   

    public function getProjectById(Request $request, $id) {
        try {
            $project = Project::find($id);

            if (!$project) {
                return response()->json(['error' => 'Project not found'], 404);
            }

            return response()->json($project);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Failed to retrieve project by ID'], 500);
        }
    }

    public function add(Request $request)
    {
        try {
            $project = new Project();
            $project->name = $request->name;
            $project->description = $request->description;
            $project->save();

            return response()->json(['message' => 'Project created successfully', 'project' => $project], 201);
        } catch (\Exception $e) {
            \Log::error('Failed to create project: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create project'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $project = Project::findOrFail($id);
            if (!$project) {
                return response()->json(['error' => 'Project not found'], 404);
            }

            if ($request->has('name')) {
                $project->name = $request->name;
            }
            if ($request->has('description')) {
                $project->description = $request->description;
            }
            $project->save();

            return response()->json(['message' => 'Project updated successfully', 'project' => $project], 200);
        } catch (\Exception $e) {
            \Log::error('Failed to update project: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update project'], 500);
        }
    }

    public function delete($id)
    {
        try {
            $project = Project::findOrFail($id);
            $project->delete();
            return response()->json(['message' => 'Project deleted successfully']);
        } catch (\Exception $e) {
            \Log::error('Failed to delete project: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete project'], 500);
        }
    }

  public function isExist(Request $request)
    {
        $name = $request->query('name');
        $exists = Project::where('name', $name)->exists();
        return response()->json(['exists' => $exists]);
    }


}