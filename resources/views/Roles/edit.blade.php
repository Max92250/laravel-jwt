@extends('product.nav')


@section('section9')
    <div class="bg-white rounded-lg mt-20 px-6 py-4 mx-auto">
        <div class="container mx-auto">
            <h1 class="text-2xl text-gray-500 font-bold mb-4">Edit Permissions for Role: {{ $role->name }}</h1>

            <form action="{{ route('update.permissions', $role->id) }}" method="POST">
                @csrf
                @foreach ($permissions as $permission)
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                            class="h-6 w-6 text-blue-500 rounded-full border-gray-300 focus:ring-blue-500"
                            {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                        <span class="ml-2 text-lg ">{{ $permission->name }}</span>
                    </label>
                    <br>
                @endforeach
                <button type="submit"
                    class="focus:outline-none text-white hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 border-gray-200 hover:bg-gray-100">Update
                    Permissions</button>
            </form>
        </div>
    </div>
@endsection
