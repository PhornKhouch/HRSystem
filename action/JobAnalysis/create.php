<?php
include("../../Config/conect.php");

if($_POST['type']=="Company")
{
    try {
        $code=$_POST['code'];
        $name=$_POST['name'];
        $status=$_POST['status'];
        $sql="INSERT INTO hrcompany values('$code','$name','$status')";
        if ($con->query($sql) === TRUE) {
            echo "Data Inserted";
        } else {
            throw new mysqli_sql_exception($con->error);
        }
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            echo "Duplicate Code: The company code '$code' already exists";
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
}
else if($_POST['type']=="Department")
{
    try {
        $code=$_POST['code'];
        $name=$_POST['name'];
        $status=$_POST['status'];
        $sql="INSERT INTO hrdepartment values('$code','$name','$status')";
        if ($con->query($sql) === TRUE) {
            echo "Data Inserted";
        } else {
            throw new mysqli_sql_exception($con->error);
        }
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            echo "Duplicate Code: The department code '$code' already exists";
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
}
else if($_POST['type']=="Division")
{
    try {
        $code=$_POST['code'];
        $name=$_POST['name'];
        $status=$_POST['status'];
        $sql="INSERT INTO hrdivision values('$code','$name','$status')";
        if ($con->query($sql) === TRUE) {
            echo "Data Inserted";
        } else {
            throw new mysqli_sql_exception($con->error);
        }
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            echo "Duplicate Code: The division code '$code' already exists";
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
}
else if($_POST['type']=="Level")
{
    try {
        $code=$_POST['code'];
        $name=$_POST['name'];
        $status=$_POST['status'];
        $sql="INSERT INTO hrlevel values('$code','$name','$status')";
        if ($con->query($sql) === TRUE) {
            echo "Data Inserted";
        } else {
            throw new mysqli_sql_exception($con->error);
        }
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            echo "Duplicate Code: The level code '$code' already exists";
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
}
else if($_POST['type']=="Position")
{
    try {
        $code=$_POST['code'];
        $name=$_POST['name'];
        $status=$_POST['status'];
        $sql="INSERT INTO hrposition values('$code','$name','$status')";
        if ($con->query($sql) === TRUE) {
            echo "Data Inserted";
        } else {
            throw new mysqli_sql_exception($con->error);
        }
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            echo "Duplicate Code: The position code '$code' already exists";
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
