<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Passport Guard
    |--------------------------------------------------------------------------
    |
    | Here you may specify which authentication guard Passport will use when
    | authenticating users. This value should correspond with one of your
    | guards that is already present in your "auth" configuration file.
    |
    | Passport がユーザーを認証する際に使用する認証ガードを指定します。
    | この値は "auth" 設定ファイルに定義済みのガードのいずれかと
    | 一致している必要があります。
    |
    */

    'guard' => 'web',

    'middleware' => [],

    /*
    |--------------------------------------------------------------------------
    | Encryption Keys
    |--------------------------------------------------------------------------
    |
    | Passport uses encryption keys while generating secure access tokens for
    | your application. By default, the keys are stored as local files but
    | can be set via environment variables when that is more convenient.
    |
    | Passport は安全なアクセストークンを生成する際に暗号化キーを使用します。
    | デフォルトではキーはローカルファイルとして保存されますが、その方が
    | 都合の良い場合は環境変数を介して設定することもできます。
    |
    */

    'private_key' => env('PASSPORT_PRIVATE_KEY'),

    'public_key' => env('PASSPORT_PUBLIC_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Passport Database Connection
    |--------------------------------------------------------------------------
    |
    | By default, Passport's models will utilize your application's default
    | database connection. If you wish to use a different connection you
    | may specify the configured name of the database connection here.
    |
    | デフォルトでは、Passport のモデルはアプリケーションのデフォルトの
    | データベース接続を使用します。別の接続を使用したい場合は、設定済みの
    | データベース接続名をここで指定できます。
    |
    */

    'connection' => env('PASSPORT_CONNECTION'),

];
