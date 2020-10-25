<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h1>{# Change balance for department $1 { <?=$department['name']?> } #}</h1>
            </div>
            <form method="post">
                <div class="card-body">
                    <?php if(isset($success)): ?>
                    <div class="alert alert-success" role="alert">
                        {# Balance changed #}
                    </div>
                    <?php endif;?>
                    <div class="row form-group">
                        <label>{# Current profile balance #}</label>
                        <input type="text" class="form-control" value="<?=$company['balance']?>" readonly>
                    </div>
                    <div class="row form-group">
                        <label>{# Current department balance #}</label>
                        <input type="text" class="form-control" value="<?=$department['balance']?>" readonly>
                    </div>
                    <div class="row form-group">
                        <label for="balance">{# Enter new balance #}</label>
                        <input type="text" class="form-control" id="balance" name="balance" value="<?=$department['balance']?>">
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
