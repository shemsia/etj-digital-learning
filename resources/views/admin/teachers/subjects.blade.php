<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Assign Subjects & Classes to {{ $teacher->user->name }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">

                <form action="{{ route('admin.teachers.subjects.update', $teacher->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- ========================= --}}
                    {{-- SUBJECTS --}}
                    {{-- ========================= --}}

                    <h3 class="text-lg font-semibold mb-4">
                        Assign Subjects
                    </h3>

                    @forelse($subjects as $subject)

                        <div class="mb-3">
                            <label class="inline-flex items-center">

                                <input
                                    type="checkbox"
                                    name="subjects[]"
                                    value="{{ $subject->id }}"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                    {{ $teacher->subjects->contains($subject->id) ? 'checked' : '' }}
                                >

                                <span class="ms-2">
                                    {{ $subject->name }} ({{ $subject->code }})
                                </span>

                            </label>
                        </div>

                    @empty

                        <p class="text-gray-500">
                            No subjects have been created yet.
                        </p>

                    @endforelse


                    {{-- ========================= --}}
                    {{-- CLASSES / SECTIONS --}}
                    {{-- ========================= --}}

                    <div class="border-t mt-8 pt-6">

                        <h3 class="text-lg font-semibold mb-4">
                            Assign Classes / Sections
                        </h3>

                        <p class="text-sm text-gray-600 mb-4">
                            Select the classes and sections this teacher will manage.
                        </p>

                        @forelse($classes as $class)

                            <div class="mb-3">
                                <label class="inline-flex items-center">

                                    <input
                                        type="checkbox"
                                        name="classes[]"
                                        value="{{ $class->id }}"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                        {{ $teacher->classes->contains($class->id) ? 'checked' : '' }}
                                    >

                                    <span class="ms-2">
                                        Grade {{ $class->grade }} - {{ $class->name }}
                                    </span>

                                </label>
                            </div>

                        @empty

                            <p class="text-gray-500">
                                No classes have been created yet.
                            </p>

                        @endforelse

                    </div>


                    {{-- ========================= --}}
                    {{-- BUTTONS --}}
                    {{-- ========================= --}}

                    <div class="mt-8">

                        <button
                            type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                        >
                            Save Assignments
                        </button>

                        <a
                            href="{{ route('admin.teachers.index') }}"
                            class="ms-2 px-4 py-2 bg-gray-500 text-white rounded-md"
                        >
                            Back
                        </a>

                    </div>

                </form>

            </div>
        </div>

    </div>
</div>