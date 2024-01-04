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
                'Connection',
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
                'FatalError',
                'DataTable',
                'ProviderRepository'
            ],
            "ComponentNotFoundException" => [
                'component',
                'ComponentNotFoundException',
                'LivewireManager',
                'call',
                'MethodNotFoundException',
            ],
            "ControllerException" => [
                'Controller',
                'controller',
                'BindResolutionException',
            ],
            "ErrorException" => [
                'parameter',
                'string',
                'array',
                'expects',
                'strpos',
                'substr',
                'non-numeric',
                'non',
                'numeric',
                'ErrorException',
                'value',
                'strlen',
                'implode',
                'htmlspecialchars',
                'explode',
                'count',
                'sizeof',
                'unsupported',
                'operand',
                'argument',
                'array_merge',
                'offset',
                'stdClass',
                'ArrayAccess',
                'constants',
                'layouts',
                'str_contains',
                'file',
                'upload',
                'json_decode',
                'json_encode'
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
                'Too',
                'few',
                'arguments',
                'Invalid',
                'invalid',
                'preset',
                'member',
                'TypeError'
            ],
            "NullValue" => [
                'null',
                'Null',
            ],
            "PropertyNotFoundException" => [
                'Property',
                'PropertyNotFoundException',
                'livewire',
                'Trying',
                'get',
                'non-object'
            ],
            "ResourceNotFoundException" => [
                'Unable',
                'locate',
                'Vite',
                'manifest',
                'resources',
                'css',
                'sass',
                'scss'
            ],
            "RouteException" => [
                'route',
                'Route',
                'Routing',
                'RouteNotFoundException',
                'UrlGenerator'
            ],
            "SyntaxException" => [
                'syntax',
                'ParseError',
                'Parse',
                'parse',
                'unexpected'
            ],
            "UndefinedFunction" => [
                'Undefined',
                'function',
            ],
            "UndefinedVariable" => [
                'variable',
                'Undefined'
            ],
            "ViewException" => [
                'resolve',
                'dependency',
                'Parameter',
                'View',
                'blade',
                'ViewException',
                'BindingResolutionException',
                'FileViewFinder',
                'welcome',
                'layouts'
            ]
        ];
        return $errorKeyword;
    }
}