<!DOCTYPE html>
<html>
<head>
    <title>ETJ Digital Learning - Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
         <form method="POST" action="{{ route('logout') }}">
    @csrf

    <button type="submit" class="block hover:bg-red-700 p-2 rounded w-full text-left">
        Logout
    </button>

</form>
        <div class="w-64 bg-blue-900 text-white p-5">

            <h2 class="text-2xl font-bold mb-6">
                ETJ Digital Learning
            </h2>

            <ul class="space-y-3">

                <li>
                    <a href="/admin/dashboard" class="block hover:bg-blue-700 p-2 rounded">
                        Dashboard
                    </a>
                </li>

                <li>
                    <a href="#" class="block hover:bg-blue-700 p-2 rounded">
                        Teachers
                    </a>
                </li>

                <li>
                    <a href="#" class="block hover:bg-blue-700 p-2 rounded">
                        Students
                    </a>
                </li>

                <li>
                    <a href="#" class="block hover:bg-blue-700 p-2 rounded">
                        Courses
                    </a>
                </li>

                <li>
                    <a href="#" class="block hover:bg-blue-700 p-2 rounded">
                        Exams
                    </a>
                </li>

                <li>
                    <a href="#" class="block hover:bg-blue-700 p-2 rounded">
                        Certificates
                    </a>
                </li>

                <li>
                    <a href="#" class="block hover:bg-blue-700 p-2 rounded">
                        Settings
                    </a>
                </li>

            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">

            @yield('content')

        </div>

    </div>

</body>
</html>