<style>
    .accordion .card-body {
        display: none;
    }
    .accordion input[type=radio]:checked ~ .card-body {
        display: block;
    }

    .custom-control-input:checked ~ .card-header .custom-control-label::before {
        color: #fff;
        border-color: #007bff;
        background-color: #007bff;
    }

    .custom-control-input:checked ~ .card-header .custom-control-label::after {
        background-color: #fff;
        -webkit-transform: translateX(.75rem);
        transform: translateX(.75rem);
    }

    .accordion .card-header {
        padding: 0;
    }

    .accordion .card-header .custom-control-label {
        width: 100%;
        padding: .75rem 1.25rem;
    }

</style>

<form class="card" method="post" action="/user/<?=$user['id'] ?>">
    <input type="hidden" name="_method" value="patch">
    <div class="card-header">
        <h1>{# Edit user "$1" { <?=$user['login'] ?> } #}</h1>
    </div>
    <div class="card-body">
        <label for="login">{# Login #}</label>
        <input type="text" class="form-control" id="login" name="login" value="<?=$user['login'] ?>">

        <label for="surname">{# Surname #}</label>
        <input type="text" class="form-control" id="surname" name="surname" value="<?=$user['surname'] ?>">

        <label for="name">{# Name #}</label>
        <input type="text" class="form-control" id="name" name="name" value="<?=$user['name'] ?>">

        <label for="patronymic">{# Patronymic #}</label>
        <input type="text" class="form-control" id="patronymic" name="patronymic" value="<?=$user['patronym'] ?>">

        <label for="password">{# Password #}</label>
        <input type="password" class="form-control" id="password" name="password">

        <label for="con-password">{# Confirm password #}</label>
        <input type="password" class="form-control" id="con-password" name="con-password">

        <label for="iin">{# IIN #}</label>
        <input type="number" class="form-control" id="iin" name="iin" value="<?=$user['iin'] ?>">

        <label for="mobile">{# Mobile phone #}</label>
        <input type="tel" class="form-control" id="mobile" name="mobile" value="<?=$user['mobile'] ?>">

        <label for="role-main">{# Role #}</label>
        <div class="accordion" id="role-accordion">
            <div class="card">
                <input type="radio" name="main-role" value="1" id="role-main-1" class="custom-control-input" <?=$user['role'] == 1 ? 'checked' : '' ?>>
                <div class="card-header">
                    <div class="custom-control custom-switch">
                        <label for="role-main-1" class="custom-control-label">{# Correspondent #}</label>
                    </div>
                </div>
                <div class="for-role-1 card-body">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="rights[]" value="telegram.sign" id="role-1-sign" class="custom-control-input" <?=isset($rights['telegram.sign'])? 'checked' : '' ?>>
                        <label for="role-1-sign" class="custom-control-label">{# Can sign telegrams #}</label>
                    </div>

                    <label for="company">{# Company #}</label>
                    <select id="company" name="company" class="form-control">
                        <?php foreach ($companies as $company): ?>
                            <option value="<?=$company['id'] ?>" <?=$company['id'] == $user['company'] ?>><?=$company['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <!--END CORRESPONDENT-->
            <div class="card">
                <input type="radio" name="main-role" value="2" id="role-main-2" class="custom-control-input" <?=$user['role'] == 2 ? 'checked' : '' ?>>
                <div class="card-header">
                    <div class="custom-control custom-switch">
                        <label for="role-main-2" class="custom-control-label">{# Telegraphist #}</label>
                    </div>
                </div>
                <div class="card-body for-role-2">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="rights[]" id="role-2-0" class="custom-control-input" value="telegram.send" <?=isset($rights['telegram.send'])? 'checked' : '' ?>>
                        <label for="role-2-0" class="custom-control-label">{# Can send telegrams #}</label>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="rights[]" id="role-2-1" class="custom-control-input" value="telegram.confirm" <?=isset($rights['telegram.confirm'])? 'checked' : '' ?>>
                        <label for="role-2-1" class="custom-control-label">{# Can confirm sending telegrams #}</label>
                    </div>
                </div>
            </div>
            <!--END TELEGRAPHIST-->
            <div class="card">
                <input type="radio" name="main-role" value="3" id="role-main-3" class="custom-control-input" <?=$user['role'] == 3 ? 'checked' : '' ?>>
                <div class="card-header">
                    <div class="custom-control custom-switch">
                        <label for="role-main-3" class="custom-control-label">{# Administrator #}</label>
                    </div>
                </div>
                <div class="for-role-3 card-body">
                    <fieldset>
                        <legend>{# Certificates #}</legend>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="rights[]" id="role-3-0" class="custom-control-input" value="certificate.ca.set" <?=isset($rights['certificate.ca.set'])? 'checked' : '' ?>>
                            <label for="role-3-0" class="custom-control-label">{# Can set/rollback Certificate Authority #}</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="rights[]" id="role-3-1" class="custom-control-input" value="certificate.user.set" <?=isset($rights['certificate.user.set'])? 'checked' : '' ?>>
                            <label for="role-3-1" class="custom-control-label">{# Can set/rollback user certificate #}</label>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>{# Companies #}</legend>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="rights[]" id="role-3-18" class="custom-control-input" value="company.get" <?=isset($rights['company.get']) ? 'checked' : '' ?>>
                            <label for="role-3-18" class="custom-control-label">{# Can see companies #}</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="rights[]" id="role-3-2" class="custom-control-input" value="company.balance.bill" <?=isset($rights['company.balance.bill']) ? 'checked' : '' ?>>
                            <label for="role-3-2" class="custom-control-label">{# Can send bills to company #}</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="rights[]" id="role-3-3" class="custom-control-input" value="company.balance.set" <?=isset($rights['company.balance.set']) ? 'checked' : '' ?>>
                            <label for="role-3-3" class="custom-control-label">{# Can add/remove balance from the company #}</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="rights[]" id="role-3-4" class="custom-control-input" value="company.block" <?=isset($rights['company.block']) ? 'checked' : '' ?>>
                            <label for="role-3-4" class="custom-control-label">{# Can block the company #}</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="rights[]" id="role-3-5" class="custom-control-input" value="company.block.notpaid" <?=isset($rights['company.block.notpaid']) ? 'checked' : '' ?>>
                            <label for="role-3-5" class="custom-control-label">{# Can block the company for not paid #}</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="rights[]" id="role-3-6" class="custom-control-input" value="company.set" <?=isset($rights['company.set']) ? 'checked' : '' ?>>
                            <label for="role-3-6" class="custom-control-label">{# Can edit company #}</label>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>{# Database #}</legend>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="rights[]" id="role-3-7" class="custom-control-input" value="database.backup" <?=isset($rights['database.backup']) ? 'checked' : '' ?>>
                            <label for="role-3-7" class="custom-control-label">{# Can make a backup of the database  #}</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="rights[]" id="role-3-8" class="custom-control-input" value="database.get" <?=isset($rights['database.get']) ? 'checked' : '' ?>>
                            <label for="role-3-8" class="custom-control-label">{# Can see database backups #}</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="rights[]" id="role-3-9" class="custom-control-input" value="database.restore" <?=isset($rights['database.restore']) ? 'checked' : '' ?>>
                            <label for="role-3-9" class="custom-control-label">{# Can restore the database #}</label>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>{# Telegram costs #}</legend>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="rights[]" id="role-3-10" class="custom-control-input" value="telegram.cost.get" <?=isset($rights['telegram.cost.get']) ? 'checked' : '' ?>>
                            <label for="role-3-10" class="custom-control-label">{# Can read telegram costs #}</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="rights[]" id="role-3-11" class="custom-control-input" value="telegram.cost.set" <?=isset($rights['telegram.cost.set']) ? 'checked' : '' ?>>
                            <label for="role-3-11" class="custom-control-label">{# Can edit telegram costs #}</label>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>{# Destinations #}</legend>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="rights[]" id="role-3-12" class="custom-control-input" value="destinations.get" <?=isset($rights['destinations.get']) ? 'checked' : '' ?>>
                            <label for="role-3-12" class="custom-control-label">{# Can read destinations #}</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="rights[]" id="role-3-13" class="custom-control-input" value="destinations.set" <?=isset($rights['destinations.set']) ? 'checked' : '' ?>>
                            <label for="role-3-13" class="custom-control-label">{# Can edit destinations #}</label>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>{# Users #}</legend>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="rights[]" id="role-3-14" class="custom-control-input" value="user.get" <?=isset($rights['user.get']) ? 'checked' : '' ?>>
                            <label for="role-3-14" class="custom-control-label">{# Can read users #}</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="rights[]" id="role-3-15" class="custom-control-input" value="user.register" <?=isset($rights['user.register']) ? 'checked' : '' ?>>
                            <label for="role-3-15" class="custom-control-label">{# Can confirm user registration #}</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="rights[]" id="role-3-16" class="custom-control-input" value="user.set" <?=isset($rights['user.set']) ? 'checked' : '' ?>>
                            <label for="role-3-16" class="custom-control-label">{# Can add/edit users #}</label>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">{# Edit #}</button>
        <a href="/users" class="btn btn-secondary">{# Cancel #}</a>
    </div>
</form>
