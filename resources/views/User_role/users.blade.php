@extends('product.nav')

@section('section9')
<div class="bg-white  shadow-md rounded-lg overflow-hidden mt-20 mx-auto w-90">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 ">
                <thead class="text-xs text-gray-700 uppercase dark:text-gray-400 bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 bg-gray-200 text-center">SN</th>
                        <th class="px-4 py-2 bg-gray-200 text-center">Name</th>
                        <th class="px-4 py-2 bg-gray-200 text-center" style="width: 500px;">email</th>
                        <th class="px-4 py-2 bg-gray-200 text-center" style="width: 200px;">Role</th>
                        <th class="px-4 py-2 bg-gray-200 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $serialNumber = 1;
                    @endphp
                    @foreach ($users as $user)
                        <tr>
                            <td class="border px-4 py-4 text-center">{{ $serialNumber++ }}</td>
                            <td class="border px-4 py-2 text-center">{{ $user->username }}</td>
                            <td class="border px-4 py-2 text-center">{{ $user->email }}</td>
                            <td class="border px-4 py-2 w-100 text-center">
                                <div class="flex flex-wrap">
                                    @foreach ($user->roles as $role)
                                        <button
                                            class="bg-blue-200 hover:bg-blue-300 text-blue-500 font-semibold py-1 px-2 rounded-full mr-1 mb-1">
                                            {{ $role->name }}
                                        </button>
                                    @endforeach
                                </div>
                            </td>
                            <td class="border px-4 py-2 text-center">
                                <a href="{{route('users.editRoles',$user->id)}}" class="focus:outline-none text-white bg-red-400 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 border-gray-200 hover:bg-gray-100 ">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
  
@endsection
