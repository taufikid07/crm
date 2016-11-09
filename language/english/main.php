<?php
// $Id: main.php, 2008/03/03 Fujicon Priangan Perdana, Inc.
// Text lable for Daily Report Module
// English version

define('_DR_ACT_REPORT', 'Action Reports');
define('_DR_ACTION', 'Action');
define('_DR_STATUS', 'Status');
define('_DR_NOTE', 'Note');
define('_DR_ACT_LIST', 'Activity List');
define('_DR_NAME', 'Name');
define('_DR_YEAR', 'Year');
define('_DR_MONTH', 'Month');
define('_DR_SHOW', 'Show');
define('_DR_PAGE', 'Page');
define('_DR_IN', 'In');
define('_DR_OUT', 'Out');
define('_DR_TIME_CARD', 'Time Card');
define('_DR_DATE', 'Date');
define('_DR_DAY', 'Day');
define('_DR_HOLIDAY', 'Holiday');
define('_DR_ACTIVITY', 'Activity');
define('_DR_WORK_TIME', 'Work Time');
define('_DR_TOTAL', 'Total');
define('_DR_INPUT', 'Input');
define('_DR_PROJECT', 'Project');
define('_DR_LEVEL', 'Level');
define('_DR_REPORT', 'Report');
define('_DR_TIME', 'Time');
define('_DR_UNTIL', 'until');
define('_DR_NEXT_DAY_TASK', 'Next Day Task');
define('_DR_SEND_MAIL_DAYS', 'Send Mail This Days');
define('_DR_SEND_MAIL_CHECK', 'Check for send mail');
define('_DR_CONTINUE', 'Continue Until');
define('_DR_CONTINUE_2', 'Continue');
define('_DR_FINISH', 'Finish');
define('_DR_NONE', 'None');
define('_DR_ANOTHER', 'Another Project');
define('_DR_PROGRESS', 'Progress');
define('_DR_VIEW_REPORT', 'View Report');
define('_DR_DAILY_REPORT', 'Daily Report');
define('_DR_MONTHLY_REPORT', 'Monthly Report');
define('_DR_ADD', 'Add');
define('_DR_UPDATE', 'Update');
define('_DR_EDIT', 'Edit');
define('_DR_PRINT_PREVIEW', 'Print Preview');
define('_DR_IMG_DAYS', 'Upload Image This Days');
define('_DR_IMG_MULTI_DAYS', 'Multiple Upload');

// Text lable for Project Manager on Daily Report Control Panel
define('_DR_PRO_DETAIL', 'Detail Project and Personnel');
define('_DR_PRO_RATE', 'Personnel Rates');
define('_DR_PRO_LIST', 'Management Project List');
define('_DR_PRO_NAME', 'Project Name');
define('_DR_SELECT_ALL', 'Select All');
define('_DR_PROJECT_EMPTY', 'Project description is empty.');
define('_DR_ACTIVITY_EMPTY', 'Project activity is empty.');

define('_DR_DATE_EMPTY', 'Holiday date is empty.');
define('_DR_DATE_NaN', 'Fill holiday date with date format (1-31).');
define('_DR_DATE_MAX', 'Maximum date for this month is ');
define('_DR_DESC_EMPTY', 'Holiday description is empty.');

define('_DR_ADD_PRO', 'Add Project: ');
define('_DR_UPD_PRO', 'Update Project: ');
define('_DR_ADD_ACT', 'Add Activity: ');
define('_DR_UPD_ACT', 'Update Activity: ');
define('_DR_ADD_DAILY', 'Add Daily Report: ');
define('_DR_UPD_DAILY', 'Update Daily Report: ');

define('_DR_DEL_CONFIRM', 'Are you sure want to delete ? ');

define('_DR_ADD_HOLY', 'Add holiday: ');
define('_DR_UPD_HOLY', 'Update holiday: ');
define('_DR_DEL_HOLY', 'Delete holiday: ');

define('_DR_STATUS_SUCCESS', 'Success');
define('_DR_STATUS_FALSE', 'Failure');

// Text label for Time Card submodule
define('_DR_TC_CODE', 'Code');
define('_DR_TC_PERSONAL', 'Personal Time Card');
define('_DR_TC_RECAPITULATION', 'Time Card Recapitulation');
define('_DR_TC_ATTENDANCE', 'Attendance');
define('_DR_TC_OVERTIME', 'Daytime Overtime');
define('_DR_TC_ATT_HOLIDAY', 'Attendance on Holiday');
define('_DR_TC_FREQUENCY', 'Frequency');
define('_DR_TC_DURATION', 'Duration');
define('_DR_TC_DAY', 'Day(s)');
define('_DR_TC_HOUR', 'HOUR(s)');
define('_DR_TC_TIME', 'time(s)');
define('_DR_TC_ABSENT', 'Absent');
define('_DR_TC_NIGHT_DUTY', 'Night Duty');
define('_DR_TC_OVERDUE', 'Overdue');
define('_DR_TC_GOBACK_EARLIER', 'Go home earlier');
define('_DR_TC_NOTE', 'Note');

