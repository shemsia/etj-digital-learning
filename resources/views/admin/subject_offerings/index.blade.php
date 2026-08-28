<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Subject Offerings
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">
                            Subject Offerings
                        </h3>

                        <a href="{{ route('admin.subject_offerings.create') }}"
                           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            + Create Subject Offering
                        </a>
                    </div>

                    @if($offerings->count())

                        <div class="overflow-x-auto">
                            <table class="min-w-full border border-gray-300">

                      <thead class="bg-gray-100">
    <tr>
        <th class="border px-4 py-3 text-left">
            Subject
        </th>

        <th class="border px-4 py-3 text-left">
            Semester
        </th>

        <th class="border px-4 py-3 text-left">
            Grade
        </th>

        <th class="border px-4 py-3 text-left">
            Modules
        </th>

        <th class="border px-4 py-3 text-left">
            Teacher
        </th>

        <th class="border px-4 py-3 text-left">
            Action
        </th>
    </tr>
</thead>

                                <tbody>

                                    @foreach($offerings as $offering)

                                        <tr>

                                            <td class="border px-4 py-3">
                                                {{ $offering->subject->name }}
                                            </td>

                                            <td class="border px-4 py-3">
                                                {{ $offering->semester->name }}
                                            </td>

                                            <td class="border px-4 py-3">
                                                Grade {{ $offering->grade_level }}
                                            </td>

                                           <td class="border px-4 py-3">

    @foreach($offering->modules as $module)

        <div class="mb-1">
            <strong>
                {{ $module->name }}
            </strong>

            — {{ $module->weight }}%
        </div>

    @endforeach

    <div class="mt-2 font-semibold">
        Total:
        {{ $offering->modules->sum('weight') }}%
    </div>

</td>


<td class="border px-4 py-3">

    @if($offering->teachers->count())

        @foreach($offering->teachers as $teacher)
            <div class="mb-1">
                {{ $teacher->user->name }}
            </div>
        @endforeach

    @else

        <span class="text-gray-500">
            Not assigned
        </span>

    @endif

</td>
                                       <td class="border px-4 py-3">
    <div class="flex items-center gap-2">

        <a href="{{ route('admin.subject_offerings.edit', $offering->id) }}"
           class="inline-flex items-center justify-center w-20 h-10 bg-yellow-500 text-white rounded hover:bg-yellow-600">
            Edit
        </a>

        <form action="{{ route('admin.subject_offerings.destroy', $offering->id) }}"
              method="POST"
              class="inline-flex"
              onsubmit="return confirm('Are you sure you want to delete this subject offering?');">

            @csrf
            @method('DELETE')

            <button type="submit"
                    class="inline-flex items-center justify-center w-20 h-10 bg-red-600 text-white rounded hover:bg-red-700">
                Delete
            </button>

        </form>

    </div>
</td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>
                        </div>

                    @else

                        <p class="text-gray-600">
                            No subject offerings have been created yet.
                        </p>

                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>