<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Front controller
 *
 * PHP version 7.0
 */

/**
 * Composer
 */



require dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
//set_error_handler('Core\Error::errorHandler');
//set_exception_handler('Core\Error::exceptionHandler');


/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Front\Index', 'action' => 'index']);

$router->add('admin', ['controller' => 'Admin\Dashboard', 'action' => 'index']);
$router->add('admin/dashboard', ['controller' => 'Admin\Dashboard', 'action' => 'index']);

$router->add('admin/login', ['controller' => 'Admin\Auth', 'action' => 'login']);
$router->add('admin/logout', ['controller' => 'Admin\Auth', 'action' => 'logout']);
$router->add('admin/registration', ['controller' => 'Admin\Auth', 'action' => 'registration']);
$router->add('admin/forgotPassword', ['controller' => 'Admin\Auth', 'action' => 'forgotPassword']);


$router->add('admin/ajax/login', ['controller' => 'Admin\Ajax\Auth', 'action' => 'login']);
$router->add('ajax/auth/socialSignUp', ['controller' => 'Front\Ajax\Auth', 'action' => 'socialSignUp']);

$router->add('admin/ajax/registration', ['controller' => 'Admin\Ajax\Auth', 'action' => 'registration']);
$router->add('admin/ajax/lock', ['controller' => 'Admin\Ajax\Lock', 'action' => 'index']);
$router->add('admin/ajax/changeStyle', ['controller' => 'Admin\Ajax\ChangeStyle', 'action' => 'index']);


$router->add('admin/profile', ['controller' => 'Admin\Profile', 'action' => 'index']);


$router->add('admin/users', ['controller' => 'Admin\User', 'action' => 'index']);
$router->add('admin/users/all', ['controller' => 'Admin\User', 'action' => 'index']);

$router->add('admin/ajax/user/paginate', ['controller' => 'Admin\Ajax\User', 'action' => 'list']);

$router->add('admin/users/add', ['controller' => 'Admin\User', 'action' => 'add']);

$router->add('admin/users/{id:\d+}/edit', ['controller' => 'Admin\User', 'action' => 'edit']);


$router->add('admin/ajax/user/add', ['controller' => 'Admin\Ajax\User', 'action' => 'add']);

$router->add('admin/ajax/user/delete', ['controller' => 'Admin\Ajax\User', 'action' => 'delete']);
$router->add('admin/ajax/user/selfDelete', ['controller' => 'Admin\Ajax\User', 'action' => 'selfDelete']);

$router->add('admin/ajax/user/deleteAvatar', ['controller' => 'Admin\Ajax\User', 'action' => 'deleteAvatar']);
$router->add('ajax/user/deleteAvatar', ['controller' => 'Front\Ajax\User', 'action' => 'deleteAvatar']);

$router->add('admin/ajax/user/selfDeleteAvatar', ['controller' => 'Admin\Ajax\User', 'action' => 'selfDeleteAvatar']);

$router->add('admin/ajax/user/edit', ['controller' => 'Admin\Ajax\User', 'action' => 'edit']);

$router->add('ajax/user/edit', ['controller' => 'Front\Ajax\User', 'action' => 'edit']);


$router->add('admin/ajax/user/selfEdit', ['controller' => 'Admin\Ajax\User', 'action' => 'selfEdit']);

$router->add('admin/ajax/user/changePassword', ['controller' => 'Admin\Ajax\User', 'action' => 'changePassword']);

$router->add('ajax/user/changePassword', ['controller' => 'Front\Ajax\User', 'action' => 'changePassword']);










$router->add('admin/settings/userGroups', ['controller' => 'Admin\UserGroup', 'action' => 'index']);
$router->add('admin/settings/userGroups/all', ['controller' => 'Admin\UserGroup', 'action' => 'index']);

$router->add('admin/ajax/userGroup/paginate', ['controller' => 'Admin\Ajax\UserGroup', 'action' => 'list']);

$router->add('admin/settings/userGroups/add', ['controller' => 'Admin\UserGroup', 'action' => 'add']);

$router->add('admin/settings/userGroups/{id:\d+}/edit', ['controller' => 'Admin\UserGroup', 'action' => 'edit']);


