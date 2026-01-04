<?php

namespace Database\Seeders;

use App\Models\CorrespondenceCategory;
use App\Models\CorrespondencePriority;
use App\Models\CorrespondenceStatus;
use App\Models\LetterType;
use Illuminate\Database\Seeder;

class CorrespondenceModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedPriorities();
        $this->seedStatuses();
        $this->seedLetterTypes();
        $this->seedCategories();
    }

    private function seedPriorities(): void
    {
        $priorities = [
            ['name' => 'Most Urgent', 'code' => 'MU', 'color' => 'red', 'sla_hours' => 4, 'sequence' => 1],
            ['name' => 'Urgent', 'code' => 'U', 'color' => 'orange', 'sla_hours' => 24, 'sequence' => 2],
            ['name' => 'Priority', 'code' => 'P', 'color' => 'yellow', 'sla_hours' => 72, 'sequence' => 3],
            ['name' => 'Normal', 'code' => 'N', 'color' => 'green', 'sla_hours' => 168, 'sequence' => 4],
        ];

        foreach ($priorities as $priority) {
            CorrespondencePriority::firstOrCreate(
                ['code' => $priority['code']],
                $priority
            );
        }
    }

    private function seedStatuses(): void
    {
        $statuses = [
            // Receipt Statuses
            ['name' => 'Received', 'code' => 'RECEIVED', 'color' => 'blue', 'type' => 'Receipt', 'is_initial' => true, 'sequence' => 1],
            ['name' => 'Under Review', 'code' => 'UNDER_REVIEW', 'color' => 'yellow', 'type' => 'Receipt', 'sequence' => 2],
            ['name' => 'Marked', 'code' => 'MARKED', 'color' => 'purple', 'type' => 'Receipt', 'sequence' => 3],
            ['name' => 'Pending Action', 'code' => 'PENDING_ACTION', 'color' => 'orange', 'type' => 'Receipt', 'sequence' => 4],
            ['name' => 'In Progress', 'code' => 'IN_PROGRESS', 'color' => 'indigo', 'type' => 'Receipt', 'sequence' => 5],
            ['name' => 'Replied', 'code' => 'REPLIED', 'color' => 'green', 'type' => 'Receipt', 'sequence' => 6],
            ['name' => 'Closed', 'code' => 'CLOSED', 'color' => 'gray', 'type' => 'Both', 'is_final' => true, 'sequence' => 7],
            ['name' => 'Archived', 'code' => 'ARCHIVED', 'color' => 'slate', 'type' => 'Both', 'is_final' => true, 'sequence' => 8],

            // Dispatch Statuses
            ['name' => 'Drafted', 'code' => 'DRAFTED', 'color' => 'gray', 'type' => 'Dispatch', 'is_initial' => true, 'sequence' => 1],
            ['name' => 'Pending Approval', 'code' => 'PENDING_APPROVAL', 'color' => 'yellow', 'type' => 'Dispatch', 'sequence' => 2],
            ['name' => 'Approved', 'code' => 'APPROVED', 'color' => 'green', 'type' => 'Dispatch', 'sequence' => 3],
            ['name' => 'Dispatched', 'code' => 'DISPATCHED', 'color' => 'blue', 'type' => 'Dispatch', 'sequence' => 4],
            ['name' => 'Delivered', 'code' => 'DELIVERED', 'color' => 'teal', 'type' => 'Dispatch', 'sequence' => 5],
            ['name' => 'Acknowledged', 'code' => 'ACKNOWLEDGED', 'color' => 'emerald', 'type' => 'Dispatch', 'is_final' => true, 'sequence' => 6],
        ];

        foreach ($statuses as $status) {
            CorrespondenceStatus::firstOrCreate(
                ['code' => $status['code']],
                array_merge(['is_initial' => false, 'is_final' => false], $status)
            );
        }
    }

    private function seedLetterTypes(): void
    {
        $letterTypes = [
            ['name' => 'Letter', 'code' => 'LTR', 'requires_reply' => true, 'default_days_to_reply' => 7],
            ['name' => 'Office Note', 'code' => 'NOTE', 'requires_reply' => false],
            ['name' => 'Memorandum', 'code' => 'MEMO', 'requires_reply' => false],
            ['name' => 'Circular', 'code' => 'CIR', 'requires_reply' => false],
            ['name' => 'Notification', 'code' => 'NTF', 'requires_reply' => false],
            ['name' => 'Fax', 'code' => 'FAX', 'requires_reply' => true, 'default_days_to_reply' => 3],
            ['name' => 'Email', 'code' => 'EMAIL', 'requires_reply' => true, 'default_days_to_reply' => 2],
            ['name' => 'Telex', 'code' => 'TLX', 'requires_reply' => false],
            ['name' => 'D.O. Letter', 'code' => 'DO', 'requires_reply' => true, 'default_days_to_reply' => 5],
            ['name' => 'U.O. Note', 'code' => 'UO', 'requires_reply' => false],
            ['name' => 'Application', 'code' => 'APP', 'requires_reply' => true, 'default_days_to_reply' => 15],
            ['name' => 'Complaint', 'code' => 'CMP', 'requires_reply' => true, 'default_days_to_reply' => 7],
        ];

        foreach ($letterTypes as $type) {
            LetterType::firstOrCreate(
                ['code' => $type['code']],
                array_merge(['requires_reply' => false, 'default_days_to_reply' => null], $type)
            );
        }
    }

    private function seedCategories(): void
    {
        $categories = [
            ['name' => 'Administration', 'code' => 'ADMIN'],
            ['name' => 'Recruitment', 'code' => 'RECRUIT'],
            ['name' => 'Transfers & Postings', 'code' => 'TRANSFER'],
            ['name' => 'Promotions', 'code' => 'PROMO'],
            ['name' => 'Leave & Attendance', 'code' => 'LEAVE'],
            ['name' => 'Training & Development', 'code' => 'TRAINING'],
            ['name' => 'Disciplinary', 'code' => 'DISCIP'],
            ['name' => 'Retirement & Benefits', 'code' => 'RETIRE'],
            ['name' => 'Salary & Allowances', 'code' => 'SALARY'],
            ['name' => 'Medical', 'code' => 'MEDICAL'],
            ['name' => 'Legal', 'code' => 'LEGAL'],
            ['name' => 'Audit & Compliance', 'code' => 'AUDIT'],
            ['name' => 'General', 'code' => 'GENERAL'],
            ['name' => 'Miscellaneous', 'code' => 'MISC'],
        ];

        foreach ($categories as $category) {
            CorrespondenceCategory::firstOrCreate(
                ['code' => $category['code']],
                $category
            );
        }
    }
}
