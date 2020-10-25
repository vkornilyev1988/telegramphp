<form class="card" method="post" action="/department/<?=$department['id'] ?>">
    <input type="hidden" name="_method" value="patch">
    <div class="card-header">
        <h1>{# Edit department "$1" of organization "$2" { <?=$department['name'] ?>|<?=$company['name'] ?> } #}</h1>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label for="name">{# Name #}</label>
            <input type="text" class="form-control" id="name" name="name" value="<?=$department['name'] ?>">
        </div>
        <div class="form-group">
            <label for="users">{# Users #}</label>
            <select class="select2" id="users" name="users[]" multiple>
                <?php foreach ($users as $user): ?>
                    <option value="<?=$user['id'] ?>"
                        <?=$user['department'] == $department['id'] ? 'selected' : '' ?>
                    ><?=$user['surname'] ?> <?=$user['name'] ?> <?=$user['patronym'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">{# Add #}</button>
        <a href="/departments" class="btn btn-secondary">{# Cancel #}</a>
    </div>
</form>
