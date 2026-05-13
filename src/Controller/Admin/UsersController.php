<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Http\Exception\NotFoundException;
use Cake\Utility\Text;

class UsersController extends AdminAppController
{
    /**
     * List all users with filtering by role
     */
    public function index()
    {
        $query = $this->fetchTable('Users')->find()
            ->orderBy(['Users.created' => 'DESC']);

        // Filter by role
        $role = $this->request->getQuery('role');
        if ($role && in_array($role, ['student', 'teacher', 'admin'])) {
            $query->where(['Users.role' => $role]);
        }

        // Filter by status
        $status = $this->request->getQuery('status');
        if ($status === 'active') {
            $query->where(['Users.active' => true]);
        } elseif ($status === 'inactive') {
            $query->where(['Users.active' => false]);
        }

        // Search by name or email
        $search = $this->request->getQuery('search');
        if ($search) {
            $query->where([
                'OR' => [
                    'Users.name LIKE' => '%' . $search . '%',
                    'Users.email LIKE' => '%' . $search . '%',
                ],
            ]);
        }

        $this->paginate = ['limit' => 25];
        $users = $this->paginate($query);

        // Get statistics
        $usersTable = $this->fetchTable('Users');
        $stats = [
            'total' => $usersTable->find()->count(),
            'students' => $usersTable->find()->where(['role' => 'student'])->count(),
            'teachers' => $usersTable->find()->where(['role' => 'teacher'])->count(),
            'admins' => $usersTable->find()->where(['role' => 'admin'])->count(),
            'active' => $usersTable->find()->where(['active' => true])->count(),
            'inactive' => $usersTable->find()->where(['active' => false])->count(),
        ];

        $this->set(compact('users', 'stats', 'role', 'status', 'search'));
    }

    /**
     * List teachers only
     */
    public function teachers()
    {
        $query = $this->fetchTable('Users')->find()
            ->where(['Users.role' => 'teacher'])
            ->orderBy(['Users.name' => 'ASC']);

        // Filter by status
        $status = $this->request->getQuery('status');
        if ($status === 'active') {
            $query->where(['Users.active' => true]);
        } elseif ($status === 'inactive') {
            $query->where(['Users.active' => false]);
        }

        // Search
        $search = $this->request->getQuery('search');
        if ($search) {
            $query->where([
                'OR' => [
                    'Users.name LIKE' => '%' . $search . '%',
                    'Users.email LIKE' => '%' . $search . '%',
                ],
            ]);
        }

        $this->paginate = ['limit' => 25];
        $teachers = $this->paginate($query);

        // Get teacher subjects
        $teacherSubjectsTable = $this->fetchTable('TeacherSubjects');
        $teacherSubjects = [];
        foreach ($teachers as $teacher) {
            $subjects = $teacherSubjectsTable->find()
                ->contain(['Subjects'])
                ->where(['TeacherSubjects.teacher_id' => $teacher->id])
                ->all();
            $teacherSubjects[$teacher->id] = $subjects;
        }

        // Get courses count per teacher
        $coursesTable = $this->fetchTable('Courses');
        $courseCounts = [];
        foreach ($teachers as $teacher) {
            $courseCounts[$teacher->id] = $coursesTable->find()
                ->where(['teacher_id' => $teacher->id, 'is_active' => true])
                ->count();
        }

        // Statistics
        $usersTable = $this->fetchTable('Users');
        $stats = [
            'total' => $usersTable->find()->where(['role' => 'teacher'])->count(),
            'active' => $usersTable->find()->where(['role' => 'teacher', 'active' => true])->count(),
            'inactive' => $usersTable->find()->where(['role' => 'teacher', 'active' => false])->count(),
        ];

        $this->set(compact('teachers', 'teacherSubjects', 'courseCounts', 'stats', 'status', 'search'));
    }

