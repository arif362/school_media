<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

class CoursesSeed extends AbstractSeed
{
    public function run(): void
    {
        $currentYear = (int)date('Y');
        $academicYear = $currentYear . '-' . ($currentYear + 1);
        $now = date('Y-m-d H:i:s');

        // Seed Cambridge Subjects
        $subjectsData = [
            // Core Subjects
            ['name' => 'English Language', 'code' => 'ENG-101', 'description' => 'Core English Language curriculum for reading, writing, and communication skills.', 'category' => 'Core', 'credit_hours' => 4, 'is_active' => true],
            ['name' => 'Mathematics', 'code' => 'MATH-101', 'description' => 'Core Mathematics curriculum covering algebra, geometry, statistics, and calculus foundations.', 'category' => 'Core', 'credit_hours' => 4, 'is_active' => true],
            ['name' => 'Combined Science', 'code' => 'SCI-101', 'description' => 'Integrated science covering Physics, Chemistry, and Biology fundamentals.', 'category' => 'Core', 'credit_hours' => 5, 'is_active' => true],
            ['name' => 'Physics', 'code' => 'PHY-101', 'description' => 'Study of matter, energy, and their interactions. Cambridge IGCSE Physics syllabus.', 'category' => 'Core', 'credit_hours' => 3, 'is_active' => true],
            ['name' => 'Chemistry', 'code' => 'CHEM-101', 'description' => 'Study of substances, their properties, and reactions. Cambridge IGCSE Chemistry syllabus.', 'category' => 'Core', 'credit_hours' => 3, 'is_active' => true],
            ['name' => 'Biology', 'code' => 'BIO-101', 'description' => 'Study of living organisms and life processes. Cambridge IGCSE Biology syllabus.', 'category' => 'Core', 'credit_hours' => 3, 'is_active' => true],

            // Elective Subjects
            ['name' => 'Computer Science', 'code' => 'CS-101', 'description' => 'Introduction to computing, programming, and computational thinking.', 'category' => 'Elective', 'credit_hours' => 3, 'is_active' => true],
            ['name' => 'History', 'code' => 'HIST-101', 'description' => 'Study of historical events, civilizations, and their impact on modern society.', 'category' => 'Elective', 'credit_hours' => 2, 'is_active' => true],
            ['name' => 'Geography', 'code' => 'GEO-101', 'description' => 'Study of physical and human geography, environmental systems, and global issues.', 'category' => 'Elective', 'credit_hours' => 2, 'is_active' => true],
            ['name' => 'Economics', 'code' => 'ECON-101', 'description' => 'Introduction to microeconomics and macroeconomics principles.', 'category' => 'Elective', 'credit_hours' => 2, 'is_active' => true],
            ['name' => 'Art & Design', 'code' => 'ART-101', 'description' => 'Creative arts including drawing, painting, sculpture, and design principles.', 'category' => 'Elective', 'credit_hours' => 2, 'is_active' => true],
            ['name' => 'Music', 'code' => 'MUS-101', 'description' => 'Music theory, performance, and appreciation across various genres.', 'category' => 'Elective', 'credit_hours' => 2, 'is_active' => true],
            ['name' => 'French', 'code' => 'FRE-101', 'description' => 'French language learning: speaking, reading, writing, and listening skills.', 'category' => 'Elective', 'credit_hours' => 2, 'is_active' => true],
            ['name' => 'Arabic', 'code' => 'ARB-101', 'description' => 'Arabic language learning: speaking, reading, writing, and listening skills.', 'category' => 'Elective', 'credit_hours' => 2, 'is_active' => true],

            // Co-curricular
            ['name' => 'Physical Education', 'code' => 'PE-101', 'description' => 'Physical fitness, sports, and health education.', 'category' => 'Co-curricular', 'credit_hours' => 1, 'is_active' => true],
            ['name' => 'Islamic Studies', 'code' => 'ISL-101', 'description' => 'Study of Islamic principles, history, and ethics.', 'category' => 'Co-curricular', 'credit_hours' => 1, 'is_active' => true],
        ];

        foreach ($subjectsData as &$subject) {
            $subject['created'] = $now;
            $subject['modified'] = $now;
        }

        $subjectsTable = $this->table('subjects');
        $subjectsTable->insert($subjectsData)->save();

        // Get subject IDs for courses
        $connection = $this->getAdapter()->getConnection();
        $subjects = [];
        $result = $connection->execute('SELECT id, code FROM subjects');
        while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            $subjects[$row['code']] = $row['id'];
        }

