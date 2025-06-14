<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <form method="POST" action="/login" class="bg-white p-6 rounded shadow w-96">
        @csrf
        <h2 class="text-xl mb-4 font-bold">Login</h2>

        <input name="email" type="email" placeholder="Email" class="w-full mb-2 p-2 border rounded">
        <input name="password" type="password" placeholder="Password" class="w-full mb-2 p-2 border rounded">

        @if($errors->any())
            <div class="text-red-600 text-sm mb-2">{{ $errors->first() }}</div>
        @endif

        <button class="w-full bg-blue-500 text-white p-2 rounded">Login</button>
    </form>
</body>
</html>
