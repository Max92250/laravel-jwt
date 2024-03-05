@extends('product.nav')

@section('section8')
    <div class="w-4/5 bg-white shadow-md rounded-lg p-8  mt-20 mx-auto">
        <h1 class="text-3xl font-semibold mb-4">Member Details</h1>
        <div class="mb-8">
            <p><strong class="font-bold text-gray-500 pt-1">Member ID:</strong>
            <div>{{ $member->id }}</div>
            </p>
            <p><strong class="font-bold text-gray-500 pt-2">Name:</strong>
            <div> {{ $member->username }}</div>
            </p>
            <p><strong class="font-bold text-gray-500 mt-2">Email:</strong>
            <div> {{ $member->email }}</div>
            </p>
            <p><strong class="font-bold text-gray-500 mt-2">Badge_id:</strong>
            <div>{{ $member->badge_id }}</div>
            </p>
        </div>

        <h2 class="text-2xl font-semibold mb-4 mt-4">Orders:</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase dark:text-gray-400 bg-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-center">Order ID</th>
                        <th class="px-4 py-3 text-center">Total Quantity</th>
                        <th class="px-4 py-3 text-center">Total Amount</th>
                        <th class="px-4 py-3 text-center">shipment_address</th>
                        <th class="px-4 py-3 text-center">Payment Method</th>
                        <th class="px-4 py-3 text-center">status</th>
                        <th class="px-4 py-3 text-center">Date</th>

                        <!-- Add more columns as needed -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="border px-4 py-4 text-center">{{ $order->id }}</td>
                            <td class="border px-4 py-4 text-center">{{ $order->quantity }}</td>
                            <td class="border px-4 py-4 text-center">${{ $order->total }}</td>
                            <td class="border px-4 py-4 text-center">
                                {{ $order->shipment->address_line1 }},{{ $order->shipment->address_line2 }},{{ $order->shipment->postal_code }}
                            </td>
                            <td class="border px-4 py-4 text-center">{{ $order->payment->name }}</td>
                            <td class="border px-4 py-4 text-center">{{ $order->status }}</td>
                            <td class="border px-4 py-4 text-center">
                                {{ \Carbon\Carbon::parse($order->created_at)->setTimezone('Asia/Kathmandu')->format('d/m/Y h:i A') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <h2 class="text-2xl font-semibold mb-4 mt-4">Credit_logs:</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase dark:text-gray-400 bg-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-center"> ID</th>
                        <th class="px-4 py-3 text-center">initial_amount</th>
                        <th class="px-4 py-3 text-center">added_amount</th>
                        <th class="px-4 py-3 text-center">final_amount</th>
                        <th class="px-4 py-3 text-center">date</th>
                        <!-- Add more columns as needed -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($creditlogs as $credit)
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="border px-4 py-4 text-center">{{ $credit->id }}</td>
                            <td class="border px-4 py-4 text-center">${{ $credit->initial_amount }}</td>
                            <td class="border px-4 py-4 text-center">${{ $credit->Added_amount }}</td>
                            <td class="border px-4 py-4 text-center">${{ $credit->final_amount }}</td>
                            <td class="border px-4 py-4 text-center">
                                {{ \Carbon\Carbon::parse($credit->created_at)->setTimezone('Asia/Kathmandu')->format('d/m/Y h:i A') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
