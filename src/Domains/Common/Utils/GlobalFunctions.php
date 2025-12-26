<?php

use Illuminate\Support\Facades\Auth;

if (! function_exists('authorize')) {
    function authorize(string $policyClass, string $method, ...$arguments): bool {
        if (! auth()->check()) {
            return false;
        }

        return Gate::forUser(auth()->user())
            ->getPolicyFor($policyClass)
            ?->{$method}( auth()->user(), ...$arguments ) ?? false;
    }
}