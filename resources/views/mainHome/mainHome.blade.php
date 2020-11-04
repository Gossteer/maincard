<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Библиотека') }}
        </h2>
    </x-slot>

    @livewire('main-home')


    @push('style')
        <link rel="stylesheet" href="{{ asset('css/cards.css') }}">
        <script src="{{ asset('js/card.js') }}" defer></script>
    @endpush
</x-app-layout>

