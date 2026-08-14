<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Section
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">

                <form method="POST" action="{{ route('admin.classes.store') }}">
                    @csrf

                    <!-- Grade -->
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">
                            Grade
                        </label>

                        <select
                            name="grade"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            required
                        >
                            <option value="">Select Grade</option>
                            <option value="9">Grade 9</option>
                            <option value="10">Grade 10</option>
                            <option value="11">Grade 11</option>
                            <option value="12">Grade 12</option>
                        </select>
                    </div>

                    <!-- Section -->
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">
                            Section
                        </label>

                        <input
                            type="text"
                            name="name"
                            placeholder="Example: A"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            required
                        >
                    </div>

                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded"
                    >
                        Create Section
                    </button>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>