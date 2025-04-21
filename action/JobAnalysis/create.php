<?php
include("../../Config/conect.php");


if($_POST['type']=="Company")
{
    $code=$_POST['code'];
    $name=$_POST['name'];
    $status=$_POST['status'];
    $sql="INSERT INTO hrcompany values('$code','$name','$status')";
    if ($con->query($sql) === TRUE) {
        echo "Data Inserted";
    } else {
        echo "Error: " . $con->error;
    }
}
else if($_POST['type']=="Department")
{
    $code=$_POST['code'];
    $name=$_POST['name'];
    $status=$_POST['status'];
    $sql="INSERT INTO hrdepartment values('$code','$name','$status')";
    if ($con->query($sql) === TRUE) {
        echo "Data Inserted";
    } else {
        echo "Error: " . $con->error;
    }
}
else if($_POST['type']=="Division")
{
    $code=$_POST['code'];
    $name=$_POST['name'];
    $status=$_POST['status'];
    $sql="INSERT INTO hrdivision values('$code','$name','$status')";
    if ($con->query($sql) === TRUE) {
        echo "Data Inserted";
    } else {
        echo "Error: " . $con->error;
    }
}
else if($_POST['type']=="Level")
{
    $code=$_POST['code'];
    $name=$_POST['name'];
    $status=$_POST['status'];
    $sql="INSERT INTO hrlevel values('$code','$name','$status')";
    if ($con->query($sql) === TRUE) {
        echo "Data Inserted";
    } else {
        echo "Error: " . $con->error;
    }
}
else if($_POST['type']=="Position")
{
    $code=$_POST['code'];
    $name=$_POST['name'];
    $status=$_POST['status'];
    $sql="INSERT INTO hrposition values('$code','$name','$status')";
    if ($con->query($sql) === TRUE) {
        echo "Data Inserted";
    } else {
        echo "Error: " . $con->error;
    }
}
?>