$router->add('admin/ajax/userGroup/add', ['controller' => 'Admin\Ajax\UserGroup', 'action' => 'add']);

$router->add('admin/ajax/userGroup/delete', ['controller' => 'Admin\Ajax\UserGroup', 'action' => 'delete']);

$router->add('admin/ajax/userGroup/edit', ['controller' => 'Admin\Ajax\UserGroup', 'action' => 'edit']);











$router->add('admin/settings/applicationStatuses', ['controller' => 'Admin\Application\Status', 'action' => 'index']);
$router->add('admin/settings/applicationStatuses/all', ['controller' => 'Admin\Application\Status', 'action' => 'index']);

$router->add('admin/ajax/applicationStatus/paginate', ['controller' => 'Admin\Ajax\Application\Status', 'action' => 'list']);
$router->add('admin/ajax/applicationStatus/update', ['controller' => 'Admin\Ajax\Application\Status', 'action' => 'update']);

$router->add('admin/settings/applicationStatuses/add', ['controller' => 'Admin\Application\Status', 'action' => 'add']);
$router->add('admin/settings/applicationStatuses/{id:\d+}/edit', ['controller' => 'Admin\Application\Status', 'action' => 'edit']);


$router->add('admin/ajax/applicationStatus/add', ['controller' => 'Admin\Ajax\Application\Status', 'action' => 'add']);

$router->add('admin/ajax/applicationStatus/delete', ['controller' => 'Admin\Ajax\Application\Status', 'action' => 'delete']);

$router->add('admin/ajax/applicationStatus/edit', ['controller' => 'Admin\Ajax\Application\Status', 'action' => 'edit']);










$router->add('admin/settings/courseStreamStatuses', ['controller' => 'Admin\Course\Stream\Status', 'action' => 'index']);
$router->add('admin/settings/courseStreamStatuses/all', ['controller' => 'Admin\Course\Stream\Status', 'action' => 'index']);

$router->add('admin/ajax/courseStreamStatus/paginate', ['controller' => 'Admin\Ajax\Course\Stream\Status', 'action' => 'list']);
$router->add('admin/ajax/courseStreamStatus/update', ['controller' => 'Admin\Ajax\Course\Stream\Status', 'action' => 'update']);

$router->add('admin/settings/courseStreamStatuses/add', ['controller' => 'Admin\Course\Stream\Status', 'action' => 'add']);

$router->add('admin/settings/courseStreamStatuses/{id:\d+}/edit', ['controller' => 'Admin\Course\Stream\Status', 'action' => 'edit']);


$router->add('admin/ajax/courseStreamStatus/add', ['controller' => 'Admin\Ajax\Course\Stream\Status', 'action' => 'add']);

$router->add('admin/ajax/courseStreamStatus/delete', ['controller' => 'Admin\Ajax\Course\Stream\Status', 'action' => 'delete']);

$router->add('admin/ajax/courseStreamStatus/edit', ['controller' => 'Admin\Ajax\Course\Stream\Status', 'action' => 'edit']);







$router->add('admin/vacancies', ['controller' => 'Admin\Vacancy', 'action' => 'index']);
$router->add('admin/vacancies/all', ['controller' => 'Admin\Vacancy', 'action' => 'index']);

$router->add('admin/ajax/vacancy/paginate', ['controller' => 'Admin\Ajax\Vacancy', 'action' => 'list']);
$router->add('admin/ajax/vacancy/update', ['controller' => 'Admin\Ajax\Vacancy', 'action' => 'update']);

$router->add('admin/vacancies/add', ['controller' => 'Admin\Vacancy', 'action' => 'add']);

$router->add('admin/vacancies/{id:\d+}/edit', ['controller' => 'Admin\Vacancy', 'action' => 'edit']);

$router->add('admin/ajax/vacancy/add', ['controller' => 'Admin\Ajax\Vacancy', 'action' => 'add']);

$router->add('admin/ajax/vacancy/delete', ['controller' => 'Admin\Ajax\Vacancy', 'action' => 'delete']);
$router->add('admin/ajax/vacancy/deleteLogo', ['controller' => 'Admin\Ajax\Vacancy', 'action' => 'deleteLogo']);

