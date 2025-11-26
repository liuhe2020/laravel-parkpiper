@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto py-16">
        <x-logo />

       <!-- Issue Permit Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all">
            <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Issue permit</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Create a new parking permit</p>
            </div>

            <div class="p-6">
                <form action="{{ route('permits.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label for="licence_plate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">License plate</label>
                        <input 
                            type="text" 
                            name="licence_plate" 
                            id="licence_plate" 
                            required 
                            class="w-full dark:scheme-dark px-3 py-2.5 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-base uppercase font-mono tracking-wide focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white"
                            placeholder="AB25CDE"
                            value="{{ old('licence_plate') }}"
                        >
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="valid_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Valid from</label>
                            <input 
                                type="datetime-local" 
                                name="valid_from" 
                                id="valid_from" 
                                required 
                                class="w-full dark:scheme-dark px-3 py-2.5 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors text-gray-900 dark:text-white"
                                value="{{ old('valid_from') }}"
                            >
                        </div>
                        
                        <div>
                            <label for="valid_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Valid to</label>
                            <input 
                                type="datetime-local" 
                                name="valid_to" 
                                id="valid_to" 
                                required 
                                class="w-full px-3 dark:scheme-dark py-2.5 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors text-gray-900 dark:text-white"
                                value="{{ old('valid_to') }}"
                            >
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <button 
                            type="submit" 
                            class="w-full bg-indigo-600 dark:bg-indigo-500 text-white py-2.5 px-4 rounded-lg font-medium text-sm hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors"
                        >
                            Create permit
                        </button>
                        <a
                            href="{{ route('permits.issue') }}"
                            type="button"
                            class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                        >
                            Reset
                        </a>
                    </div>
                </form>

                @if($errors->issue->any())
                    <div class="mt-5 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div class="flex-1">
                                @foreach($errors->issue->all() as $error)
                                    <p class="text-sm text-red-800 dark:text-red-300">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mt-5 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg flex items-start gap-3">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm text-green-800 dark:text-green-300 font-medium">{{ session('success') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection