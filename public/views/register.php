<form class="card" method="post" enctype="multipart/form-data">
    <div class="card-header">
        <h1>{# Register #}</h1>
    </div>
    <div class="card-body">
        <fieldset >
            <legend>{# Identification #}</legend>
            <div class="form-group">
                <label for="login">{# E-mail #}</label>
                <input type="text" class="form-control" id="login" name="login" required />
            </div>
            <div class="form-group">
                <label for="surname">{# Surname #}</label>
                <input type="text" class="form-control" id="surname" name="surname" required />
            </div>
            <div class="form-group">
                <label for="name">{# Name #}</label>
                <input type="text" class="form-control" id="name" name="name" required />
            </div>
            <div class="form-group">
                <label for="patronym">{# Patronymic #}</label>
                <input type="text" class="form-control" id="patronym" name="patronym" />
            </div>
            <div class="form-group">
                <label for="iin">{# IIN #}</label>
                <input type="text" class="form-control" id="iin" name="iin" />
            </div>
            <div class="form-group">
                <label for="password">{# Password #}</label>
                <input type="password" class="form-control" id="password" name="password" required>
                       <!--pattern="^(?=.*[A-Z].*[A-Z])(?=.*[!@#$&*])(?=.*[0-9].*[0-9])(?=.*[a-z].*[a-z].*[a-z]).{8,}$" title="Password must be at least 8 characters and contain at least 2 capital letters, 1 special symbol, 2 numbers and 3 lowercase characters"/>-->
            </div>
            <div class="form-group">
                <label for="con-password">{# Confirm password #}</label>
                <input type="password" class="form-control" id="con-password" name="con-password" required />
            </div>
        </fieldset>
        <fieldset>
            <legend>{# Contact data #}</legend>
            <!--<div class="form-group">
                <label for="email">{# E-mail #}</label>
                <input type="email" class="form-control" id="email" name="email" required />
            </div>-->
            <div class="form-group">
                <label for="mobile">{# Mobile phone #}</label>
                <input type="tel" class="form-control" id="mobile" name="mobile" />
            </div>
            <div class="form-group">
                <label for="work-number">{# Work phone #}</label>
                <input type="tel" class="form-control" id="work-number" name="work" />
            </div>
        </fieldset>
        <fieldset>
            <legend class="custom-switch">
                <input type="checkbox" class="custom-control-input decider" id="is-director" name="is-director">
                <label for="is-director" class="custom-control-label">{# I am the Director #}</label>
            </legend>
            <div class="for-is-director-1">
                <label for="assign-file">{# Order on assigning #}</label>
                <input type="file" class="form-control-file" id="assign-file" name="assign-file">
            </div>
            <div class="for-is-director-0">
                <div class="form-group">
                    <label for="allowance-file">{# Order on Allowance #}</label>
                    <input type="file" class="form-control-file" id="allowance-file" name="allow-file">
                </div>
                <div class="form-group">
                    <label for="position">{# Position #}</label>
                    <input type="text" class="form-control" id="position" name="position" />
                </div>
                <fieldset>
                    <legend>{# Director #}</legend>
                    <div class="form-group">
                        <label for="dir-surname">{# Surname #}</label>
                        <input type="text" class="form-control" id="dirsurname" name="director[surname]" />
                    </div>
                    <div class="form-group">
                        <label for="dir-name">{# Name #}</label>
                        <input type="text" class="form-control" id="dir-name" name="director[name]" />
                    </div>
                    <div class="form-group">
                        <label for="dir-patronymic">{# Patronymic #}</label>
                        <input type="text" class="form-control" id="dir-patronymic" name="director[patronym]" />
                    </div>
                </fieldset>
            </div>
        </fieldset>
        <div class="form-group custom-switch">
            <input type="checkbox" class="custom-control-input" id="can-sign" name="can-sign" />
            <label for="can-sign" class="custom-control-label">{# Able to sign telegrams #}</label>
        </div>
        <fieldset>
            <legend>{# Organization information #}</legend>
            <div class="form-group">
                <label for="org-name">{# Name #}</label>
                <input type="text" class="form-control" id="org-name" name="org[name]" required />
            </div>
            <div class="form-group">
                <label for="org-bin">{# BIN #}</label>
                <input type="text" class="form-control" id="org-bin" name="org[bin]" required/>
            </div>
            <div class="form-group">
                <label for="org-iban">{# IBAN #}</label>
                <input type="text" class="form-control" id="org-iban" name="org[iban]" required />
            </div>
            <div class="form-group">
                <label for="org-address">{# Legal address #}</label>
                <input type="text" class="form-control" id="org-address" name="org[address]" required />
            </div>
            <div class="form-group">
                <label for="org-site">{# Site #}</label>
                <input type="text" class="form-control" id="org-site" name="org[site]" />
            </div>
            <div class="form-group">
                <label for="org-email">{# Accountant E-mail #}</label>
                <input type="text" class="form-control" id="org-email" name="org[accountant-email]" required />
            </div>
        </fieldset>
        <div class="form-group">
            <label for="const-documents">{# Constituent documents #}</label>
            <input type="file" class="form-control-file" id="const-documents" name="const-documents">
        </div>
        <div class="form-group">
            <label for="decision">{# Decision #}</label>
            <input type="file" class="form-control-file" id="decision" name="decision">
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">{# Register #}</button>
        <a href="index.html" class="btn btn-secondary">{# Cancel #}</a>
    </div>
</form>
<script>
    let formObj = document.querySelector('form');
    formObj.onsubmit = () => {
        //Check for passwords
        if (formObj.getElementById('password').value !== formObj.getElementById('con-password').value)
            return false;
        //Check for iin if can sign telegrams
        if (formObj.getElementById('can-sign').checked && !formObj.getElementById('iin').value)
            return false;

        //IF director
        //THEN check assign-file
        //ELSE check allowance-file
        if (formObj.getElementById('is-director').checked) {
            if (! formObj.getElementById('assign-file').value)
                return false;
        } else {
            if (! formObj.getElementById('allowance-file').value)
                return false;
        }
        return true;
    };
    document.querySelectorAll('.decider').forEach(
        it => {
            it.addEventListener('change', ev => {
                document.querySelectorAll('[class*="for-' + it.name + '-"]').forEach( cl => cl.style.display = "none");
                let selector = '.for-' + it.name + '-' + (it.tagName === 'SELECT' ? it.value : (it.checked ? '1' : '0'));
                document.querySelectorAll(selector).forEach( cl => cl.style.display = "block")
            });
            it.dispatchEvent(new Event('change'));
        }
    );
</script>