$router->add('admin/ajax/vacancy/edit', ['controller' => 'Admin\Ajax\Vacancy', 'action' => 'edit']);













$router->add('admin/settings/vacancySalaries', ['controller' => 'Admin\Vacancy\Salary', 'action' => 'index']);
$router->add('admin/settings/vacancySalaries/all', ['controller' => 'Admin\Vacancy\Salary', 'action' => 'index']);

$router->add('admin/ajax/vacancySalary/paginate', ['controller' => 'Admin\Ajax\Vacancy\Salary', 'action' => 'list']);
$router->add('admin/ajax/vacancySalary/update', ['controller' => 'Admin\Ajax\Vacancy\Salary', 'action' => 'update']);

$router->add('admin/settings/vacancySalaries/add', ['controller' => 'Admin\Vacancy\Salary', 'action' => 'add']);

$router->add('admin/settings/vacancySalaries/{id:\d+}/edit', ['controller' => 'Admin\Vacancy\Salary', 'action' => 'edit']);


$router->add('admin/ajax/vacancySalary/add', ['controller' => 'Admin\Ajax\Vacancy\Salary', 'action' => 'add']);

$router->add('admin/ajax/vacancySalary/delete', ['controller' => 'Admin\Ajax\Vacancy\Salary', 'action' => 'delete']);

$router->add('admin/ajax/vacancySalary/edit', ['controller' => 'Admin\Ajax\Vacancy\Salary', 'action' => 'edit']);









$router->add('admin/currencies', ['controller' => 'Admin\Currency', 'action' => 'index']);
$router->add('admin/currencies/all', ['controller' => 'Admin\Currency', 'action' => 'index']);

$router->add('admin/ajax/currency/paginate', ['controller' => 'Admin\Ajax\Currency', 'action' => 'list']);
$router->add('admin/ajax/currency/update', ['controller' => 'Admin\Ajax\Currency', 'action' => 'update']);

$router->add('admin/currencies/add', ['controller' => 'Admin\Currency', 'action' => 'add']);

$router->add('admin/currencies/{id:\d+}/edit', ['controller' => 'Admin\Currency', 'action' => 'edit']);

$router->add('admin/ajax/currency/add', ['controller' => 'Admin\Ajax\Currency', 'action' => 'add']);

$router->add('admin/ajax/currency/delete', ['controller' => 'Admin\Ajax\Currency', 'action' => 'delete']);

$router->add('admin/ajax/currency/edit', ['controller' => 'Admin\Ajax\Currency', 'action' => 'edit']);







$router->add('admin/currencies/rates', ['controller' => 'Admin\Currency\Rate', 'action' => 'index']);
$router->add('admin/currencies/rates/all', ['controller' => 'Admin\Currency\Rate', 'action' => 'index']);

$router->add('admin/ajax/currency/rate/paginate', ['controller' => 'Admin\Ajax\Currency\Rate', 'action' => 'list']);

$router->add('admin/currencies/rates/add', ['controller' => 'Admin\Currency\Rate', 'action' => 'add']);


$router->add('admin/ajax/currency/rate/add', ['controller' => 'Admin\Ajax\Currency\Rate', 'action' => 'add']);








$router->add('admin/settings/taskStatuses', ['controller' => 'Admin\TaskStatus', 'action' => 'index']);
$router->add('admin/settings/taskStatuses/all', ['controller' => 'Admin\TaskStatus', 'action' => 'index']);

$router->add('admin/ajax/taskStatus/paginate', ['controller' => 'Admin\Ajax\TaskStatus', 'action' => 'list']);

$router->add('admin/settings/taskStatuses/add', ['controller' => 'Admin\TaskStatus', 'action' => 'add']);

$router->add('admin/settings/taskStatuses/{id:\d+}/edit', ['controller' => 'Admin\TaskStatus', 'action' => 'edit']);


$router->add('admin/ajax/taskStatus/add', ['controller' => 'Admin\Ajax\TaskStatus', 'action' => 'add']);

$router->add('admin/ajax/taskStatus/delete', ['controller' => 'Admin\Ajax\TaskStatus', 'action' => 'delete']);

