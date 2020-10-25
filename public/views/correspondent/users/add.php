<form class="card" method="post" enctype="multipart/form-data" >
    <input type="hidden" name="_method" value="put">
    <div class="card-header">
        <h1>{# Add user to organization "$1" { <?=$company['name'] ?> } #}</h1>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label for="login">{# Login #}</label>
            <input class="form-control" type="text" id="login" name="login" required>
        </div>
        <div class="form-group">
            <label for="surname">{# Surname #}</label>
            <input class="form-control" type="text" id="surname" name="surname" required>
        </div>
        <div class="form-group">
            <label for="name">{# Name #}</label>
            <input class="form-control" type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="patronymic">{# Patronymic #}</label>
            <input class="form-control" type="text" id="patronymic" name="patronymic" >
        </div>
        <div class="form-group">
            <label for="department">{# Department #}</label>
            <select class="form-control" name="department" id="department">
                <option value="">{# No department #}</option>
                <?php foreach ($departments as $department): ?>
                    <option value="<?=$department['id'] ?>"><?=$department['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="iin">{# IIN #}</label>
            <input type="text" class="form-control" id="iin" name="iin" />
        </div>
        <div class="form-group">
            <label for="position">{# Position #}</label>
            <input class="form-control" type="text" id="position" name="position">
        </div>
        <div class="form-group">
            <label for="password">{# Password #}</label>
            <input class="form-control" type="password" id="password" name="password" required>
        </div>
        <!-- TODO: Rights -->
        <div class="form-group">
            <label for="mobile">{# Mobile phone #}</label>
            <input class="form-control" type="tel" id="mobile" name="mobile">
        </div>
        <div class="form-group">
            <label for="work-phone">{# Work phone #}</label>
            <input class="form-control" type="tel" id="work-phone" name="work-phone">
        </div>
        <div class="form-group custom-switch">
            <input type="checkbox" class="custom-control-input" id="can-sign" name="can-sign">
            <label for="can-sign" class="custom-control-label">{# Able to sign #}</label>
            <span class="approved"></span>
        </div>
        <div class="form-group">
            <label for="allowance-file">{# Allowance file #}</label>
            <input type="file" class="form-control-file" name="allowance-file" id="allowance-file">
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">{# Add #}</button>
        <a href="/users" class="btn btn-secondary">{# Cancel #}</a>
    </div>
</form>
