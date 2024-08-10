<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Article Details') }}
        </h2>
    </x-slot>

    <!-- Add CSS here if only for this view -->
    <style>
        ul,
        ol {
            margin-left: 1.5em;
            list-style-position: inside;
        }

        li {
            margin-bottom: 0.5em;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">{{ $article->title }}</h3>

                    <!-- Check if the article has an image and display it -->
                    @if ($article->image && $article->image != 'article_images/article_base.png')
                    <div class="mt-4">
                        <img src="{{ Storage::url($article->image) }}" alt="{{ $article->title }}"
                            class="w-full h-auto">
                    </div>
                    @endif

                    <div class="mt-4">
                        {!! $article->content !!}
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('articles.index') }}" class="text-blue-500">{{ __('Back to articles') }}</a>
                    </div>

                    <!-- Comments Section -->
                    <div class="mt-6">
                        <h4 class="font-semibold text-lg">{{ __('Comments') }}</h4>

                        @foreach ($article->comments as $comment)
                        <div class="mt-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-md">
                            <p>{{ $comment->content }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                {{ $comment->created_at->format('Y-m-d h:i A') }}{{ $comment->created_at->isToday() ? ' (' . $comment->created_at->diffForHumans() . ')' : '' }}
                                @if ($comment->updated_at != $comment->created_at)
                                <span class="text-gray-500 italic"> - {{ __('Edited') }}</span>
                                @endif
                                - {{ $comment->user->name }}
                            </p>

                            @can('update', $comment)
                            <a href="{{ route('comments.edit', $comment->id) }}"
                                class="text-yellow-500 hover:text-yellow-600 mr-4">{{ __('Edit') }}</a>
                            @endcan
                            @can('delete', $comment)
                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-500 hover:text-red-600">{{ __('Delete') }}</button>
                            </form>
                            @endcan
                        </div>
                        @endforeach
                    </div>

                    <!-- Add Comment Form -->
                    <div class="mt-6">
                        <h4 class="font-semibold text-lg">{{ __('Add a Comment') }}</h4>
                        <form method="POST" action="{{ route('comments.store', $article->id) }}">
                            @csrf

                            <div class="mt-4">
                                <x-input-label for="content" :value="__('Comment')" />
                                <textarea id="content" class="block mt-1 w-full" name="content" required
                                    style="color: black;">{{ old('content') }}</textarea>
                                <x-input-error :messages="$errors->get('content')" class="mt-2" />
                            </div>

                            <!-- CAPTCHA Section -->
                            <div class="mt-4">
                                <x-input-label for="captcha" :value="__('CAPTCHA')" />

                                <!-- CAPTCHA Image -->
                                <div class="flex items-center">
                                    @php
                                    $captcha_text = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
                                    session(['captcha_code' => $captcha_text]);

                                    $image = imagecreate(150, 50);
                                    $bg_color = imagecolorallocate($image, 255, 255, 255);
                                    $text_color = imagecolorallocate($image, 0, 0, 0);
                                    $font = realpath(public_path('arial.ttf')); // Ensure your font file is available

                                    imagettftext($image, 20, 0, 20, 35, $text_color, $font, $captcha_text);

                                    ob_start();
                                    imagepng($image);
                                    $image_data = ob_get_contents();
                                    ob_end_clean();
                                    imagedestroy($image);

                                    $captcha_image_data = 'data:image/png;base64,' . base64_encode($image_data);
                                    @endphp

                                    <img src="{{ $captcha_image_data }}" alt="CAPTCHA" class="mr-4">
                                    <x-text-input id="captcha" class="block mt-1 w-1/2" type="text" name="captcha"
                                        required />
                                </div>

                                <x-input-error :messages="$errors->get('captcha')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button class="ml-4">
                                    {{ __('Add Comment') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>