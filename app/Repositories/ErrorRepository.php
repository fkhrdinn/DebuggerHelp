<?php

namespace App\Repositories;


class ErrorRepository
{
    static public function getErrorWordCloud()
    {
        $errorKeyword = [
            "AuthenticationException" => [
                'authenticate',
                'Authenticator',
                'authenticators',
                'AuthHandler',
            ],
            "BadMethodCallException" => [
                'Method',
                'Macroable',
                'BadMethodCallException'
            ],
            "QueryException" => [
                'SQL',
                'PDO',
                'constraint',
                'SQLSTATE',
                'table',
                'Database',
                'QueryException',
                'Eloquent',
                'Builder',
                'violation',
                'Integrity',
                'MySQL',
                'MongoDB',
                'PostgreSQL',
                'DB'
            ],
            "ClassException" => [
                'Class',
                'DataTableComponent',
                'DataTable',
                'ProviderRepository',
                'Target class'
            ],
            "ComponentNotFoundException" => [
                'ComponentNotFoundException',
                'NonPublicComponentMethodCall',
                'Unable to call component method'
            ],
            "ControllerException" => [
                'Controller',
                'controller',
                'BindResolutionException',
            ],
            "ErrorException" => [
                'strpos',
                'substr',
                'ErrorException',
                'strlen',
                'implode',
                'htmlspecialchars',
                'explode',
                'sizeof',
                'unsupported',
                'operand',
                'array_merge',
                'offset',
                'stdClass',
                'ArrayAccess',
                'str_contains',
                'upload',
                'json_decode',
                'json_encode',
                'Division by zero',
                'Array to string conversion'
            ],
            "HttpResponseException" => [
                'response',
                'sender',
                'identity',
                'response code'
            ],
            "InvalidArgumentException" => [
                'logger',
                'configured',
                'Log',
                'LogManager',
                'InvalidArgumentException',
                'Invalid',
                'invalid',
                'preset',
                'TypeError',
                'Too few arguments to function',
                'Argument 1 passed',
                'Argument #1',
                'Argument #2'
            ],
            "NullValue" => [
                'null',
                'Null',
            ],
            "NamespaceNotFoundException" => [
                'NamespaceNotFoundException',
                'Namespace',
                'namespace'
            ],
            "PropertyNotFoundException" => [
                'Property',
                'property',
                'PropertyNotFoundException',
            ],
            "PackageNotFoundException" => [
                'package'
            ],
            "RunTimeException" => [
                'RuntimeException'
            ],
            "ResourceNotFoundException" => [
                'locate',
                'Vite',
                'manifest',
                'css',
                'sass',
                'scss'
            ],
            "TraitNotFoundException" => [
                'Trait'
            ],
            "RouteException" => [
                'route',
                'Route',
                'routing',
                'Routing',
                'RouteNotFoundException',
                'UrlGenerator',
                'This driver does not support creating temporary URLs'
            ],
            "SyntaxException" => [
                'Syntax',
                'syntax',
                'ParseError',
                'Parse',
                'parse',
                'unexpected',
                'exit',
                'Unclosed'
            ],
            "UndefinedFunction" => [
                'Call to undefined function',
                'Call to a member function'
            ],
            "UndefinedConstant" => [
                'Undefined constant',
            ],
            "UndefinedVariable" => [
                'Undefined variable',
                'variable',
            ],
            "UndefinedMethod" => [
                'Call to undefined method'
            ],
            "DependencyException" => [
                'Unable to resolve dependency'
            ],
            "ViewException" => [
                'FileViewFinder',
                'View'
            ],
            "SMTPException" => [
                'smtp'
            ]
        ];
        return $errorKeyword;
    }
}