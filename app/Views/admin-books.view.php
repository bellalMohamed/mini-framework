<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="pricing.css" rel="stylesheet">
</head>

<body>

    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
        <h5 class="my-0 mr-md-auto font-weight-normal">Admin Dashboard</h5>
        <nav class="my-2 my-md-0 mr-md-3">
            <a class="p-2 text-dark" href="/admin/librarians">Librarian</a>
            <a class="p-2 text-dark" href="/admin/books">Books</a>
            <a class="p-2 text-dark" href="/admin/users">Users</a>
        </nav>
        <a class="btn btn-outline-primary" href="#">Logout</a>
    </div>

	<?php
		if (App\Session::exists('success')) {
			echo "<div class='alert alert-success' role='alert'>
		        ".App\Session::flash('success')."
		    </div>";
		}

		if (App\Session::exists('error')) {
			echo "<div class='alert alert-danger' role='alert'>
		        ".App\Session::flash('error')."
		    </div>";
		}
	?>


    <div class="container-fluid">

        <div class="row">
            <div class="card col-md-4 col-sm-12">
                <div class="card-header">
                    Add Librarian
                </div>
                <div class="card-body">
                    <form action="/admin/book/new" method="POST">
                        <div class="form-group">
                            <label for="book-name">Name</label>
                            <input type="text" name="name" class="form-control" id="book-name" placeholder="Name">
                        </div>
                        <div class="form-group">
                            <label for="book-author">Author</label>
                            <input type="text" name="author" class="form-control" id="book-author" placeholder="Author name">

                        </div>
                        <div class="form-group">
                            <label for="book-copies">Copies</label>
                            <input type="copies" name="copies" class="form-control" id="book-copies" placeholder="Copies">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                </div>
            </div>
            <div class="card col-md-8">
                <div class="card-header">
                    All Librarian
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Author</th>
                                <th scope="col">Copies</th>
                                <th scope="col">Book ID</th>
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php foreach ($books as $index => $book) {
                        		?>
                        		<tr>
	                                <th><?php echo $index + 1 ?></th>
	                                <td><?php echo $book->name ?></td>
                                    <td><?php echo $book->author ?></td>
                                    <td><?php echo $book->copies ?></td>
                                    <td><?php echo $book->book_id ?></td>
	                                <td>
                                        <a href="/admin/books/delete?id=<?php echo $book->id ?>" class="btn btn-danger">Delete</a>
                                    </td>
	                            </tr>

                        	<?php } ?>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

</body>

</html>