    /**
     * List students only
     */
    public function students()
    {
        $query = $this->fetchTable('Users')->find()
            ->where(['Users.role' => 'student'])
            ->orderBy(['Users.name' => 'ASC']);

        // Filter by status
        $status = $this->request->getQuery('status');
        if ($status === 'active') {
            $query->where(['Users.active' => true]);
        } elseif ($status === 'inactive') {
            $query->where(['Users.active' => false]);
        }

        // Filter by class
        $classId = $this->request->getQuery('class_id');

        // Search
        $search = $this->request->getQuery('search');
        if ($search) {
            $query->where([
                'OR' => [
                    'Users.name LIKE' => '%' . $search . '%',
                    'Users.email LIKE' => '%' . $search . '%',
                ],
            ]);
        }

        $this->paginate = ['limit' => 25];
        $students = $this->paginate($query);

        // Get student classes
        $studentClassesTable = $this->fetchTable('StudentClasses');
        $studentClasses = [];
        foreach ($students as $student) {
            $classes = $studentClassesTable->find()
                ->contain(['Classes'])
                ->where([
                    'StudentClasses.student_id' => $student->id,
                    'StudentClasses.status' => 'active',
                ])
                ->all();
            $studentClasses[$student->id] = $classes;
        }

        // Get courses count per student
        $studentCoursesTable = $this->fetchTable('StudentCourses');
        $courseCounts = [];
        foreach ($students as $student) {
            $courseCounts[$student->id] = $studentCoursesTable->find()
                ->where(['student_id' => $student->id, 'status' => 'enrolled'])
                ->count();
        }

        // Get classes for filter
        $classesTable = $this->fetchTable('Classes');
        $classes = $classesTable->find('list', keyField: 'id', valueField: function ($class) {
            return $class->name . ($class->section ? ' - ' . $class->section : '');
        })->find('active')->toArray();

        // Statistics
        $usersTable = $this->fetchTable('Users');
        $stats = [
            'total' => $usersTable->find()->where(['role' => 'student'])->count(),
            'active' => $usersTable->find()->where(['role' => 'student', 'active' => true])->count(),
            'inactive' => $usersTable->find()->where(['role' => 'student', 'active' => false])->count(),
        ];

        $this->set(compact('students', 'studentClasses', 'courseCounts', 'stats', 'status', 'search', 'classes', 'classId'));
    }

    /**
     * Add a new teacher
     */
    public function addTeacher()
    {
        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['role'] = 'teacher';
            $data['active'] = true;

            // Generate password if not provided
            if (empty($data['password'])) {
                $data['password'] = $this->generatePassword();
                $generatedPassword = $data['password'];
            }

            $user = $usersTable->patchEntity($user, $data);

            if ($usersTable->save($user)) {
                // Assign subjects if selected
                $subjectIds = $this->request->getData('subject_ids', []);
                if (!empty($subjectIds)) {
                    $this->assignTeacherSubjects($user->id, $subjectIds);
                }

                $message = __('Teacher account created successfully.');
                if (isset($generatedPassword)) {
                    $message .= ' ' . __('Generated password: {0}', $generatedPassword);
                }
                $this->Flash->success($message);

                return $this->redirect(['action' => 'teachers']);
            }
            $this->Flash->error(__('Unable to create teacher account. Please try again.'));
        }

        // Get subjects for assignment (ordered alphabetically)
        $subjectsTable = $this->fetchTable('Subjects');
        $subjects = $subjectsTable->find()
            ->find('active')
            ->orderBy(['Subjects.name' => 'ASC'])
            ->all();

