<?php


require 'vendor/autoload.php';
include_once("config.php");

use Azad\Database\Connection;

$Sql = new Connection(MySqlConfig::class);

$Sql->LoadPlugin("MyUsers",[]);

$Users = $Sql->Table("Users");

if(!$Users->RowExists("first_name","Mohammad3")){
    $Users->Insert()
        ->Key("first_name")->Value("Mohammad3")
        ->Key("last_name")->Value("Azad3")
        ->Key("status")->Value(MyProject\Enums\UserStatus::Active)
        ->Key("wallet")->Value("50000")
    ->End();
}

$Find = $Users->Select("*")->WHERE("user_id",1);

var_dump($Find->LastRow()->Result);

# Nested Update
$NewData = $Find->LastRow()
                ->Update
                    ->Key("wallet")->Value("10000")
                ->Push()
->Result;

var_dump($NewData);