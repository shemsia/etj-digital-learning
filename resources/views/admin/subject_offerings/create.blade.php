
<!DOCTYPE html>
<html>
<head>
    <title>Create Subject Offering</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        select,
        input {
            padding: 8px;
            width: 300px;
        }

        .module-row {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            width: 350px;
        }

        .module-row input {
            width: 150px;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }

        #total-container {
            margin-top: 20px;
            font-size: 18px;
        }

        #submit-button {
            padding: 10px 20px;
            cursor: pointer;
        }

        #submit-button:disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }
    </style>
</head>

<body>

<h1>Create Subject Offering</h1>

@if ($errors->any())
    <div class="error">
        <strong>Please fix the following:</strong>

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.subject_offerings.store') }}">
    @csrf

    {{-- Subject --}}
    <div class="form-group">
        <label for="subject_id">Subject</label>

        <select name="subject_id" id="subject_id" required>
            <option value="">-- Select Subject --</option>

            @foreach($subjects as $subject)
                <option
                    value="{{ $subject->id }}"
                    {{ old('subject_id') == $subject->id ? 'selected' : '' }}
                >
                    {{ $subject->name }} ({{ $subject->code }})
                </option>
            @endforeach
        </select>
    </div>

    {{-- Semester --}}
    <div class="form-group">
        <label for="semester_id">Semester</label>

        <select name="semester_id" id="semester_id" required>
            <option value="">-- Select Semester --</option>

            @foreach($semesters as $semester)
                <option
                    value="{{ $semester->id }}"
                    {{ old('semester_id') == $semester->id ? 'selected' : '' }}
                >
                    {{ $semester->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Grade --}}
    <div class="form-group">
        <label for="grade_level">Grade Level</label>

        <input
            type="number"
            name="grade_level"
            id="grade_level"
            min="1"
            max="12"
            value="{{ old('grade_level') }}"
            required
        >
    </div>

    {{-- Number of Modules --}}
    <div class="form-group">
        <label for="module_count">Number of Modules</label>

        <input
            type="number"
            id="module_count"
            min="1"
            max="20"
            value="{{ old('modules') ? count(old('modules')) : '' }}"
            required
        >
    </div>

    {{-- Dynamic Modules --}}
    <div id="modules-container"></div>

    {{-- Total --}}
    <div id="total-container" style="display:none;">
        <strong>
            Total Weight:
            <span id="total-weight">0.00</span>%
        </strong>
    </div>

    <br>

    <button type="submit" id="submit-button" disabled>
        Create Subject Offering
    </button>

</form>

<script>

    const moduleCount = document.getElementById('module_count');
    const modulesContainer = document.getElementById('modules-container');
    const totalContainer = document.getElementById('total-container');
    const totalWeight = document.getElementById('total-weight');
    const submitButton = document.getElementById('submit-button');

    const oldModules = @json(old('modules', []));

    function createModules(count) {

        modulesContainer.innerHTML = '';

        totalContainer.style.display = 'none';

        submitButton.disabled = true;

        if (!count || count < 1 || count > 20) {
            return;
        }

        totalContainer.style.display = 'block';

        for (let i = 1; i <= count; i++) {

            const oldWeight =
                oldModules[i - 1]?.weight ?? '';

            modulesContainer.innerHTML += `
                <div class="module-row">

                    <h3>Module ${i}</h3>

                    <label>
                        Weight (%)
                    </label>

                    <input
                        type="number"
                        name="modules[${i - 1}][weight]"
                        class="module-weight"
                        min="0"
                        max="100"
                        step="0.01"
                        value="${oldWeight}"
                        required
                    >

                </div>
            `;
        }

        document
            .querySelectorAll('.module-weight')
            .forEach(input => {
                input.addEventListener('input', calculateTotal);
            });

        calculateTotal();
    }

    moduleCount.addEventListener('input', function () {

        const count = parseInt(this.value);

        createModules(count);

    });

    function calculateTotal() {

        let total = 0;

        document
            .querySelectorAll('.module-weight')
            .forEach(input => {

                total += parseFloat(input.value) || 0;

            });

        totalWeight.textContent = total.toFixed(2);

        submitButton.disabled =
            Math.abs(total - 100) > 0.001;
    }

    // Restore modules after validation error
    if (oldModules.length > 0) {

        createModules(oldModules.length);

    }

</script>

</body>
</html>

