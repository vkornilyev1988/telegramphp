<form class="row" method="post">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h1>{# New telegram #}</h1>
            </div>
            <div class="card-body">
                <div class="row custom-control custom-switch">
                    <input class="custom-control-input" id="is-urgent" type="checkbox" name="is-urgent" value="1" <?=$telegram['is_urgent'] ? 'checked' : '' ?>>
                    <label class="custom-control-label" for="is-urgent">{# Urgent #}</label>
                </div>
                <div class="row form-group">
                    <label for="points">{# Where, whom #}</label>
                    <!--<input class="form-control" type="text" name="destination" value="<?=$telegram['destination'] ?>" id="destination">-->
                    <select id="points" class="select2 form-control" name="points[]" multiple="multiple">
						<?php foreach($points as $point):?>
                            <option value="<?=$point['id']?>" <?=$point['selected'] ? 'selected' : '' ?>><?=$point['name']?></option>
						<?php endforeach;?>
                    </select>
                </div>
                <div class="row form-group">
                    <label for="message">{# Message #}</label>
                    <textarea id="message" name="message" class="form-control"><?=$telegram['message'] ?></textarea>
                </div>
                <div class="row form-group">
                    <label for="con-message">{# Repeat message #}</label>
                    <span class="con-status"></span>
                    <textarea id="con-message" class="form-control"><?=$telegram['message'] ?></textarea>
                </div>

                <div class="row form-group">
                    <label class="col-2">{# EDS #}:</label>
                    <span class="eds-info col"><?=isset($telegram['sign']) ? $telegram['sign'] : '' ?></span>
                </div>
                <div class="row form-group">
                    <label class="col-2">{# Actor #}:</label>
                    <div class="col-4">
                        <span class="actor-name">
                            <?=$telegram['surname'] ?>
                            <?=$telegram['name'] ?>
                            <?=$telegram['patronym'] ?>
                        </span>
                    </div>
                    <div class="col-6">
                        <label>{# Phone #}:</label>
                        <span class="actor-phone"><?=$telegram['mobile'] ?>, <?=$telegram['work_phone'] ?></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-6">
                        <label>{# Words count #}:</label>
                        <span class="word-count"><?=$telegram['wordcount'] ?></span>
                    </div>
                    <div class="col-6">
                        <label>{# Total cost #}:</label>
                        <span class="cost-value"><?=$telegram['cost'] ?></span>
                        <span class="cost-sign">₸</span>
                    </div>
                </div>

            </div>
            <div class="card-footer text-right">
                <button class="btn btn-primary" formaction="/telegram/<?=$telegram['id'] ?>/save">{# Save #}</button>
                <button class="btn btn-primary" formaction="/telegram/<?=$telegram['id'] ?>/send">{# Send #}</button>
                <a class="btn btn-secondary" href="/telegrams">{# Cancel #}</a>
            </div>
        </div>
    </div>
</form>
<script>
    let wordCostRegular = <?=$wordCost ?>;
    let wordCostUrgent = <?=$wordCostUrgent ?>;

    document.querySelector('#message').oninput = () => {
        let isUrgent = document.getElementById('is-urgent').checked;
        let wordCost = isUrgent ? wordCostUrgent : wordCostRegular;
        let words = countWords('#message');
        document.querySelector('.word-count').innerHTML = words;
        document.querySelector('.cost-value').innerHTML = words * wordCost;
    };

    document.getElementById('is-urgent').oncheck = () => {
        document.querySelector('#message').dispatchEvent(new Event('input'));
    };

    function countWords(el) {
        if (typeof el === "string")
            el = document.querySelector(el);
        let a = el.value
            .replace(/[аАбБвВгГдДеЕёЁжЖзЗиИйЙкКлЛмМнНоОпПрРсСтТуУфФхХцЦчЧшШщЩъЪыЫьЬэЭюЮяЯ\w]/g,'a')
            .match(/\b[a-]+\b|\b[0-9.]+|\b[^ \n]/g);
        console.log(a);
        return a.length;
    }
</script>
