<div class="col-xl-6 offset-xl-3">
<form class="card" action="/login" method="post">
    <div class="card-header">
        <h1>{# Login #}</h1>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label for="login">{# Login #}</label>
            <input type="text" class="form-control" id="login" name="login">
        </div>
        <div class="form-group">
            <label for="password">{# Password #}</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">{# Log in #}</button>
        <a href="/register" class="btn btn-secondary">{# Register #}</a>
    </div>
</form>
</div>