<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

class AttendanceSeed extends AbstractSeed
{
    public function getDependencies(): array
    {
        return [
            'ClassesSeed',
        ];
    }

    public function run(): void
    {
        // First, create student class enrollments
        $studentClasses = [];

        // Get student user ID (assuming ID 3 is a student based on typical seed structure)
        // We'll enroll the student in Grade 5 (class_id 7)
        $studentClasses[] = [
            'student_id' => 3,
            'class_id' => 7, // Grade 5
            'roll_number' => '001',
            'enrolled_date' => date('Y-01-15'),
            'status' => 'active',
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s'),
        ];

        $studentClassesTable = $this->table('student_classes');
        $studentClassesTable->insert($studentClasses)->save();

        // Create attendance records for the student
        $attendanceData = [];
        $statuses = ['present', 'present', 'present', 'present', 'present', 'present', 'present', 'late', 'absent', 'excused'];
        $currentYear = (int)date('Y');
        $currentMonth = (int)date('m');

        // Generate attendance for the past 3 months
        for ($monthOffset = 2; $monthOffset >= 0; $monthOffset--) {
            $month = $currentMonth - $monthOffset;
            $year = $currentYear;

            if ($month < 1) {
                $month += 12;
                $year--;
            }

            // Get number of days in the month
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $maxDay = ($monthOffset === 0) ? min((int)date('d'), $daysInMonth) : $daysInMonth;

            for ($day = 1; $day <= $maxDay; $day++) {
                $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                $dayOfWeek = date('N', strtotime($date));

                // Skip weekends (Saturday = 6, Sunday = 7)
                if ($dayOfWeek >= 6) {
                    continue;
                }

                // Random status with weight towards present
                $statusIndex = rand(0, count($statuses) - 1);
                $status = $statuses[$statusIndex];

                $checkIn = null;
                $checkOut = null;
                $remarks = null;

                if ($status !== 'absent') {
                    // Generate check-in time (between 7:30 and 8:30)
                    $checkInHour = 7;
                    $checkInMin = rand(30, 59);

                    if ($status === 'late') {
                        $checkInHour = 8;
                        $checkInMin = rand(15, 45);
                        $remarks = 'Late arrival - traffic delay';
                    }

                    $checkIn = sprintf('%02d:%02d:00', $checkInHour, $checkInMin);

                    // Generate check-out time (between 14:00 and 15:00)
                    $checkOut = sprintf('%02d:%02d:00', rand(14, 15), rand(0, 59));

                    if ($status === 'half_day') {
                        $checkOut = sprintf('%02d:%02d:00', 12, rand(0, 30));
                        $remarks = 'Left early - medical appointment';
                    }
                } elseif ($status === 'absent') {
                    $remarks = ['Sick leave', 'Family emergency', 'No reason provided'][rand(0, 2)];
                } elseif ($status === 'excused') {
                    $remarks = ['Medical appointment', 'School event', 'Family function'][rand(0, 2)];
                }

                $attendanceData[] = [
                    'student_id' => 3,
                    'class_id' => 7,
                    'date' => $date,
                    'status' => $status,
                    'check_in_time' => $checkIn,
                    'check_out_time' => $checkOut,
                    'remarks' => $remarks,
                    'marked_by' => 2, // Teacher
                    'created' => date('Y-m-d H:i:s'),
                    'modified' => date('Y-m-d H:i:s'),
                ];
            }
        }

        $attendanceTable = $this->table('attendances');
        $attendanceTable->insert($attendanceData)->save();

        // Update student's class_id
        $this->execute("UPDATE users SET class_id = 7 WHERE id = 3");
    }
}