$router->add('admin/ajax/taskStatus/edit', ['controller' => 'Admin\Ajax\TaskStatus', 'action' => 'edit']);








$router->add('admin/ajax/notification/request', ['controller' => 'Admin\Ajax\Notification', 'action' => 'request']);

$router->add('admin/ajax/notification/other', ['controller' => 'Admin\Ajax\Notification', 'action' => 'other']);

































$router->add('admin/applications', ['controller' => 'Admin\Application', 'action' => 'index']);
$router->add('admin/applications/all', ['controller' => 'Admin\Application', 'action' => 'index']);

$router->add('admin/ajax/application/paginate', ['controller' => 'Admin\Ajax\Application', 'action' => 'list']);

$router->add('admin/applications/add', ['controller' => 'Admin\Application', 'action' => 'add']);

$router->add('admin/applications/{id:\d+}/edit', ['controller' => 'Admin\Application', 'action' => 'edit']);


$router->add('admin/ajax/application/add', ['controller' => 'Admin\Ajax\Application', 'action' => 'add']);

$router->add('admin/ajax/application/delete', ['controller' => 'Admin\Ajax\Application', 'action' => 'delete']);

$router->add('admin/ajax/application/edit', ['controller' => 'Admin\Ajax\Application', 'action' => 'edit']);
$router->add('admin/ajax/application/update', ['controller' => 'Admin\Ajax\Application', 'action' => 'update']);









$router->add('admin/feedbacks', ['controller' => 'Admin\Feedback', 'action' => 'indexAdmin']);
$router->add('admin/feedbacks/all', ['controller' => 'Admin\Feedback', 'action' => 'indexAdmin']);


$router->add('admin/feedbacks/my', ['controller' => 'Admin\Feedback', 'action' => 'index']);
$router->add('admin/feedbacks/my/all', ['controller' => 'Admin\Feedback', 'action' => 'index']);

$router->add('admin/ajax/feedback/my/paginate', ['controller' => 'Admin\Ajax\Feedback', 'action' => 'list']);
$router->add('admin/ajax/feedback/paginate', ['controller' => 'Admin\Ajax\Feedback', 'action' => 'listAdmin']);

$router->add('admin/feedbacks/my/add', ['controller' => 'Admin\Feedback', 'action' => 'add']);

$router->add('admin/feedbacks/my/{id:\d+}/edit', ['controller' => 'Admin\Feedback', 'action' => 'edit']);


$router->add('admin/ajax/feedback/my/add', ['controller' => 'Admin\Ajax\Feedback', 'action' => 'add']);

$router->add('admin/ajax/feedback/delete', ['controller' => 'Admin\Ajax\Feedback', 'action' => 'delete']);

$router->add('admin/ajax/feedback/edit', ['controller' => 'Admin\Ajax\Feedback', 'action' => 'edit']);
$router->add('admin/ajax/feedback/update', ['controller' => 'Admin\Ajax\Feedback', 'action' => 'update']);








$router->add('admin/sections/all', ['controller' => 'Admin\Section', 'action' => 'index']);
$router->add('admin/sections/add', ['controller' => 'Admin\Section', 'action' => 'add']);
$router->add('admin/sections/{id:\d+}/edit', ['controller' => 'Admin\Section', 'action' => 'edit']);

$router->add('admin/ajax/section/add', ['controller' => 'Admin\Ajax\Section', 'action' => 'add']);
$router->add('admin/ajax/section/getChildren', ['controller' => 'Admin\Ajax\Section', 'action' => 'getChildren']);

$router->add('admin/ajax/section/deleteLogo', ['controller' => 'Admin\Ajax\Section', 'action' => 'deleteLogo']);
$router->add('admin/ajax/section/delete', ['controller' => 'Admin\Ajax\Section', 'action' => 'delete']);
$router->add('admin/ajax/section/edit', ['controller' => 'Admin\Ajax\Section', 'action' => 'edit']);
$router->add('admin/ajax/section/update', ['controller' => 'Admin\Ajax\Section', 'action' => 'update']);








