<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 10 courses
        $courses = \App\Models\Course::factory(10)->create();

        // For each course, create 3-8 lessons
        foreach ($courses as $course) {
            \App\Models\Lesson::factory(rand(3, 8))
                ->create(['course_id' => $course->id]);
        }

        // Create 20 students
        $students = \App\Models\Student::factory(20)->create();

        // Enroll students in random courses
        foreach ($students as $student) {
            $randomCourses = $courses->random(rand(1, 3));
            foreach ($randomCourses as $course) {
                \App\Models\Enrollment::create([
                    'course_id' => $course->id,
                    'student_id' => $student->id,
                ]);
            }
        }
    }
}
