<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Chana Frozen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    
    <div class="w-full max-w-sm p-6 bg-white rounded-lg shadow-lg">
        <!-- Logo -->
        <div class="flex flex-col items-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Chana" class="h-36 w-36 object-contain">
        </div>

        <!-- Login Form -->
        <form method="POST" action="/login" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input name="email" type="email" placeholder="you@example.com" required
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input name="password" type="password" placeholder="********" required
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
            </div>

            @if($errors->any())
                <div class="text-red-600 text-sm">{{ $errors->first() }}</div>
            @endif

            <button type="submit"
                class="w-full bg-blue-500 text-white font-semibold py-2 rounded hover:bg-blue-600 transition">
                Login
            </button>
        </form>
    </div>

</body>
</html>
