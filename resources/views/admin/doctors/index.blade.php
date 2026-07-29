<x-app-layout>
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Manage Doctors</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3">Photo</th>
                    <th class="p-3">Name</th>
                    <th class="p-3">Type</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Approval</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($doctors as $doctor)
                    <tr class="border-b">
                        <td class="p-3">
                            <img src="{{ $doctor->profile_photo ? asset('storage/'.$doctor->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode($doctor->user->name) }}" class="w-10 h-10 rounded-full object-cover">
                        </td>
                        <td class="p-3">{{ $doctor->user->name }}</td>
                        <td class="p-3">{{ $doctor->doctor_type }}</td>
                        <td class="p-3">{{ $doctor->availability_status }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-sm
                                {{ $doctor->approval_status == 'approved' ? 'bg-green-100 text-green-700' : ($doctor->approval_status=='pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                {{ ucfirst($doctor->approval_status) }}
                            </span>
                        </td>
<td class="p-3">
    <a href="{{ route('admin.doctors.show', $doctor->id) }}"
       class="bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700">
        View & Verify
    </a>
</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="p-4 text-center text-gray-500">No doctors found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $doctors->links() }}</div>
</div>
</x-app-layout>
