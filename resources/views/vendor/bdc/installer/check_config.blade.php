@extends('views::layout')

@section('title', 'Home')

@section('content')
    <div class="p-sm-25 p-15 bd-one bd-t-zero bd-c-stroke bd-ra-8 bd-tl-ra-0 bd-tr-ra-0 bg-white mb-25">
        <h4 class="flex-row-reverse fs-6 my-1 p-10 rounded-2 section-title text-black" style="background-color: #c6e4e9 !important;">
            Please check your (PHP) configuration</h4>
        <div class="table-responsive">
            <table class="table table-borderless table-bordered">
                <thead>
                <tr>
                    <th scope="col">Required Version</th>
                    <th scope="col">Current Version</th>
                    <th scope="col">Status</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><strong>^8.0.0</strong></td>
                    <td><strong>{{ phpversion() }}</strong></td>
                    <td class="w-105"><span
                            class="status {{ phpversion() < 8.0 ? 'error' : '' }}">{{ phpversion() < 8.0 ? 'Error' : 'Ok' }}</span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4 class="flex-row-reverse fs-6 my-1 p-10 rounded-2 section-title text-black" style="background-color: #c6e4e9 !important;">
            Please check all extension or settings are installed/enabled ?</h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">Extensions/Settings</th>
                    <th scope="col">Status</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>MySQLi</td>
                    <td class="w-105"><span
                            class="status {{ !extension_loaded('mysqli') ? 'error' : '' }}">{{ !extension_loaded('mysqli') ? 'Error' : 'Ok' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>GD</td>
                    <td class="w-105"><span
                            class="status {{ !extension_loaded('gd') ? 'error' : '' }}">{{ !extension_loaded('gd') ? 'Error' : 'Ok' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>cURL</td>
                    <td class="w-105"><span
                            class="status {{ function_exists('curl_version') ? '' : 'error' }}">{{ function_exists('curl_version') ? 'Ok' : 'Error' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>allow_url_fopen</td>
                    <td class="w-105"><span
                            class="status {{ ini_get('allow_url_fopen') ? '' : 'error' }}">{{ ini_get('allow_url_fopen') ? 'Ok' : 'Error' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>OpenSSL PHP Extension</td>
                    <td class="w-105"><span
                            class="status {{ !extension_loaded('openssl') ? 'error' : '' }}">{{ !extension_loaded('openssl') ? 'Error' : 'Ok' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>PDO PHP Extension</td>
                    <td class="w-105"><span
                            class="status {{ !extension_loaded('pdo') ? 'error' : '' }}">{{ !extension_loaded('pdo') ? 'Error' : 'Ok' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>BCMAth PHP Extension</td>
                    <td class="w-105"><span
                            class="status {{ !extension_loaded('bcmath') ? 'error' : '' }}">{{ !extension_loaded('bcmath') ? 'Error' : 'Ok' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>Ctype PHP Extension</td>
                    <td class="w-105"><span
                            class="status {{ !extension_loaded('ctype') ? 'error' : '' }}">{{ !extension_loaded('ctype') ? 'Error' : 'Ok' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>Fileinfo PHP Extension</td>
                    <td class="w-105"><span
                            class="status {{ !extension_loaded('fileinfo') ? 'error' : '' }}">{{ !extension_loaded('fileinfo') ? 'Error' : 'Ok' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>MBstring PHP Extension</td>
                    <td class="w-105"><span
                            class="status {{ !extension_loaded('mbstring') ? 'error' : '' }}">{{ !extension_loaded('mbstring') ? 'Error' : 'Ok' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>Tokenizer PHP Extension</td>
                    <td class="w-105"><span
                            class="status {{ !extension_loaded('tokenizer') ? 'error' : '' }}">{{ !extension_loaded('tokenizer') ? 'Error' : 'Ok' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>XML PHP Extension</td>
                    <td class="w-105"><span
                            class="status {{ !extension_loaded('xml') ? 'error' : '' }}">{{ !extension_loaded('xml') ? 'Error' : 'Ok' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>Json PHP Extension</td>
                    <td class="w-105"><span
                            class="status {{ !extension_loaded('json') ? 'error' : '' }}">{{ !extension_loaded('json') ? 'Error' : 'Ok' }}</span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <h4 class="bg-body-bg flex-row-reverse fs-6 my-1 p-10 rounded-2 section-title text-black" style="background-color: #c6e4e9 !important;">
            Check you have writable permission on the following folders or files ?</h4>
        <div class="table-responsive">
            <table class="table table-borderless table-bordered">
                <thead>
                <tr>
                    <th scope="col">Folder/File</th>
                    <th scope="col">Status</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><strong>./resources</strong></td>
                    <td class="w-105">
                                            <span
                                                class="status {{ File::exists(base_path('resources')) && $resource_value == 1 ? '' : 'error' }}">{{ File::exists(base_path('resources')) && $resource_value == 1 ? 'Ok' : 'Error' }}</span>
                    </td>
                </tr>
                <tr>
                    <td><strong>./public</strong></td>
                    <td class="w-105">
                                            <span
                                                class="status {{ File::exists(base_path('public')) && $public_value == 1 ? '' : 'error' }}">{{ File::exists(base_path('public')) && $public_value == 1 ? 'Ok' : 'Error' }}</span>
                    </td>
                </tr>
                <tr>
                    <td><strong>./storage</strong></td>
                    <td class="w-105">
                                            <span
                                                class="status {{ File::exists(base_path('storage')) && $storage_value == 1 ? '' : 'error' }}">{{ File::exists(base_path('storage')) && $storage_value == 1 ? 'Ok' : 'Error' }}</span>
                    </td>
                </tr>
                <tr>
                    <td><strong>.env</strong></td>
                    <td class="w-105">
                                            <span
                                                class="status {{ File::exists(base_path('.env')) && $env_value == 1 ? '' : 'error' }}">{{ File::exists(base_path('.env')) && $env_value == 1 ? 'Ok' : 'Error' }}</span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <form action="{{ route('BdcInstaller::check-validation') }}" method="post">
            @csrf
            <input type="hidden" name="resources"
                   value="{{ File::exists(base_path('resources')) && $resource_value == 1 ? 1 : 0 }}">
            <input type="hidden" name="public"
                   value="{{ File::exists(base_path('public')) && $public_value == 1 ? 1 : 0 }}">
            <input type="hidden" name="storage"
                   value="{{ File::exists(base_path('storage')) && $storage_value == 1 ? 1 : 0 }}">
            <input type="hidden" name="env" value="{{ File::exists(base_path('.env')) && $env_value == 1 ? 1 : 0 }}">
            <button class="bg-para-text w-100 py-10 rounded-3 text-bg-success fw-1000 border-0 " style="background-color: #24bbd3!important;">Next</button>
        </form>
    </div>
@endsection
