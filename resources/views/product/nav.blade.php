<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/css/app.css')
</head>


<body class="bg-gray-100 w-full  h-screen">
    <nav class="py-4 bg-white fixed pt-6 top-0 left-0 right-0 z-50 shadow-md">
        <div class="container mx-auto flex justify-between items-center px-6">
            <div class="flex items-center">
                <!-- Logo -->
                @if (Auth::user()->isAdmin())
                    <!-- If user is admin, link to admin page -->
                    <a href="{{ route('dashboard') }}" class="text-black text-lg font-semibold mr-4">Admin</a>
                @else
                    <!-- If user is not admin, link to user page -->
                    <a href="{{ route('dashboard') }}" class="text-black text-lg font-semibold mr-4">User</a>
                @endif

                <div class="relative mr-4">
                    @if (Auth::user()->isAdmin())
                        <!-- If user is admin, link to admin page -->
                        <a href="{{ route('dashboard') }}"
                            class="text-black hover:text-gray-300 focus:outline-none">Customer</a>
                    @else
                        <!-- If user is not admin, link to user page -->
                        <a href="{{ route('categories') }}"
                            class="text-black hover:text-gray-300 focus:outline-none">Categories</a>
                    @endif
                </div>

                <!-- Product Size Link -->
                <div class="relative mr-4">
                    @if (Auth::user()->isAdmin())
                        <!-- If user is admin, link to admin page -->
                        <a href="{{ route('users.details') }}"
                            class="text-black hover:text-gray-300 focus:outline-none">User</a>
                    @else
                        <!-- If user is not admin, link to user page -->
                        <a href="{{ route('sizes') }}"
                            class="text-black hover:text-gray-300 focus:outline-none">Size</a>
                    @endif

                </div>

                <!-- Product Link -->
                <div class="relative mr-4">
                    @if (Auth::user()->isAdmin())
                        <!-- If user is admin, link to admin page -->
                    @else
                        <!-- If user is not admin, link to user page -->
                        <a href="{{ route('dashboard') }}"
                            class="text-black hover:text-gray-300 focus:outline-none">Product</a>
                    @endif
                </div>
                <div class="relative mr-4">
                    @if (Auth::user()->isAdmin())
                        <!-- If user is admin, link to admin page -->
                    @else
                        <!-- If user is not admin, link to user page -->
                        <a href="{{ route('products.create.form') }}"
                            class="text-black hover:text-gray-300 focus:outline-none">Create</a>
                    @endif

                </div>
                <div class="relative mr-4">
                    @if (auth()->user()->Role('admin'))
                        <a class="text-black hover:text-gray-300 focus:outline-none" href="{{route('signup')}}">Signup</a>
                    @endif
    
                </div>
                <div class="relative mr-4">
                    @if (auth()->user()->Role('admin'))
                        <a class="text-black hover:text-gray-300 focus:outline-none" href="{{route('role.index')}}">Permission</a>
                    @endif
    
                </div>

                <div class="relative mr-4">
                    @if (auth()->user()->Role('admin'))
                        <a class="text-black hover:text-gray-300 focus:outline-none" href="{{route('roles.index')}}">Roles</a>
                    @endif

                </div>

            </div>
        
            <!-- User Profile and Logout -->
            <div class="flex items-center">
                @if (Auth::user()->isAdmin())
                    <!-- If user is admin, link to admin page -->
                    <form action="{{ route('admin.customers.search') }}" method="GET" class="ml-auto flex">
                        <div class="relative">
                            <input type="text" id="searchQuery" class="form-input w-64 sm:w-auto"
                                placeholder="Search for customers" name="q" required>

                        </div>
                    </form>
                @else
                    <form action="{{ route('products.by.search') }}" method="GET" class="ml-auto flex">
                        <div class="relative">
                            <input type="text" id="searchQuery" class="form-input w-64 sm:w-auto"
                                placeholder="Search for products" name="q" required>

                        </div>
                    </form>
                @endif

                <div class="relative mr-4 text-black hover:text-gray-300 focus:outline-none">
                    <div class="relative">

                        <select onchange="UserChange(this)"
                            class="text-black focus:outline-none bg-white  border-2 border-gray-300 py-1 px-3 rounded-lg">
                            <option>{{ session('user') }}</option>
                            <option value="profile">Profile</option>
                            <option value="logout">Logout</option>

                        </select>

                    </div>
                </div>

            </div>

        </div>
    </nav>
    @yield('section')
    @yield('section1')
    @yield('section2')
    @yield('section3')
    @yield('section4')
    @yield('section5')
    @yield('section6')
    @yield('section7')
    @yield('section8')
    @yield('section9')
    <script>
        function UserChange(select) {
            const selectedOption = select.options[select.selectedIndex].value;
            if (selectedOption === 'profile') {

                window.location.href = '/profiles';
            } else if (selectedOption === 'logout') {

                window.location.href = '/logout';
            }
        }
    </script>
</body>

</html>
