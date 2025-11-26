@extends('layouts.app')

@section('title', 'Permits | ParkPiper')

@section('content')
    <div class="max-w-5xl mx-auto pt-8 pb-16">
        @php
            $options = [
                'valid_from_desc' => 'Valid from desc',
                'valid_from_asc'  => 'Valid from asc',
                'valid_to_desc'   => 'Valid to desc',
                'valid_to_asc'    => 'Valid to asc',
                'created_at_desc' => 'Created at desc',
                'created_at_asc'  => 'Created at asc',
            ];

            $current = "{$sort}_{$direction}";
        @endphp
        <form method="GET" class="mb-6 flex items-center gap-3 text-sm text-gray-700 dark:text-gray-300">
            <div class="flex items-center gap-3 text-sm text-gray-700 dark:text-gray-300">
                <label for="sort_option" class="whitespace-nowrap">Sort</label>
                <div class="relative w-48">
                    <select
                        id="sort_option"
                        name="sort_option"
                        class="w-full appearance-none px-3 py-2 pr-10 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-md text-sm text-gray-900 dark:text-white focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-400"
                        onchange="this.form.submit()"
                    >
                        @foreach($options as $key => $label)
                            <option value="{{ $key }}" {{ $current === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400 dark:text-gray-500 text-xs">
                        ▼
                    </span>
                </div>
            </div>
        </form>

        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Licence Plate</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Valid From</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Valid To</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($permits as $permit)
                        <tr>
                            <td class="px-6 py-4 text-sm font-mono font-semibold text-gray-900 dark:text-white">{{ $permit->licence_plate }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                                {{ $permit->valid_from->timezone('Europe/London')->format('d M Y - H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                                {{ $permit->valid_to->timezone('Europe/London')->format('d M Y - H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $now = now();

                                    if ($permit->valid_to < $now) {
                                        $status = 'Expired';
                                        $badgeClasses = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300';
                                    } elseif ($permit->valid_from > $now) {
                                        $status = 'Upcoming';
                                        $badgeClasses = 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300';
                                    } else {
                                        $status = 'Active';
                                        $badgeClasses = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300';
                                    }
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $badgeClasses }}">
                                    {{ $status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                No permits found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                {{ $permits->links() }}
            </div>
        </div>
    </div>
@endsection