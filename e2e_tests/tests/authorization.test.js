import { Selector } from 'testcafe';
import { users } from '../config/common.users';
import { config } from '../config/common.config';

fixture `Проверка авторизации`
    .page `${config.url}`;

test('Авторизация', async t => {
    var authWindow = Selector('#asu_auth');
    var dashboardActiveTab = Selector('#myTab li.active');

    await t
        .click('p.asu_auth_info > a')
        .wait(500)
        .expect(authWindow).ok('Окно авторизации появилось')
        .typeText('#login', users.admin.login)
        .typeText('#password', users.admin.password)
        .click('#asu_auth button.btn.btn-primary')
        .wait(500)
        .expect(dashboardActiveTab.innerText).contains('Личный рабочий стол');
});