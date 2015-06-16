<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 28.05.12
 * Time: 13:42
 * To change this template use File | Settings | File Templates.
 */
class CCalendarController extends CBaseController {
    public function __construct() {
        $this->_smartyEnabled = true;

        parent::__construct();
    }
    /**
     * Главная страница - она и показывает сам календарь
     *
     * @return mixed
     */
    public function actionIndex() {
        // главная страница просмотра календаря
        // по умолчанию просматривается текущая неделя
        if (CRequest::getInt("resource_id") == 0) {
            if (!is_null(CSession::getCurrentPerson())) {
                $res = CSession::getCurrentPerson()->getResource();
            } else {
                $res = null;
            }
        } else {
            $res = CResourcesManager::getResourceById(CRequest::getInt("resource_id"));
        }

        if (is_null($res)) {
            $this->renderView("calendars.notAResource.tpl");
            return;
        }

        if (CRequest::getInt("calendar_id") == 0) {
            $calendar = $res->getDefaultCalendar();
        } else {
            foreach ($res->getCalendars()->getItems() as $i) {
                if ($i->getId() == CRequest::getInt("calendar_id")) {
                    $calendar = $i;
                }
            }
        }

        if (is_null($calendar)) {
            $this->renderView("calendars.calendarNotExists.tpl");
            return;
        }

        $this->addJSInclude("_modules/_calendar/_core.js");
        $this->addJSInclude("_core/jCalendar/fullcalendar.js");
        $this->addCSSInclude("_core/jCalendar/fullcalendar.css");
        $this->addCSSInclude("_core/jCalendar/fullcalendar.print.css");
        $this->addJSInlineInclude('
        $(function(){
            $("#fullCalendar").fullCalendar({
                enabled: true,
                header: {
                    left: "prev,next today",
                    center: "title",
                    right: "month,agendaWeek,agendaDay"
                },
                events: "?action=getEventsJSON&calendar_id='.$calendar->getId().'&resource_id='.$res->getId().'"
            });
        });
        ');

        $this->setData("resource", $res);
        $this->setData("calendar", $calendar);

        $this->renderView("_calendar/index.tpl");
    }
    /**
     * Добавление события в календарь
     */
    public function actionAdd() {
        $this->addJSInlineInclude('
        $(function(){
            $.datepicker.setDefaults( $.datepicker.regional[ "ru" ] );
            $("#eventStart").datepicker({
                dateFormat: "dd.mm.yy"
            });
            $("#eventEnd").datepicker({
                dateFormat: "dd.mm.yy"
            });
            $("#members_show").tagit({
                tagSource: "'.WEB_ROOT.'_modules/_resources/?action=getResourcesJSON",
                select: true,
                sortable: \'hande\',
                allowNewTags: false,
                triggerKeys: "comma"
            });
        });
        ');

        // http://trentrichardson.com/examples/timepicker/
        // http://akquinet.github.com/jquery-toastmessage-plugin/
        // http://www.ddl-turkey.net/modules/2752-jquery-dashboard-codecanyon.html

        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addJSInclude("_core/jTagIt/tag-it.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->addCSSInclude("_core/jTagIt/jquery.tagit.css");
        $this->setData("calendars", CSession::getCurrentPerson()->getResource()->getCalendarsList());
        $this->renderView("_calendar/add.tpl");
    }
    public function actionSave() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $event = CFactory::createEvent();
        $event->setName(CRequest::getString("name"));
        $event->setDescription(CRequest::getString("description"));
        $event->setStartTime(CRequest::getString("eventStart"));
        $event->setEndTime(CRequest::getString("eventEnd"));

        $members = new CArrayList();
        $members->add(CSession::getCurrentPerson()->getResource()->getId(), CSession::getCurrentPerson()->getResource());

        foreach (CRequest::getArray("members") as $m) {
            $member = CResourcesManager::getResourceById($m);
            if (!is_null($member)) {
                $members->add($member->getId(), $member);
            }
        }

        $event->setMembers($members);
        $event->setCalendar(CResourcesManager::getCalendarById(CRequest::getInt("calendar_id")));
        $event->save();

        $this->redirect(WEB_ROOT."_modules/_calendar/?resource_id=".CSession::getCurrentPerson()->getResource()->getId()."&calendar_id=".CRequest::getInt("calendar_id"));
    }
    public function actionGetEventsJSON() {
        $start = CRequest::getInt("start");
        $end = CRequest::getInt("end");
        $calendar = CResourcesManager::getCalendarById(CRequest::getInt("calendar_id"));
        $calendar->setStartTime($start);
        $calendar->setEndTime($end);
        $r = array();
        foreach ($calendar->getEvents()->getItems() as $e) {
            $r[] = $e->toArrayForJSON();
        }
        echo json_encode($r);
    }
}