$router->add('admin/lessons/all', ['controller' => 'Admin\Lesson', 'action' => 'index']);
$router->add('admin/lessons/add', ['controller' => 'Admin\Lesson', 'action' => 'add']);
$router->add('admin/lessons/{id:\d+}/edit', ['controller' => 'Admin\Lesson', 'action' => 'edit']);



$router->add('admin/ajax/lesson/add', ['controller' => 'Admin\Ajax\Lesson', 'action' => 'add']);
$router->add('admin/ajax/lesson/delete', ['controller' => 'Admin\Ajax\Lesson', 'action' => 'delete']);
$router->add('admin/ajax/lesson/edit', ['controller' => 'Admin\Ajax\Lesson', 'action' => 'edit']);
$router->add('admin/ajax/lesson/update', ['controller' => 'Admin\Ajax\Lesson', 'action' => 'update']);











$router->add('admin/tasks/individual/all', ['controller' => 'Admin\Task\IndividualTask', 'action' => 'index']);
$router->add('admin/tasks/individual/add', ['controller' => 'Admin\Task\IndividualTask', 'action' => 'add']);
$router->add('admin/tasks/individual/{id:\d+}/edit', ['controller' => 'Admin\Task\IndividualTask', 'action' => 'edit']);



$router->add('admin/tasks/team/all', ['controller' => 'Admin\Task\TeamTask', 'action' => 'index']);
$router->add('admin/tasks/team/add', ['controller' => 'Admin\Task\TeamTask', 'action' => 'add']);
$router->add('admin/tasks/team/{id:\d+}/edit', ['controller' => 'Admin\Task\TeamTask', 'action' => 'edit']);


$router->add('admin/ajax/attachment/add', ['controller' => 'Admin\Ajax\Attachment', 'action' => 'add']);
$router->add('admin/ajax/attachment/edit', ['controller' => 'Admin\Ajax\Attachment', 'action' => 'edit']);
$router->add('admin/ajax/attachment/delete', ['controller' => 'Admin\Ajax\Attachment', 'action' => 'delete']);

$router->add('admin/ajax/audio/add', ['controller' => 'Admin\Ajax\Audio', 'action' => 'add']);
$router->add('admin/ajax/audio/update', ['controller' => 'Admin\Ajax\Audio', 'action' => 'update']);
$router->add('admin/ajax/audio/delete', ['controller' => 'Admin\Ajax\Audio', 'action' => 'delete']);



$router->add('admin/teams', ['controller' => 'Admin\Team', 'action' => 'index']);
$router->add('admin/teams/all', ['controller' => 'Admin\Team', 'action' => 'index']);

$router->add('admin/ajax/team/paginate', ['controller' => 'Admin\Ajax\Team', 'action' => 'list']);
/*$router->add('admin/ajax/recall/liveReload', ['controller' => 'Admin\Ajax\Application', 'action' => 'liveReload']);
*/
$router->add('admin/teams/add', ['controller' => 'Admin\Team', 'action' => 'add']);

$router->add('admin/teams/{id:\d+}/edit', ['controller' => 'Admin\Team', 'action' => 'edit']);


$router->add('admin/ajax/team/add', ['controller' => 'Admin\Ajax\Team', 'action' => 'add']);

$router->add('admin/ajax/team/delete', ['controller' => 'Admin\Ajax\Team', 'action' => 'delete']);
$router->add('admin/ajax/team/deleteLogo', ['controller' => 'Admin\Ajax\Team', 'action' => 'deleteLogo']);

$router->add('admin/ajax/team/edit', ['controller' => 'Admin\Ajax\Team', 'action' => 'edit']);
$router->add('admin/ajax/team/update', ['controller' => 'Admin\Ajax\Team', 'action' => 'update']);



















$router->add('admin/staticPages', ['controller' => 'Admin\StaticPage', 'action' => 'index']);
$router->add('admin/staticPages/all', ['controller' => 'Admin\StaticPage', 'action' => 'index']);

$router->add('admin/ajax/staticPage/paginate', ['controller' => 'Admin\Ajax\StaticPage', 'action' => 'list']);

$router->add('admin/staticPages/add', ['controller' => 'Admin\StaticPage', 'action' => 'add']);

$router->add('admin/staticPages/{id:\d+}/edit', ['controller' => 'Admin\StaticPage', 'action' => 'edit']);


