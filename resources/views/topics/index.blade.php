<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Topics') }}
                </h2>
                @auth
                    <form method="GET" action="{{ route('topics.index') }}" class="ml-4">
                        <div class="flex items-center">
                            <label for="sort" class="mr-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Sort by') }}</label>
                            <select name="sort" id="sort" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>{{ __('Date of Creation') }}</option>
                                <option value="author" {{ request('sort') === 'author' ? 'selected' : '' }}>{{ __('Author') }}</option>
                                <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>{{ __('Title') }}</option>
                            </select>
                            <button type="submit" class="ml-2 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                {{ __('Sort') }}
                            </button>
                        </div>
                    </form>
                @endauth
            </div>
            @if(Auth::user()->role === 'Admin')
                <a href="{{ route('topics.create') }}" class="bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded">
                    {{ __('Create Topic') }}
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @foreach ($topics as $topic)
                        <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-md">
                            <h4 class="text-xl font-bold">{{ $topic->title }}</h4>
                            <p class="text-gray-700 dark:text-gray-300">{{ Str::limit($topic->description, 150) }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $topic->created_at->format('Y-m-d h:i A') }}{{ $topic->created_at->isToday() ? ' (' . $topic->created_at->diffForHumans() . ')' : '' }}
                            </p>
                            <div class="flex items-center">
                                <a href="{{ route('topics.show', $topic->id) }}" class="text-blue-500 hover:text-blue-600 mr-4">{{ __('Read more') }}</a>
                                @can('update', $topic)
                                    <a href="{{ route('topics.edit', $topic->id) }}" class="text-yellow-500 hover:text-yellow-600 mr-4">{{ __('Edit') }}</a>
                                @endcan
                                @can('delete', $topic)
                                    <form action="{{ route('topics.destroy', $topic->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-600">{{ __('Delete') }}</button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination Links -->
                    <div class="mt-4">
                        {{ $topics->appends(['sort' => request('sort')])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
