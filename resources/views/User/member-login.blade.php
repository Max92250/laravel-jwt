<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/css/app.css')
</head>

<body>
    <!-- resources/views/member-login.blade.php -->

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Member Login</title>
        <!-- Include Tailwind CSS -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body class="bg-gray-100 flex items-center justify-center h-screen">

        <div class="bg-white rounded-lg shadow-md p-8 w-full sm:w-96">
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Validation Error!</strong>

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </div>
            @endif

            <h1 class="text-2xl mb-4 text-center">Member Login</h1>
            <form action="{{ route('login.member') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium mt-2 text-gray-900 ">Your email</label>
                    <input type="email" name="email" id="email"
                        class="bg-gray-50 border border-gray-300 mt-4 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5  "
                        placeholder="name@company.com" required="">

                </div>
                <div>
                    <label for="password" class="block mb-2 text-sm mt-2 font-medium text-gray-900 ">Password</label>
                    <input type="password" name="password" id="password" placeholder="••••••••"
                        class="bg-gray-50 border border-gray-300 mt-4 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5  required="">
                </div>
                <button type="submit"
                    class="text-white bg-blue-700 mt-6  hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">login</button>
            </form>
        </div>
    </body>

    </html>

</body>

</html>
