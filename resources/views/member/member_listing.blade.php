@extends('product.nav')

@section('section8')


<div class="bg-white shadow-md rounded-lg overflow-hidden mt-16 mx-auto">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase dark:text-gray-400 bg-gray-200">
            <tr>
                <th  class="px-4 py-3 text-center">ID</th>
                <th  class="px-4 py-3 text-center">Name</th>
                <th  class="px-4 py-3 text-center">Email</th>
                <th  class="px-4 py-3 text-center">Action</th>
                <!-- Add more columns as needed -->
            </tr>
        </thead>
        <tbody>
            @foreach($members as $member)
            <tr class="border-b border-gray-200 dark:border-gray-700">
                <td  class="border px-4 py-4 text-center">{{ $member->id }}</td>
                <td  class="border px-4 py-4 text-center">{{ $member->username }}</td>
                <td  class="border px-4 py-4 text-center">{{ $member->email }}</td>
                <td  class="border px-4 py-2 text-center align-middle text-center"><a href="{{ route('members.show', $member->id) }}"  class="bg-white hover:bg-gray-100 text-gray-500 font-semibold py-2 px-4 border border-gray-400 rounded shadow">details</a></td>
                <!-- Add more columns as needed -->
            </tr>
            @endforeach
        </tbody>
    </table>
</div>








@endsection