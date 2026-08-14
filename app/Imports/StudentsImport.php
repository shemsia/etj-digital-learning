<?php

namespace App\Imports;

use App\Models\ClassModel;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
class StudentsImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsEmptyRows
{
    use RemembersRowNumber;
    public int $imported = 0;
    public array $skipped = [];

    public function model(array $row)
    {
        $sectionParts = explode('-', $row['section'], 2);

        $sectionGrade = $sectionParts[0] ?? null;
        $sectionName = $sectionParts[1] ?? null;

        // Check whether grade matches the section
        if ($sectionGrade != $row['grade']) {
            $this->skipped[] = [
                'row' => $this->getRowNumber(),
                'reason' => "Grade {$row['grade']} does not match Section {$row['section']}.",
            ];

            return null;
        }

        // Find the class
        $class = ClassModel::where('grade', $row['grade'])
            ->where('name', $sectionName)
            ->first();

        if (!$class) {
            $this->skipped[] = [
                'row' => $this->getRowNumber(),
                'reason' => "Section {$row['section']} does not exist for Grade {$row['grade']}.",
            ];

            return null;
        }

        // Check duplicate student ID
        if (Student::where('student_id', $row['student_id'])->exists()) {
            $this->skipped[] = [
                'row' => $this->getRowNumber(),
                'reason' => "Student ID {$row['student_id']} already exists.",
            ];

            return null;
        }

        $email = strtolower(
            Str::slug($row['student_name'], '') .
            $row['student_id'] .
            '@school.local'
        );

        $user = User::create([
            'name' => $row['student_name'],
            'email' => $email,
            'password' => Hash::make('Student@123'),
            'role' => 'student',
        ]);

        $this->imported++;

        return new Student([
            'user_id' => $user->id,
            'student_id' => $row['student_id'],
            'class_id' => $class->id,
            'gender' => $row['gender'],
        ]);
    }

    public function rules(): array
    {
        return [
            'student_id' => [
                'required',
                'string',
                'max:50',
                'unique:students,student_id',
            ],

            'student_name' => [
                'required',
                'string',
                'max:255',
            ],

            'grade' => [
                'required',
                'in:9,10,11,12',
            ],

            'section' => [
                'required',
                'string',
                'max:50',
            ],

            'gender' => [
                'required',
                'in:Male,Female',
            ],
        ];
    }

   
}
