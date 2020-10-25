<form class="card" method="post">
    <div class="card-header">
        <h1>{# Telegram costs #}</h1>
    </div>
    <div class="card-body">
        <fieldset>
            <legend>{# Cost per word #}</legend>
            <div class="form-group">
                <label for="regular-cost">{# Regular telegram #}</label>
                <input type="number" class="form-control" id="regular-cost" name="regular" value="<?=$regular ?>">
            </div>
            <div class="form-group">
                <label for="urgent-cost">{# Urgent telegram #}</label>
                <input type="number" class="form-control" id="urgent-cost" name="urgent" value="<?=$urgent ?>">
            </div>
        </fieldset>
        <fieldset>
            <legend>{# Cost per telegram #}</legend>
            <div class="form-group">
                <label for="usage-cost">{#  #}</label>
                <input type="number" class="form-control" id="usage-cost" name="usage" value="<?=$usage ?>">
            </div>
        </fieldset>
    </div>
    <div class="card-footer">
        <button class="btn btn-primary" type="submit">{# Save #}</button>
        <a href="" class="btn btn-secondary">{# Cancel #}</a>
    </div>
</form>
