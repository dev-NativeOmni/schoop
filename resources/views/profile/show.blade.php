<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50 dark:bg-gray-900">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile – {{ Auth::user()->name ?? 'User' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex flex-col items-center justify-center p-6">
    <div class="max-w-2xl w-full bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 space-y-6">
        <div class="flex items-center space-x-4">
            <img src="{{ Auth::user()->profile_picture_url ?? 'https://via.placeholder.com/96' }}"
                 alt="Avatar"
                 class="w-24 h-24 rounded-full border-4 border-gray-300 dark:border-gray-600" />
            <div>
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
                    {{ Auth::user()->name ?? 'Unnamed User' }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    {{ Auth::user()->email ?? 'No email' }}
                </p>
            </div>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
            <h2 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">Details</h2>
            <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                <li><strong>Registered on:</strong> {{ Auth::user()->created_at->format('M d, Y') }}</li>
                <li><strong>Last updated:</strong> {{ Auth::user()->updated_at->format('M d, Y') }}</li>
                <!-- Add more user attributes as needed -->
            </ul>
        </div>
        <div class="flex justify-end space-x-3 mt-4">
            <a href="{{ Route::has('profile.edit') ? route('profile.edit') : '#' }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">Edit Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition">
                    Logout
                </button>
            </form>
        </div>
    </div>
</body>
</html>
