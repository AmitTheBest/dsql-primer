<?php
use atk4\dsql;
include 'config.php';

function q()
{
    return new dsql\Query(['connection'=>$GLOBALS['pdo']]);
}

function records_in_table($table)
{
    return q()->table($table)->field('count(*)')->getOne();
}

function show_employees($q)
{
    foreach($q as $employee)
    {
        echo "Name ${employee['first_name']} \n";
    }
}

echo "There are a total of ".
  records_in_table('employees')." employees, ".
  records_in_table('departments')." departments and ".
  records_in_table('salaries')." salary records\n";

echo "\nThe salary record was set by: ";


// Define a query for the smallest salary
$q_salary = q()
    ->table('salary')->order('salary desc')->limit(1);

$q_employee = q()
    // select only employee data with lowest salary
    ->table('employee')->where('emp_no',(clone $q_salary)->filed('emp_no'))

    // additionally select how many months position was taken
    ->field((clone $q_salary)->field($q_employee->expr('TIMESTAMPDIFF(MONTH, from_date, to_date)')),'months')
    
    ;


var_Dump($q_employee->getRow());

