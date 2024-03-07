@extends('product.nav')


@section('section9')
<div class="bg-white mt-20 px-6 py-4 mx-auto">
    <div class="container mx-auto">
        <h1 class="text-2xl text-gray-500 font-bold mb-4">Edit Users Roles</h1>

    <form action="{{ route('users.updateRoles', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <h2 class="text-2xl text-gray-500  mb-4">User: {{ $user->username }}</h2>

        <h3>Roles:</h3>
        <ul>
            @foreach ($roles as $role)
                
                    <label>
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                            {{ $user->roles->contains($role->id) ? 'checked' : '' }}>
                        {{ $role->name }}
                    </label>
                
            @endforeach
        </ul>

        <button type="submit"
        class="focus:outline-none text-white mt-4 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 border-gray-200 hover:bg-gray-100">Update
        Permissions</button>
    </form>
    </div>
@endsection 
