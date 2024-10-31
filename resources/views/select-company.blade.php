<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Select Company') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    会社を指定してください
                    <form method="POST" action="{{ route('select-company') }}">
                        @csrf
                        <div class="mt-4">
                            <select id="company_id" class="block mt-1 w-1/2" name="company_id" :value="old('company_id')" required>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}" @selected($company->id === $current_company_id)>{{ $company->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
                                決定
                        </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
