<form class="card" method="post" action="/company/<?=$company['id'] ?>">
    <input type="hidden" name="_method" value="patch">
    <div class="card-header">
        <h1>{# Edit company $1 { <?=$company['name'] ?> } #}</h1>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label for="name">{# Name #}</label>
            <input type="text" class="form-control" id="name" name="name" value="<?=$company['name'] ?>" required />
        </div>
        <div class="form-group">
            <label for="bin">{# BIN #}</label>
            <input type="text" class="form-control" id="bin" name="bin" value="<?=$company['bin'] ?>" required/>
        </div>
        <div class="form-group">
            <label for="iban">{# IBAN #}</label>
            <input type="text" class="form-control" id="iban" name="iban" value="<?=$company['iban'] ?>" required />
        </div>
        <div class="form-group">
            <label for="address">{# Legal address #}</label>
            <input type="text" class="form-control" id="address" name="address" value="<?=$company['address'] ?>" required />
        </div>
        <div class="form-group">
            <label for="site">{# Site #}</label>
            <input type="text" class="form-control" id="site" name="site" value="<?=$company['site'] ?>"/>
        </div>
        <div class="form-group">
            <label for="email">{# Accountant E-mail #}</label>
            <input type="text" class="form-control" id="email" name="accountant-email" value="<?=$company['accountant_email'] ?>" required />
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">{# Edit #}</button>
        <a href="/companies" class="btn btn-secondary">{# Cancel #}</a>
    </div>
</form>
