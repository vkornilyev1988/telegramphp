<form class="card" method="post">
    <div class="card-header">
        <h1>{# Add certificate authority #}</h1>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label for="common-name">{# Common name #}</label>
            <input type="text" class="form-control" name="name" id="common-name" required>
        </div>
        <div class="form-group">
            <label for="country">{# Country #}</label>
            <input type="text" class="form-control" name="country" id="country" required maxlength="2" placeholder="KZ">
        </div>
        <div class="form-group">
            <label for="organization">{# Organization #}</label>
            <input type="text" class="form-control" name="organization" id="organization" required>
        </div>

        <div class="form-group">
            <label for="email">{# Email #}</label>
            <input type="text" class="form-control" name="email" id="email">
        </div>

        <div class="form-group">
            <label for="days">{# Days #}</label>
            <input type="number" class="form-control" name="days" id="days">
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">{# Add #}</button>
        <a href="/certificates" class="btn btn-secondary">{# Cancel #}</a>
    </div>
</form>
