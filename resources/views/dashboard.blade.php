<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p>{{ __('Welcome to your dashboard!') }}</p>

                    @if(Auth::user()->role === 'Admin')
                        <p>{{ __('You are logged in as an Admin.') }}</p>
                        <!-- Add admin-specific content here -->

                        <div class="mt-4">
                            <a href="{{ route('topics.create') }}" class="bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded mr-2">
                                {{ __('Create Topic') }}
                            </a>
                            <a href="{{ route('articles.create') }}" class="bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded">
                                {{ __('Create Article') }}
                            </a>
                        </div>

                    @elseif(Auth::user()->role === 'Writer')
                        <p>{{ __('You are logged in as a Writer.') }}</p>
                        <!-- Add writer-specific content here -->

                        <div class="mt-4">
                            <a href="{{ route('articles.create') }}" class="bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded">
                                {{ __('Create Article') }}
                            </a>
                        </div>
                    @else
                        <p>{{ __('You are logged in as a User.') }}</p>
                        <!-- Add user-specific content here -->
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