        // Get class IDs
        $classes = [];
        $result = $connection->execute('SELECT id, name, grade_level FROM classes WHERE is_active = 1');
        while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            $classes[$row['grade_level']] = $row['id'];
        }

        // Get teacher IDs
        $teachers = [];
        $result = $connection->execute("SELECT id, name FROM users WHERE role IN ('teacher', 'admin') LIMIT 5");
        while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            $teachers[] = $row['id'];
        }

        // Create courses for each class
        $coursesData = [];
        $courseSubjects = ['ENG-101', 'MATH-101', 'PHY-101', 'CHEM-101', 'BIO-101', 'CS-101'];

        foreach ($classes as $gradeLevel => $classId) {
            foreach ($courseSubjects as $index => $subjectCode) {
                if (!isset($subjects[$subjectCode])) {
                    continue;
                }

                $teacherId = !empty($teachers) ? $teachers[$index % count($teachers)] : null;

                $coursesData[] = [
                    'subject_id' => $subjects[$subjectCode],
                    'class_id' => $classId,
                    'teacher_id' => $teacherId,
                    'name' => null,
                    'academic_year' => $academicYear,
                    'term' => 'Term 1',
                    'schedule' => null,
                    'syllabus' => "Course syllabus for {$subjectCode} - Grade {$gradeLevel}\n\n1. Introduction to the subject\n2. Core concepts and theories\n3. Practical applications\n4. Assessment and evaluation",
                    'max_students' => 35,
                    'is_active' => true,
                    'created' => $now,
                    'modified' => $now,
                ];
            }
        }

        if (!empty($coursesData)) {
            $coursesTable = $this->table('courses');
            $coursesTable->insert($coursesData)->save();
        }

        // Get student IDs
        $students = [];
        $result = $connection->execute("SELECT id FROM users WHERE role = 'student' LIMIT 20");
        while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            $students[] = $row['id'];
        }

        // Get course IDs
        $courses = [];
        $result = $connection->execute('SELECT id, class_id FROM courses LIMIT 10');
        while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            $courses[] = $row;
        }

        // Create student course enrollments
        $enrollmentsData = [];
        $grades = ['A*', 'A', 'B', 'C', 'D', 'E', null];

        foreach ($students as $studentIndex => $studentId) {
            // Enroll each student in 3-4 courses
            $studentCourses = array_slice($courses, 0, min(4, count($courses)));

            foreach ($studentCourses as $course) {
                $grade = $grades[array_rand($grades)];
                $marks = $grade ? rand(40, 100) : null;

                $enrollmentsData[] = [
                    'student_id' => $studentId,
                    'course_id' => $course['id'],
                    'enrolled_date' => date('Y-m-d', strtotime('-' . rand(1, 60) . ' days')),
                    'status' => 'enrolled',
                    'grade' => $grade,
                    'marks' => $marks,
                    'remarks' => $grade ? 'Good progress' : null,
                    'created' => $now,
                    'modified' => $now,
                ];
            }
        }

        if (!empty($enrollmentsData)) {
            $studentCoursesTable = $this->table('student_courses');
            $studentCoursesTable->insert($enrollmentsData)->save();
        }

        // Create teacher-subject specializations
        $teacherSubjectsData = [];
        $subjectIds = array_values($subjects);

        foreach ($teachers as $index => $teacherId) {
            // Assign 2-3 subjects to each teacher
            $teacherSubjectIds = array_slice($subjectIds, ($index * 2) % count($subjectIds), 3);

            foreach ($teacherSubjectIds as $subjectIndex => $subjectId) {
                $teacherSubjectsData[] = [
                    'teacher_id' => $teacherId,
                    'subject_id' => $subjectId,
                    'is_primary' => $subjectIndex === 0,
                    'created' => $now,
                    'modified' => $now,
                ];
            }
        }

        if (!empty($teacherSubjectsData)) {
            $teacherSubjectsTable = $this->table('teacher_subjects');
            $teacherSubjectsTable->insert($teacherSubjectsData)->save();
        }

        // Create some sample course materials
        $materialsData = [];
        $courseIds = array_column($courses, 'id');
        $materialTypes = ['document', 'video', 'link', 'assignment', 'notes'];

        foreach (array_slice($courseIds, 0, 3) as $courseId) {
            $materialsData[] = [
                'course_id' => $courseId,
                'title' => 'Course Introduction',
                'description' => 'Welcome to the course. This document contains the course overview and expectations.',
                'type' => 'document',
                'file_path' => null,
                'external_url' => null,
                'uploaded_by' => $teachers[0] ?? null,
                'is_visible' => true,
                'order_num' => 1,
                'created' => $now,
                'modified' => $now,
            ];

            $materialsData[] = [
                'course_id' => $courseId,
                'title' => 'Week 1: Fundamentals',
                'description' => 'Introduction to core concepts and foundational knowledge.',
                'type' => 'notes',
                'file_path' => null,
                'external_url' => null,
                'uploaded_by' => $teachers[0] ?? null,
                'is_visible' => true,
                'order_num' => 2,
                'created' => $now,
                'modified' => $now,
            ];

            $materialsData[] = [
                'course_id' => $courseId,
                'title' => 'Assignment 1',
                'description' => 'First assessment covering topics from weeks 1-3.',
                'type' => 'assignment',
                'file_path' => null,
                'external_url' => null,
                'uploaded_by' => $teachers[0] ?? null,
                'is_visible' => true,
                'order_num' => 3,
                'created' => $now,
                'modified' => $now,
            ];
        }

        if (!empty($materialsData)) {
            $courseMaterialsTable = $this->table('course_materials');
            $courseMaterialsTable->insert($materialsData)->save();
        }
    }
}
