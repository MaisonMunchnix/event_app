<?= view('template/header') ?>

<div class="container mt-5 col-md-4">

    <h3 class="text-center mb-3">Create User Account</h3>

    <form action="/register/account" method="post">
        <div class="mb-3">
            <label>Email Address*</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password*</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Confirm Password*</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>

        <button class="btn btn-primary w-100">Register</button>
    </form>

    <p class="mt-3 text-center">Already have an account? <strong><a href="/login" class="link-offset-2 link-underline link-underline-opacity-0">Login</a></strong></p>

</div>

<?= view('template/footer') ?>