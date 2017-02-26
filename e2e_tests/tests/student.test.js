import { Selector } from 'testcafe';
import { config } from '../config/common.config';
import { users} from '../config/common.users';

fixture `Студенты`
    .page `${config.url}`
    .beforeEach(async t => {
        var navbarSection = Selector('a.dropdown-toggle').withText('Прочее');
        var studentsLink = Selector('a').withText('Студенты');
        var studentsFeatureTitle = Selector('h2');

        await t
            .click('p.asu_auth_info > a')
            .wait(500)
            .typeText('#login', users.admin.login)
            .typeText('#password', users.admin.password)
            .click('#asu_auth button.btn.btn-primary')
            .click(navbarSection)
            .click(studentsLink);
    });

test('Создание студента', async t => {
    var addStudentButton = Selector('.menu_item_container a').nth(2);

    await t
        .click(addStudentButton);
})

test('Поиск студента', async t => {
    var searchBar = Selector('#search');
    var foundStudent = Selector('.typeahead.dropdown-menu a')
    var studentsInTable = Selector('#MainView table tr');
    var studentLink = Selector('#MainView table tr:nth(1) td:nth(3) a');

    await t
        .typeText(searchBar, 'Гибадатова Алина Ильгамовна')
        .wait(500)
        .click(foundStudent)
        // .expect(studentsInTable.length).eql(2, 'Найден только один студент');
        // .click(studentLink);
});