$router->add('admin/ajax/staticPage/add', ['controller' => 'Admin\Ajax\StaticPage', 'action' => 'add']);
$router->add('admin/ajax/staticPage/deleteCover', ['controller' => 'Admin\Ajax\StaticPage', 'action' => 'deleteCover']);
$router->add('admin/ajax/staticPage/deleteLogo', ['controller' => 'Admin\Ajax\StaticPage', 'action' => 'deleteLogo']);
$router->add('admin/ajax/staticPage/delete', ['controller' => 'Admin\Ajax\StaticPage', 'action' => 'delete']);

$router->add('admin/ajax/staticPage/edit', ['controller' => 'Admin\Ajax\StaticPage', 'action' => 'edit']);





$router->add('admin/courses', ['controller' => 'Admin\Course', 'action' => 'index']);
$router->add('admin/courses/all', ['controller' => 'Admin\Course', 'action' => 'index']);

$router->add('admin/ajax/course/paginate', ['controller' => 'Admin\Ajax\Course', 'action' => 'list']);
$router->add('admin/ajax/course/update', ['controller' => 'Admin\Ajax\Course', 'action' => 'update']);

$router->add('admin/courses/add', ['controller' => 'Admin\Course', 'action' => 'add']);

$router->add('admin/courses/{id:\d+}/edit', ['controller' => 'Admin\Course', 'action' => 'edit']);
$router->add('admin/courses/{courseid:\d+}/streams', ['controller' => 'Admin\Course\Stream', 'action' => 'index']);
$router->add('admin/courses/{courseid:\d+}/streams/{id:\d+}/edit', ['controller' => 'Admin\Course\Stream', 'action' => 'edit']);
$router->add('admin/courses/{courseid:\d+}/streams/add', ['controller' => 'Admin\Course\Stream', 'action' => 'add']);

$router->add('admin/ajax/course/stream/byCourse', ['controller' => 'Admin\Ajax\Course\Stream', 'action' => 'liteStreamList']);

$router->add('admin/ajax/course/stream/paginate', ['controller' => 'Admin\Ajax\Course\Stream', 'action' => 'list']);
$router->add('admin/ajax/course/stream/add', ['controller' => 'Admin\Ajax\Course\Stream', 'action' => 'add']);

$router->add('admin/ajax/course/stream/delete', ['controller' => 'Admin\Ajax\Course\Stream', 'action' => 'delete']);
$router->add('admin/ajax/course/stream/edit', ['controller' => 'Admin\Ajax\Course\Stream', 'action' => 'edit']);





$router->add('admin/ajax/course/add', ['controller' => 'Admin\Ajax\Course', 'action' => 'add']);

$router->add('admin/ajax/course/delete', ['controller' => 'Admin\Ajax\Course', 'action' => 'delete']);
$router->add('admin/ajax/course/deleteLogo', ['controller' => 'Admin\Ajax\Course', 'action' => 'deleteLogo']);
$router->add('admin/ajax/course/deleteCover', ['controller' => 'Admin\Ajax\Course', 'action' => 'deleteCover']);
$router->add('admin/ajax/course/edit', ['controller' => 'Admin\Ajax\Course', 'action' => 'edit']);






$router->add('admin/courses/{courseid:\d+}/skills', ['controller' => 'Admin\Course\Skill', 'action' => 'index']);
$router->add('admin/ajax/course/skill/paginate', ['controller' => 'Admin\Ajax\Course\Skill', 'action' => 'list']);
$router->add('admin/ajax/course/skill/update', ['controller' => 'Admin\Ajax\Course\Skill', 'action' => 'update']);

$router->add('admin/courses/{courseid:\d+}/skills/add', ['controller' => 'Admin\Course\Skill', 'action' => 'add']);
$router->add('admin/courses/{courseid:\d+}/skills/{id:\d+}/edit', ['controller' => 'Admin\Course\Skill', 'action' => 'edit']);

$router->add('admin/ajax/course/skill/add', ['controller' => 'Admin\Ajax\Course\Skill', 'action' => 'add']);

$router->add('admin/ajax/course/skill/delete', ['controller' => 'Admin\Ajax\Course\Skill', 'action' => 'delete']);
$router->add('admin/ajax/course/skill/deleteLogo', ['controller' => 'Admin\Ajax\Course\Skill', 'action' => 'deleteLogo']);
$router->add('admin/ajax/course/skill/edit', ['controller' => 'Admin\Ajax\Course\Skill', 'action' => 'edit']);










$router->add('admin/projects', ['controller' => 'Admin\Project', 'action' => 'index']);
$router->add('admin/projects/all', ['controller' => 'Admin\Project', 'action' => 'index']);

$router->add('admin/ajax/project/paginate', ['controller' => 'Admin\Ajax\Project', 'action' => 'list']);
$router->add('admin/ajax/project/update', ['controller' => 'Admin\Ajax\Project', 'action' => 'update']);

$router->add('admin/projects/add', ['controller' => 'Admin\Project', 'action' => 'add']);

$router->add('admin/projects/{id:\d+}/edit', ['controller' => 'Admin\Project', 'action' => 'edit']);


$router->add('admin/ajax/project/add', ['controller' => 'Admin\Ajax\Project', 'action' => 'add']);

$router->add('admin/ajax/project/delete', ['controller' => 'Admin\Ajax\Project', 'action' => 'delete']);
$router->add('admin/ajax/project/deleteLogo', ['controller' => 'Admin\Ajax\Project', 'action' => 'deleteLogo']);
$router->add('admin/ajax/project/deleteCover', ['controller' => 'Admin\Ajax\Project', 'action' => 'deleteCover']);
$router->add('admin/ajax/project/edit', ['controller' => 'Admin\Ajax\Project', 'action' => 'edit']);









$router->add('admin/faqs', ['controller' => 'Admin\FAQ', 'action' => 'index']);
$router->add('admin/faqs/all', ['controller' => 'Admin\FAQ', 'action' => 'index']);

$router->add('admin/ajax/faq/paginate', ['controller' => 'Admin\Ajax\FAQ', 'action' => 'list']);

$router->add('admin/ajax/faq/update', ['controller' => 'Admin\Ajax\FAQ', 'action' => 'update']);

$router->add('admin/faqs/add', ['controller' => 'Admin\FAQ', 'action' => 'add']);

$router->add('admin/faqs/{id:\d+}/edit', ['controller' => 'Admin\FAQ', 'action' => 'edit']);


$router->add('admin/ajax/faq/add', ['controller' => 'Admin\Ajax\FAQ', 'action' => 'add']);

$router->add('admin/ajax/faq/delete', ['controller' => 'Admin\Ajax\FAQ', 'action' => 'delete']);

$router->add('admin/ajax/faq/edit', ['controller' => 'Admin\Ajax\FAQ', 'action' => 'edit']);








$router->add('admin/branches', ['controller' => 'Admin\Branch', 'action' => 'index']);
$router->add('admin/branches/all', ['controller' => 'Admin\Branch', 'action' => 'index']);

$router->add('admin/ajax/branch/paginate', ['controller' => 'Admin\Ajax\Branch', 'action' => 'list']);
$router->add('admin/ajax/branch/update', ['controller' => 'Admin\Ajax\Branch', 'action' => 'update']);
$router->add('admin/branches/add', ['controller' => 'Admin\Branch', 'action' => 'add']);

$router->add('admin/branches/{id:\d+}/edit', ['controller' => 'Admin\Branch', 'action' => 'edit']);


$router->add('admin/ajax/branch/add', ['controller' => 'Admin\Ajax\Branch', 'action' => 'add']);

$router->add('admin/ajax/branch/delete', ['controller' => 'Admin\Ajax\Branch', 'action' => 'delete']);

$router->add('admin/ajax/branch/edit', ['controller' => 'Admin\Ajax\Branch', 'action' => 'edit']);










$router->add('admin/ajax/confirmRegistration', ['controller' => 'Admin\Ajax\Auth', 'action' => 'confirmRegistration']);
$router->add('admin/ajax/forgotPasswordQuery', ['controller' => 'Admin\Ajax\Auth', 'action' => 'forgotPasswordQuery']);
$router->add('admin/ajax/confirmChangePassword', ['controller' => 'Admin\Ajax\Auth', 'action' => 'confirmChangePassword']);

$router->add('ajax/confirmChangePassword', ['controller' => 'Front\Ajax\Auth', 'action' => 'confirmChangePassword']);



$router->add('panel/registrationVerification/{token:\w+}', ['controller' => 'Admin\Auth', 'action' => 'registrationVerification']);
$router->add('panel/changeEmailVerification/{token:\w+}', ['controller' => 'Admin\Auth', 'action' => 'changeEmailVerification']);
$router->add('panel/confirmForgotPasswordVerification/{token:\w+}', ['controller' => 'Admin\Auth', 'action' => 'confirmForgotPasswordVerification']);


$router->add('confirmForgotPasswordVerification/{token:\w+}', ['controller' => 'Front\Auth', 'action' => 'confirmForgotPasswordVerification']);


$router->add('admin/settings/defaults', ['controller' => 'Admin\Defaults', 'action' => 'index']);

$router->add('test', ['controller' => 'Admin\Dashboard', 'action' => 'test']);


$router->add('admin/ajax/defaults/save', ['controller' => 'Admin\Ajax\Defaults', 'action' => 'index']);


$router->add('contacts', ['controller' => 'Front\Contact', 'action' => 'index']);
$router->add('vacancies', ['controller' => 'Front\Vacancy', 'action' => 'index']);


$router->add('ajax/auth/login', ['controller' => 'Front\Ajax\Auth', 'action' => 'login']);
$router->add('ajax/auth/registration', ['controller' => 'Front\Ajax\Auth', 'action' => 'registration']);
$router->add('ajax/auth/forgotPasswordQuery', ['controller' => 'Front\Ajax\Auth', 'action' => 'forgotPasswordQuery']);

$router->add('ajax/auth/logout', ['controller' => 'Front\Ajax\Auth', 'action' => 'logout']);
$router->add('logout', ['controller' => 'Front\Auth', 'action' => 'logout']);



//$router->add('{alturl:([A-Za-z0-9\-\_]+)}.html', ['controller' => 'Front\StaticPage', 'action' => 'index']);
$router->add('vacancies/{alturl:([A-Za-z0-9\-\_]+)}', ['controller' => 'Front\Vacancy', 'action' => 'single']);

$router->add('courses/{alturl:([A-Za-z0-9\-\_]+)}', ['controller' => 'Front\Course', 'action' => 'single']);
$router->add('courses', ['controller' => 'Front\Course', 'action' => 'index']);
$router->add('courses/page/{page:([0-9]+)}', ['controller' => 'Front\Course', 'action' => 'index']);



$router->add('projects/{alturl:([A-Za-z0-9\-\_]+)}', ['controller' => 'Front\Project', 'action' => 'single']);
$router->add('projects', ['controller' => 'Front\Project', 'action' => 'index']);
$router->add('projects/page/{page:([0-9]+)}', ['controller' => 'Front\Project', 'action' => 'index']);


$router->add('pages/{alturl:([A-Za-z0-9\-\_]+)}', ['controller' => 'Front\StaticPage', 'action' => 'single']);
$router->add('pages', ['controller' => 'Front\StaticPage', 'action' => 'index']);
$router->add('pages/page/{page:([0-9]+)}', ['controller' => 'Front\StaticPage', 'action' => 'index']);


$router->add('ajax/application/add', ['controller' => 'Front\Ajax\Application', 'action' => 'add']);
$router->add('api/telegramBot', ['controller' => 'Api\TelegramBot', 'action' => 'hook']);


// Reserve

$router->add('auth/facebook', ['controller' => 'Front\Auth', 'action' => 'facebook']);
$router->add('auth/google', ['controller' => 'Front\Auth', 'action' => 'google']);

//$router->add('test', ['controller' => 'Front\Home', 'action' => 'test']);
//$router->add('{restaurant:([A-Za-z0-9\-\_]+)}-menu', ['controller' => 'Front\Place', 'action' => 'index']);
//$router->add('api/getAllCategories', ['controller' => 'API\MainData', 'action' => 'index']);

$router->dispatch($_SERVER['QUERY_STRING']);
