<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Task::query()->withTrashed(); // ✅ Includes soft deleted tasks

        // Apply Search
        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('category', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%");
            });
        }

        // ✅ Apply Filters for Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // ✅ Apply Filters for Category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // ✅ Apply Due Date Filter (Single Date)
        if ($request->filled('due_date')) {
            $query->whereDate('due_date', '=', $request->due_date);
        }

        // ✅ Apply Due Date Range Filter Correctly
        if ($request->filled('due_date_from') && $request->filled('due_date_to')) {
            $dueDateFrom = date('Y-m-d', strtotime($request->due_date_from)); // Convert to proper format
            $dueDateTo = date('Y-m-d', strtotime($request->due_date_to));

            $query->whereBetween('due_date', [$dueDateFrom, $dueDateTo]);
        } elseif ($request->filled('due_date_from')) {
            $dueDateFrom = date('Y-m-d', strtotime($request->due_date_from));
            $query->whereDate('due_date', '>=', $dueDateFrom);
        } elseif ($request->filled('due_date_to')) {
            $dueDateTo = date('Y-m-d', strtotime($request->due_date_to));
            $query->whereDate('due_date', '<=', $dueDateTo);
        }

        // ✅ Apply Soft Delete Filter (Show Only Deleted Tasks)
        if ($request->has('deleted') && $request->deleted == 1) {
            $query->onlyTrashed();
        }

        // Apply Sorting
        $sortBy = $request->query('sortBy', 'created_at'); // Default sort column
        $sortOrder = $request->query('sortOrder', 'desc'); // Default order

        if (in_array($sortBy, ['title', 'description', 'status', 'category', 'due_date', 'created_at', 'updated_at']) &&
            in_array($sortOrder, ['asc', 'desc'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // ✅ Return JSON with pagination
        return response()->json($query->paginate(10));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:pending,completed',
            'category' => 'in:Work,Personal,Urgent',
            'due_date' => 'nullable|date',
        ]);

        $task = Task::create($validated);

        return response()->json($task, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return response()->json($task);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,completed',
            'category' => 'required|in:Work,Personal,Urgent',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);

        return response()->json(['message' => 'Task updated successfully', 'task' => $task]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task soft deleted']);
    }

    public function restore($id)
    {
        $task = Task::onlyTrashed()->findOrFail($id);
        $task->restore();

        return response()->json(['message' => 'Task restored', 'task' => $task]);
    }


    public function summary()
    {
        $now = Carbon::now();

        $totalTasks = Task::count();
        $pendingTasks = Task::where('status', 'pending')->count();
        $completedTasks = Task::where('status', 'completed')->count();
        $overdueTasks = Task::whereNotNull('due_date')
                            ->where('due_date', '<', $now)
                            ->where('status', '!=', 'completed')
                            ->count();

        return response()->json([
            'total' => $totalTasks,
            'pending' => $pendingTasks,
            'completed' => $completedTasks,
            'overdue' => $overdueTasks,
        ]);
    }

}
