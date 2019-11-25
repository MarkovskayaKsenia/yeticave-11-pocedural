<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($outfit_categories as $value): ?>
                <li class="nav__item">
                    <a href="pages/all-lots.html"><?= checkUserData($value['description']); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <form class="form container<?= (count($errors) != 0) ? ' form--invalid' : ''; ?>" action="user-reg.php" method="post" autocomplete="off">
        <h2>Регистрация нового аккаунта</h2>
        <div class="form__item<?= isset($errors['email']) ? ' form__item--invalid' : ''; ?>">
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= checkUserData(getFormData($_POST, 'email')); ?>">
            <span class="form__error"><?= $errors['email'] ?? ''; ?></span>
        </div>
        <div class="form__item<?= isset($errors['password']) ? ' form__item--invalid' : ''; ?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?= checkUserData(getFormData($_POST, 'password')); ?>">
            <span class="form__error"><?= $errors['password'] ?? ''; ?></span>
        </div>
        <div class="form__item<?= isset($errors['name']) ? ' form__item--invalid' : ''; ?>">
            <label for="name">Имя <sup>*</sup></label>
            <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= checkUserData(getFormData($_POST, 'name')); ?>">
            <span class="form__error"><?= $errors['name'] ?? ''; ?></span>
        </div>
        <div class="form__item<?= isset($errors['message']) ? ' form__item--invalid' : ''; ?>">
            <label for="message">Контактные данные <sup>*</sup></label>
            <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?= checkUserData(getFormData($_POST, 'message')); ?></textarea>
            <span class="form__error"><?= $errors['message'] ?? ''; ?></span>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Зарегистрироваться</button>
        <a class="text-link" href="#">Уже есть аккаунт</a>
    </form>
</main>
