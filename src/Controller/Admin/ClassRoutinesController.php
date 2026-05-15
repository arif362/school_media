<?php
/**
 * ClassRoutines Controller
 *
 * Manages class routine/timetable operations.
 *
 * @created 2026-05-15
 * @author Arif
 */
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Entity\ClassRoutine;
use App\Model\Entity\RoutineTemplate;
use App\Model\Entity\SchoolClass;

class ClassRoutinesController extends AdminAppController
{
    public function index()
    {
        $classesTable = $this->fetchTable('Classes');

        $query = $classesTable->find()
            ->contain(['ClassTeachers'])
            ->find('active')
            ->orderBy(['Classes.grade_level' => 'ASC', 'Classes.name' => 'ASC']);

        // Filters
        $gradeLevel = $this->request->getQuery('grade_level');
        $academicYear = $this->request->getQuery('academic_year');

        if ($gradeLevel) {
            $query->where(['Classes.grade_level' => $gradeLevel]);
        }
        if ($academicYear) {
            $query->where(['Classes.academic_year' => $academicYear]);
        } else {
            // Default to current academic year
            $academicYear = $this->getCurrentAcademicYear();
            $query->where(['Classes.academic_year' => $academicYear]);
        }

        $classes = $query->all();

        // Calculate routine completion for each class
        $classRoutinesTable = $this->fetchTable('ClassRoutines');
        $classesWithCompletion = [];
        foreach ($classes as $class) {
            $completion = $classRoutinesTable->getCompletionPercentage($class->id, $class->academic_year);
            $classesWithCompletion[] = [
                'class' => $class,
                'completion' => $completion,
            ];
        }

        $gradeLevels = SchoolClass::GRADE_LEVELS;
        $academicYears = $this->getAcademicYears();

        $this->set(compact('classesWithCompletion', 'gradeLevels', 'academicYears', 'gradeLevel', 'academicYear'));
    }

    /**
     * Print-friendly view of class timetable
     */
    public function view(?string $classId = null)
    {
        $classesTable = $this->fetchTable('Classes');
        $class = $classesTable->get($classId, contain: ['ClassTeachers']);

        $periodsTable = $this->fetchTable('Periods');
        $periods = $periodsTable->find()
            ->find('active')
            ->find('byAcademicYear', year: $class->academic_year)
            ->find('ordered')
            ->all();

        // Get routine grid
        $routineGrid = $this->ClassRoutines->getRoutineGrid($class->id, $class->academic_year);

        // Get completion percentage
        $completion = $this->ClassRoutines->getCompletionPercentage($class->id, $class->academic_year);

        $weekdays = ClassRoutine::getWeekdays();
        $generatedDate = date('F j, Y \a\t g:i A');

        // Use minimal layout for print
        $this->viewBuilder()->setLayout('print');

        $this->set(compact('class', 'periods', 'routineGrid', 'weekdays', 'completion', 'generatedDate'));
    }

    public function edit(?string $classId = null)
    {
        $classesTable = $this->fetchTable('Classes');
        $class = $classesTable->get($classId, contain: ['ClassTeachers']);

        $periodsTable = $this->fetchTable('Periods');
        $periods = $periodsTable->find()
            ->find('active')
            ->find('byAcademicYear', year: $class->academic_year)
            ->find('ordered')
            ->all();

        if ($periods->isEmpty()) {
            $this->Flash->warning(__('No periods defined for academic year {0}. Please create periods first.', $class->academic_year));
            return $this->redirect(['controller' => 'Periods', 'action' => 'add', '?' => ['academic_year' => $class->academic_year]]);
        }

        // Get existing routine grid
        $routineGrid = $this->ClassRoutines->getRoutineGrid($class->id, $class->academic_year);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $saved = 0;
            $errors = [];

            // Process grid data
            if (!empty($data['routine'])) {
                foreach ($data['routine'] as $periodId => $days) {
                    foreach ($days as $dayOfWeek => $slotData) {
                        $result = $this->saveRoutineSlot(
                            $class->id,
                            (int) $periodId,
                            (int) $dayOfWeek,
                            $class->academic_year,
                            $slotData
                        );

                        if ($result === true) {
                            $saved++;
                        } elseif ($result !== null) {
                            $errors[] = $result;
                        }
                    }
                }
            }

            if (empty($errors)) {
                $this->Flash->success(__('Class routine has been saved. {0} slots updated.', $saved));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Some errors occurred: {0}', implode(', ', $errors)));
            }

            // Refresh grid after save attempt
            $routineGrid = $this->ClassRoutines->getRoutineGrid($class->id, $class->academic_year);
        }

