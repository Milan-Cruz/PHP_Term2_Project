<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;

class TopicController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // Handle sorting
        $sort = $request->get('sort', 'created_at');
        $topics = Topic::with('user')
            ->when($sort === 'author', function ($query) {
                $query->join('users', 'users.id', '=', 'topics.user_id')
                    ->select('topics.*', 'users.name as author_name')
                    ->orderBy('author_name');
            }, function ($query) use ($sort) {
                $query->orderBy($sort);
            })
            ->paginate(10); // Added pagination
    
        // Fetch the 5 most recent topics for the dropdown
        $recentTopics = Topic::orderBy('created_at', 'desc')->take(5)->get();
    
        return view('topics.index', compact('topics', 'sort', 'recentTopics'));
    }
    


    public function create()
    {
        $this->authorize('create', Topic::class);
        return view('topics.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Topic::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Topic::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('topics.index')->with('success', 'Topic created successfully.');
    }

    public function show(Topic $topic)
    {
        $this->authorize('view', $topic);
        return view('topics.show', compact('topic'));
    }

    public function edit(Topic $topic)
    {
        $this->authorize('update', $topic);
        return view('topics.edit', compact('topic'));
    }

    public function update(Request $request, Topic $topic)
    {
        $this->authorize('update', $topic);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $topic->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('topics.index')->with('success', 'Topic updated successfully.');
    }

    public function destroy(Topic $topic)
    {
        $this->authorize('delete', $topic);

        $topic->delete();

        return redirect()->route('topics.index')->with('success', 'Topic deleted successfully.');
    }
}
