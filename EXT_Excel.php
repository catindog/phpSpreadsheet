<?php

require 'vendor/autoload.php';

$inputFileName = "/Users/yangjian/Downloads/export_result3.csv";
$inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

$spreadsheet = $reader->load($inputFileName);

$worksheet = $spreadsheet->getActiveSheet();
// Get the highest row number and column letter referenced in the worksheet
$highestRow = $worksheet->getHighestRow(); // e.g. 10
$highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
// Increment the highest column letter
$highestColumn++;

$jiami = array(
    'ks_name',
    'ks_ename',
    'card_id',
    'ks_number',
    'xs_number',
    'ss_number',
    'ss_by_number',
    'zc_number',
    'phone',
    'mobile_phone',
    'email',
    'language_score',
    'business1_score',
    'subject1_score',
    'second_total',
    'coures_total',
    'total_score',
);

echo 'insert into doctor_apply (apply_id,school_id,year,user_id,col_id,dir_id,apply_number,ks_name,ks_ename,card_type,card_id,ks_number,birthday,nation_code,sex_code,marriage_code,political_code,soldier_code,csd_code,jgsz_code,hkszd_code,daszd_code,daszd_dw,daszd_dwdz,daszd_yb_code,ks_scorce_code,study_nature,study_place,work_experience,jlcf,family_member,paper,xs_school_code,xs_school_name,xs_special_code,xs_special_name,xs_date,xs_number,bk_by_school_code,bk_by_school_name,bk_by_special_code,bk_by_special_name,bk_by_date,bk_by_number,bk_xl_type,ss_school_code,ss_school_name,ss_special_code,ss_special_name,ss_date,ss_number,ss_type,ss_by_school_code,ss_by_school_name,ss_by_special_code,ss_by_special_name,ss_by_date,ss_by_number,zh_xw_code,zh_xl_code,zc_number,bk_school_code,bk_school_name,bk_college_code,bk_college_name,bk_special_code,bk_special_name,bk_direction_code,bk_direction_name,bk_study_type,bk_tutor_name,bk_tutor_number,bk_tutor_nature,exam_type_code,bk_lb_code,zsjh,dx_school_code,dx_school_name,is_apply_check,language_code,language_name,business1_code,business1_name,business2_code,business2_name,ks_adress,ks_post_code,phone,mobile_phone,email,remark,remark1,remark2,remark3,lh_school_code,lh_school_name,language_score,business1_score,business2_score,subject1_score,subject2_score,subject3_score,subject4_score,subject5_score,is_pay,photo,affirm_result,apply_check_status,apply_check_opinion,allow_exam,check_status,check_opinion,confirm_status,second_total,coures_total,total_score,is_first_passing,is_second_passing,is_enroll,add_status,field1,field2,field3,field4,field5,field6,field7,field8,field9,field10,field11,field12,field13,field14,field15,add_time,modify_time,information,prize,is_download) values ';
for ($row = 1; $row <= $highestRow; ++$row) {
    if ($row != 1) {
        echo '(';
    }
    for ($col = 'A'; $col != $highestColumn; ++$col) {
        $data = $worksheet->getCell($col . $row)->getValue();
        if ($row == 1) {
            if (in_array($data, $jiami)) {
                $aJiami[] = $col;
            }
        } else {
            if ($data == '(null)') {
                $data = '';
            }
            if (substr($data, 0, 1) == "'") {
                $data = substr($data, 1);
            }
            if (in_array($col, array('DD', 'DF', 'DG', 'DI', 'DM', 'DN', 'DO')) && !$data) {
                $data = 'N';
            }
            if (in_array($col, array('DC', 'DP')) && !$data) {
                $data = '1';
            }
            if (in_array($col, array('E', 'F')) && !$data) {
                $data = '0';
            }
            if (in_array($col, $aJiami)) {
                $aSql[$row][] = "AES_ENCRYPT('" .$data."', 'eol#2019zs')";
            } else {
                $aSql[$row][] = "'" .$data."'";
            }
        }
    }
    if ($row != 1) {
        echo implode(',', $aSql[$row]);
        echo ')';
        if ($row == $highestRow) {
            echo ';';
        } else {
            echo ',';
        }
    }
}

