@extends('product.nav')

@section('section7')



    <div id="create-customer-modal" class="fixed top-0 left-0 w-full h-full bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white  shadow-md p-6 rounded-md relative">
            <button id="close-create-customer-modal" class="absolute top-0 right-0   text-gray-600 hover:text-gray-800 focus:outline-none" aria-label="Close">
                <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        <h2 class="text-2xl font-semibold mb-4">Create User</h2>
      
        @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Validation Error!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
        <form action="{{ route('Users.Store') }}" method="POST" class="space-y-4" id="create-user-form">
            @csrf

            <div class="mb-4 flex">
                <div class="w-2/4">
                    <label for="customer_id" class="block font-semibold">Customer:</label>
                    <select name="customer_id" id="customer_id" class="block w-3/4 bg-gray-50 border border-gray-300 mt-4 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 ">
                        <option value="">Select Customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">User Name</label>
                <input type="text" name="username" id="username" class=" w-3/4 bg-gray-50 border border-gray-300 mt-4 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 " required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 mt-4 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 " required>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 mt-4 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 " required>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">Create User</button>
            </div>
        </form>
    </div>
    </div>
    

</div>
<div class="bg-white shadow-md rounded-lg overflow-hidden pt-20  mx-auto">
    <div class="overflow-x-auto">
        <div class="flex justify-start py-4 px-4">
            <a id="open-create-customer-modal" class="bg-white hover:bg-gray-100 text-gray-500 font-semibold py-2 px-4 border border-gray-400 rounded shadow">Create User</a>
        </div>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase dark:text-gray-400 bg-gray-200">
                                <tr>
                                    <th  class="px-4 py-3">ID</th>
                                    <th  class="px-4 py-3">Name</th>
                                    <th  class="px-4 py-3">Email</th>
                                    <th  class="px-4 py-3">Status</th>
                                    <th  class="px-4 py-3">Type</th>
                                    <th  class="px-4 py-3">Created At</th>
                                    <th  class="px-4 py-3">Updated At</th>
                                    <th class="px-4 py-2 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="border px-4 py-4">{{ $user->id }}</td>
                                        <td class="border px-4 py-4">{{ ucfirst($user->username) }}</td>
                                        <td class="border px-4 py-4">{{ $user->email }}</td>
                                        <td class="border px-4 py-4">{{ $user->active == 1 ? 'Active' : 'Inactive' }}</td>
                                        <td class="border px-4 py-4">{{ $user->type }}
                                            [ {{ $user->createdBy->name }}]</td>
                                        <td class="border px-4 py-4">{{ \Carbon\Carbon::parse($user->created_at)
                                        ->setTimezone('Asia/Kathmandu')->format('d/m/Y h:i A') }}
                                       {{ $user->creator->username ?? '' }} </td>
                                        <td class="border px-4 py-4">{{ \Carbon\Carbon::parse($user->updated_at)->setTimezone('Asia/Kathmandu')->format('d/m/Y h:i A') }}
                                            {{ $user->updator->username ?? '' }}
                                        </td>
                                        <td class="border px-4 py-2 text-center align-middle text-center">
                                            <button class="text-blue-500 hover:underline"
                                                onclick="openEditModal('{{ $user->id }}', 
                                               '{{ $user->username }}',)">
                                                <i class="fas fa-edit cursor-pointer"></i> <!-- Edit Icon -->
                                            </button>
                                        </td>
            
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            
        </div>
    </div>
    <div id="editModal" class="modal fixed top-20 left-1/2 transform -translate-x-1/2 z-50" style="display: none;">
        <div class="modal-content p-4 mt-20 bg-white shadow-md rounded-lg" style="width: 400px;">
          
        
            <span class="close font-bold mt-2 mr-2 cursor-pointer" onclick="closeEditModal()">&times;</span>
            <h2 class="text-center font-bold mb-4">Edit User</h2>
            <form id="editForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <input type="text" id="userName" name="username"
                        class="block w-full p-2 border border-gray-300 rounded-md">
                </div>
             
        
                <button type="submit"
                    class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-1 px-2 border border-gray-400 rounded shadow">
                    Update 
                </button>
            </form>
        </div>
    </div>
    <script>
          function openEditModal(id, name) {
            var modal = document.getElementById("editModal");
            var form = document.getElementById("editForm");
            form.action = "{{ url('users') }}" + "/" + id + "/update";
            var sizeNameInput = document.getElementById("userName");

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


        <!-- In your Blade view -->

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

     // Check if there are errors and show the modal accordingly
     const errors = {!! $errors->toJson() !!}; // Convert PHP errors to JavaScript object
     if (Object.keys(errors).length > 0) {
         openModal();
     }
 });
</script>

   
@endsection
