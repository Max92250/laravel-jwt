@extends('product.nav')

@section('section5')

    <div class="bg-gray-100">

        <!-- End of Navbar -->
        @can('view-profile')
            <div class="container  mx-auto my-5 p-5">
                <div class="md:flex  mt-10 no-wrap md:-mx-2 ">
                    <!-- Left Side -->
                    <div class="w-full md:w-3/12 md:mx-2">
                        <!-- Profile Card -->
                        <div class="bg-white p-3 border-t-4 border-green-400">
                            <div class="image overflow-hidden">
                                <img class="h-auto w-full mx-auto" src="https://www.w3schools.com/w3css/img_avatar3.png"
                                    alt="">
                            </div>

                            <h1 class="text-gray-900 font-bold text-xl leading-8 my-1">{{ $user->username }}</h1>
                            <h3 class="text-gray-600 font-lg text-semibold leading-6">Owner at Her Company Inc.</h3>
                            <p class="text-sm text-gray-500 hover:text-gray-600 leading-6">Lorem ipsum dolor sit amet
                                consectetur adipisicing elit.
                                Reprehenderit, eligendi dolorum sequi illum qui unde aspernatur non deserunt</p>
                            <ul
                                class="bg-gray-100 text-gray-600 hover:text-gray-700 hover:shadow py-2 px-3 mt-3 divide-y rounded shadow-sm">
                                <li class="flex items-center py-3">
                                    <span>Status</span>
                                    @if ($user->active == 1)
                                        <span class="bg-green-500 ml-2 py-1 px-2 rounded text-white text-sm">Active</span>
                                    @else
                                        <span class="bg-red-500 py-1 px-2 rounded text-white text-sm">Inactive</span>
                                    @endif
                                </li>
                                <li class="flex items-center py-3">
                                    <span>Member since </span>
                                    <span class="ml-auto">{{ date('d/m/Y h:i A', strtotime($user->created_at)) }}</span>
                                </li>
                            </ul>
                        </div>
                        <!-- End of profile card -->

                        <!-- End of friends card -->
                    </div>
                    <!-- Right Side -->
                    <div class="w-full md:w-9/12 mx-2 h-64">
                        <!-- Profile tab -->
                        <!-- About Section -->
                        <div class="bg-white p-3 shadow-sm rounded-sm">
                            <div class="flex items-center space-x-2 font-semibold text-gray-900 leading-8">
                                <span clas="text-green-500">
                                    <svg class="h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </span>
                                <span class="tracking-wide">About</span>
                            </div>
                            <div class="text-gray-700">
                                <div class="grid md:grid-cols-2 text-sm">
                                    <div class="grid grid-cols-2">
                                        <div class="px-4 py-2 font-semibold">First Name</div>
                                        <div class="px-4 py-2">{{ $user->username }}</div>
                                    </div>


                                    <div class="grid grid-cols-2">
                                        <div class="px-4 py-2 font-semibold">Current Address</div>
                                        <div class="px-4 py-2">Kathmandu,nepal,sattobato,balkumari</div>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <div class="px-4 py-2 font-semibold">Permanant Address</div>
                                        <div class="px-4 py-2">kathmandu,kalanki</div>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <div class="px-4 py-2 font-semibold">Email.</div>
                                        <div class="px-4 py-2">
                                            <a class="text-blue-800" href="mailto:jane@gmail.com">{{ $user->email }}</a>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <div class="px-4 py-2 font-semibold">Type</div>
                                        <div class="px-4 py-2">{{ $user->type }}</div>
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>
                </div>
            @endcan
        @endsection
