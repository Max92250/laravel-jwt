@extends('admin.nav')

@section('section6')

<div class="bg-white shadow-md rounded-lg overflow-hidden mt-16 mx-auto">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase dark:text-gray-400 bg-gray-200">
                <tr>
                    <th class="px-4 py-3">SN</th>
                    <th class="px-4 py-3">Username</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Created At</th>
                    <th class="px-4 py-3">Updated At</th>
                </tr>
            </thead>
            <tbody>
                @php $serialNumber = 1; @endphp
                @foreach ($user as $userData)
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <td class="border px-4 py-4">{{ $serialNumber++ }}</td>
                    <td class="border px-4 py-4">{{ ucfirst($userData->username) }}</td>
                    <td class="border px-4 py-4">{{ $userData->email }}</td>
                    <td class="border px-4 py-4">{{ $userData->active == 1 ? 'Active' : 'Inactive' }}</td>
                    <td class="border px-4 py-4">{{ $userData->type }}</td>
                    <td class="border px-4 py-4">{{ \Carbon\Carbon::parse($userData->created_at)->setTimezone('Asia/Kathmandu')->format('d/m/Y h:i A') }}</td>
                    <td class="border px-4 py-4">{{ \Carbon\Carbon::parse($userData->updated_at)->setTimezone('Asia/Kathmandu')->format('d/m/Y h:i A') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
