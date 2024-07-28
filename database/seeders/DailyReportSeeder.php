<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\DailyReport;
use App\Models\User;
use App\Models\CompanyDocument;
use App\Models\Project;
use App\Models\UnselectedFile;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class DailyReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $faker = Faker::create();

        $UserTypes = ['Sales', 'Marketing', 'HR', 'Finance', 'Manager'];
        foreach ($UserTypes as $ut) {
            User::create([
                'name' => "{$ut} Manager",
                'email' => strtolower($ut) . 'A@example.com',
                'password' => Hash::make('password'),
                'type' => $ut,
           

            ]);
        }
        // Create departments
        $departments = ['Baghdad', 'Basra', 'Erbil', 'Karbala'];
        foreach ($departments as $dept) {
            Department::create([
                'name' => $dept,
                'user_id' => User::where('type', 'Manager')->first()->id,
                'info' => $faker->text(),
            ]);
        }

        // Create sample files
        Storage::makeDirectory('public/DailyReport');
        Storage::makeDirectory('public/CompanyDocument');
        Storage::makeDirectory('public/UnselectedFile');
        for ($i = 1; $i <= 5; $i++) {
            Storage::put("public/DailyReport/sample{$i}.txt", "Sample file content {$i}");
            Storage::put("public/CompanyDocument/sample{$i}.txt", "Sample file content {$i}");
            Storage::put("public/UnselectedFile/sample{$i}.txt", "Sample file content {$i}");
        }

        Project::create([
            'name' => 'test',
            'info' => $faker->text(),
            'department_id' => Department::inRandomOrder()->first()->id,
        ]);
        // Create daily reports
        for ($i = 0; $i < 20; $i++) {
            DailyReport::create([
                'date' => $faker->date(),
                'type' => $faker->word(),
                'number' => $faker->randomNumber(),
                'department_id' => Department::inRandomOrder()->first()->id,
                'project_id' => Project::first()->id,
                'file_path' => "DailyReport/sample" . rand(1, 5) . ".txt",
            ]);
            CompanyDocument::create([
                'date' => $faker->date(),
                'type' => $faker->word(),
                'number' => $faker->randomNumber(),
                'department_id' => Department::inRandomOrder()->first()->id,
                'file_path' => "CompanyDocument/sample" . rand(1, 5) . ".txt",
            ]);
            UnselectedFile::create([
                'date' => $faker->date(),
                'type' => $faker->word(),
                'number' => $faker->randomNumber(),
                'department_id' => Department::inRandomOrder()->first()->id,
                'file_path' => "UnselectedFile/sample" . rand(1, 5) . ".txt",
            ]);
        }
    }

}
