<?php
function showCats($arr,$groups) {
    foreach ($arr as $cat): ?>
        <div class="card mb-2">
            <?php if ($cat['id'] > 0): ?>
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h4><?=$cat['name'] ?></h4>
                        </div>
                        <div class="col-auto">
                            |
                            <div class="d-inline">
                                <a href="#" class="delete">{# Delete #}</a>
                                <form class="d-none" action="/destinations/groups" method="post">
                                    <input type="hidden" name="id" value="<?=$cat['id'] ?>">
                                    <input type="hidden" name="_method" value="delete">
                                </form>
                            </div>
                            |
                            <div class="d-inline">
                                <a href="#" data-toggle="dropdown">{# Edit #}</a>
                                <form class="dropdown-menu" action="/destinations/group/<?=$cat['id'] ?>" method="post">
                                    <input type="hidden" name="_method" value="patch">
                                    <label for="dest-g-<?=$cat['id'] ?>-name">{# Name #}</label>
                                    <input id="dest-g-<?=$cat['id'] ?>-name" type="text" name="name" value="<?=$cat['name'] ?>" class="form-control">
                                    <label for="dest-g-<?=$cat['id'] ?>-group">{# Group #}</label>
                                    <select id="dest-g-<?=$cat['id'] ?>-group" class="form-control" name="parent">
                                        <option value="" <?=$cat['id'] == 0 ? 'selected' : '' ?>>{# Parent #}</option>
                                        <?php foreach ($groups as $group): ?>
                                            <option value="<?=$group['id'] ?>" <?=$cat['parent'] == $group['id'] ? 'selected' : '' ?>><?=$group['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="btn btn-primary">{# Edit #}</button>
                                </form>
                            </div>
                            |
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="card-body">
                <?php if (isset($cat['children'])) showCats($cat['children'],$groups); ?>
                <?php if (isset($cat['items'])): ?>
                    <?php foreach ($cat['items'] as $item): ?>
                        <div class="row">
                            <div class="col"><?=$item['name'] ?></div>
                            <div class="col-auto">
                                |
                                <div class="d-inline">
                                    <a href="#" class="delete">{# Delete #}</a>
                                    <form class="d-none" action="/destinations" method="post">
                                        <input type="hidden" name="id" value="<?=$item['id'] ?>">
                                        <input type="hidden" name="_method" value="delete">
                                    </form>
                                </div>
                                |
                                <div class="d-inline">
                                    <a href="#" data-toggle="dropdown">{# Edit #}</a>
                                    <form class="dropdown-menu" action="/destination/<?=$item['id'] ?>" method="post">
                                        <input type="hidden" name="_method" value="patch">
                                        <label for="dest-<?=$item['id'] ?>-name">{# Name #}</label>
                                        <input id="dest-<?=$item['id'] ?>-name" type="text" name="name" value="<?=$item['name'] ?>" class="form-control">
                                        <label for="dest-<?=$item['id'] ?>-group">{# Group #}</label>
                                        <select id="dest-<?=$item['id'] ?>-group" class="form-control" name="group">
                                            <option value="0" <?=$cat['id'] == 0 ? 'selected' : '' ?>>{# Parent #}</option>
                                            <?php foreach ($groups as $group): ?>
                                                <option value="<?=$group['id'] ?>" <?=$cat['id'] == $group['id'] ? 'selected' : '' ?>><?=$group['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="btn btn-primary">{# Edit #}</button>
                                    </form>
                                </div>
                                |
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach;
} ?>

<div class="card">
    <div class="card-header">
        <h1>{# Destinations #}</h1>
        |
        <div class="d-inline">
            <a href="#" data-toggle="dropdown">{# Add group #}</a>
            <form class="dropdown-menu" action="/destinations/group/add" method="post">
                <input type="hidden" name="_method" value="put">
                <label for="dest-g-add-name">{# Name #}</label>
                <input id="dest-g-add-name" type="text" name="name" class="form-control">
                <label for="dest-g-add-group">{# Group #}</label>
                <select id="dest-g-add-group" class="form-control" name="parent">
                    <?php foreach ($groups as $group): ?>
                        <option value="<?=$group['id'] ?>"><?=$group['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary">{# Add #}</button>
            </form>
        </div>
        |
        <div class="d-inline">
            <a href="#" data-toggle="dropdown">{# Add destination #}</a>
            <form class="dropdown-menu" action="/destination/add" method="post">
                <input type="hidden" name="_method" value="put">
                <label for="dest-add-name">{# Name #}</label>
                <input id="dest-add-name" type="text" name="name" class="form-control">
                <label for="dest-add-group">{# Group #}</label>
                <select id="dest-add-group" class="form-control" name="group">
                    <?php foreach ($groups as $group): ?>
                        <option value="<?=$group['id'] ?>"><?=$group['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary">{# Add #}</button>
            </form>
        </div>
        |
    </div>
    <div class="card-body">
        <?php showCats($destinations,$groups); ?>
    </div>
</div>
