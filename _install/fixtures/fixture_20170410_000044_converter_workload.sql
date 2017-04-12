INSERT INTO pl_corriculum_workload 
(hours_kind_id,
 person_id, 
 year_id, 
 year_part_id, 
 discipline_id, 
 speciality_id, 
 level_id, 
 load_type_id, 
 groups_count, 
 students_count, 
 comment, 
 on_filial, 
 students_contract_count,
 _created_by)
  SELECT
    hours_kind.id,
    hours_kind.kadri_id,
    hours_kind.year_id,
    hours_kind.part_id,
    hours_kind.subject_id,
    hours_kind.spec_id,
    hours_kind.level_id,
    hours_kind.hours_kind_type,
    hours_kind.groups_cnt,
    hours_kind.stud_cnt,
    hours_kind.comment,
    hours_kind.on_filial,
    hours_kind.stud_cnt_add,
    67
  FROM hours_kind WHERE (hours_kind.subject_id != 0 AND 
                         hours_kind.subject_id != 1 AND 
                         hours_kind.subject_id != 235 AND 
                         hours_kind.subject_id != 130 AND 
                         hours_kind.subject_id != 302 AND 
                         hours_kind.subject_id != 191 AND 
                         hours_kind.subject_id != 76 AND  
                         hours_kind.subject_id != 187 AND 
                         hours_kind.subject_id != 231 AND 
                         hours_kind.subject_id != 140 AND 
                         hours_kind.subject_id != 209 AND 
                         hours_kind.subject_id != 141 AND 
                         hours_kind.subject_id != 131 AND
                         hours_kind.kadri_id != 163 AND 
                         hours_kind.spec_id != 0 AND 
                         hours_kind.level_id != 0 AND 
                         hours_kind.hours_kind_type != 0);


INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'lects'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'budgetStudyLoad'),
  hours_kind.lects,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'practs'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'budgetStudyLoad'),
  hours_kind.practs,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'labor'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'budgetStudyLoad'),
  hours_kind.labor,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;
 
INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'rgr'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'budgetStudyLoad'),
  hours_kind.rgr,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'ksr'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'budgetStudyLoad'),
  hours_kind.ksr,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'kollokvium'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'budgetStudyLoad'),
  hours_kind.kollokvium,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'kurs_proj'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'budgetStudyLoad'),
  hours_kind.kurs_proj,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'consult'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'budgetStudyLoad'),
  hours_kind.consult,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'test'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'budgetStudyLoad'),
  hours_kind.test,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'exams'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'budgetStudyLoad'),
  hours_kind.exams,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'study_pract'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'budgetStudyLoad'),
  hours_kind.study_pract,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'work_pract'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'budgetStudyLoad'),
  hours_kind.work_pract,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'consult_dipl'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'budgetStudyLoad'),
  hours_kind.consult_dipl,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'gek'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'budgetStudyLoad'),
  hours_kind.gek,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'aspirants'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'budgetStudyLoad'),
  hours_kind.aspirants,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'aspir_manage'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'budgetStudyLoad'),
  hours_kind.aspir_manage,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'duty'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'budgetStudyLoad'),
  hours_kind.duty,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;



INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'lects'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'contractStudyLoad'),
  hours_kind.lects_add,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'practs'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'contractStudyLoad'),
  hours_kind.practs_add,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'labor'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'contractStudyLoad'),
  hours_kind.labor_add,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;
 
INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'rgr'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'contractStudyLoad'),
  hours_kind.rgr_add,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'ksr'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'contractStudyLoad'),
  hours_kind.ksr_add,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'kollokvium'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'contractStudyLoad'),
  hours_kind.kollokvium_add,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'kurs_proj'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'contractStudyLoad'),
  hours_kind.kurs_proj_add,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'consult'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'contractStudyLoad'),
  hours_kind.consult_add,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'test'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'contractStudyLoad'),
  hours_kind.test_add,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'exams'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'contractStudyLoad'),
  hours_kind.exams_add,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'study_pract'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'contractStudyLoad'),
  hours_kind.study_pract_add,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'work_pract'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'contractStudyLoad'),
  hours_kind.work_pract_add,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'consult_dipl'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'contractStudyLoad'),
  hours_kind.consult_dipl_add,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'gek'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'contractStudyLoad'),
  hours_kind.gek_add,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'aspirants'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'contractStudyLoad'),
  hours_kind.aspirants_add,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'aspir_manage'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'contractStudyLoad'),
  hours_kind.aspir_manage_add,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;

INSERT INTO pl_corriculum_workload_by_type 
(workload_id,
 type_id, 
 kind_id, 
 workload, 
 _created_by)
 SELECT
  pl_corriculum_workload.id,
  (SELECT spravochnik_uch_rab.id FROM `spravochnik_uch_rab` WHERE `name_hours_kind` = 'duty'),
  (SELECT taxonomy_terms.id FROM `taxonomy_terms` WHERE `alias` = 'contractStudyLoad'),
  hours_kind.duty_add,
  67
FROM hours_kind
  INNER JOIN pl_corriculum_workload
    ON hours_kind.id = pl_corriculum_workload.hours_kind_id;