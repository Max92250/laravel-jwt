@extends('admin.nav')

@section('section2')

    <div class="grid grid-cols-1 mt-40 gap-6 max-w-lg mx-auto ">

        <div class="bg-white shadow-md p-6  rounded-md">
            <h2 class="text-2xl font-semibold mb-4">Create Customer</h2>

            @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('identifier')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <form action="{{ route('customers.store') }}" method="POST" class="space-y-4" id="create-customer-form">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Customer Name</label>
                        <input type="text" name="name" id="name" class="form-input @error('name') border-red-500 @enderror" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="identifier" class="block text-sm font-medium text-gray-700">Customer Identifier</label>
                        <input type="text" name="identifier" id="identifier" class="form-input @error('identifier') border-red-500 @enderror" required>
                        @error('identifier')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>





                <div class="text-center">
                    <button type="submit"
                        class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">Create</button>
                </div>
            </form>
        </div>

    </div>


@endsection