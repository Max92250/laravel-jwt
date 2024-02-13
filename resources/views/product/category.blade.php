@extends('product.nav')

@section('section1')

<div class="bg-white shadow-md rounded-lg overflow-hidden  mt-20  mx-auto">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase dark:text-gray-400 bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-center">SN</th>
                        <th class="px-4 py-2 text-center">Name</th>
                        <th class="px-4 py-2 text-center">Status</th>
                        <th class="px-4 py-2 text-center">Created At</th>
                        <th class="px-4 py-2 text-center">Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sn = 1; @endphp
                    @foreach($categories as $category)
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="border px-4 py-2 text-center">{{ $sn++ }}</td>
                            <td class="border px-4 py-2 text-center">{{ $category->name }}</td>
                            <td class="border px-4 py-2 text-center">{{ $category->status == 0 ? 'Active' : 'Inactive' }}</td>
                            <td class="border px-4 py-2 text-center">{{ date('d/m/Y h:i A', strtotime($category->created_at)) }}</td>
                            <td class="border px-4 py-2 text-center">{{ date('d/m/Y h:i A', strtotime($category->updated_at)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
