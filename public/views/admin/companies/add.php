<form class="card" method="post">
    <input type="hidden" name="_method" value="put">
    <div class="card-header">
        <h1>{# Add company #}</h1>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label for="name">{# Name #}</label>
            <input type="text" class="form-control" id="name" name="name" required />
        </div>
        <div class="form-group">
            <label for="bin">{# BIN #}</label>
            <input type="text" class="form-control" id="bin" name="bin" required/>
        </div>
        <div class="form-group">
            <label for="iban">{# IBAN #}</label>
            <input type="text" class="form-control" id="iban" name="iban" required />
        </div>
        <div class="form-group">
            <label for="address">{# Legal address #}</label>
            <input type="text" class="form-control" id="address" name="address" required />
        </div>
        <div class="form-group">
            <label for="site">{# Site #}</label>
            <input type="text" class="form-control" id="site" name="site" />
        </div>
        <div class="form-group">
            <label for="email">{# Accountant E-mail #}</label>
            <input type="text" class="form-control" id="email" name="accountant-email" required />
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">{# Add #}</button>
        <a href="/companies" class="btn btn-secondary">{# Cancel #}</a>
    </div>
</form>
