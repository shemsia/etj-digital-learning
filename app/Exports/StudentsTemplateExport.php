<?php

namespace App\Exports;

use App\Models\ClassModel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class StudentsTemplateExport implements FromArray, WithHeadings, WithEvents
{
    public function headings(): array
    {
        return [
            'Student ID',
            'Student Name',
            'Grade',
            'Section',
            'Gender',
        ];
    }

    public function array(): array
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // Grade dropdown
                for ($row = 2; $row <= 501; $row++) {

                    $validation = new DataValidation();

                    $validation->setType(
                        DataValidation::TYPE_LIST
                    );

                    $validation->setErrorStyle(
                        DataValidation::STYLE_STOP
                    );

                    $validation->setAllowBlank(true);

                    $validation->setShowDropDown(true);

                    $validation->setShowInputMessage(true);

                    $validation->setShowErrorMessage(true);

                    $validation->setErrorTitle(
                        'Invalid Grade'
                    );

                    $validation->setError(
                        'Please select Grade 9, 10, 11, or 12.'
                    );

                    $validation->setFormula1(
                        '"9,10,11,12"'
                    );

                    $sheet->getCell("C{$row}")
                        ->setDataValidation($validation);
                }

                // Gender dropdown
                for ($row = 2; $row <= 501; $row++) {

                    $validation = new DataValidation();

                    $validation->setType(
                        DataValidation::TYPE_LIST
                    );

                    $validation->setErrorStyle(
                        DataValidation::STYLE_STOP
                    );

                    $validation->setAllowBlank(true);

                    $validation->setShowDropDown(true);

                    $validation->setShowInputMessage(true);

                    $validation->setShowErrorMessage(true);

                    $validation->setErrorTitle(
                        'Invalid Gender'
                    );

                    $validation->setError(
                        'Please select Male or Female.'
                    );

                    $validation->setFormula1(
                        '"Male,Female"'
                    );

                    $sheet->getCell("E{$row}")
                        ->setDataValidation($validation);
                }

                // Get sections from database
                $classes = ClassModel::orderBy('grade')
                    ->orderBy('name')
                    ->get()
                    ->groupBy('grade');

                // Put sections into helper columns
                $gradeColumns = [
                    9 => 'H',
                    10 => 'I',
                    11 => 'J',
                    12 => 'K',
                ];

                foreach ($gradeColumns as $grade => $column) {

                    $sheet->setCellValue(
                        "{$column}1",
                        "Grade {$grade}"
                    );

                    $sections = $classes->get(
                        $grade,
                        collect()
                    );

                    $excelRow = 2;

                    foreach ($sections as $class) {

                        $sheet->setCellValue(
                            "{$column}{$excelRow}",
                            $class->name
                        );
                        $excelRow++;
                    }
                }
// Section dropdown
$sectionOptions = $classes
    ->map(function ($sections, $grade) {
        return $sections->map(function ($class) use ($grade) {
            return $grade . '-' . $class->name;
        });
    })
    ->flatten()
    ->values()
    ->toArray();

if (!empty($sectionOptions)) {

    $sectionFormula = '"' . implode(',', $sectionOptions) . '"';

    for ($row = 2; $row <= 501; $row++) {

        $validation = new DataValidation();

        $validation->setType(
            DataValidation::TYPE_LIST
        );

        $validation->setErrorStyle(
            DataValidation::STYLE_STOP
        );

        $validation->setAllowBlank(true);

        $validation->setShowDropDown(true);

        $validation->setShowErrorMessage(true);

        $validation->setErrorTitle(
            'Invalid Section'
        );

        $validation->setError(
            'Please select a section from the list.'
        );

        $validation->setFormula1($sectionFormula);

        $sheet->getCell("D{$row}")
            ->setDataValidation($validation);
    }
}
                // Hide helper columns
                foreach (['H', 'I', 'J', 'K'] as $column) {
                    $sheet->getColumnDimension($column)
                        ->setVisible(false);
                }

                // Column widths
                $sheet->getColumnDimension('A')->setWidth(18);
                $sheet->getColumnDimension('B')->setWidth(30);
                $sheet->getColumnDimension('C')->setWidth(12);
                $sheet->getColumnDimension('D')->setWidth(15);
                $sheet->getColumnDimension('E')->setWidth(15);
            },
        ];
    }
}