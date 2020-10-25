<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h1>{# Add balance #}</h1>
            </div>
            <form method="post" action="/balance/add">
                <div class="card-body">
                    <div class="row form-group">
                        <label for="sum">{# Enter amount #}</label>
                        <input type="text" class="form-control" id="sum" name="sum">
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">{# Next #}</button>
                    <a class="btn btn-secondary" href="/telegrams">{# Cancel #}</a>
                </div>
            </form>
        </div>
    </div>
</div>
