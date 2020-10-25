<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h1>{# Change password #}</h1>
            </div>
            <form method="post">
                <div class="card-body">
                    <?php if(isset($error) and $error == 'old-password'): ?>
                    <div class="alert alert-danger" role="alert">
                        {# Old password entered incorrectly #}
                    </div>
                    <?php endif;?>
                    <?php if(isset($error) and $error == 'new-password'): ?>
                    <div class="alert alert-danger" role="alert">
                        {# Passwords do not match #}
                    </div>
                    <?php endif;?>
                    <?php if(isset($success)): ?>
                    <div class="alert alert-success" role="alert">
                        {# Password changed #}
                    </div>
                    <?php endif;?>
                    <div class="row form-group">
                        <label for="old-password">{# Enter old password #}</label>
                        <input type="password" class="form-control" id="old-password" name="old-password">
                    </div>
                    <div class="row form-group">
                        <label for="new-password">{# Enter new password #}</label>
                        <input type="password" class="form-control" id="new-password" name="new-password">
                    </div>
                    <div class="row form-group">
                        <label for="retry-new-password">{# Retry new password #}</label>
                        <input type="password" class="form-control" id="retry-new-password" name="retry-new-password">
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">{# Save #}</button>
                    <a class="btn btn-secondary" href="/profile">{# Cancel #}</a>
                </div>
            </form>
        </div>
    </div>
</div>
