<?php
function showCats($arr,$groups) {
    foreach ($arr as $cat): ?>
        <div class="card">
            <?php if ($cat['id'] > 0): ?>
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h4><?=$cat['name'] ?></h4>
                        </div>
                        <div class="col-auto">
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
    </div>
    <div class="card-body">
        <?php showCats($destinations,$groups); ?>
    </div>
</div>
