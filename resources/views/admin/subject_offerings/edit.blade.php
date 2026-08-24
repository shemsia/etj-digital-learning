<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Subject Offering
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    <strong>Please fix the following:</strong>

                    <ul class="mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <form
                        action="{{ route('admin.subject_offerings.update', $offering->id) }}"
                        method="POST"
                    >

                        @csrf
                        @method('PUT')

                        {{-- Subject --}}
                        <div class="mb-5">
                            <label class="block font-medium text-gray-700 mb-2">
                                Subject
                            </label>

                            <select
                                name="subject_id"
                                class="w-full border-gray-300 rounded-md shadow-sm"
                                required
                            >
                                @foreach ($subjects as $subject)
                                    <option
                                        value="{{ $subject->id }}"
                                        {{ $offering->subject_id == $subject->id ? 'selected' : '' }}
                                    >
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Semester --}}
                        <div class="mb-5">
                            <label class="block font-medium text-gray-700 mb-2">
                                Semester
                            </label>

                            <select
                                name="semester_id"
                                class="w-full border-gray-300 rounded-md shadow-sm"
                                required
                            >
                                @foreach ($semesters as $semester)
                                    <option
                                        value="{{ $semester->id }}"
                                        {{ $offering->semester_id == $semester->id ? 'selected' : '' }}
                                    >
                                        {{ $semester->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Grade --}}
                        <div class="mb-5">
                            <label class="block font-medium text-gray-700 mb-2">
                                Grade Level
                            </label>

                            <input
                                type="number"
                                name="grade_level"
                                min="1"
                                max="12"
                                value="{{ old('grade_level', $offering->grade_level) }}"
                                class="w-full border-gray-300 rounded-md shadow-sm"
                                required
                            >
                        </div>

                        {{-- Number of Modules --}}
                        <div class="mb-6">
                            <label class="block font-medium text-gray-700 mb-2">
                                Number of Modules
                            </label>

                            <input
                                type="number"
                                id="module_count"
                                min="1"
                                max="20"
                                value="{{ $offering->modules->count() }}"
                                class="w-full border-gray-300 rounded-md shadow-sm"
                                required
                            >
                        </div>

                        {{-- Modules --}}
                        <div class="mb-6">

                            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                Module Weights
                            </h3>

                            <div id="modules-container"></div>

                            <div class="mt-4 p-3 bg-gray-100 rounded">
                                <strong>
                                    Total Weight:
                                    <span id="total-weight">0</span>%
                                </strong>
                            </div>

                            <div
                                id="weight-error"
                                class="mt-3 p-3 bg-red-100 text-red-700 rounded hidden"
                            >
                                Module weights must total exactly 100%.
                            </div>

                        </div>

                        {{-- Buttons --}}
                        <div class="flex gap-3">

                            <button
                                type="submit"
                                id="update-button"
                                class="px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                            >
                                Update Subject Offering
                            </button>

                            <a
                                href="{{ route('admin.subject_offerings.index') }}"
                                class="px-5 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600"
                            >
                                Cancel
                            </a>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>


    <script>
        const moduleCountInput = document.getElementById('module_count');
        const modulesContainer = document.getElementById('modules-container');
        const totalWeight = document.getElementById('total-weight');
        const weightError = document.getElementById('weight-error');
        const updateButton = document.getElementById('update-button');

        const existingModules = @json(
            $offering->modules->map(function ($module) {
                return [
                    'id' => $module->id,
                    'name' => $module->name,
                    'weight' => $module->weight
                ];
            })->values()
        );

        function renderModules() {

            let count = parseInt(moduleCountInput.value);

            if (isNaN(count) || count < 1) {
                count = 1;
            }

            if (count > 20) {
                count = 20;
                moduleCountInput.value = 20;
            }

            modulesContainer.innerHTML = '';

            for (let i = 0; i < count; i++) {

                let weight = '';

                if (existingModules[i]) {
                    weight = existingModules[i].weight;
                }

                const moduleDiv = document.createElement('div');

                moduleDiv.className = 'mb-4';

                moduleDiv.innerHTML = `
                    <label class="block font-medium text-gray-700 mb-2">
                        Module ${i + 1}
                    </label>

                    <div class="flex items-center gap-3">

                        <input
                            type="number"
                            name="modules[${i}][weight]"
                            min="0"
                            max="100"
                            step="0.01"
                            value="${weight}"
                            class="flex-1 border-gray-300 rounded-md shadow-sm module-weight"
                            required
                        >

                        <span>%</span>

                    </div>
                `;

                modulesContainer.appendChild(moduleDiv);
            }

            attachWeightListeners();
            calculateTotal();
        }


        function attachWeightListeners() {

            const inputs = document.querySelectorAll('.module-weight');

            inputs.forEach(input => {

                input.addEventListener('input', calculateTotal);

            });
        }


        function calculateTotal() {

            const inputs = document.querySelectorAll('.module-weight');

            let total = 0;

            inputs.forEach(input => {

                total += parseFloat(input.value) || 0;

            });

            totalWeight.textContent = total.toFixed(2);

            if (Math.abs(total - 100) < 0.01) {

                weightError.classList.add('hidden');
                updateButton.disabled = false;
                updateButton.classList.remove('opacity-50');

            } else {

                weightError.classList.remove('hidden');
                updateButton.disabled = true;
                updateButton.classList.add('opacity-50');

            }
        }


        moduleCountInput.addEventListener('input', renderModules);

        renderModules();
    </script>

</x-app-layout>