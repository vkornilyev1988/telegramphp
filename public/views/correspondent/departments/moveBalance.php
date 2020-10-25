<form class="card" method="post">
    <div class="card-header">
        <h1>{# Move balance between company and departments #}</h1>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label for="move-from">{# From #}</label>
            <select class="form-control" id="move-from" name="from">
                <option value="c<?=$company['id'] ?>" data-balance="<?=$company['balance'] ?>"><?=$company['name'] ?> (<?=$company['balance'] ?> ₸)</option>
                <?php foreach ($departments as $department): ?>
                    <option value="<?=$department['id'] ?>" data-balance="<?=$department['balance'] ?>"><?=$department['name'] ?> (<?=$department['balance'] ?> ₸)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="move-to">{# To #}</label>
            <select class="form-control" id="move-to" name="to">
                <option value="c<?=$company['id'] ?>"><?=$company['name'] ?> (<?=$company['balance'] ?> ₸)</option>
                <?php foreach ($departments as $department): ?>
                    <option value="<?=$department['id'] ?>"><?=$department['name'] ?> (<?=$department['balance'] ?> ₸)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="move-sum">{# Sum #}</label>
            <input class="form-control" type="number" name="sum" id="move-sum" min="0" max="<?=$company['balance'] ?>"> Tg
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">{# Move #}</button>
        <a href="/departments" class="btn btn-secondary">{# Cancel #}</a>
    </div>
</form>
<script>
    let moveFrom = document.querySelector('#move-from');
    moveFrom.onchange = () => {
        document.querySelector('#move-sum').max = moveFrom.selectedOptions[0].dataset.balance;
    }
</script>
