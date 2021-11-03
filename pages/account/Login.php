<?php

include 'templates/Header.php';

?>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="login-form bg-light mt-4 p-4">
                <form action="/login" method="post" class="row g-3">
                    <h4>Login</h4>
                    <div class="col-12">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" placeholder="account@email.com" required>
                    </div>
                    <div class="col-12">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe">
                            <label class="form-check-label" for="rememberMe"> Remember me</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-dark float-end">Login</button>
                    </div>
                </form>
                <hr class="mt-4">
                <div class="col-12">
                    <p class="text-center mb-0">Have not account yet? <a href="/register">Signup</a></p>
                </div>
            </div>
        </div>
    </div>
</div>