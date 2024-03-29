<?php foreach ($sale_ads as $key => $value): ?>
    <li class="lots__item lot">
        <div class="lot__image">
            <img src="../<?= $value['img_url']; ?>" width="350" height="260" alt="<?= $value['outfit_title']; ?>">
        </div>
        <div class="lot__info">
            <span class="lot__category"><?= checkUserData($value['outfit_category']); ?></span>
            <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?= $value['id']; ?>"><?= $value['outfit_title']; ?></a></h3>
            <div class="lot__state">
                <div class="lot__rate">
                    <span class="lot__amount"><?= ($value['bid_count'] != 0) ? declensionOfNouns($value['bid_count'], $bids_declension) : 'Стартовая цена'; ?></span>
                    <span class="lot__cost"><?= formatPrice($value['price'], true); ?></span>
                </div>
                <div class="lot__timer timer<?= ($expiry_times[$key][0] === '00') ? ' timer--finishing' : ''; ?>">
                    <?= $expiry_times[$key][0] . ':' . $expiry_times[$key][1]; ?>
                </div>
            </div>
        </div>
    </li>
<?php endforeach; ?>
