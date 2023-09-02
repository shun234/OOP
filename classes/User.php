<?php

require_once "Database.php";


class User extends Database
{
  //store()
  public function store($request)
  {
    // $request will catch all the data from $_POST in action>register.php
    /*
    $_POST['first_name']; -> $request['first_name']
    $_POST['last_name'];  -> $request['last_name']
    $_POST['username'];   -> $request['username']
    $_POST['password'];   -> $request['password']
    */
    $first_name = $request['first_name'];
    $last_name = $request['last_name'];
    $username  = $request['username'];
    $password  = $request['password'];

    $password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (first_name, last_name, username, password) VALUES ('$first_name', '$last_name', '$username', '$password')";

    if($this->conn->query($sql)){
      header('location: ../views');  //go to index.php which is the login page
      exit;
    } else {
      die('Error creating the user: ' . $this->conn->error);
    }
  }
  // login()
  public function login($request)
  {
      /*
          $request will catch the data from the $_POST in actions>login.php
          $_POST['username'];    -->    $request['username'];
          $_POST['password'];    -->    $request['password'];
      */
      $username = $request['username'];
      $password = $request['password'];
      $sql = "SELECT * FROM users WHERE username = '$username'";
      $result = $this->conn->query($sql);
      // $result holds the record of the user


      # check the username
      if ($result->num_rows == 1){
          #check if the password is correct
          $user = $result->fetch_assoc();
          // $user = ['id' => 1, 'first_name' => 'shun', 'last_name' => 'kondo', 'username' => 'abc', 'password' => '234SD#$@s']
          /*
              $user is now the array name

              $user['id'];   get the value 1
              $user['username'];    get the value 'abc'
              $user['first_name']   get the value 'shun'
              $user['last_name']    get the value 'kondo'
              $user['password']     get the value of password from the database
          */
          if (password_verify($password, $user['password'])){
              # Create session variables for future use.
              session_start();
              $_SESSION['id']         = $user['id'];
              $_SESSION['username']   = $user['username'];
              $_SESSION['full_name']  = $user['first_name'] . " " . $user['last_name'];
              header('location: ../views/dashboard.php');
              exit;
          } else {
              die('Password is incorrect');
          }
      } else {
          die('Username not found.');
      }
  }
  // end login()



  //getAllusers()
  public function getAllUsers()
  {
    $sql = "SELECT id, first_name, last_name, username, photo FROM users";

    if ($result = $this->conn->query($sql)){
      //$result holds all theusers from the database
      return $result;
    }else {
      die('Error retrieving all users' . $this->conn->error);
    }
  }
  // end getAllUsers()


  //getUser()
  public function getUser()
  {
    $id = $_SESSION['id'];

    $sql = "SELECT first_name, last_name, username, photo FROM users WHERE id = $id";

    if ($result = $this->conn->query($sql)){
      return $result->fetch_assoc();
    }else {
      die('Error retrieving the user: ' . $this->conn->error);
    }
  }
  // end getUser()


  public function update($request, $files) {

    session_start();

    $id = $_SESSION['id'];
    $first_name = $request['first_name'];
    $last_name = $request['last_name'];
    $username = $request['username'];
    $photo = $files['photo']['name'];
    $tmp_photo = $files['photo']['tmp_name'];

    $sql ="UPDATE users SET first_name = '$first_name', last_name = '$last_name',username = '$username' WHERE id =$id";
    
    if($this->conn->query($sql)){
      
      $_SESSEION['$username'] = $username;
      $_SESSEION['full_name'] = "$first_name $last_name";

      if($photo){
        $sql = "UPDATE users SET photo = '$photo' WHERE id = $id";
        $destination = "../assets/images/$photo";

        if ($this->conn->query($sql)){
          if(move_uploaded_file($tmp_photo, $destination)){
            header('location: ../views/dashboard.php');
            exit;
          }else{
            die('Error moving the photo. ');
          }
        }else{
          die('Error uploading photo: ' . $this->conn->error);
        }
      }
      header('location: ../views/dashboard.php');
      exit;
    }else{
      die('Error updating your account: ' . $this->conn->error);
    }
    
  }

  public function delete(){
    
    session_start();

    $id = $_SESSION['id'];

    $sql = "DELETE FROM users WHERE id = $id";

    if($this->conn->query($sql)){
      $this->logout();
    }else{
      die('Error deleting your account: ' .$this->conn->error);
    }
  }



  //logout()
  public function logout() {
    session_start();
    session_unset();
    session_destroy();

    header('location: ../views');
    exit;
  }
//end logout()

}


?>


