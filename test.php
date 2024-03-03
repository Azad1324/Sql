<?php

include_once("Repos/Load.php");

$Sql = new Azad\Sql('127.0.0.1','root','',"AzadSql");

$Users = $Sql->Table("Users");

try {

    $Users->Insert()
        ->Key("ID")->Value('1212')
        ->Key("first_name")->Value('Mohammad')
        ->Key("last_name")->Value('Azad')
        ->Key("salary")->Value('20000000')
    ->End();

    $Users = $Users->Select("*");

    $User = $Users->WHERE("ID",1212);

    // 10% increase to salary.
    $NewSalary = $User->WorkOn("salary")->
        Tool("Percentage")
            -> Append(10)
        ->Close()
    ->Result();

    // Update salary
    $User->Manage()->Update($NewSalary,"salary");

    // Get Salary
    echo $User->FirstRow ()['salary'];
    // Result: 22 000 000

} catch (\Azad\Conditions\Exception $e) {
    var_dump($e->Debug);
    // The value of [USD] is equal to 400 - but you have defined (350) in the EqualTo
}


/*
INSERT DATA :
$Wallet = $Sql->Table("Wallet");

    $Wallet->Insert()
        ->Key("ID")->Value('100')
        ->Key("IRT")->Value('25000')
        ->Key("USD")->Value('300')
    ->End();


    $ManageWallet = $Wallet->WHERE("ID",13)->Manage ();

    $ManageWallet->Update(400,"USD");

    $ManageWallet
        ->Condition
            ->IF("USD")->EqualTo(350)
        ->End()
            ->Update(500,"USD");

    $ManageWallet
        ->Condition
            ->IF("IRT")->EqualTo(30000)
        ->End()
            ->Update(30000,"IRT");


*/