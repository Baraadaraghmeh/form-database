<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$firs_name = $last_name = $e_mail = "";
$firs_name_err = $last_name_err = $e_mail_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate firs_name
    $input_name = trim($_POST["firs_name"]);
    if(empty($input_name)){
        $firs_name_err = "Please enter a firs_name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $firs_name_err = "Please enter a valid firs_name.";
    } else{
        $firs_name = $input_name;
    }
    
    // Validate last_name
    $input_address = trim($_POST["last_name"]);
    if(empty($input_address)){
        $last_name_err = "Please enter an last_name.";     
    } else{
        $last_name = $input_address;
    }
    
    // Validate last_name
    $input_e_mail = trim($_POST["e_mail"]);
    if(empty($input_e_mail)){
        $e_mail_err = "Please enter the e_mail amount.";     
    } elseif(!ctype_digit($input_e_mail)){
        $e_mail_err = "Please enter a positive integer value.";
    } else{
        $e_mail = $input_e_mail;
    }
    
    // Check input errors before inserting in database
    if(empty($firs_name_err) && empty($last_name_err) && empty($e_mail_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO employees (firs_name, last_name, e_mail) VALUES (:firs_name, :last_name, :e_mail)";
 
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":firs_name", $param_firs_name);
            $stmt->bindParam(":last_name", $param_last_name);
            $stmt->bindParam(":e_mail", $param_e_mail);
            
            // Set parameters
            $param_name = $firs_name;
            $param_address = $last_name;
            $param_e_mail = $e_mail;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        unset($stmt);
    }
    
    // Close connection
    unset($pdo);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($firs_name_err)) ? 'has-error' : ''; ?>">
                            <label>firs_name</label>
                            <input type="text" name="firs_name" class="form-control" value="<?php echo $firs_name; ?>">
                            <span class="help-block"><?php echo $firs_name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($last_name_err)) ? 'has-error' : ''; ?>">
                            <label>last_name</label>
                            <textarea name="last_name" class="form-control"><?php echo $last_name; ?></textarea>
                            <span class="help-block"><?php echo $last_name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($e_mail_err)) ? 'has-error' : ''; ?>">
                            <label>e_mail</label>
                            <input type="text" name="e_mail" class="form-control" value="<?php echo $e_mail; ?>">
                            <span class="help-block"><?php echo $e_mail_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>