<?php
    /**
     * Базовые константы
     */
    define('CORE_DS', DIRECTORY_SEPARATOR);
    define('CORE_CWD', str_replace(CORE_DS."core_constants.php", "", __FILE__));
    define("CACHE_DIR", CORE_CWD.CORE_DS."tmp".CORE_DS."cache");
    define('SMARTY_FOLDER', CORE_CWD.'/_core/_external/smarty');
    define('SMARTY_DIR', SMARTY_FOLDER.'/libs/');
    define('VIEWS_DIR', CORE_CWD.CORE_DS.'_core'.CORE_DS.'_views'.CORE_DS);
    define('TEMPLATES_DIR', CORE_CWD.CORE_DS.'_core'.CORE_DS.'_templates'.CORE_DS);
    define('CORE_ENABLED', true);
    define("JSON_CONTROLLERS_DIR", CORE_CWD.CORE_DS.'_core'.CORE_DS.'_json_controllers'.CORE_DS);
    define('AJAX_VIEW', "_ajax.html.php");
    define('SMARTY_TEMPLATES', VIEWS_DIR);
    define('SMARTY_COMPILE', CORE_CWD.CORE_DS.'tmp'.CORE_DS.'smarty'.CORE_DS);
    define('SMARTY_CACHE', CORE_CWD.CORE_DS.'tmp'.CORE_DS.'smarty'.CORE_DS);
    define("PHPMAILER_DIR", CORE_CWD.'/_core/_external/phpmailer');
    define("PRINT_ENGINE_WORD", CORE_CWD.CORE_DS.'_core'.CORE_DS.'_external'.CORE_DS.'phpword'.CORE_DS);
    define("PRINT_TEMPLATES_DIR", CORE_CWD.CORE_DS.'library'.CORE_DS.'templates'.CORE_DS);
    define("PRINT_DOCUMENTS_DIR", CORE_CWD.CORE_DS.'tmp'.CORE_DS.'print'.CORE_DS);
    define("ZIP_DOCUMENTS_DIR", CORE_CWD.CORE_DS.'tmp'.CORE_DS.'zip'.CORE_DS);
    define("TIMTHUMB_CACHE", CORE_CWD.CORE_DS.'tmp'.CORE_DS.'timthumb'.CORE_DS);
    /**
     * Константы таблиц
     */
    define("TABLE_RESOURCES", "pl_resources");
    define("TABLE_CALENDARS", "pl_calendars");
    define("TABLE_PERSON", "kadri");
    define("TABLE_USERS", "users");
    define("TABLE_BIOGRAPHY", "biography");
    define("TABLE_SCHEDULE", "time");
    define("TABLE_SCHEDULE_KIND_WORK", "time_kind");
    define("TABLE_FAMILY_STATUS", "family_status");
    define("TABLE_EVENT_MEMBERSHIP", "pl_event_membership");
    define("TABLE_EVENTS", "pl_events");
    define("TABLE_POSTS", "dolgnost");
    define("TABLE_TYPES", "person_types");
    define("TABLE_MENUS", "menu");
    define("TABLE_MENU_ITEMS", "menu_items");
    define("TABLE_MENU_ITEMS_ACCESS", "menu_items_access");
    define("TABLE_SEB_QUESTIONS", "seb_question");
    define("TABLE_SEB_TICKETS", "seb_ticket");
    define("TABLE_DISCIPLINES", "subjects");
    define("TABLE_DISCIPLINES_BOOKS", "subject_books");
    define("TABLE_SPECIALITIES", "specialities");
    define("TABLE_SCIENCE_SPECIALITIES", "specialities_science");
    define("TABLE_STUDY_LEVELS", "levels");
    define("TABLE_DEPARTMENT_PROTOCOLS", "protocols");
    define("TABLE_DEP_PROTOCOL_AGENDA", "protocol_details");
    define("TABLE_DEP_PROTOCOL_VISIT", "protocol_visit");
    define("TABLE_YEARS", "time_intervals");
    define("TABLE_YEAR_PARTS", "time_parts");
    define("TABLE_SEB_QUSTIONS_IN_TICKETS", "seb_question_in_ticket");
    define("TABLE_STUDENT_GROUPS", "study_groups");
    define("TABLE_STUDENTS", "students");
    define("TABLE_DIPLOMS", "diploms");
    define("TABLE_COURSE_PROJECTS", "course_projects");
    define("TABLE_COURSE_PROJECTS_TASKS", "course_projects_tasks");
    define("TABLE_COURSE_PROJECTS_COMMISSION_MEMBERS", "course_projects_commision_members");
    define("TABLE_DIPLOM_PREVIEWS", "diplom_previews");
    define("TABLE_DIPLOM_CONFIRMATIONS", "diplom_confirms");
    define("TABLE_DIPLOM_PREVIEW_COMISSIONS", "diplom_preview_committees");
    define("TABLE_DIPLOM_PREVIEW_MEMBERS", "diplom_preview_kadri");
    define("TABLE_DIPLOM_ANTIPLAGIAT_CHECKS", "diplom_antiplagiat_checks");
    define("TABLE_PRACTICE_PLACES", "pract_places");
    define("TABLE_TOWNS", "towns");
    define("TABLE_FILIAL_GOING", "filial_going");
    define("TABLE_FILIAL_ACTIONS", "filial_actions");
    define("TABLE_FILIALS", "filials");
    define("TABLE_TRANSPORT", "transport");
    define("TABLE_LANGUAGES", "language");
    define("TABLE_MARKS", "study_marks");
    define("TABLE_SEB_PROTOCOLS", "seb_protocol");
    define("TABLE_SEB_PROTOCOL_MEMBERS", "seb_protocol_members");
    define("TABLE_USER_GROUPS", "user_groups");
    define("TABLE_USER_IN_GROUPS", "user_in_group");
    define("TABLE_USER_GROUPS_HIERARCHY", "user_groups_hierarchy");
    define("TABLE_USER_ROLES", "tasks");
    define("TABLE_USER_GROUP_HAS_ROLES", "task_in_group");
    define("TABLE_USER_HAS_ROLES", "task_in_user");
    define("TABLE_CORRICULUMS", "pl_corriculum");
    define("TABLE_CORRICULUM_DISCIPLINES", "pl_corriculum_disciplines");
    define("TABLE_CORRICULUM_DISCIPLINE_LABORS", "pl_corriculum_discipline_labors");
    define("TABLE_CORRICULUM_DISCIPLINE_CONTROLS", "pl_corriculum_discipline_controls");
    define("TABLE_CORRICULUM_DISCIPLINE_HOURS", "pl_corriculum_discipline_hours");
    define("TABLE_CORRICULUM_DISCIPLINE_SECTIONS", "pl_corriculum_discipline_sections");
    define("TABLE_CORRICULUM_CYCLES", "pl_corriculum_cycles");
    define("TABLE_CORRICULUM_PRACTICES", "pl_corriculum_practices");
    define("TABLE_CORRICULUM_DISCIPLINE_COMPETENTIONS", "pl_corriculum_discipline_competentions");
    define("TABLE_CORRICULUM_DISCIPLINE_KNOWLEDGES", "pl_corriculum_discipline_knowledges");
    define("TABLE_CORRICULUM_DISCIPLINE_SKILLS", "pl_corriculum_discipline_skills");
    define("TABLE_CORRICULUM_DISCIPLINE_EXPERIENCES", "pl_corriculum_discipline_experiences");
    define("TABLE_CORRICULUM_DISCIPLINE_STATEMENTS", "pl_corriculum_discipline_statements");
    define("TABLE_CORRICULUM_ATTESTATIONS", "pl_corriculum_attestations");
    define("TABLE_CORRICULUM_BOOKS", "pl_corriculum_books");
    define("TABLE_TAXONOMY", "taxonomy");
    define("TABLE_TAXONOMY_TERMS", "taxonomy_terms");
    define("TABLE_TAXONOMY_CHILD_TERMS", "taxonomy_term_hierarchy");
    define("TABLE_EDUCATION_FORMS", "study_forms");
    define("TABLE_RATING_INDEXES", "rating_index");
    define("TABLE_PERSON_RATINGS", "rating_person_indexes");
    define("TABLE_PASSWORD_RECOVERY_REQUESTS", "user_password_requests");
    define("TABLE_NOTIFICATION_TEMPLATES", "template_notification");
    define("TABLE_STAFF_ORDERS", "orders");
    define("TABLE_PUBLICATION_BY_PERSONS", "works");
    define("TABLE_PUBLICATIONS", "izdan");
    define("TABLE_PUBLICATIONS_TYPES", "izdan_type");
    define("TABLE_TITLES", "zvanie");
    define("TABLE_SETTINGS", "settings");
    define("TABLE_RATING_INDEX_VALUES", "rating_index_value");
    define("TABLE_HELP", "help");
    define("TABLE_DEGREES", "stepen");
    define("TABLE_PERSON_DISSER", "disser");
    define("TABLE_EXAMINATION_QUESTIONS", "questions");
    define("TABLE_EXAMINATION_TICKETS", "questions_tickets");
    define("TABLE_EXAMINATION_QUESTIONS_IN_TICKETS", "questions_tickets_questions");
    define("TABLE_QUESTION_TO_USERS", "question2users");
    define("TABLE_QUESTION_STATUS", "question_status");
    define("TABLE_ORDER_TYPES", "order_type");
    define("TABLE_ORDER_MONEY_TYPES", "order_type_money");
    define("TABLE_PRINT_FORMSETS", "print_formset");
    define("TABLE_PRINT_FORMS", "print_form");
    define("TABLE_PRINT_FIELDS", "print_field");
    define("TABLE_STUDENTS_ACTIVITY", "study_activity");
    define("TABLE_STUDENTS_CONTROL_TYPES", "study_act");
    define("TABLE_STUDENT_GROUP_HISTORY", "student_group_history");
    define("TABLE_GRADEBOOKS", "study_gradebook");
    define("TABLE_DASHBOARD", "dashboard");
    define("TABLE_USER_SETTINGS", "user_settings");
    define("TABLE_MESSAGES", "mails");
    define("TABLE_ACL_TABLES", "acl_tables");
    define("TABLE_ACL_DEFAULTS", "acl_defaults");
    define("TABLE_GENDERS", "pol");
    define("TABLE_USATU_ORDERS", "orders_dep");
    define("TABLE_USATU_ORDER_TYPES", "orders_dep_type");
    define("TABLE_NEWS", "news");
    define("TABLE_LIBRARY_DOCUMENTS", "documents");
    define("TABLE_LIBRARY_FILES", "files");
    define("TABLE_GRANTS", "grants");
    define("TABLE_GRANT_MEMBERS", "grant_members");
    define("TABLE_GRANT_ATTACHMENTS", "grant_attachments");
    define("TABLE_GRANT_PERIODS", "grant_periods");
    define("TABLE_GRANT_MONEY", "grant_money");
    define("TABLE_GRANT_EVENTS", "grant_events");
    define("TABLE_TAXONOMIES_LEGACY", "sprav_links");
    define("TABLE_PAGES", "pg_uploads");
    define("TABLE_SUBSCRIPTIONS", "subscriptions");
    define("TABLE_PERSON_CHILDREN", "kadri_childs");
    define("TABLE_PERSON_BY_TYPES", "kadri_in_ptypes");
    define("TABLE_PERSON_DIPLOMS", "obrazov");
    define("TABLE_PERSON_COURCES", "courses");
    define("TABLE_SAB_COMMISSIONS", "sab_commission");
    define("TABLE_SAB_COMMISSION_MEMBERS", "sab_commission_members");
    define("TABLE_SAB_COMMISSION_DIPLOMS", "sab_commission_diploms");
    define("TABLE_SAB_PERSON_ORDERS", "sab_person_orders");
    define("TABLE_RATES", "pl_rates");
    define("TABLE_CORE_MODELS", "core_models");
    define("TABLE_CORE_MODEL_TASKS", "core_model_tasks");
    define("TABLE_CORE_MODEL_FIELDS", "core_model_fields");
    define("TABLE_CORE_MODEL_FIELD_TRANSLATIONS", "core_model_field_translations");
    define("TABLE_CORE_VALIDATORS", "core_validators");
    define("TABLE_CORE_MODEL_VALIDATORS", "core_model_validators");
    define("TABLE_CORE_MODEL_FIELD_VALIDATORS", "core_model_field_validators");
    define("TABLE_IND_PLAN_WORKTYPES", "spravochnik_vidov_rabot");
    define("TABLE_IND_PLAN_LOADS", "ip_loads");
    define("TABLE_IND_PLAN_LOADS_ORDERS", "ip_loads_orders");
    define("TABLE_IND_PLAN_WORKS", "ip_works");
    define("TABLE_IND_PLAN_PLANNED", "hours_kind");
    define("TABLE_IND_PLAN_PLANNED_TYPES", "hours_kind_type");
    define("TABLE_WORKLOAD", "pl_corriculum_workload");
    define("TABLE_WORKLOAD_WORKS", "pl_corriculum_workload_by_type");
    define("TABLE_WORKLOAD_WORK_TYPES", "spravochnik_uch_rab");
    define("TABLE_WORKLOAD_STUDY_GROUPS", "pl_corriculum_workload_groups");
    define("TABLE_ACCESS_LEVELS", "task_rights");
    define("TABLE_DOCUMENT_FOLDERS", "files_folders");
    define("TABLE_DOCUMENTS", "files");
    define("TABLE_REPORTS", "reports");
    define("TABLE_DASHBOARD_REPORTS", "dashboard_reports");
    define("TABLE_NMS_PROTOCOL", "protocols_nms");
    define("TABLE_NMS_PROTOCOL_AGENDA", "protocol_nms_details");
    define("TABLE_PROTOCOL_OPINIONS", "protocol_opinions");
    define("TABLE_NMS_PROTOCOL_AGENDA_MEMBERS", "protocol_nms_detail_members");
    define("TABLE_HOURS_RATE", "hours_rate");
    define("TABLE_WORK_PLANS", "pl_corriculum_workplans");
    define("TABLE_WORK_PLAN_PROFILES", "pl_corriculum_workplan_profiles");
    define("TABLE_WORK_PLAN_GOALS", "pl_corriculum_workplan_goals");
    define("TABLE_WORK_PLAN_TASKS", "pl_corriculum_workplan_tasks");
    define("TABLE_WORK_PLAN_COMPETENTIONS", "pl_corriculum_workplan_competentions");
    define("TABLE_WORK_PLAN_KNOWLEDGES", "pl_corriculum_workplan_knowledges");
    define("TABLE_WORK_PLAN_SKILLS", "pl_corriculum_workplan_skills");
    define("TABLE_WORK_PLAN_EXPERIENCES", "pl_corriculum_workplan_experiences");
    define("TABLE_WORK_PLAN_DISCIPLINES_BEFORE", "pl_corriculum_workplan_disciplines_before");
    define("TABLE_WORK_PLAN_DISCIPLINES_AFTER", "pl_corriculum_workplan_disciplines_after");
    define("TABLE_WORK_PLAN_CONTENT_SECTIONS", "pl_corriculum_workplan_content_sections");
    define("TABLE_WORK_PLAN_TERMS", "pl_corriculum_workplan_terms");
    define("TABLE_WORK_PLAN_PROJECT_THEMES", "pl_corriculum_workplan_projects");
    define("TABLE_WORK_PLAN_SELFEDUCATION", "pl_corriculum_workplan_selfeducation");
    define("TABLE_WORK_PLAN_MARK_TYPES", "pl_corriculum_workplan_marktypes");
    define("TABLE_WORK_PLAN_MARK_TYPE_FUNDS", "pl_corriculum_workplan_marktype_fund");
    define("TABLE_WORK_PLAN_MARK_TYPE_PLACES", "pl_corriculum_workplan_marktype_place");
    define("TABLE_WORK_PLAN_FUND_MARK_TYPES", "pl_corriculum_workplan_fund_marktypes");
    define("TABLE_WORK_PLAN_FUND_MARK_TYPES_COMPETENTIONS", "pl_corriculum_workplan_fund_marktypes_competention");
    define("TABLE_WORK_PLAN_FUND_MARK_TYPES_LEVELS", "pl_corriculum_workplan_fund_marktypes_level");
    define("TABLE_WORK_PLAN_FUND_MARK_TYPES_CONTROLS", "pl_corriculum_workplan_fund_marktypes_control");
    define("TABLE_WORK_PLAN_BRS", "pl_corriculum_workplan_brs");
    define("TABLE_WORK_PLAN_LITERATURE", "pl_corriculum_workplan_literature");
    define("TABLE_WORK_PLAN_SOFTWARE", "pl_corriculum_workplan_software");
    define("TABLE_WORK_PLAN_AUTHORS", "pl_corriculum_workplan_authors");
    define("TABLE_WORK_PLAN_CONTENT_CATEGORIES", "pl_corriculum_workplan_content_categories");
    define("TABLE_WORK_PLAN_CONTENT_CONTROLS", "pl_corriculum_workplan_content_section_controls");
    define("TABLE_WORK_PLAN_CONTENT_LOADS", "pl_corriculum_workplan_content_section_loads");
    define("TABLE_WORK_PLAN_FINAL_CONTROL", "pl_corriculum_workplan_final_control");
    define("TABLE_WORK_PLAN_CONTENT_TOPICS", "pl_corriculum_workplan_content_section_load_topics");
    define("TABLE_WORK_PLAN_CONTENT_TECHNOLOGIES", "pl_corriculum_workplan_content_section_load_technologies");
    define("TABLE_WORK_PLAN_COMPETENTION_CAN_USE", "pl_corriculum_workplan_competention_can_use");
    define("TABLE_WORK_PLAN_ADDITIONAL_SUPPLY", "pl_corriculum_workplan_additional_supply");
    define("TABLE_WORK_PLAN_TYPES_CONTROL", "pl_corriculum_workplan_types_control");
    define("TABLE_WORK_PLAN_CRITERIA_OF_EVALUATION", "pl_corriculum_workplan_criteria_of_evaluation");
    define("TABLE_WORK_PLAN_EVALUATION_MATERIALS", "pl_corriculum_workplan_evaluation_materials");
    define("TABLE_WORK_PLAN_MARKS_STUDY_ACTIVITY", "pl_corriculum_workplan_marks_study_activity");
    define("TABLE_WORK_PLAN_RECOMMENDED_LITERATURE", "pl_corriculum_workplan_recommended_literature");
    define("TABLE_WORK_PLAN_PROTOCOLS_DEP", "pl_corriculum_workplan_protocols_dep");
    define("TABLE_WORK_PLAN_PROTOCOLS_NMS", "pl_corriculum_workplan_protocols_nms");
    define("TABLE_WORK_PLAN_CALCULATION_TASKS", "pl_corriculum_workplan_calculation_tasks");
    define("TABLE_WORK_PLAN_INTERMEDIATE_CONTROL", "pl_corriculum_workplan_intermediate_control");
    /**
     * Особые таксономии
     */
    define("TAXONOMY_DEPARTMENT_ROLES", "department_roles");
    define("TAXONOMY_COMPETENTIONS", "corriculum_competentions");
    define("TAXONOMY_SPECIALITY", "specialities");
    /**
     * Импорт отсальных констант. Там уже можно использовать настройки
     * из базы данных, так как автолоадер уже запущен
     */
    /**
     * Константы пагинатора
     */
    define("PAGINATION_ALL", "all");
    define("PAGINATION_DEFAULT", 20);
    /**
     * Константы для диссертаций
     */
    define("DISSER_DOCTOR", "доктор");
    define("DISSER_PHD", "кандидат");
    define("DISSER_DEGREE", "степень");
    define("DISSER_PORTFOLIO", "портфолио");
    /**
     * Стандартные действия
     */
    define("ACTION_INDEX", "index");
    /**
     * Уровни доступа константами
     */
    define("ACCESS_LEVEL_NO_ACCESS", 0);
    define("ACCESS_LEVEL_READ_OWN_ONLY", 1);
    define("ACCESS_LEVEL_READ_ALL", 2);
    define("ACCESS_LEVEL_WRITE_OWN_ONLY", 3);
    define("ACCESS_LEVEL_WRITE_ALL", 4);
    // Типы участия на кафедре
    define("TYPE_PPS", "профессорско-преподавательский состав");
    define("TYPE_REVIEWER", "рецензент");
    define("TYPE_REVIEWER_ARCHIVE", "архив рецензент");
    define("USER_TYPE_ADMIN", "администратор");
    define("HEAD_OF_DEPARTMENT", "2");

    // типы связей
    define("RELATION_HAS_ONE", "1");
    define("RELATION_HAS_MANY", "many");
    define("RELATION_COMPUTED", "computed");
    define("RELATION_MANY_TO_MANY", "many_to_many");

    /**
     * Типы полей.
     * Для загрузки файлов и других корректировок
     */
    define("FIELD_UPLOADABLE", "uploadable");
    define("FIELD_MYSQL_DATE", "mysql_date");

    // сообщения об ошибках
    define("ERROR_FIELD_REQUIRED", "Поле \"%name%\" обязательно для заполнения");
    define("ERROR_FIELD_NUMERIC", "Значение поля \"%name%\" должно быть числовым");
    define("ERROR_FIELD_SELECTED", "Значение поля \"%name%\" должно быть выбрано из списка");
    define("ERROR_FIELD_NOT_A_DATE", "Значение в поле \"%name%\" должно быть датой в формате дд.мм.гггг");
    define("ERROR_FIELD_IS_IMAGE", "Значение поля \"%name%\" должно быть изображением");
    define("ERROR_INSUFFICIENT_ACCESS_LEVEL", "У Вас недостаточно прав для доступа к данной задаче");

    // события валидации
    define("VALIDATION_EVENT_READ", "onRead");
    define("VALIDATION_EVENT_UPDATE", "onUpdate");
    define("VALIDATION_EVENT_CREATE", "onCreate");
    define("VALIDATION_EVENT_REMOVE", "onDelete");