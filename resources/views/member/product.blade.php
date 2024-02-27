@extends('member.navbar')

@section('section')
    <div class="w-4/5 mx-auto">
        <div class="grid grid-cols-1  mt-20  sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4 px-4">

            @foreach ($products as $product)
                <div class="p-4 border h-80  rounded-lg bg-white">
                    <div class="p-4 border h-60 rounded-lg bg-white flex flex-col justify-center items-center">
                    <a href="">
                        @foreach ($product->images as $index => $image)
                            @if ($index === 0)
                                <img class="h-40 w-40 " src="{{ asset('images/' . $image->image_path) }}"
                                    alt="{{ $product->name }}">
                            @break
                        @endif
                    @endforeach
                </a>
                    </div>
                <p class="text-sm text-gray-500 text-center ">{{ \Str::words($product->description, 20, '...') }}</p>
                <div class="font-bold text-xl text-gray-500 mt-2 mb-2 text-center">{{ $product->name }}</div>
            </div>
        @endforeach
    </div>
</div>
@endsection
