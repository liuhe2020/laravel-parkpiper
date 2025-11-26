@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto py-16">
        <x-logo />

            <!-- Check Status Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Check permit status</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Verify permit coverage</p>
                </div>

                <div class="p-6">
                    <form action="{{ route('permits.check') }}" method="GET" class="space-y-5">
                        <div>
                            <label for="licence_plate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">License plate</label>
                            <input 
                                type="text" 
                                name="licence_plate" 
                                id="licence_plate" 
                                required 
                                maxlength="8"
                                pattern="[A-Za-z0-9 ]{1,8}"
                                title="Up to 8 characters: letters, numbers, spaces only"
                                class="w-full px-3 py-2.5 dark:scheme-dark bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-base uppercase font-mono tracking-wide focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white"
                                placeholder="AB25CDE"
                                value="{{ old('licence_plate', request('licence_plate')) }}"
                            >
                        </div>

                        <!-- Check Mode Options -->
                         @php
                            $selectedMode = request()->filled('stay_start') && request()->filled('stay_end')
                                ? 'duration'
                                : (request()->filled('check_date') ? 'specific' : 'current');

                            $modeCards = [
                                'current' => [
                                    'title' => 'Current time',
                                    'hint' => 'Verify if vehicle is covered right now',
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                                ],
                                'specific' => [
                                    'title' => 'Specific time',
                                    'hint' => 'Check coverage at specific time',
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
                                ],
                                'duration' => [
                                    'title' => 'Duration check',
                                    'hint' => 'Verify coverage for entire stay',
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>',
                                ],
                            ];
                        @endphp
                        <div class="space-y-5">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Check mode</label>

                            <div class="grid gap-3 md:grid-cols-3" id="checkModeGroup">
                                @foreach($modeCards as $mode => $card)
                                    <label for="{{ $mode }}"  class="block cursor-pointer" data-mode-card="{{ $mode }}">
                                        <input 
                                            type="radio" 
                                            name="check_mode" 
                                            id="{{ $mode }}" 
                                            value="{{ $mode }}" 
                                            class="sr-only peer"
                                            @checked(request('check_mode', $selectedMode) === $mode)
                                        />
                                        <div class="flex items-start gap-3 p-3 rounded-lg border transition
                                            peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:dark:bg-indigo-900/30
                                            border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                                            <div class="w-5 h-5 bg-indigo-100 dark:bg-indigo-900/50 rounded flex items-center justify-center mt-0.5 shrink-0">
                                                <svg class="w-3 h-3 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    {!! $card['icon'] !!}
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $card['title'] }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $card['hint'] }}</p>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <div id="specificFields" class="{{ $selectedMode !== 'specific' ? 'hidden' : '' }} space-y-2">
                                <label for="check_date" class="block text-sm font-medium text-gray-900 dark:text-white">Date time</label>
                                <input 
                                    type="datetime-local" 
                                    name="check_date" 
                                    id="check_date" 
                                    class="w-full px-3 py-2.5 dark:scheme-dark bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors text-gray-900 dark:text-white"
                                    value="{{ old('check_date', request('check_date')) }}"
                                >
                            </div>

                            <div id="durationFields" class="{{ $selectedMode !== 'duration' ? 'hidden' : '' }} space-y-5">
                                <div class="space-y-2">
                                    <label for="stay_start" class="block text-sm font-medium text-gray-900 dark:text-white">Start date time</label>
                                    <input 
                                        type="datetime-local" 
                                        name="stay_start"
                                        id="stay_start"
                                        class="w-full px-3 py-2.5 dark:scheme-dark bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors text-gray-900 dark:text-white"
                                        value="{{ old('stay_start', request('stay_start')) }}"
                                    >
                                </div>
                                <div class="space-y-2">
                                    <label for="stay_end" class="block text-sm font-medium text-gray-900 dark:text-white">End date time</label>
                                    <input 
                                        type="datetime-local" 
                                        name="stay_end"
                                        id="stay_end"
                                        class="w-full dark:scheme-dark px-3 py-2.5 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors text-gray-900 dark:text-white"
                                        value="{{ old('stay_end', request('stay_end')) }}"
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button 
                                type="submit" 
                                class="flex-1 bg-indigo-600 dark:bg-indigo-500 text-white py-2.5 px-4 rounded-lg font-medium text-sm hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors"
                            >
                                Check status
                            </button>
                            <a
                                href="{{ route('home') }}"
                                type="button"
                                class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                            >
                                Reset
                            </a>
                        </div>
                    </form>

                    @if($errors->check->any())
                        <div class="mt-5 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1">
                                    @foreach($errors->check->all() as $error)
                                        <p class="text-sm text-red-800 dark:text-red-300">{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(isset($result))
                        <!-- Status Badge -->
                        <div class="mt-5 p-5 rounded-lg border-2
                            @if($result === 'covered') bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800
                            @elseif($result === 'partially_covered') bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800
                            @else bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 @endif">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0">
                                    @if($result === 'covered')
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    @elseif($result === 'partially_covered')
                                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-base
                                        @if($result === 'covered') text-green-900 dark:text-green-300
                                        @elseif($result === 'partially_covered') text-yellow-900 dark:text-yellow-300
                                        @else text-red-900 dark:text-red-300 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $result)) }}
                                    </p>
                                    <p class="text-sm mt-1
                                        @if($result === 'covered') text-green-700 dark:text-green-400
                                        @elseif($result === 'partially_covered') text-yellow-700 dark:text-yellow-400
                                        @else text-red-700 dark:text-red-400 @endif">
                                        @if($result === 'covered')
                                            Permit fully covers the requested period.
                                        @elseif($result === 'partially_covered')
                                            Permit only covers part of the requested period.
                                        @else
                                            No permit matches the requested period.
                                        @endif
                                    </p>
                                    @if($result === 'partially_covered' && isset($coverage['uncovered_periods']) && count($coverage['uncovered_periods']))
                                        <div class="mt-3 text-sm space-y-1 text-yellow-800 dark:text-yellow-200">
                                            <p class="font-semibold">Uncovered windows</p>
                                            @foreach($coverage['uncovered_periods'] as $gap)
                                            @php
                                                $start = \Carbon\Carbon::parse($gap['start'])->timezone('Europe/London');
                                                $end = \Carbon\Carbon::parse($gap['end'])->timezone('Europe/London');

                                                $totalMinutes = $start->diffInMinutes($end);
                                                $days = intdiv($totalMinutes, 1440);
                                                $hours = intdiv($totalMinutes % 1440, 60);
                                                $minutes = $totalMinutes % 60;

                                                $duration = collect([
                                                    $days ? $days.'d' : null,
                                                    $hours ? $hours.'h' : null,
                                                    $minutes ? $minutes.'m' : null,
                                                ])->filter()->implode(' ');
                                            @endphp
                                            <div class="pl-2 border-l-2 border-yellow-400 dark:border-yellow-600">
                                                {{ $start->format('d M Y - H:i') }} →
                                                {{ $end->format('d M Y - H:i') }}
                                                <div class="">
                                                    Duration: {{ $duration ?: '0m' }}
                                                </div>
                                            </div>
                                        @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Related Permits list-->
                        @if(isset($relatedPermits) && $relatedPermits->isNotEmpty())
                            <div class="mt-4 space-y-3">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
                                    Related permits ({{ $relatedPermits->count() }})
                                </h4>
                                @foreach($relatedPermits as $permitItem)
                                    <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="font-mono text-xs text-gray-600 dark:text-gray-400">Permit ID #{{ $permitItem->id }}</span>
                                            <span class="font-mono font-semibold text-sm text-gray-900 dark:text-white">{{ $permitItem->licence_plate }}</span>
                                        </div>
                                        <div class="space-y-1 text-xs">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">From</span>
                                                <span class="text-gray-900 dark:text-white">
                                                    {{ $permitItem->valid_from->timezone('Europe/London')->format('d M Y - H:i') }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">To</span>
                                                <span class="text-gray-900 dark:text-white">
                                                    {{ $permitItem->valid_to->timezone('Europe/London')->format('d M Y - H:i') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- All Permit list -->
                        @if(isset($allPermits) && $allPermits->isNotEmpty())
                            <div class="mt-4 space-y-3">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
                                    All permits ({{ $allPermits->count() }})
                                </h4>
                                @foreach($allPermits as $permitItem)
                                    <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="font-mono text-xs text-gray-600 dark:text-gray-400">Permit ID #{{ $permitItem->id }}</span>
                                            <span class="font-mono font-semibold text-sm text-gray-900 dark:text-white">{{ $permitItem->licence_plate }}</span>
                                        </div>
                                        <div class="space-y-1 text-xs">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">From</span>
                                                <span class="text-gray-900 dark:text-white">
                                                    {{ $permitItem->valid_from->timezone('Europe/London')->format('d M Y - H:i') }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">To</span>
                                                <span class="text-gray-900 dark:text-white">
                                                    {{ $permitItem->valid_to->timezone('Europe/London')->format('d M Y - H:i') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            </div>
    </div>
@endsection