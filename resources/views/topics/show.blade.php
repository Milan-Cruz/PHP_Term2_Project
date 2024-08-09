<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Topic Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">{{ $topic->title }}</h3>
                    <p>{{ $topic->description }}</p>

                    <h4 class="mt-6 font-semibold">Articles</h4>
                    @if($topic->articles->isEmpty())
                        <p>No articles available for this topic.</p>
                    @else
                        @foreach ($topic->articles as $article)
                            <div class="mt-2 p-2 bg-white dark:bg-gray-700 rounded-lg shadow-md">
                                <h5 class="text-md font-semibold">{{ $article->title }}</h5>
                                <p>{{ Str::limit($article->content, 150) }}</p>
                                <a href="{{ route('articles.show', $article) }}" class="text-blue-500">Read more</a>
                            </div>
                        @endforeach
                    @endif

                    @if(Auth::user()->role === 'Admin')
                        <div class="mt-6 flex space-x-2">
                            <a href="{{ route('topics.edit', $topic) }}" class="bg-blue-500 text-white px-4 py-2 rounded-md">Edit Topic</a>
                            <form action="{{ route('topics.destroy', $topic) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md">Delete Topic</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
