@extends('product.nav')

@section('section2')
    @if (Auth::user()->hasPermission('create-size'))
        <div id="create-customer-modal"
            class="fixed top-0 left-0 w-full h-full bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
            <div class="bg-white w-2/5 shadow-md p-6 rounded-md relative">
                <button id="close-create-customer-modal"
                    class="absolute top-0 right-0   text-gray-600 hover:text-gray-800 focus:outline-none" aria-label="Close">
                    <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>


                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <strong class="font-bold">Validation Error!</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('sizes.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text"
                            class=" w-3/4 bg-gray-50 border border-gray-300 mt-4 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="parent_id">Parent Size:</label>
                        <select
                            class="block w-3/4 bg-gray-50 border border-gray-300 mt-4 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 "
                            id="parent_id" name="parent_id">
                            <option value="">None</option>
                            @foreach ($sizes as $size)
                                @if ($size->parent_id === null)
                                    <option value="{{ $size->id }}">{{ $size->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label for="status">Status:</label>
                        <select
                            class="block w-3/4 bg-gray-50 border border-gray-300 mt-4 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            id="status" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <button type="submit"
                        class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">Create</button>
                </form>

            </div>

        </div>
    @endif

    <div id="overlay" class="fixed top-0 left-0 w-full h-full bg-black opacity-30 z-20" style="display: none;"></div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden mt-20 mx-auto">
        <div class="overflow-x-auto">
            <div class="flex justify-start py-4 px-4">
                <a id="open-create-customer-modal"
                    class="bg-white hover:bg-gray-100 text-gray-500 font-semibold py-2 px-4 border border-gray-400 rounded shadow">Create
                    Size</a>
            </div>
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
                    @foreach ($sizes as $size)
                        @if (!$size->parent_id)
                            <tr>
                                <td class="border px-4 py-2 text-center align-middle text-center">
                                    <a href="{{ route('sizes.subsizes', ['id' => $size->id]) }}">{{ $sn++ }}</a>
                                </td>
                                <td class="border px-4 py-2 text-center align-middle text-center">{{ $size->name }}</td>
                                <td class="border px-4 py-2 text-center align-middle text-center">
                                    {{ $size->status == 1 ? 'Active' : 'Inactive' }}</td>
                                <td class="border px-4 py-2 text-center align-middle text-center">
                                    {{ \Carbon\Carbon::parse($size->created_at)->setTimezone('Asia/Kathmandu')->format('d/m/Y h:i A') }}
                                    {{ $size->createdBy->username ?? '' }}
                                </td>
                                <td class="border px-4 py-2 text-center align-middle text-center">
                                    {{ \Carbon\Carbon::parse($size->created_at)->setTimezone('Asia/Kathmandu')->format('d/m/Y h:i A') }}
                                    {{ $size->updatedBy->username ?? '' }}</td>
                                <td class="border px-4 py-2 text-center align-middle text-center">
                                    <button class="text-blue-500 hover:underline"
                                        onclick="openEditModal('{{ $size->id }}', '{{ $size->name }}','{{$size->status}}')">
                                        <i class="fas fa-edit cursor-pointer"></i> <!-- Edit Icon -->
                                    </button>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        @if (Auth::user()->hasPermission('size-edit'))
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
                        <div class="mb-4">
                            <label for="userStatus" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="userStatus" class="block w-full p-2 border border-gray-300 rounded-md">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <button type="submit"
                            class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-1 px-2 border border-gray-400 rounded shadow">
                            Update Size
                        </button>
                    </form>
                </div>
            </div>
        @endif
        <script>
            function openEditModal(id, name,status) {
                var modal = document.getElementById("editModal");
                var form = document.getElementById("editForm");
                form.action = "{{ url('sizes') }}" + "/" + id + "/update";
                var sizeNameInput = document.getElementById("sizeName");
                var statusInput = document.getElementById("userStatus");
            statusInput.value = status;
                sizeNameInput.value = name;
                modal.style.display = "block";

                // Show the overlay
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

            document.addEventListener("DOMContentLoaded", function() {
                const openModalButton = document.getElementById("open-create-customer-modal");
                const modal = document.getElementById("create-customer-modal");
                const closeModalButton = document.getElementById("close-create-customer-modal");

                // Function to open the modal
                function openModal() {
                    modal.classList.remove("hidden");
                }

                // Function to close the modal
                function closeModal() {
                    modal.classList.add("hidden");
                }

                // Event listener to open the modal when the button is clicked
                openModalButton.addEventListener("click", openModal);

                // Event listener to close the modal when the close button is clicked
                closeModalButton.addEventListener("click", closeModal);

                // Event listener to close the modal when clicked outside of it
                modal.addEventListener("click", function(event) {
                    if (event.target === modal) {
                        closeModal();
                    }
                });
                const errors = {!! $errors->toJson() !!}; // Convert PHP errors to JavaScript object
                if (Object.keys(errors).length > 0) {
                    openModal();
                }
            });
        </script>
    @endsection
