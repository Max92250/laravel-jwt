@extends('product.nav')

@section('section4')
    <div id="overlay" class="fixed top-0 left-0 w-full h-full bg-black opacity-30 z-20" style="display: none;"></div>
    <div class="bg-white shadow-md rounded-lg overflow-hidden mt-20 mx-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 ">
            <thead class="text-xs text-gray-700 uppercase dark:text-gray-400 bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-center">SN</th>
                    <th class="px-4 py-2 text-center">Size</th>
                    <th class="px-4 py-2 text-center">Status</th>
                    <th class="px-4 py-2 text-center">Created At</th>
                    <th class="px-4 py-2 text-center">Updated At</th>
                    <th class="px-4 py-2 text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @php $sn = 1; @endphp
                @foreach ($sizes as $subSize)
                    @if ($subSize->parent_id === $size->id)
                        <tr>
                             <td class="border px-4 py-2 align-middle text-center">{{ $sn++ }}</td>
                            <td class="border px-4 py-2 align-middle text-center">{{ $subSize->name }}</td>
                            <td class="border px-4 py-2 align-middle text-center">{{ $subSize->status }}</td>
                            <td class="border px-4 py-2 align-middle text-center">
                                {{ date('d/m/Y h:i A', strtotime($subSize->created_at)) }}
                             {{$subSize->createdBy->username}}</td>
                            <td class="border px-4 py-2 align-middle text-center">
                                {{ date('d/m/Y h:i A', strtotime($subSize->updated_at)) }}
                                {{$subSize->updatedBy->username}}
                               </td>
                               </td>
                            <td class="border px-4 py-2 align-middle text-center">
                                <button class="text-blue-500 hover:underline"
                                    onclick="openEditModal('{{ $subSize->id }}', '{{ $subSize->name }}')">
                                    <i class="fas fa-edit cursor-pointer"></i> <!-- Edit Icon -->
                                </button>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="editModal" class="modal fixed top-20 left-1/2 transform -translate-x-1/2 z-50" style="display: none;">
        <div class="modal-content p-4 mt-20 bg-white shadow-md rounded-lg" style="width: 400px;">
            <span class="close font-bold mt-2 mr-2 cursor-pointer" onclick="closeEditModal()">&times;</span>
            <h2 class="text-center font-bold mb-4">Edit Size</h2>
            <form id="editForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <input type="text" id="sizeName" name="sizeName"
                        class="block w-full p-2 border border-gray-300 rounded-md">
                </div>
                <button type="submit"
                    class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-1 px-2 border border-gray-400 rounded shadow">
                    Update Size
                </button>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, name) {
            var modal = document.getElementById("editModal");
            var form = document.getElementById("editForm");
            form.action = "{{ url('sizes') }}" + "/" + id + "/update";
            var sizeNameInput = document.getElementById("sizeName");
            sizeNameInput.value = name;
            modal.style.display = "block";

            var overlay = document.getElementById("overlay");
            overlay.style.display = "block";
        }

        function closeEditModal() {
            var modal = document.getElementById("editModal");
            modal.style.display = "none";

            // Hide the overlay
            var overlay = document.getElementById("overlay");
            overlay.style.display = "none";
        }
    </script>
@endsection
