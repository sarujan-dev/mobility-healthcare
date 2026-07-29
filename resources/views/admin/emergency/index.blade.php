<x-app-layout>
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Emergency VOG Requests</h1>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3">Patient</th>
                    <th class="p-3">Doctor</th>
                    <th class="p-3">Hospital</th>
                    <th class="p-3">Distance</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Requested At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                    <tr class="border-b">
                        <td class="p-3">{{ $req->patient->user->name ?? '-' }}</td>
                        <td class="p-3">{{ $req->doctor->user->name ?? '-' }}</td>
                        <td class="p-3">{{ $req->doctor->hospital->name ?? '-' }}</td>
                        <td class="p-3">{{ $req->distance_km ? round($req->distance_km, 1).' km' : '-' }}</td>
                        <td class="p-3">
                            <span class="text-xs px-2 py-1 rounded bg-red-100 text-red-700">{{ $req->status }}</span>
                        </td>
                        <td class="p-3 text-sm text-gray-500">{{ $req->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-4 text-center text-gray-500">No emergency requests yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $requests->links() }}</div>
</div>
</x-app-layout>