        $this->set(compact('user', 'subjects'));
    }

    /**
     * Add a new student
     */
    public function addStudent()
    {
        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['role'] = 'student';
            $data['active'] = true;

            // Generate password if not provided
            if (empty($data['password'])) {
                $data['password'] = $this->generatePassword();
                $generatedPassword = $data['password'];
            }

            $user = $usersTable->patchEntity($user, $data);

            if ($usersTable->save($user)) {
                // Assign to class if selected
                $classId = $this->request->getData('class_id');
                if ($classId) {
                    $this->assignStudentToClass($user->id, (int)$classId);
                }

                $message = __('Student account created successfully.');
                if (isset($generatedPassword)) {
                    $message .= ' ' . __('Generated password: {0}', $generatedPassword);
                }
                $this->Flash->success($message);

                return $this->redirect(['action' => 'students']);
            }
            $this->Flash->error(__('Unable to create student account. Please try again.'));
        }

        // Get classes for assignment
        $classesTable = $this->fetchTable('Classes');
        $classes = $classesTable->find('list', keyField: 'id', valueField: function ($class) {
            return $class->name . ($class->section ? ' - ' . $class->section : '') . ' (' . $class->grade_level . ')';
        })->find('active')->toArray();

        // Get grade levels
        $gradeLevels = $this->getGradeLevels();

        $this->set(compact('user', 'classes', 'gradeLevels'));
    }

    /**
     * View user details with comprehensive information
     */
    public function view(?string $id = null)
    {
        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->get($id);

        $teacherSubjects = null;
        $teacherCourses = null;
        $teacherStats = null;
        $studentClasses = null;
        $studentCourses = null;
        $attendanceStats = null;
        $attendanceHistory = null;
        $feesSummary = null;
        $recentPayments = null;
        $grades = null;

        $currentYear = (int)date('Y');
        $academicYear = $currentYear . '-' . ($currentYear + 1);

        if ($user->role === 'teacher') {
            // Get teacher's subjects
            $teacherSubjectsTable = $this->fetchTable('TeacherSubjects');
            $teacherSubjects = $teacherSubjectsTable->find()
                ->contain(['Subjects'])
                ->where(['TeacherSubjects.teacher_id' => $id])
                ->all();

            // Get teacher's courses with student count
            $coursesTable = $this->fetchTable('Courses');
            $teacherCourses = $coursesTable->find()
                ->contain(['Subjects', 'Classes'])
                ->where(['Courses.teacher_id' => $id])
                ->orderBy(['Courses.academic_year' => 'DESC', 'Courses.created' => 'DESC'])
                ->all();

            // Teacher statistics
            $studentCoursesTable = $this->fetchTable('StudentCourses');
            $totalStudents = 0;
            $courseIds = [];
            foreach ($teacherCourses as $course) {
                $courseIds[] = $course->id;
            }

            if (!empty($courseIds)) {
                $totalStudents = $studentCoursesTable->find()
                    ->where(['course_id IN' => $courseIds, 'status' => 'enrolled'])
                    ->count();
            }

            $activeCourses = $coursesTable->find()
                ->where(['teacher_id' => $id, 'is_active' => true])
                ->count();

            $teacherStats = [
                'total_courses' => count($teacherCourses),
                'active_courses' => $activeCourses,
                'total_students' => $totalStudents,
                'subjects_count' => $teacherSubjects ? $teacherSubjects->count() : 0,
            ];

        } elseif ($user->role === 'student') {
            // Get student's current class
            $studentClassesTable = $this->fetchTable('StudentClasses');
            $studentClasses = $studentClassesTable->find()
                ->contain(['Classes'])
                ->where(['StudentClasses.student_id' => $id])
                ->orderBy(['StudentClasses.academic_year' => 'DESC'])
                ->all();

            // Get student's courses with grades
            $studentCoursesTable = $this->fetchTable('StudentCourses');
            $studentCourses = $studentCoursesTable->find()
                ->contain(['Courses' => ['Subjects', 'Classes', 'Teachers']])
                ->where(['StudentCourses.student_id' => $id])
                ->orderBy(['StudentCourses.enrolled_date' => 'DESC'])
                ->all();

            // Calculate grade statistics
            $gradePoints = ['A*' => 5, 'A' => 4.5, 'B' => 4, 'C' => 3, 'D' => 2, 'E' => 1, 'U' => 0];
            $totalPoints = 0;
            $gradedCourses = 0;
            $gradeDistribution = [];

            foreach ($studentCourses as $sc) {
                if ($sc->grade) {
                    $gradedCourses++;
                    $totalPoints += $gradePoints[$sc->grade] ?? 0;
                    $gradeDistribution[$sc->grade] = ($gradeDistribution[$sc->grade] ?? 0) + 1;
                }
            }

            $grades = [
                'gpa' => $gradedCourses > 0 ? round($totalPoints / $gradedCourses, 2) : null,
                'graded_courses' => $gradedCourses,
                'total_courses' => $studentCourses->count(),
                'distribution' => $gradeDistribution,
            ];

            // Detailed attendance stats
            $attendanceTable = $this->fetchTable('Attendances');

            // Overall stats
            $totalAttendance = $attendanceTable->find()
                ->where(['Attendances.student_id' => $id])
                ->count();
            $presentCount = $attendanceTable->find()
                ->where(['Attendances.student_id' => $id, 'Attendances.status' => 'present'])
                ->count();
            $absentCount = $attendanceTable->find()
                ->where(['Attendances.student_id' => $id, 'Attendances.status' => 'absent'])
                ->count();
            $lateCount = $attendanceTable->find()
                ->where(['Attendances.student_id' => $id, 'Attendances.status' => 'late'])
                ->count();

            $attendanceStats = [
                'total' => $totalAttendance,
                'present' => $presentCount,
                'absent' => $absentCount,
                'late' => $lateCount,
                'percentage' => $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100, 1) : 0,
            ];

            // Monthly attendance for the last 6 months
            $attendanceHistory = [];
            for ($i = 5; $i >= 0; $i--) {
                $monthStart = date('Y-m-01', strtotime("-$i months"));
                $monthEnd = date('Y-m-t', strtotime("-$i months"));
                $monthName = date('M Y', strtotime("-$i months"));

                $monthPresent = $attendanceTable->find()
                    ->where([
                        'student_id' => $id,
                        'status' => 'present',
                        'date >=' => $monthStart,
                        'date <=' => $monthEnd,
                    ])->count();

                $monthTotal = $attendanceTable->find()
                    ->where([
                        'student_id' => $id,
                        'date >=' => $monthStart,
                        'date <=' => $monthEnd,
                    ])->count();

                $attendanceHistory[] = [
                    'month' => $monthName,
                    'present' => $monthPresent,
                    'total' => $monthTotal,
                    'percentage' => $monthTotal > 0 ? round(($monthPresent / $monthTotal) * 100, 1) : 0,
                ];
            }

            // Get fees summary (with null check for table existence)
            try {
                $studentFeesTable = $this->fetchTable('StudentFees');
                $feesSummary = $studentFeesTable->getStudentFeesSummary((int)$id, $academicYear);

                // Recent payments
                $feePaymentsTable = $this->fetchTable('FeePayments');
                $recentPayments = $feePaymentsTable->find()
                    ->contain(['StudentFees' => ['FeeTypes']])
                    ->matching('StudentFees', function ($q) use ($id) {
                        return $q->where(['StudentFees.student_id' => $id]);
                    })
                    ->orderBy(['FeePayments.payment_date' => 'DESC'])
                    ->limit(5)
                    ->all();
            } catch (\Exception $e) {
                // Tables don't exist yet, skip fees
                $feesSummary = null;
                $recentPayments = null;
            }
        }

        $this->set(compact(
            'user',
            'teacherSubjects',
            'teacherCourses',
            'teacherStats',
            'studentClasses',
            'studentCourses',
            'attendanceStats',
            'attendanceHistory',
            'feesSummary',
            'recentPayments',
            'grades',
            'academicYear'
        ));
    }

    /**
     * Edit user details
     */
    public function edit(?string $id = null)
    {
        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            // Don't update password if empty
            if (empty($data['password'])) {
                unset($data['password']);
            }

            $user = $usersTable->patchEntity($user, $data);

            if ($usersTable->save($user)) {
                $this->Flash->success(__('User has been updated.'));

                if ($user->role === 'teacher') {
                    return $this->redirect(['action' => 'teachers']);
                } elseif ($user->role === 'student') {
                    return $this->redirect(['action' => 'students']);
                }
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update user. Please try again.'));
        }

        // Get grade levels for students
        $gradeLevels = $this->getGradeLevels();

        $this->set(compact('user', 'gradeLevels'));
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(?string $id = null)
    {
        $this->request->allowMethod(['post']);

        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->get($id);

        // Prevent deactivating yourself
        $currentUser = $this->request->getAttribute('identity');
        if ($currentUser->id === $user->id) {
            $this->Flash->error(__('You cannot deactivate your own account.'));
            return $this->redirect($this->referer(['action' => 'index']));
        }

        $user->active = !$user->active;

        if ($usersTable->save($user)) {
            $status = $user->active ? __('activated') : __('deactivated');
            $this->Flash->success(__('User has been {0}.', $status));
        } else {
            $this->Flash->error(__('Unable to update user status.'));
        }

        return $this->redirect($this->referer(['action' => 'index']));
    }

    /**
     * Delete user
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->get($id);

        // Prevent deleting yourself
        $currentUser = $this->request->getAttribute('identity');
        if ($currentUser->id === $user->id) {
            $this->Flash->error(__('You cannot delete your own account.'));
            return $this->redirect($this->referer(['action' => 'index']));
        }

        if ($usersTable->delete($user)) {
            $this->Flash->success(__('User has been deleted.'));
        } else {
            $this->Flash->error(__('Unable to delete user.'));
        }

        return $this->redirect($this->referer(['action' => 'index']));
    }

    /**
     * Reset user password
     */
    public function resetPassword(?string $id = null)
    {
        $this->request->allowMethod(['post']);

        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->get($id);

        $newPassword = $this->generatePassword();
        $user->password = $newPassword;

        if ($usersTable->save($user)) {
            $this->Flash->success(__('Password reset successfully. New password: {0}', $newPassword));
        } else {
            $this->Flash->error(__('Unable to reset password.'));
        }

        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * Manage teacher's subject assignments
     */
    public function teacherSubjects(?string $id = null)
    {
        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->get($id);

        if ($user->role !== 'teacher') {
            $this->Flash->error(__('This user is not a teacher.'));
            return $this->redirect(['action' => 'index']);
        }

        $teacherSubjectsTable = $this->fetchTable('TeacherSubjects');

        if ($this->request->is('post')) {
            $subjectIds = $this->request->getData('subject_ids', []);
            $primarySubjectId = $this->request->getData('primary_subject_id');

            // Remove existing assignments
            $teacherSubjectsTable->deleteAll(['teacher_id' => $id]);

            // Add new assignments
            foreach ($subjectIds as $subjectId) {
                $assignment = $teacherSubjectsTable->newEntity([
                    'teacher_id' => $id,
                    'subject_id' => $subjectId,
                    'is_primary' => ($subjectId == $primarySubjectId),
                ]);
                $teacherSubjectsTable->save($assignment);
            }

            $this->Flash->success(__('Subject assignments updated.'));
            return $this->redirect(['action' => 'view', $id]);
        }

        // Get current assignments
        $currentSubjects = $teacherSubjectsTable->find()
            ->contain(['Subjects'])
            ->where(['TeacherSubjects.teacher_id' => $id])
            ->all();

        $currentSubjectIds = $currentSubjects->extract('subject_id')->toArray();
        $primarySubjectId = null;
        foreach ($currentSubjects as $ts) {
            if ($ts->is_primary) {
                $primarySubjectId = $ts->subject_id;
                break;
            }
        }

        // Get all subjects
        $subjectsTable = $this->fetchTable('Subjects');
        $subjects = $subjectsTable->find()->find('active')->all();

        $this->set(compact('user', 'subjects', 'currentSubjectIds', 'primarySubjectId'));
    }

    /**
     * Manage student's class assignments
     */
    public function studentClasses(?string $id = null)
    {
        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->get($id);

        if ($user->role !== 'student') {
            $this->Flash->error(__('This user is not a student.'));
            return $this->redirect(['action' => 'index']);
        }

        $studentClassesTable = $this->fetchTable('StudentClasses');

        if ($this->request->is('post')) {
            $classId = $this->request->getData('class_id');
            $academicYear = $this->request->getData('academic_year');

            // Check if already assigned to this class
            $existing = $studentClassesTable->find()
                ->where([
                    'student_id' => $id,
                    'class_id' => $classId,
                    'academic_year' => $academicYear,
                ])
                ->first();

            if ($existing) {
                $this->Flash->warning(__('Student is already assigned to this class for this academic year.'));
            } else {
                // Deactivate previous class assignments for same year
                $studentClassesTable->updateAll(
                    ['status' => 'transferred'],
                    [
                        'student_id' => $id,
                        'academic_year' => $academicYear,
                        'status' => 'active',
                    ]
                );

                $assignment = $studentClassesTable->newEntity([
                    'student_id' => $id,
                    'class_id' => $classId,
                    'academic_year' => $academicYear,
                    'status' => 'active',
                ]);

                if ($studentClassesTable->save($assignment)) {
                    $this->Flash->success(__('Student assigned to class successfully.'));
                    return $this->redirect(['action' => 'view', $id]);
                }
            }
        }

        // Get current assignments
        $currentClasses = $studentClassesTable->find()
            ->contain(['Classes'])
            ->where(['StudentClasses.student_id' => $id])
            ->orderBy(['StudentClasses.academic_year' => 'DESC'])
            ->all();

        // Get all classes
        $classesTable = $this->fetchTable('Classes');
        $classes = $classesTable->find('list', keyField: 'id', valueField: function ($class) {
            return $class->name . ($class->section ? ' - ' . $class->section : '') . ' (' . $class->grade_level . ')';
        })->find('active')->toArray();

        $academicYears = $this->getAcademicYears();

        $this->set(compact('user', 'currentClasses', 'classes', 'academicYears'));
    }

    /**
     * Bulk import students
     */
    public function importStudents()
    {
        if ($this->request->is('post')) {
            $uploadedFile = $this->request->getData('csv_file');

            if ($uploadedFile && $uploadedFile->getError() === UPLOAD_ERR_OK) {
                $handle = fopen($uploadedFile->getStream()->getMetadata('uri'), 'r');

                if ($handle !== false) {
                    $usersTable = $this->fetchTable('Users');
                    $header = fgetcsv($handle); // Skip header row

                    $imported = 0;
                    $failed = 0;
                    $errors = [];

                    while (($data = fgetcsv($handle)) !== false) {
                        if (count($data) < 2) {
                            continue;
                        }

                        $userData = [
                            'name' => $data[0] ?? '',
                            'email' => $data[1] ?? '',
                            'password' => $data[2] ?? $this->generatePassword(),
                            'role' => 'student',
                            'active' => true,
                            'phone' => $data[3] ?? null,
                            'date_of_birth' => !empty($data[4]) ? $data[4] : null,
                            'grade_level' => $data[5] ?? null,
                        ];

                        $user = $usersTable->newEntity($userData);

                        if ($usersTable->save($user)) {
                            $imported++;

                            // Assign to class if class_id provided
                            if (!empty($data[6])) {
                                $this->assignStudentToClass($user->id, (int)$data[6]);
                            }
                        } else {
                            $failed++;
                            $errors[] = "Row " . ($imported + $failed + 1) . ": " . $userData['email'] . " - " . implode(', ', $user->getErrors());
                        }
                    }

                    fclose($handle);

                    if ($imported > 0) {
                        $this->Flash->success(__('Successfully imported {0} students.', $imported));
                    }
                    if ($failed > 0) {
                        $this->Flash->warning(__('Failed to import {0} students.', $failed));
                    }

                    $this->set('importErrors', $errors);
                }
            } else {
                $this->Flash->error(__('Please upload a valid CSV file.'));
            }
        }

        // Get classes for reference
        $classesTable = $this->fetchTable('Classes');
        $classes = $classesTable->find('list', keyField: 'id', valueField: function ($class) {
            return $class->name . ($class->section ? ' - ' . $class->section : '');
        })->find('active')->toArray();

        $this->set(compact('classes'));
    }

    /**
     * Bulk import teachers
     */
    public function importTeachers()
    {
        if ($this->request->is('post')) {
            $uploadedFile = $this->request->getData('csv_file');

            if ($uploadedFile && $uploadedFile->getError() === UPLOAD_ERR_OK) {
                $handle = fopen($uploadedFile->getStream()->getMetadata('uri'), 'r');

                if ($handle !== false) {
                    $usersTable = $this->fetchTable('Users');
                    $header = fgetcsv($handle); // Skip header row

                    $imported = 0;
                    $failed = 0;
                    $errors = [];

                    while (($data = fgetcsv($handle)) !== false) {
                        if (count($data) < 2) {
                            continue;
                        }

                        $userData = [
                            'name' => $data[0] ?? '',
                            'email' => $data[1] ?? '',
                            'password' => $data[2] ?? $this->generatePassword(),
                            'role' => 'teacher',
                            'active' => true,
                            'phone' => $data[3] ?? null,
                        ];

                        $user = $usersTable->newEntity($userData);

                        if ($usersTable->save($user)) {
                            $imported++;

                            // Assign subjects if provided (comma-separated IDs)
                            if (!empty($data[4])) {
                                $subjectIds = array_map('trim', explode(',', $data[4]));
                                $this->assignTeacherSubjects($user->id, $subjectIds);
                            }
                        } else {
                            $failed++;
                            $errors[] = "Row " . ($imported + $failed + 1) . ": " . $userData['email'];
                        }
                    }

                    fclose($handle);

                    if ($imported > 0) {
                        $this->Flash->success(__('Successfully imported {0} teachers.', $imported));
                    }
                    if ($failed > 0) {
                        $this->Flash->warning(__('Failed to import {0} teachers.', $failed));
                    }

                    $this->set('importErrors', $errors);
                }
            } else {
                $this->Flash->error(__('Please upload a valid CSV file.'));
            }
        }

        // Get subjects for reference
        $subjectsTable = $this->fetchTable('Subjects');
        $subjects = $subjectsTable->find()->find('active')->all();

        $this->set(compact('subjects'));
    }

    /**
     * Helper: Generate random password
     */
    private function generatePassword(): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        for ($i = 0; $i < 10; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $password;
    }

    /**
     * Helper: Get grade levels
     */
    private function getGradeLevels(): array
    {
        return [
            'Play Group' => 'Play Group',
            'Nursery' => 'Nursery',
            'KG' => 'KG',
            'Grade 1' => 'Grade 1',
            'Grade 2' => 'Grade 2',
            'Grade 3' => 'Grade 3',
            'Grade 4' => 'Grade 4',
            'Grade 5' => 'Grade 5',
            'Grade 6' => 'Grade 6',
            'Grade 7' => 'Grade 7',
            'Grade 8' => 'Grade 8',
            'Grade 9' => 'Grade 9 (O Level)',
            'Grade 10' => 'Grade 10 (O Level)',
        ];
    }

    /**
     * Helper: Get academic years
     */
    private function getAcademicYears(): array
    {
        $currentYear = (int)date('Y');
        $years = [];
        for ($i = -1; $i < 3; $i++) {
            $year = $currentYear + $i;
            $key = $year . '-' . ($year + 1);
            $years[$key] = $key;
        }
        return $years;
    }

    /**
     * Helper: Assign teacher to subjects
     */
    private function assignTeacherSubjects(int $teacherId, array $subjectIds): void
    {
        $teacherSubjectsTable = $this->fetchTable('TeacherSubjects');

        foreach ($subjectIds as $index => $subjectId) {
            $assignment = $teacherSubjectsTable->newEntity([
                'teacher_id' => $teacherId,
                'subject_id' => (int)$subjectId,
                'is_primary' => $index === 0,
            ]);
            $teacherSubjectsTable->save($assignment);
        }
    }

    /**
     * Helper: Assign student to class
     */
    private function assignStudentToClass(int $studentId, int $classId): void
    {
        $studentClassesTable = $this->fetchTable('StudentClasses');
        $currentYear = (int)date('Y');
        $academicYear = $currentYear . '-' . ($currentYear + 1);

        $assignment = $studentClassesTable->newEntity([
            'student_id' => $studentId,
            'class_id' => $classId,
            'academic_year' => $academicYear,
            'status' => 'active',
        ]);
        $studentClassesTable->save($assignment);
    }
}
