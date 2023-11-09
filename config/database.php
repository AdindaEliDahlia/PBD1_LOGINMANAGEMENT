<?php

function getDatabaseConfig(): array{
    return [
        "database" => [
            "url" => "mysql:host=localhost:3306;dbname=php_login_mangement_test",
            "username" => "root",
            "password" => ""
        ],
        "prod" => [
            "url" => "mysql:host=localhost:3306;dbname=php_login_mangement",
            "username" => "root",
            "password" => ""
        ]
    ];
}
