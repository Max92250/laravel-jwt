@extends('product.nav')


@section('section2')

    
<div class="bg-white shadow-md rounded-lg overflow-hidden    mt-20 mx-auto">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 ">
        <thead class="text-xs text-gray-700 uppercase dark:text-gray-400 bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-center">SN</th>
                        <th class="px-4 py-2 text-center">Size</th>
                       
                        <th class="px-4 py-2 text-center">Status</th>
                        <th class="px-4 py-2 text-center">Created At</th>
                        <th class="px-4 py-2 text-center">Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sn = 1; @endphp
                    @foreach($sizes as $size)
                        @if(!$size->parent_id)
                            <tr>
                                <td class="border px-4 py-2 text-center align-middle text-center">{{ $sn++ }}</td>
                                <td class="border px-4 py-2 text-centeralign-middle text-center">
                                    <a href="{{ route('sizes.subsizes', ['id' => $size->id]) }}" class="text-blue-500 hover:underline">{{ $size->name }}</a>
                                </td>
                               
                                <td class="border px-4 py-2 align-middle text-center">{{ $size->status }}</td>
                                <td class="border px-4 py-2 align-middle text-center">{{ date('d/m/Y h:i A', strtotime($size->created_at)) }}</td>
                                <td class="border px-4 py-2 align-middle text-center">{{ date('d/m/Y h:i A', strtotime($size->updated_at)) }}</td>
                            </tr>
                           
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection