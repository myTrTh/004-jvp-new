<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

// app routes
$routes->add('app_index', new Route('/', array('_controller' => 'App\Controller\AppController::index')));

// error routes
$routes->add('error', new Route('/error/{errno}', array('_controller' => 'App\Controller\ErrorController::error'), array('errno' => '403|404|500')));

// auth routes
$routes->add('auth_registration', new Route('/registration', array('_controller' => 'App\Controller\AuthController::registration')));
$routes->add('auth_registration_send_email', new Route('/registration/send-email', array('_controller' => 'App\Controller\AuthController::registrationSendEmail')));
$routes->add('auth_registration_confirmation', new Route('/registration/confirmation/{token}', array('_controller' => 'App\Controller\AuthController::registrationConfirmation'), array('token' => '.+')));

$routes->add('auth_login', new Route('/login', array('_controller' => 'App\Controller\AuthController::login')));
$routes->add('auth_logout', new Route('/logout', array('_controller' => 'App\Controller\AuthController::logout')));

$routes->add('auth_reset_password', new Route('/reset-password', array('_controller' => 'App\Controller\AuthController::resetPassword')));
$routes->add('auth_reset_password_send_email', new Route('/reset-password/send-email', array('_controller' => 'App\Controller\AuthController::resetPasswordSendEmail')));
$routes->add('auth_reset_password_confirmation', new Route('/reset-password/{token}', array('_controller' => 'App\Controller\AuthController::resetPasswordConfirmation'), array('token' => '.+')));

// user routes
$routes->add('profile', new Route('/profile', array('_controller' => 'App\Controller\UserController::profile')));
$routes->add('settings', new Route('/profile/settings', array('_controller' => 'App\Controller\UserController::settings')));
$routes->add('user_change_password', new Route('/profile/change-password', array('_controller' => 'App\Controller\UserController::changePassword')));
$routes->add('user_list', new Route('/users/{sort}', array('_controller' => 'App\Controller\UserController::list', 'sort' => 'alpha_asc', array('sort' => 'alpha_asc|alpha_desc|since_asc|since_desc'))));
$routes->add('user_profile', new Route('/user/{id}', array('_controller' => 'App\Controller\UserController::show'), array('id' => '[0-9]+')));

// role routes
$routes->add('role_list', new Route('/roles', array('_controller' => 'App\Controller\Admin\RoleController::list')));
$routes->add('role_add', new Route('/roles/add', array('_controller' => 'App\Controller\Admin\RoleController::add')));
$routes->add('role_edit', new Route('/roles/edit/{id}', array('_controller' => 'App\Controller\Admin\RoleController::edit'), array('id' => '[0-9]+')));
$routes->add('role_permission', new Route('/roles/permission/{id}', array('_controller' => 'App\Controller\Admin\RoleController::show'), array('id' => '[0-9]+')));
$routes->add('role_delete', new Route('/roles/delete/{id}', array('_controller' => 'App\Controller\Admin\RoleController::delete'), array('id' => '[0-9]+')));

// permission routes
$routes->add('permission_list', new Route('/permissions', array('_controller' => 'App\Controller\Admin\PermissionController::list')));
$routes->add('permission_add', new Route('/permissions/add', array('_controller' => 'App\Controller\Admin\PermissionController::add')));
$routes->add('permission_edit', new Route('/permission/edit/{id}', array('_controller' => 'App\Controller\Admin\PermissionController::edit'), array('id' => '[0-9]+')));
$routes->add('permission_delete', new Route('/permissions/delete/{id}', array('_controller' => 'App\Controller\Admin\PermissionController::delete'), array('id' => '[0-9]+')));

// admin routes
$routes->add('admin_panel', new Route('/admin/panel', array('_controller' => 'App\Controller\Admin\AdminController::index')));
$routes->add('ajax_admin_permissions', new Route('/ajax/admin/permissions', array('_controller' => 'App\Controller\Admin\AdminController::ajax_permissions')));
$routes->add('ajax_admin_roles', new Route('/ajax/admin/roles', array('_controller' => 'App\Controller\Admin\AdminController::ajax_roles')));
$routes->add('admin_permissions', new Route('/admin/permissions/{id}', array('_controller' => 'App\Controller\Admin\AdminController::permissions'), array('id' => '[0-9]+')));
$routes->add('admin_guestbook', new Route('/admin/guestbook/{page}', array('_controller' => 'App\Controller\Admin\AdminbookController::guestbook', 'page' => 1), array('page' => '[0-9]+')));
$routes->add('admin_upload', new Route('/admin/upload', array('_controller' => 'App\Controller\Admin\AdminController::upload')));

// content routes
$routes->add('content_show', new Route('/{type}', array('_controller' => 'App\Controller\ContentController::show'), array('type' => 'rules|faq|about|alert')));
$routes->add('content_edit', new Route('/admin/{type}/edit', array('_controller' => 'App\Controller\ContentController::edit'), array('type' => 'rules|faq|about|alert')));

// guestbook routes
$routes->add('guestbook', new Route('/guestbook/{page}', array('_controller' => 'App\Controller\GuestbookController::guestbook', 'page' => 1), array('page' => '[0-9]+')));
$routes->add('ajax_guestbook_rate', new Route('ajax/guestbook/rate', array('_controller' => 'App\Controller\GuestbookController::ajax_rate')));

// votes routes
$routes->add('vote_list', new Route('/votes/{page}', array('_controller' => 'App\Controller\VoteController::list', 'page' => 1), array('page' => '[0-9]+')));
$routes->add('vote_show', new Route('/vote/{id}/{access}', array('_controller' => 'App\Controller\VoteController::show', 'id' => 1, 'access' => 'close'), array('id' => '[0-9]+', 'access' => 'open|close')));
$routes->add('vote_add', new Route('/vote/add', array('_controller' => 'App\Controller\VoteController::add')));
$routes->add('vote_edit', new Route('/vote/edit/{id}', array('_controller' => 'App\Controller\VoteController::edit'), array('id' => '[0-9]+')));
$routes->add('vote_delete', new Route('/vote/delete/{id}', array('_controller' => 'App\Controller\VoteController::delete'), array('id' => '[0-9]+')));
$routes->add('ajax_vote_show', new Route('ajax/vote/show', array('_controller' => 'App\Controller\VoteController::ajax_show')));
$routes->add('ajax_vote_send', new Route('ajax/vote/send', array('_controller' => 'App\Controller\VoteController::ajax_send')));

return $routes;