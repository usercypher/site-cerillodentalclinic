<?php
// _.set_route.php

/**
 * ------------------------------------------------------------------------
 * Homepage
 * ------------------------------------------------------------------------
 */

// GET
$group = [
    'pipe_prepend' => ['Pipe_NotAuth', 'Pipe_CsrfGenerate', 'Pipe_HomePage', 'Pipe_OutputCompression'],
];
$app->groupRoute($group, 'GET', '', []);
$app->groupRoute($group, 'GET', 'home', []);

/**
 * ------------------------------------------------------------------------
 * User
 * ------------------------------------------------------------------------
 */

// GET
$app->setRoute('GET', 'user/logout', ['pipe' => ['Pipe_User_Logout']]);

$group = [
    'pipe_append' => ['Pipe_OutputCompression'],
];
$app->groupRoute($group, 'GET', 'dashboard', ['pipe' => ['Pipe_Auth_User', 'Pipe_CsrfGenerate', 'Pipe_User_Dashboard']]);
$app->groupRoute($group, 'GET', 'settings', ['pipe' => ['Pipe_Auth', 'Pipe_CsrfGenerate', 'Pipe_User_Settings']]);
$app->groupRoute($group, 'GET', 'reset-password', ['pipe' => ['Pipe_OtpExist', 'Pipe_CsrfGenerate', 'Pipe_User_ResetPassword']]);
$app->groupRoute($group, 'GET', 'register', ['pipe' => ['Pipe_OtpExist', 'Pipe_CsrfGenerate', 'Pipe_User_RegisterView']]);
// route=profile/:id
$app->groupRoute($group, 'GET', 'profile/:id:?:[0-9]+', ['pipe' => ['Pipe_User_Profile']]);

// POST
$app->setRoute('POST', 'user/search-table.ajax', ['pipe' => ['Pipe_Auth', 'Pipe_User_SearchTable']]);

$group = [
    'pipe_prepend' => ['Pipe_CsrfValidate'],
];
$app->groupRoute($group, 'POST', 'user/login', ['pipe' => ['Pipe_User_LoginHandler']]);
$app->groupRoute($group, 'POST', 'user/store', ['pipe' => ['Pipe_User_Store']]);
$app->groupRoute($group, 'POST', 'user/forgot-password', ['pipe' => ['Pipe_OtpGenerate', 'Pipe_User_ForgotPassword']]);
$app->groupRoute($group, 'POST', 'user/register', ['pipe' => ['Pipe_OtpGenerate', 'Pipe_User_Register']]);
$app->groupRoute($group, 'POST', 'user/otp-update', ['pipe' => ['Pipe_OtpValidate', 'Pipe_User_Update']]);
$app->groupRoute($group, 'POST', 'user/otp-store', ['pipe' => ['Pipe_OtpValidate', 'Pipe_User_OtpStoreData', 'Pipe_User_Store']]);

$group = [
    'pipe_prepend' => ['Pipe_Auth', 'Pipe_CsrfValidate'],
];
$app->groupRoute($group, 'POST', 'user/update', ['pipe' => ['Pipe_User_Update']]);
$app->groupRoute($group, 'POST', 'user/delete', ['pipe' => ['Pipe_User_Delete']]);
$app->groupRoute($group, 'POST', 'user/delete-batch', ['pipe' => ['Pipe_User_DeleteBatch']]);
/**
 * ------------------------------------------------------------------------
 * Admin
 * ------------------------------------------------------------------------
 */

// GET
$app->setRoute('GET', 'admin/generate', ['pipe' => ['Pipe_Admin_Generate']]);

$group = [
    'pipe_prepend' => ['Pipe_Auth_Admin', 'Pipe_CsrfGenerate'],
    'pipe_append' => ['Pipe_OutputCompression'],
];
$app->groupRoute($group, 'GET', 'admin/chatbot', ['pipe' => ['Pipe_Admin_Chatbot_Index']]);
$app->groupRoute($group, 'GET', 'admin/api', ['pipe' => ['Pipe_Admin_Api_Index']]);
$app->groupRoute($group, 'GET', 'admin/service', ['pipe' => ['Pipe_Admin_Service_Index']]);
$app->groupRoute($group, 'GET', 'admin/user', ['pipe' => ['Pipe_Admin_User_Index']]);
$app->groupRoute($group, 'GET', 'admin/dashboard', ['pipe' => ['Pipe_Admin_Dashboard']]);
$app->groupRoute($group, 'GET', 'admin/schedule', ['pipe' => ['Pipe_Admin_Schedule_Index']]);
// route=admin/schedule/:user_id
$app->groupRoute($group, 'GET', 'admin/schedule/:user_id::[0-9]+', ['pipe' => ['Pipe_Admin_Schedule_View']]);
// route=admin/schedule/:user_id/edit/:schedule_id
$app->groupRoute($group, 'GET', 'admin/schedule/:user_id::[0-9]+/edit/:schedule_id::[0-9]+', ['pipe' => ['Pipe_Admin_Schedule_Edit']]);
/**
 * ------------------------------------------------------------------------
 * Doctor
 * ------------------------------------------------------------------------
 */

// GET
$group = [
    'pipe_prepend' => ['Pipe_Auth_Doctor', 'Pipe_CsrfGenerate'],
    'pipe_append' => ['Pipe_OutputCompression'],
];

$app->groupRoute($group, 'GET', 'doctor/appointment', ['pipe' => ['Pipe_Appointment_Index']]);
$app->groupRoute($group, 'GET', 'doctor/dashboard', ['pipe' => ['Pipe_Doctor_Dashboard']]);

/**
 * ------------------------------------------------------------------------
 * Staff
 * ------------------------------------------------------------------------
 */

