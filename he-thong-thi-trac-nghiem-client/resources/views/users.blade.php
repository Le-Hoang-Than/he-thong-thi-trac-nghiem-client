<!DOCTYPE html>
<html>
<head>
    <title>Users</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

<h2 class="mb-4">User List</h2>
<form action="/add-user" method="POST" class="mb-3">
    @csrf

    <div class="row">
        <div class="col-md-6">
            <input type="text" name="name" class="form-control" placeholder="Nhập tên" required>
        </div>

        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">
                Add User
            </button>
        </div>
    </div>
</form>
<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
        </tr>
    </thead>

    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user['id'] }}</td>
            <td>{{ $user['name'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>