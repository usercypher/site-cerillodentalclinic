<?php
// _…set_unit.php

/**
 * ------------------------------------------------------------------------
 * Model Units with Dependencies
 * ------------------------------------------------------------------------
 */

$group = [
    'args_prepend' => ['App', 'Lib_Database'],
    'load_prepend' => ['Lib_DatabaseHelper']
];
$app->groupUnit($group, 'Repo_User');
$app->groupUnit($group, 'Repo_Schedule');
$app->groupUnit($group, 'Repo_Service');
$app->groupUnit($group, 'Repo_Chatbot');
$app->groupUnit($group, 'Repo_Appointment');
$app->groupUnit($group, 'Repo_Message');
$app->groupUnit($group, 'Repo_Api');

/**
 * ------------------------------------------------------------------------
 * Pipe
 * ------------------------------------------------------------------------
 */
$app->setUnit('Pipe_Init', array('args' => array('App', 'Lib_Session')));


/**
 * ------------------------------------------------------------------------
 * Pipe Lock
 * ------------------------------------------------------------------------
 */

$group = [
    'args_prepend' => ['App']
];
$app->groupUnit($group, 'Pipe_LockAcquire');
$app->groupUnit($group, 'Pipe_LockRelease');

/**
 * ------------------------------------------------------------------------
 * Pipe Auth
 * ------------------------------------------------------------------------
 */

$group = [
    'args_prepend' => ['App', 'Lib_Session']
];
$app->groupUnit($group, 'Pipe_NotAuth');
$app->groupUnit($group, 'Pipe_Auth_Admin');
$app->groupUnit($group, 'Pipe_Auth_Doctor');
$app->groupUnit($group, 'Pipe_Auth_User');
$app->groupUnit($group, 'Pipe_Auth_Staff');
$app->groupUnit($group, 'Pipe_Auth');

/**
 * ------------------------------------------------------------------------
 * Pipe HomePage
 * ------------------------------------------------------------------------
 */

$app->setUnit('Pipe_HomePage', ['args' => ['App', 'Lib_Session', 'Repo_Service', 'Repo_User']]);

/**
 * ------------------------------------------------------------------------
 * Pipe User
 * ------------------------------------------------------------------------
 */

$group = [
    'args_prepend' => ['App', 'Lib_Session']
];
$app->groupUnit($group, 'Pipe_User_Store', ['args' => ['Repo_User']]);
$app->groupUnit($group, 'Pipe_User_Update', ['args' => ['Repo_User']]);
$app->groupUnit($group, 'Pipe_User_Delete', ['args' => ['Repo_User']]);
$app->groupUnit($group, 'Pipe_User_DeleteBatch', ['args' => ['Repo_User']]);
$app->groupUnit($group, 'Pipe_User_LoginHandler', ['args' => ['Repo_User']]);
$app->groupUnit($group, 'Pipe_User_Profile', ['args' => ['Repo_User']]);
$app->groupUnit($group, 'Pipe_User_Settings');
$app->groupUnit($group, 'Pipe_User_Logout');
$app->groupUnit($group, 'Pipe_User_Dashboard', ['args' => ['Repo_Appointment', 'Repo_Message']]);
$app->groupUnit($group, 'Pipe_User_ForgotPassword', ['args' => ['Repo_User', 'Lib_GoogleApiGmail']]);
$app->groupUnit($group, 'Pipe_User_Register', ['args' => ['Repo_User', 'Lib_GoogleApiGmail']]);
$app->groupUnit($group, 'Pipe_User_RegisterView');
$app->groupUnit($group, 'Pipe_User_OtpStoreData');
$app->groupUnit($group, 'Pipe_User_ResetPassword', ['args' => ['Repo_User']]);
$app->groupUnit($group, 'Pipe_User_SearchTable', ['args' => ['Repo_User']]);

/**
 * ------------------------------------------------------------------------
 * Pipe Admin
 * ------------------------------------------------------------------------
 */

$app->setUnit('Pipe_Admin_Generate', ['args' => ['Lib_Session', 'Repo_User']]);

$group = [
    'args_prepend' => ['App', 'Lib_Session']
];
$app->groupUnit($group, 'Pipe_Admin_Service_Index', ['args' => ['Repo_Service']]);
$app->groupUnit($group, 'Pipe_Admin_User_Index');
$app->groupUnit($group, 'Pipe_Admin_Chatbot_Index', ['args' => ['Repo_Chatbot']]);
$app->groupUnit($group, 'Pipe_Admin_Api_Index', ['args' => ['Repo_Api']]);
$app->groupUnit($group, 'Pipe_Admin_Dashboard', ['args' => ['Repo_Appointment', 'Repo_Message', 'Repo_Schedule']]);
$app->groupUnit($group, 'Pipe_Admin_Schedule_Index', ['args' => ['Repo_User']]);
$app->groupUnit($group, 'Pipe_Admin_Schedule_View', ['args' => ['Repo_Schedule', 'Repo_User']]);
$app->groupUnit($group, 'Pipe_Admin_Schedule_Edit', ['args' => ['Repo_Schedule', 'Repo_Appointment', 'Repo_User']]);
/**
 * ------------------------------------------------------------------------
 * Pipe Doctor
 * ------------------------------------------------------------------------
 */


