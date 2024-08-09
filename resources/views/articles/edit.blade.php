<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Article') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Main Article Edit Form -->
                    <form method="POST" action="{{ route('articles.update', $article) }}" enctype="multipart/form-data"
                        onsubmit="tinyMCE.triggerSave();">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                                :value="old('title', $article->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="content" :value="__('Content')" />
                            <textarea id="content" class="block mt-1 w-full" name="content" required
                                style="color: black;">{{ old('content', $article->content) }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="image" :value="__('Image')" />
                            <x-text-input id="image" class="block mt-1 w-full" type="file" name="image" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="topic_id" :value="__('Topic')" />
                            <select id="topic_id" name="topic_id" class="block mt-1 w-full" style="color: black;">
                                @foreach ($topics as $topic)
                                    <option value="{{ $topic->id }}" @if ($article->topic_id == $topic->id) selected @endif>
                                        {{ $topic->title }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('topic_id')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Update') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <!-- Separate Form for Removing Image -->
                    @if ($article->image && $article->image != 'article_images/article_base.png')
                        <div class="mt-4 text-center">
                            <img src="{{ asset('storage/' . $article->image) }}" alt="Current Image"
                                class="h-40 w-40 object-cover mx-auto">
                            <form method="POST" action="{{ route('articles.resetImage', $article->id) }}" class="mt-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-red-500 text-white text-sm px-4 py-2 rounded-md hover:bg-red-600">
                                    {{ __('Remove Image') }}
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Include TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/yygbiqzvm8j8lfojssvabzmp69hswa5hc1jejxam1jcfrykc/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#content',
            plugins: 'lists link image table',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image table',
            menubar: false,
            height: 300,
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save();
                });
            }
        });
    </script>
</x-app-layout>
