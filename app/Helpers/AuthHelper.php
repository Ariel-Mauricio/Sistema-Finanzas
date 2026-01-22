<?php
/**
 * @var \Illuminate\Contracts\Auth\Factory|\Illuminate\Contracts\Auth\Guard
 */
function auth() {
    return \Illuminate\Support\Facades\Auth::getFacadeRoot();
}

/**
 * @return \App\Models\User|null
 */
function auth_user() {
    return \Illuminate\Support\Facades\Auth::user();
}
