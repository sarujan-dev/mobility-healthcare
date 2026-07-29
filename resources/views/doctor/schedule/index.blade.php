<x-app-layout>
<div style="padding:24px; max-width:1400px; margin:0 auto;">
    <h1 class="text-2xl font-bold mb-4">Weekly Schedule</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('doctor.schedule.update') }}" class="bg-white shadow rounded-lg p-6">
        @csrf
        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="py-2">Day</th>
                    <th class="py-2">Start Time</th>
                    <th class="py-2">End Time</th>
                    <th class="py-2">Day Off</th>
                </tr>
            </thead>
            <tbody>
                @foreach($days as $day)
                    @php $schedule = $schedules[$day] ?? null; @endphp
                    <tr class="border-b">
                        <td class="py-2 font-medium">{{ $day }}</td>
                        <td class="py-2">
                            <input type="time" name="start_time_{{ $day }}"
    value="{{ (!$schedule || $schedule->is_off) ? '' : ($schedule->start_time ?? '') }}"
    class="border rounded p-1"
    placeholder="--:--">
                        </td>
                        <td class="py-2">
                            <input type="time" name="end_time_{{ $day }}" value="{{ $schedule->end_time ?? '' }}" class="border rounded p-1">
                        </td>
                        <td class="py-2">
                            <input type="checkbox" name="is_off_{{ $day }}" value="1" {{ ($schedule->is_off ?? false) ? 'checked' : '' }}>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="mt-4 bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">Save Schedule</button>
    </form>

    <div class="mt-4">
        <a href="{{ route('doctor.profile.edit') }}" class="text-indigo-600 underline">&larr; Back to Profile</a>
    </div>
</div>
</x-app-layout>