$group = [
    'args_prepend' => ['App', 'Lib_Session']
];
$app->groupUnit($group, 'Pipe_Doctor_Dashboard', ['args' => ['Repo_Appointment', 'Repo_Message', 'Repo_Schedule']]);

/**
 * ------------------------------------------------------------------------
 * Pipe Staff
 * ------------------------------------------------------------------------
 */


$group = [
    'args_prepend' => ['App', 'Lib_Session']
];
$app->groupUnit($group, 'Pipe_Staff_Dashboard', ['args' => ['Repo_Message']]);

/**
 * ------------------------------------------------------------------------
 * Pipe Schedule
 * ------------------------------------------------------------------------
 */

$group = [
    'args_prepend' => ['App', 'Lib_Session']
];
$app->groupUnit($group, 'Pipe_Schedule_Store', ['args' => ['Repo_Schedule']]);
$app->groupUnit($group, 'Pipe_Schedule_Delete', ['args' => ['Repo_Schedule', 'Repo_Appointment', 'Repo_Message']]);

/**
 * ------------------------------------------------------------------------
 * Pipe Service
 * ------------------------------------------------------------------------
 */

$group = [
    'args_prepend' => ['App', 'Lib_Session']
];
$app->groupUnit($group, 'Pipe_Service_Store', ['args' => ['Repo_Service']]);
$app->groupUnit($group, 'Pipe_Service_Delete', ['args' => ['Repo_Service']]);
$app->groupUnit($group, 'Pipe_Service_Update', ['args' => ['Repo_Service']]);

/**
 * ------------------------------------------------------------------------
 * Pipe Chatbot
 * ------------------------------------------------------------------------
 */

$group = [
    'args_prepend' => ['App', 'Lib_Session']
];
$app->groupUnit($group, 'Pipe_Chatbot_Store', ['args' => ['Repo_Chatbot']]);
$app->groupUnit($group, 'Pipe_Chatbot_Delete', ['args' => ['Repo_Chatbot']]);
$app->groupUnit($group, 'Pipe_Chatbot_Update', ['args' => ['Repo_Chatbot']]);
$app->groupUnit($group, 'Pipe_Chatbot_Query', ['args' => ['Repo_Chatbot', 'Repo_Service', 'Repo_User']]);

/**
 * ------------------------------------------------------------------------
 * Pipe Appointment
 * ------------------------------------------------------------------------
 */

$group = [
    'args_prepend' => ['App', 'Lib_Session']
];
$app->groupUnit($group, 'Pipe_Appointment_Index', ['args' => ['Repo_Schedule']]);
$app->groupUnit($group, 'Pipe_Appointment_Create', ['args' => ['Repo_Appointment', 'Repo_Service', 'Repo_Schedule', 'Repo_User']]);
$app->groupUnit($group, 'Pipe_Appointment_Store', ['args' => ['Repo_Appointment']]);
$app->groupUnit($group, 'Pipe_Appointment_Delete', ['args' => ['Repo_Appointment']]);
$app->groupUnit($group, 'Pipe_Appointment_StoreNotRegular', ['args' => ['Repo_Appointment', 'Repo_Message']]);
$app->groupUnit($group, 'Pipe_Appointment_Cancel', ['args' => ['Repo_Appointment' , 'Repo_Message']]);
$app->groupUnit($group, 'Pipe_Appointment_Update', ['args' => ['Repo_Appointment', 'Repo_Message']]);
$app->groupUnit($group, 'Pipe_Appointment_SearchTable', ['args' => ['Repo_Appointment']]);
/**
 * ------------------------------------------------------------------------
 * Pipe Message
 * ------------------------------------------------------------------------
 */

$group = [
    'args_prepend' => ['App', 'Lib_Session']
];
$app->groupUnit($group, 'Pipe_Message_Notification_Count', ['args' => ['Repo_Message']]);
$app->groupUnit($group, 'Pipe_Message_Notification_Index', ['args' => ['Repo_Message']]);
$app->groupUnit($group, 'Pipe_Message_Notification_List', ['args' => ['Repo_Message']]);
$app->groupUnit($group, 'Pipe_Message_Notification_View', ['args' => ['Repo_Message']]);
$app->groupUnit($group, 'Pipe_Message_Delete', ['args' => ['Repo_Message']]);


/**
 * ------------------------------------------------------------------------
 * Pipe Api
 * ------------------------------------------------------------------------
 */

$group = [
    'args_prepend' => ['App', 'Lib_Session']
];
$app->groupUnit($group, 'Pipe_Api_Store', ['args' => ['Repo_Api']]);
$app->groupUnit($group, 'Pipe_Api_Delete', ['args' => ['Repo_Api']]);
$app->groupUnit($group, 'Pipe_Api_Update', ['args' => ['Repo_Api']]);
$app->groupUnit($group, 'Pipe_Api_Auth', ['args' => ['Repo_Api']]);
$app->groupUnit($group, 'Pipe_Api_ReminderSend', ['args' => ['Lib_GoogleApiGmail', 'Repo_Message', 'Repo_Appointment']]);
