@extends('errors.layout')

@section('code', '401')
@section('message', __('Unauthorized'))
@section('description', __('You must be logged in to access this page.'))