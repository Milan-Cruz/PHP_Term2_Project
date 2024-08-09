<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Articles') }}
                </h2>
                @auth
                    <form method="GET" action="{{ route('articles.index') }}" class="ml-4">
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
            @if(Auth::user()->role === 'Admin' || Auth::user()->role === 'Writer')
                <a href="{{ route('articles.create') }}" class="bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded">
                    {{ __('Create Article') }}
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @foreach ($articles as $article)
                        <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-md flex">
                            @if ($article->image)
                                <div class="mr-4 flex-shrink-0 flex items-center justify-center">
                                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }} image" class="h-[90px] w-[120px] object-cover rounded-md" />
                                </div>
                            @endif
                            <div class="flex flex-col justify-center">
                                <h4 class="text-xl font-bold">{{ $article->title }}</h4>
                                <p class="text-gray-700 dark:text-gray-300 line-clamp-4">{!! Str::limit($article->content, 150) !!}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $article->created_at->format('Y-m-d h:i A') }}{{ $article->created_at->isToday() ? ' (' . $article->created_at->diffForHumans() . ')' : '' }}
                                </p>
                                <div class="flex items-center mt-2">
                                    <a href="{{ route('articles.show', $article->id) }}" class="text-blue-500 hover:text-blue-600 mr-4">{{ __('Read more') }}</a>
                                    @can('update', $article)
                                        <a href="{{ route('articles.edit', $article->id) }}" class="text-yellow-500 hover:text-yellow-600 mr-4">{{ __('Edit') }}</a>
                                    @endcan
                                    @can('delete', $article)
                                        <form action="{{ route('articles.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-600">{{ __('Delete') }}</button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination Links -->
                    <div class="mt-4">
                        {{ $articles->appends(['sort' => request('sort')])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
