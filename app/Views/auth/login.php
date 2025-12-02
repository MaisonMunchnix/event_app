<?= view('template/header') ?>

<div class="container mt-5 col-md-4">

    <h3 class="text-center mb-3">Admin Login</h3>

    <form action="/login/process" method="post">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button class="btn btn-primary w-100">Login</button>
    </form>

</div>

<?= view('template/footer') ?>