<?php
/**
 * Middleware classes are each in their own file:
 *   AuthMiddleware.php  – redirect unauthenticated users
 *   AdminMiddleware.php – restrict to admin role
 *   CsrfMiddleware.php  – validate CSRF token on POST requests
 */
namespace App\Middleware;