//new

define('DR_HOLIDAY_THIS_MONTH', 'Holiday this month');
define('_DR_ID', 'ID');
define('_DR_CODE', 'Code');
define('_DR_OLD', 'Old');
define('_DR_NEW', 'New');
define('_DR_EDITOR', 'Editor');
define('_DR_MAN_DAY', 'Man Day');
define('_DR_OVERTIME', 'Overtime');
define('_DR_EVENTIDE', 'Eventide');
define('_DR_NIGHT', 'Night');
define('_DR_SEAL', 'Seal');

define('_DR_SUM_DAY_OVERTIME', 'Daytime Overtime OVERTIME HOUR(s) SUM (h1.50) =');
define('_DR_SUM_NIGHT_OVERTIME', 'Night Duty 	OVERTIME HOUR(s) SUM (h2.00) =');
define('_DR_MAN_PERDAY', 'MAN PER DAY (8h/DAY) =');

define('_DR_JOB_DETAIL', 'Job Detail');
define('_DR_DEADLINE', 'Deadline');	
define('_DR_TODAY', 'Today');
define('_DR_YESTERDAY', 'Yesterday');
define('_DR_DISPLAY', 'Display');
define('_DR_NO_DATA', "There wasn't data to be show.");

//////////////////////////////////// Message //////////////////////////////////////////

// for Daily Report

define('_MSG_NOTE_BLANK', 'Please fill the note with your job description.');
define('_MSG_TASK_CHECKED', 'Please check the next day task.');
define('_MSG_PROGRESS_BLANK', 'Please fill the progress.');
define('_MSG_PROGRES_NOT_NUMBER', 'Please fill the progress with number (1-100).');

define('_MSG_SAVE_PROJECT_SUCCESS', 'Save New Project Successfully!');
define('_MSG_SAVE_PROJECT_FAIL', 'Save New Project Failure!');
define('_MSG_UPD_PROJECT_SUCCESS', 'Update Project Successfully!');
define('_MSG_UPD_PROJECT_FAIL', 'Update Project Failure!');

define('_MSG_SAVE_ACTIVITY_SUCCESS', 'Save New Activity Successfully!');
define('_MSG_SAVE_ACTIVITY_FAIL', 'Save New Activity Failure!');
define('_MSG_UPD_ACTIVITY_SUCCESS', 'Update Activity Successfully!');
define('_MSG_UPD_ACTIVITY_FAIL', 'Update Activity Failure!');

define('_MSG_DEL_TC_SUCCESS', 'Delete Time Card Successfully!');
define('_MSG_DEL_TC_FAIL', 'Delete Time Card Failure!');
define('_MSG_UPD_TC_SUCCESS', 'Update Time Card Successfully!');
define('_MSG_UPD_TC_FAIL', 'Update Time Card Failure!');

define('_MSG_SAVE_HOLIDAY_SUCCESS', 'Save New Holiday Successfully!');
define('_MSG_SAVE_HOLIDAY_FAIL', 'Save New Holiday Failure!');
define('_MSG_UPD_HOLIDAY_SUCCESS', 'Update Holiday Successfully!');
define('_MSG_UPD_HOLIDAY_FAIL', 'Update Holiday Failure!');
define('_MSG_DEL_HOLIDAY_SUCCESS', 'Delete Holiday Successfully!');
define('_MSG_DEL_HOLIDAY_FAIL', 'Delete Holiday Failure!');

define('_MSG_SAVE_DAILY_SUCCESS', 'Save New Schedule Successfully!');
define('_MSG_SAVE_DAILY_FAIL', 'Save New Schedule Failure!');
define('_MSG_UPD_DAILY_SUCCESS', 'Update Schedule Successfully!');
define('_MSG_UPD_DAILY_FAIL', 'Update Schedule Failure!');
define('_MSG_DEL_DAILY_SUCCESS', 'Delete Schedule Successfully!');
define('_MSG_DEL_DAILY_FAIL', 'Delete Schedule Failure!');
define('_MSG_NO_PERMISSION', 'You don\'t have permission to use TimeCard.');

define('_MSG_DEL_CONFIRM_DAILY', 'Are you sure you want to delete this schedule?');
define('_MSG_HAS_BEEN_ABSENT', 'sorry your time card data is already in.');

define('_MSG_SEARCH_DATA_EMPTY', 'Data that your searching for is not available.');

define('_DR_MD', 'MD');
define('_DR_MONTHLY', 'Monthly');
define('_DR_ALL', 'All');
define('_DR_SHOW_CART', 'Show Cart');
define('_DR_PERIOD', 'Period');
define('_DR_PRINT_AT', 'Printed at %s');
define('_DR_WORK_REPORT', 'Work Report');

define('_DATESTRING_TC', 'Y/m/d H:i');

define('_ALL_USER', 'All User');
define('_VIEW_USER', 'User View');
define('_USING_USER', 'User Operating');

define('_DR_MODE_1', 'Mode 1');
define('_DR_MODE_2', 'Mode 2');
?>