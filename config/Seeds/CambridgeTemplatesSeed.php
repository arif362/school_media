<?php
/**
 * Cambridge Curriculum Templates Seed
 *
 * Creates default Cambridge curriculum templates for Primary,
 * Lower Secondary, and IGCSE stages with recommended period allocations.
 *
 * @created 2026-05-15
 * @author Arif
 */
declare(strict_types=1);

use Migrations\AbstractSeed;

class CambridgeTemplatesSeed extends AbstractSeed
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        // Get subject IDs by code
        $connection = $this->getAdapter()->getConnection();
        $subjects = [];
        $result = $connection->execute('SELECT id, code FROM subjects');
        while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            $subjects[$row['code']] = $row['id'];
        }

        // Seed Routine Templates
        $templatesData = [
            // Primary Stage Templates
            [
                'name' => 'Cambridge Primary - Standard',
                'cambridge_stage' => 'primary',
                'description' => 'Standard Cambridge Primary curriculum (Grades 1-6) with balanced subject distribution across 30 periods per week.',
                'is_active' => true,
                'created' => $now,
                'modified' => $now,
            ],
            [
                'name' => 'Cambridge Primary - Extended',
                'cambridge_stage' => 'primary',
                'description' => 'Extended Cambridge Primary curriculum with additional language and STEM focus (35 periods per week).',
                'is_active' => true,
                'created' => $now,
                'modified' => $now,
            ],

            // Lower Secondary Stage Templates
            [
                'name' => 'Cambridge Lower Secondary - Core',
                'cambridge_stage' => 'lower_secondary',
                'description' => 'Core Cambridge Lower Secondary curriculum (Grades 7-9) focusing on essential subjects.',
                'is_active' => true,
                'created' => $now,
                'modified' => $now,
            ],
            [
                'name' => 'Cambridge Lower Secondary - Science Track',
                'cambridge_stage' => 'lower_secondary',
                'description' => 'Science-focused Lower Secondary track with enhanced Physics, Chemistry, and Biology allocation.',
                'is_active' => true,
                'created' => $now,
                'modified' => $now,
            ],

            // IGCSE Stage Templates
            [
                'name' => 'Cambridge IGCSE - Sciences',
                'cambridge_stage' => 'igcse',
                'description' => 'IGCSE curriculum (Grades 10-11) with focus on separate sciences (Physics, Chemistry, Biology).',
                'is_active' => true,
                'created' => $now,
                'modified' => $now,
            ],
            [
                'name' => 'Cambridge IGCSE - Combined Science',
                'cambridge_stage' => 'igcse',
                'description' => 'IGCSE curriculum with Combined Science pathway for broader subject coverage.',
                'is_active' => true,
                'created' => $now,
                'modified' => $now,
            ],
            [
                'name' => 'Cambridge IGCSE - Business & Economics',
                'cambridge_stage' => 'igcse',
                'description' => 'IGCSE curriculum with emphasis on Business Studies and Economics.',
                'is_active' => true,
                'created' => $now,
                'modified' => $now,
            ],
        ];

        $templatesTable = $this->table('routine_templates');
        $templatesTable->insert($templatesData)->save();

        // Get inserted template IDs
        $templates = [];
        $result = $connection->execute('SELECT id, name FROM routine_templates');
        while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            $templates[$row['name']] = $row['id'];
        }

        // Seed Template Items
        $templateItems = [];

        // Cambridge Primary - Standard (30 periods/week)
        $primaryStandard = $templates['Cambridge Primary - Standard'] ?? null;
        if ($primaryStandard) {
            $templateItems = array_merge($templateItems, [
                ['routine_template_id' => $primaryStandard, 'subject_id' => $subjects['ENG-101'] ?? null, 'periods_per_week' => 6, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $primaryStandard, 'subject_id' => $subjects['MATH-101'] ?? null, 'periods_per_week' => 6, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $primaryStandard, 'subject_id' => $subjects['SCI-101'] ?? null, 'periods_per_week' => 4, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $primaryStandard, 'subject_id' => $subjects['GEO-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $primaryStandard, 'subject_id' => $subjects['HIST-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $primaryStandard, 'subject_id' => $subjects['ART-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $primaryStandard, 'subject_id' => $subjects['MUS-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $primaryStandard, 'subject_id' => $subjects['PE-101'] ?? null, 'periods_per_week' => 3, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $primaryStandard, 'subject_id' => $subjects['ISL-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $primaryStandard, 'subject_id' => $subjects['ARB-101'] ?? null, 'periods_per_week' => 1, 'is_required' => false, 'created' => $now, 'modified' => $now],
            ]);
        }

        // Cambridge Primary - Extended (35 periods/week)
        $primaryExtended = $templates['Cambridge Primary - Extended'] ?? null;
        if ($primaryExtended) {
            $templateItems = array_merge($templateItems, [
                ['routine_template_id' => $primaryExtended, 'subject_id' => $subjects['ENG-101'] ?? null, 'periods_per_week' => 7, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $primaryExtended, 'subject_id' => $subjects['MATH-101'] ?? null, 'periods_per_week' => 7, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $primaryExtended, 'subject_id' => $subjects['SCI-101'] ?? null, 'periods_per_week' => 5, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $primaryExtended, 'subject_id' => $subjects['CS-101'] ?? null, 'periods_per_week' => 2, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $primaryExtended, 'subject_id' => $subjects['GEO-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $primaryExtended, 'subject_id' => $subjects['HIST-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $primaryExtended, 'subject_id' => $subjects['ART-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $primaryExtended, 'subject_id' => $subjects['PE-101'] ?? null, 'periods_per_week' => 3, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $primaryExtended, 'subject_id' => $subjects['FRE-101'] ?? null, 'periods_per_week' => 3, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $primaryExtended, 'subject_id' => $subjects['ISL-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
            ]);
        }

        // Cambridge Lower Secondary - Core (32 periods/week)
        $lsCore = $templates['Cambridge Lower Secondary - Core'] ?? null;
        if ($lsCore) {
            $templateItems = array_merge($templateItems, [
                ['routine_template_id' => $lsCore, 'subject_id' => $subjects['ENG-101'] ?? null, 'periods_per_week' => 5, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $lsCore, 'subject_id' => $subjects['MATH-101'] ?? null, 'periods_per_week' => 5, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $lsCore, 'subject_id' => $subjects['SCI-101'] ?? null, 'periods_per_week' => 6, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $lsCore, 'subject_id' => $subjects['GEO-101'] ?? null, 'periods_per_week' => 3, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $lsCore, 'subject_id' => $subjects['HIST-101'] ?? null, 'periods_per_week' => 3, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $lsCore, 'subject_id' => $subjects['CS-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $lsCore, 'subject_id' => $subjects['ART-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $lsCore, 'subject_id' => $subjects['PE-101'] ?? null, 'periods_per_week' => 2, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $lsCore, 'subject_id' => $subjects['FRE-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $lsCore, 'subject_id' => $subjects['ISL-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
            ]);
        }

        // Cambridge Lower Secondary - Science Track (32 periods/week)
        $lsScience = $templates['Cambridge Lower Secondary - Science Track'] ?? null;
        if ($lsScience) {
            $templateItems = array_merge($templateItems, [
                ['routine_template_id' => $lsScience, 'subject_id' => $subjects['ENG-101'] ?? null, 'periods_per_week' => 4, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $lsScience, 'subject_id' => $subjects['MATH-101'] ?? null, 'periods_per_week' => 5, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $lsScience, 'subject_id' => $subjects['PHY-101'] ?? null, 'periods_per_week' => 3, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $lsScience, 'subject_id' => $subjects['CHEM-101'] ?? null, 'periods_per_week' => 3, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $lsScience, 'subject_id' => $subjects['BIO-101'] ?? null, 'periods_per_week' => 3, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $lsScience, 'subject_id' => $subjects['CS-101'] ?? null, 'periods_per_week' => 3, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $lsScience, 'subject_id' => $subjects['GEO-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $lsScience, 'subject_id' => $subjects['PE-101'] ?? null, 'periods_per_week' => 2, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $lsScience, 'subject_id' => $subjects['FRE-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $lsScience, 'subject_id' => $subjects['ISL-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
            ]);
        }

        // Cambridge IGCSE - Sciences (35 periods/week)
        $igcseSciences = $templates['Cambridge IGCSE - Sciences'] ?? null;
        if ($igcseSciences) {
            $templateItems = array_merge($templateItems, [
                ['routine_template_id' => $igcseSciences, 'subject_id' => $subjects['ENG-101'] ?? null, 'periods_per_week' => 5, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseSciences, 'subject_id' => $subjects['MATH-101'] ?? null, 'periods_per_week' => 5, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseSciences, 'subject_id' => $subjects['PHY-101'] ?? null, 'periods_per_week' => 4, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseSciences, 'subject_id' => $subjects['CHEM-101'] ?? null, 'periods_per_week' => 4, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseSciences, 'subject_id' => $subjects['BIO-101'] ?? null, 'periods_per_week' => 4, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseSciences, 'subject_id' => $subjects['CS-101'] ?? null, 'periods_per_week' => 4, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseSciences, 'subject_id' => $subjects['GEO-101'] ?? null, 'periods_per_week' => 3, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseSciences, 'subject_id' => $subjects['PE-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseSciences, 'subject_id' => $subjects['FRE-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseSciences, 'subject_id' => $subjects['ISL-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
            ]);
        }

        // Cambridge IGCSE - Combined Science (35 periods/week)
        $igcseCombined = $templates['Cambridge IGCSE - Combined Science'] ?? null;
        if ($igcseCombined) {
            $templateItems = array_merge($templateItems, [
                ['routine_template_id' => $igcseCombined, 'subject_id' => $subjects['ENG-101'] ?? null, 'periods_per_week' => 5, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseCombined, 'subject_id' => $subjects['MATH-101'] ?? null, 'periods_per_week' => 5, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseCombined, 'subject_id' => $subjects['SCI-101'] ?? null, 'periods_per_week' => 8, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseCombined, 'subject_id' => $subjects['GEO-101'] ?? null, 'periods_per_week' => 3, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseCombined, 'subject_id' => $subjects['HIST-101'] ?? null, 'periods_per_week' => 3, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseCombined, 'subject_id' => $subjects['CS-101'] ?? null, 'periods_per_week' => 3, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseCombined, 'subject_id' => $subjects['ART-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseCombined, 'subject_id' => $subjects['PE-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseCombined, 'subject_id' => $subjects['FRE-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseCombined, 'subject_id' => $subjects['ISL-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
            ]);
        }

        // Cambridge IGCSE - Business & Economics (35 periods/week)
        $igcseBusiness = $templates['Cambridge IGCSE - Business & Economics'] ?? null;
        if ($igcseBusiness) {
            $templateItems = array_merge($templateItems, [
                ['routine_template_id' => $igcseBusiness, 'subject_id' => $subjects['ENG-101'] ?? null, 'periods_per_week' => 5, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseBusiness, 'subject_id' => $subjects['MATH-101'] ?? null, 'periods_per_week' => 5, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseBusiness, 'subject_id' => $subjects['SCI-101'] ?? null, 'periods_per_week' => 5, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseBusiness, 'subject_id' => $subjects['ECON-101'] ?? null, 'periods_per_week' => 4, 'is_required' => true, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseBusiness, 'subject_id' => $subjects['HIST-101'] ?? null, 'periods_per_week' => 3, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseBusiness, 'subject_id' => $subjects['GEO-101'] ?? null, 'periods_per_week' => 3, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseBusiness, 'subject_id' => $subjects['CS-101'] ?? null, 'periods_per_week' => 3, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseBusiness, 'subject_id' => $subjects['PE-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseBusiness, 'subject_id' => $subjects['FRE-101'] ?? null, 'periods_per_week' => 3, 'is_required' => false, 'created' => $now, 'modified' => $now],
                ['routine_template_id' => $igcseBusiness, 'subject_id' => $subjects['ISL-101'] ?? null, 'periods_per_week' => 2, 'is_required' => false, 'created' => $now, 'modified' => $now],
            ]);
        }

        // Filter out items with null subject_id
        $templateItems = array_filter($templateItems, function ($item) {
            return $item['subject_id'] !== null;
        });

        if (!empty($templateItems)) {
            $itemsTable = $this->table('routine_template_items');
            $itemsTable->insert(array_values($templateItems))->save();
        }
    }
}
