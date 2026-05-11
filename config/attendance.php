<?php

return [
    'work_start_time' => env('ATTENDANCE_WORK_START_TIME', '08:00'),
    'late_after' => env('ATTENDANCE_LATE_AFTER', '08:00'),
    'work_end_time' => env('ATTENDANCE_WORK_END_TIME', '17:00'),
    'auto_mark_absent_at' => env('ATTENDANCE_AUTO_MARK_ABSENT_AT', '23:55'),
    'working_days' => [1, 2, 3, 4, 5],
    'submission_types' => ['izin', 'sakit'],
    'location_accuracy_tolerance_cap' => env('ATTENDANCE_LOCATION_ACCURACY_TOLERANCE_CAP', 30),
    'attachment_disk' => env('ATTENDANCE_ATTACHMENT_DISK', 'public'),
    'attachment_directory' => env('ATTENDANCE_ATTACHMENT_DIRECTORY', 'attendances/attachments'),
];
