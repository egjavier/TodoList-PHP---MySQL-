<?php

// Establish database configuration
// default ⬇
  $host = 'localhost';
  $username = 'root';
  $password = '';
  $database = 'todolist';

// Establish connection to database
  $con = new mysqli($host, $username, $password, $database);

// Check if the connection is working
  if ($con -> connect_error) {
    echo "Database connection failed";
  }

// check if someone send a post request
// $_SERVER is another superglobal to check if post is requested
  if($_SERVER['REQUEST_METHOD'] == 'POST') {

    // check if newtodo input is not empty
    // use isset()
      if(isset($_POST['newtodo'])) {
        // Add task to the database(MySQL)
          $sql = "INSERT INTO todo (name, status) VALUES ('".$_POST['newtodo']."', 'pending')";
          $con -> query($sql);
      } 
  }

// FUNCTION TO FETCH THE LIST FROM DATABASE
  function displayTodoList() {

    // we need to declare $con as global since we are using it inside the function
      global $con;

    // select all data
      $sql = "SELECT * FROM todo";
    // store the data from $result variable
      $result = $con -> query($sql);

    // check if array/data is not empty
    // num_rows is built-in
      if($result -> num_rows > 0) {

        echo '<ul class="list-disc">';
          // loop through the list by row
          // store the row info to $row variable using fetch_assoc() or the associated row
            while($row = $result -> fetch_assoc()) {
              if($row['status'] == 'pending') {
                echo '<li class="flex justify-between">' . $row['name'] . '<small class="italic">PENDING</small>' . '</li>';
              } else {
                echo '<li class="flex justify-between">' . $row['name'] . '<small class="italic">COMPLETED</small>' . '</li>';
              }
            }

        echo "</ul>";

      } else {
        echo "<p>There are currently no tasks in the list.</p>";
      }

  }


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            clifford: '#da373d',
          }
        }
      }
    }
  </script>
</head>

<body class="p-5">
  <main class="p-5 m-5 max-w-[1000px] border rounded-md shadow-md mx-auto">
    <h1 class="p-3 font-black text-center text-xl">
      📃 ToDo List
      <br/>
      <small class="text-center w-full font-medium text-xs">
        Powered by PHP + MySQL
      </small>
    </h1>
    <hr>

    <form class="px-3 mt-5 text-end" 
          action="index.php" 
          method="POST">

      <input type="text" 
              required
              name="newtodo" 
              id="newtodo"
              class="border border-2 rounded indent-2 w-full py-2 placeholder:italic mb-2" 
              placeholder="Write your todo here ..." />

      <button class="border border-sky-600 text-sky-600 px-3 py-1 rounded font-bold hover:shadow-md mt-2
                    w-36"
              type="submit">
        + Add
      </button>
    </form>

    <form action="#"
          method="POST"
          class="text-end">
      <button class=" border border-sky-600  bg-sky-600 text-white px-3 py-1 rounded hover:shadow-md
                    mx-3 mt-3 w-36">
        🗑️ Clear List
      </button>
    </form>

    <div class="px-10 py-3">
      <ul class="list-disc">
        <?php
          displayTodoList()
        ?>
      </ul>
    </div>
  </main>
</body>

</html>