        // Get form data
        $subjectsTable = $this->fetchTable('Subjects');
        $subjects = $subjectsTable->find('list')->find('active')->toArray();

        $usersTable = $this->fetchTable('Users');
        $teachers = $usersTable->find('list', keyField: 'id', valueField: 'name')
            ->where(['role IN' => ['admin', 'teacher']])
            ->toArray();

        $weekdays = ClassRoutine::getWeekdays();

        $this->set(compact('class', 'periods', 'routineGrid', 'subjects', 'teachers', 'weekdays'));
    }

    /**
     * Save or update a single routine slot
     */
    private function saveRoutineSlot(
        int $classId,
        int $periodId,
        int $dayOfWeek,
        string $academicYear,
        array $slotData
    ): bool|string|null {
        $subjectId = !empty($slotData['subject_id']) ? (int) $slotData['subject_id'] : null;
        $teacherId = !empty($slotData['teacher_id']) ? (int) $slotData['teacher_id'] : null;
        $room = $slotData['room'] ?? null;

        // Find existing entry
        $existing = $this->ClassRoutines->find()
            ->where([
                'class_id' => $classId,
                'period_id' => $periodId,
                'day_of_week' => $dayOfWeek,
                'academic_year' => $academicYear,
            ])
            ->first();

        // If all empty and no existing, skip
        if (!$subjectId && !$teacherId && !$room && !$existing) {
            return null;
        }

        // If all empty but existing, delete it
        if (!$subjectId && !$teacherId && !$room && $existing) {
            $this->ClassRoutines->delete($existing);
            return true;
        }

        if ($existing) {
            $routine = $this->ClassRoutines->patchEntity($existing, [
                'subject_id' => $subjectId,
                'teacher_id' => $teacherId,
                'room' => $room,
            ]);
        } else {
            $routine = $this->ClassRoutines->newEntity([
                'class_id' => $classId,
                'period_id' => $periodId,
                'day_of_week' => $dayOfWeek,
                'academic_year' => $academicYear,
                'subject_id' => $subjectId,
                'teacher_id' => $teacherId,
                'room' => $room,
                'is_active' => true,
            ]);
        }

        if ($this->ClassRoutines->save($routine)) {
            return true;
        }

        return __('Error saving slot for Period {0}, Day {1}', $periodId, $dayOfWeek);
    }

    /**
     * Apply a Cambridge template to a class routine
     */
    public function applyTemplate(?string $classId = null)
    {
        $classesTable = $this->fetchTable('Classes');
        $class = $classesTable->get($classId, contain: ['ClassTeachers']);

        // Get Cambridge stage for this class
        $cambridgeStage = RoutineTemplate::getStageForGradeLevel($class->grade_level);

        // Get available templates for this stage
        $templatesTable = $this->fetchTable('RoutineTemplates');
        $templates = [];
        if ($cambridgeStage) {
            $templates = $templatesTable->find()
                ->find('active')
                ->find('byStage', stage: $cambridgeStage)
                ->find('withItems')
                ->orderBy(['RoutineTemplates.name' => 'ASC'])
                ->all()
                ->toArray();
        }

        // Also provide all templates for flexibility
        $allTemplates = $templatesTable->getAllGroupedByStage();

        // Get periods for this academic year
        $periodsTable = $this->fetchTable('Periods');
        $periods = $periodsTable->find()
            ->find('active')
            ->find('byAcademicYear', year: $class->academic_year)
            ->find('ordered')
            ->all()
            ->toArray();

        $teachablePeriods = array_filter($periods, fn($p) => !$p->is_break);

        if (empty($teachablePeriods)) {
            $this->Flash->warning(__('No periods defined for academic year {0}. Please create periods first.', $class->academic_year));
            return $this->redirect(['action' => 'edit', $classId]);
        }

        if ($this->request->is('post')) {
            $templateId = (int) $this->request->getData('template_id');

            if (!$templateId) {
                $this->Flash->error(__('Please select a template to apply.'));
            } else {
                $template = $templatesTable->get($templateId, contain: ['RoutineTemplateItems' => ['Subjects']]);

                // Check if routine already has entries
                $existingCount = $this->ClassRoutines->find()
                    ->where([
                        'class_id' => $class->id,
                        'academic_year' => $class->academic_year,
                    ])
                    ->count();

                $clearExisting = (bool) $this->request->getData('clear_existing', false);
                if ($existingCount > 0 && $clearExisting) {
                    // Delete existing routine entries
                    $this->ClassRoutines->deleteAll([
                        'class_id' => $class->id,
                        'academic_year' => $class->academic_year,
                    ]);
                }

                // Apply template - distribute subjects across periods/days
                $slotsCreated = $this->distributeTemplateToRoutine(
                    $class->id,
                    $class->academic_year,
                    $template,
                    $teachablePeriods
                );

                if ($slotsCreated > 0) {
                    $this->Flash->success(__('Template "{0}" applied successfully. {1} slots created.', $template->name, $slotsCreated));
                    return $this->redirect(['action' => 'edit', $classId]);
                } else {
                    $this->Flash->warning(__('Template applied but no slots could be created. Check that subjects exist.'));
                }
            }
        }

        // Get existing routine count for warning
        $existingCount = $this->ClassRoutines->find()
            ->where([
                'class_id' => $class->id,
                'academic_year' => $class->academic_year,
            ])
            ->count();

        $this->set(compact('class', 'templates', 'allTemplates', 'cambridgeStage', 'periods', 'existingCount'));
    }

    /**
     * Distribute template subjects across periods and days
     */
    private function distributeTemplateToRoutine(
        int $classId,
        string $academicYear,
        $template,
        array $teachablePeriods
    ): int {
        if (empty($template->routine_template_items)) {
            return 0;
        }

        // Build allocation queue - repeat subjects based on periods_per_week
        $allocationQueue = [];
        foreach ($template->routine_template_items as $item) {
            for ($i = 0; $i < $item->periods_per_week; $i++) {
                $allocationQueue[] = [
                    'subject_id' => $item->subject_id,
                    'subject_name' => $item->subject->name ?? 'Unknown',
                    'is_required' => $item->is_required,
                ];
            }
        }

        // Shuffle for variety but keep required subjects first in each day
        shuffle($allocationQueue);

        // Available slots: 5 days × number of teaching periods
        $weekdays = [ClassRoutine::MONDAY, ClassRoutine::TUESDAY, ClassRoutine::WEDNESDAY, ClassRoutine::THURSDAY, ClassRoutine::FRIDAY];
        $periodsPerDay = count($teachablePeriods);
        $totalSlots = $periodsPerDay * 5;

        // If more subjects than slots, truncate
        if (count($allocationQueue) > $totalSlots) {
            $allocationQueue = array_slice($allocationQueue, 0, $totalSlots);
        }

        // Distribute: fill day by day, period by period
        $slotsCreated = 0;
        $slotIndex = 0;

        foreach ($weekdays as $dayOfWeek) {
            foreach ($teachablePeriods as $period) {
                if ($slotIndex >= count($allocationQueue)) {
                    break 2;
                }

                $allocation = $allocationQueue[$slotIndex];

                $routine = $this->ClassRoutines->newEntity([
                    'class_id' => $classId,
                    'period_id' => $period->id,
                    'day_of_week' => $dayOfWeek,
                    'academic_year' => $academicYear,
                    'subject_id' => $allocation['subject_id'],
                    'teacher_id' => null, // Teacher assignment is manual
                    'room' => null,
                    'is_active' => true,
                ]);

                if ($this->ClassRoutines->save($routine)) {
                    $slotsCreated++;
                }

                $slotIndex++;
            }
        }

        return $slotsCreated;
    }

    /**
     * Copy routine from one class to another
     */
    public function copy(?string $sourceClassId = null)
    {
        $classesTable = $this->fetchTable('Classes');
        $sourceClass = $classesTable->get($sourceClassId, contain: ['ClassTeachers']);

        // Get source routine entries count
        $sourceRoutineCount = $this->ClassRoutines->find()
            ->where([
                'class_id' => $sourceClass->id,
                'academic_year' => $sourceClass->academic_year,
            ])
            ->count();

        if ($sourceRoutineCount === 0) {
            $this->Flash->warning(__('Source class has no routine entries to copy.'));
            return $this->redirect(['action' => 'index']);
        }

        // Get available target classes
        $academicYears = $this->getAcademicYears();
        $targetClasses = $classesTable->find()
            ->find('active')
            ->where(['Classes.id !=' => $sourceClass->id])
            ->orderBy(['Classes.academic_year' => 'DESC', 'Classes.grade_level' => 'ASC', 'Classes.name' => 'ASC'])
            ->all();

        // Group target classes by academic year
        $classesGrouped = [];
        foreach ($targetClasses as $class) {
            $classesGrouped[$class->academic_year][] = $class;
        }

        if ($this->request->is('post')) {
            $targetClassId = (int) $this->request->getData('target_class_id');
            $copyTeachers = (bool) $this->request->getData('copy_teachers', false);
            $clearTarget = (bool) $this->request->getData('clear_target', false);

            if (!$targetClassId) {
                $this->Flash->error(__('Please select a target class.'));
            } else {
                $targetClass = $classesTable->get($targetClassId);

                // Check if target already has entries
                $targetEntryCount = $this->ClassRoutines->find()
                    ->where([
                        'class_id' => $targetClass->id,
                        'academic_year' => $targetClass->academic_year,
                    ])
                    ->count();

                if ($targetEntryCount > 0 && $clearTarget) {
                    $this->ClassRoutines->deleteAll([
                        'class_id' => $targetClass->id,
                        'academic_year' => $targetClass->academic_year,
                    ]);
                }

                // Perform copy
                $copiedCount = $this->ClassRoutines->copyRoutine(
                    $sourceClass->id,
                    $targetClass->id,
                    $targetClass->academic_year,
                    $copyTeachers
                );

                if ($copiedCount > 0) {
                    $this->Flash->success(__('Successfully copied {0} routine entries to {1}.', $copiedCount, $targetClass->display_name));
                    return $this->redirect(['action' => 'edit', $targetClassId]);
                } else {
                    $this->Flash->warning(__('No entries were copied. Check if periods exist for the target academic year.'));
                }
            }
        }

        $this->set(compact('sourceClass', 'sourceRoutineCount', 'classesGrouped', 'academicYears'));
    }

    /**
     * AJAX endpoint to check for teacher conflicts
     */
    public function checkConflict()
    {
        $this->request->allowMethod(['get', 'post']);
        $this->viewBuilder()->setClassName('Json');

        $teacherId = (int) $this->request->getQuery('teacher_id');
        $periodId = (int) $this->request->getQuery('period_id');
        $dayOfWeek = (int) $this->request->getQuery('day_of_week');
        $academicYear = $this->request->getQuery('academic_year');
        $excludeClassId = $this->request->getQuery('exclude_class_id');

        if (!$teacherId || !$periodId || !$dayOfWeek || !$academicYear) {
            $this->set([
                'success' => false,
                'hasConflict' => false,
                'message' => __('Missing required parameters'),
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'hasConflict', 'message']);
            return;
        }

        $conflicts = $this->ClassRoutines->findConflicts(
            $teacherId,
            $periodId,
            $dayOfWeek,
            $academicYear,
            $excludeClassId ? (int) $excludeClassId : null
        );

        $conflictDetails = [];
        foreach ($conflicts as $conflict) {
            $conflictDetails[] = [
                'class' => $conflict->class->name . ($conflict->class->section ? ' - ' . $conflict->class->section : ''),
                'subject' => $conflict->subject ? $conflict->subject->name : __('No subject'),
                'period' => $conflict->period->name,
            ];
        }

        $this->set([
            'success' => true,
            'hasConflict' => !empty($conflicts),
            'conflicts' => $conflictDetails,
            'message' => !empty($conflicts)
                ? __('Teacher is already assigned to {0} at this time', $conflictDetails[0]['class'])
                : '',
        ]);
        $this->viewBuilder()->setOption('serialize', ['success', 'hasConflict', 'conflicts', 'message']);
    }

    private function getAcademicYears(): array
    {
        $currentYear = (int) date('Y');
        $years = [];
        for ($i = -1; $i < 3; $i++) {
            $year = $currentYear + $i;
            $key = $year . '-' . ($year + 1);
            $years[$key] = $key;
        }
        return $years;
    }

    private function getCurrentAcademicYear(): string
    {
        $month = (int) date('n');
        $year = (int) date('Y');

        // Academic year starts in August
        if ($month >= 8) {
            return $year . '-' . ($year + 1);
        }
        return ($year - 1) . '-' . $year;
    }
}
