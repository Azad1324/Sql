<?php


require 'vendor/autoload.php';
include_once("config.php");

use Azad\Database\Connection;
use Azad\Database\Jobs\Exception as ExceptionJob;

$Sql = new Connection(MySqlConfig::class);

$Users = $Sql->Table("Users");

/*
$Users->Insert()
    ->Key("first_name")->Value("Mohammad")
    ->Key("last_name")->Value("Azad")
    ->Key("status")->Value(MyProject\Enums\UserStatus::Active)
->End();
*/

$Find = $Users->Select("*")->WHERE("user_id",1);
$Data = $Find->LastRow();


var_dump($Data->Result['status']->value ?? "no value"); // 1
var_dump($Data->Result['status']->name); // "Active"
var_dump($Data->Result['status']->toPersian()); // فعال
var_dump($Data->Result['status'] == MyProject\Enums\UserStatus::Active); // true

$New = $Data->Update
    ->Key("status")->Value(MyProject\Enums\UserStatus::Disable)
->Push();

/*

Structure of Jobs

try {

    # ---- New Job

    # Save the users you intend to work on!

    # Orders you intend to execute.

    # Exception Errors

    # ---- Start Job

} catch (ExceptionJob $E) {

    # Handle Exception

}

*/


// ---- Trade example:
try {

    $Job1 = $Sql->NewJob();

    # Job1 -> Find (VALUE_PRIMARY_KEY) -> From (TABLE_NAME)
    $User1 = $Job1->Find(1)->From("Users");
    $User1_Wallet = $User1->Result['wallet'];
    $User2 = $Job1->Find(2)->From("Users");
    $User2_Wallet = $User2->Result['wallet'];

    $Amount = 10000;

    # Job1 -> Table (TABLE_NAME) -> SELECT (COLUMN_NAME) -> To (NEW_VALUE) -> Who? (UserObject)
    $Job1->Table("Users")->Select("wallet")->To($User1_Wallet + $Amount)->Who($User1);
    $Job1->Table("Users")->Select("wallet")->To($User2_Wallet - $Amount)->Who($User2);

    if ($User2_Wallet < $Amount) {
        $Job1->Exception(new ExceptionJob("User 2 does not have enough inventory",-1));
    }

    $Job1->Start();

} catch (ExceptionJob $E) {
    $message = match ($E->getCode()) {
        -1 => "User 2 you do not have enough balance, please recharge your account.",
        default => "There is a problem, please try it later."
    };
    print($message);
}



$Sql->CloseLog ();


/*

$Users = $Sql->Table("Users");
$ID = $Users->Insert()
    ->Key("first_name")->Value("F_".rand(1,99999))
    ->Key("last_name")->Value("L_".rand(1,99999))
->End();

$Find = $Users->Select("*")->WHERE("user_id",$ID);
$Data = $Find->LastRow();

$Result1 = $Data
    ->Update
        ->Key("first_name")->Value("Mo434ha424d32wew32")
        ->Key("last_name")->Value("A4224d123rwr331")
    ->Push()
->Result;
*/


