@extends('admin.nav')

@section('section1')



<div class="grid grid-cols-1 gap-6 max-w-lg mx-auto mt-40">

    <div class="bg-white shadow-md p-6 rounded-md">
        <h2 class="text-2xl font-semibold mb-4">Create User</h2>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Validation Error!</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('Users.Store') }}" method="POST" class="space-y-4" id="create-user-form">
            @csrf

            <div class="mb-4 flex">
                <div class="w-1/2">
                    <label for="customer_id" class="block font-semibold">Customer:</label>
                    <select name="customer_id" id="customer_id" class="block w-3/4 bg-gray-50 border border-gray-300 mt-4 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 ">
                        <option value="">Select Customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">User Name</label>
                <input type="text" name="username" id="username" class=" w-3/4 bg-gray-50 border border-gray-300 mt-4 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 " required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 mt-4 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 " required>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 mt-4 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 " required>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">Create User</button>
            </div>
        </form>
    </div>

</div>
@endsection