// GET
$group = [
    'pipe_prepend' => ['Pipe_Auth_Staff', 'Pipe_CsrfGenerate'],
    'pipe_append' => ['Pipe_OutputCompression'],
];


$app->groupRoute($group, 'GET', 'staff/appointment', ['pipe' => ['Pipe_Appointment_Index']]);
$app->groupRoute($group, 'GET', 'staff/dashboard', ['pipe' => ['Pipe_Staff_Dashboard']]);


/**
 * ------------------------------------------------------------------------
 * Schedule
 * ------------------------------------------------------------------------
 */

// POST
$group = [
    'pipe_prepend' => ['Pipe_Auth', 'Pipe_CsrfValidate'],
];
$app->groupRoute($group, 'POST', 'schedule/store', ['pipe' => ['Pipe_Schedule_Store']]);
$app->groupRoute($group, 'POST', 'schedule/delete', ['pipe' => ['Pipe_Schedule_Delete']]);

/**
 * ------------------------------------------------------------------------
 * Service
 * ------------------------------------------------------------------------
 */

// POST
$group = [
    'pipe_prepend' => ['Pipe_Auth', 'Pipe_CsrfValidate'],
];
$app->groupRoute($group, 'POST', 'service/store', ['pipe' => ['Pipe_Service_Store']]);
$app->groupRoute($group, 'POST', 'service/delete', ['pipe' => ['Pipe_Service_Delete']]);
$app->groupRoute($group, 'POST', 'service/update', ['pipe' => ['Pipe_Service_Update']]);

/**
 * ------------------------------------------------------------------------
 * Chatbot
 * ------------------------------------------------------------------------
 */

// POST
$app->setRoute('POST', 'chatbot/query', ['pipe' => ['Pipe_Chatbot_Query']]);

$group = [
    'pipe_prepend' => ['Pipe_Auth', 'Pipe_CsrfValidate'],
];
$app->groupRoute($group, 'POST', 'chatbot/store', ['pipe' => ['Pipe_Chatbot_Store']]);
$app->groupRoute($group, 'POST', 'chatbot/delete', ['pipe' => ['Pipe_Chatbot_Delete']]);
$app->groupRoute($group, 'POST', 'chatbot/update', ['pipe' => ['Pipe_Chatbot_Update']]);


/**
 * ------------------------------------------------------------------------
 * Api
 * ------------------------------------------------------------------------
 */

// POST
$app->setRoute('POST', 'api/reminder-send', ['pipe' => ['Pipe_LockAcquire', 'Pipe_Api_Auth', 'Pipe_Api_ReminderSend', 'Pipe_LockRelease']]);

$group = [
    'pipe_prepend' => ['Pipe_Auth', 'Pipe_CsrfValidate'],
];
$app->groupRoute($group, 'POST', 'api/store', ['pipe' => ['Pipe_Api_Store']]);
$app->groupRoute($group, 'POST', 'api/delete', ['pipe' => ['Pipe_Api_Delete']]);
$app->groupRoute($group, 'POST', 'api/update', ['pipe' => ['Pipe_Api_Update']]);


/**
 * ------------------------------------------------------------------------
 * Appointment
 * ------------------------------------------------------------------------
 */

// GET
$group = [
    'pipe_prepend' => ['Pipe_Auth_User', 'Pipe_CsrfGenerate'],
    'pipe_append' => ['Pipe_OutputCompression'],
];
$app->groupRoute($group, 'GET', 'appointment', ['pipe' => ['Pipe_Appointment_Index']]);
$app->groupRoute($group, 'GET', 'appointment/create', ['pipe' => ['Pipe_Appointment_Create']]);

// POST
$app->setRoute('POST', 'appointment/search-table.ajax', ['pipe' => ['Pipe_Auth', 'Pipe_Appointment_SearchTable']]);

$group = [
    'pipe_prepend' => ['Pipe_Auth', 'Pipe_CsrfValidate'],
];
$app->groupRoute($group, 'POST', 'appointment/store', ['pipe' => ['Pipe_Appointment_Store']]);
$app->groupRoute($group, 'POST', 'appointment/delete', ['pipe' => ['Pipe_Appointment_Delete']]);
$app->groupRoute($group, 'POST', 'appointment/store-not-regular', ['pipe' => ['Pipe_Appointment_StoreNotRegular']]);
$app->groupRoute($group, 'POST', 'appointment/cancel', ['pipe' => ['Pipe_Appointment_Cancel']]);
// route=appointment/update/:redirect
$app->groupRoute($group, 'POST', 'appointment/update/:redirect:*:', ['pipe' => ['Pipe_Appointment_Update']]);

/**
 * ------------------------------------------------------------------------
 * Message
 * ------------------------------------------------------------------------
 */

// GET
$group = [
    'pipe_prepend' => ['Pipe_Auth'],
    'pipe_append' => ['Pipe_OutputCompression'],
];
$app->groupRoute($group, 'GET', 'message/notification/count', ['pipe' => ['Pipe_Message_Notification_Count']]);
$app->groupRoute($group, 'GET', 'notification', ['pipe' => ['Pipe_CsrfGenerate', 'Pipe_Message_Notification_Index']]);
// route=notification/:id
$app->groupRoute($group, 'GET', 'notification/:id::[0-9]+', ['pipe' => ['Pipe_Message_Notification_View']]);

// POST
$app->groupRoute($group, 'POST', 'message/notification/list', ['pipe' => ['Pipe_Auth', 'Pipe_Message_Notification_List']]);

$group = [
    'pipe_prepend' => ['Pipe_Auth', 'Pipe_CsrfValidate'],
];
$app->groupRoute($group, 'POST', 'message/delete', ['pipe' => ['Pipe_Message_Delete']]);
