<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Login extends CI_Model
{

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
        //$ocidb = $this->load->database('oracle',TRUE);

    }
    public function test()
    {
        $ocidb = $this->load->database('oracle', true);
        $sql1 = "select * from ADV_DETAILS";
        return $ocidb->query($sql1)->result_array();
    }
    public function User($email, $password)
    {
        $this->db->select('Email_id,User_Password');
        $this->db->where('Email_id', $email);
        $this->db->where('User_Password', $password);
        //$this->db->where('TYPE', $type);
        $this->db->from('UserProfile');
        $query = $this->db->get();
        $result = $query->row();
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function User1($email, $password)
    {

        $this->db->select('Email_id,User_Password');
        $this->db->where('Email_id', $email);
        $this->db->where('User_Password', $password);
        //$this->db->where('TYPE', $type);
        $this->db->from('UserProfile');
        $query = $this->db->get();
        $result = $query->row();
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function getDisplayBoard($date)
    {

//        $sql1 = "select TOP 100 COURT_NO,ITEM_NO,CTYPE,REG_NO,REG_YR from COURT_MASTER where cast(DATE_LIST as date) = '$date'";
//        $sql = "select COURT_NO,ITEM_NO,CTYPE,REG_NO,REG_YR from COURT_MASTER";

        $sql = "SELECT COURT.COURT_NO, ITEM_NO,CONCAT(CTYPE,'/',REG_NO,'/',REG_YR) CASE_NUMBER, ISNULL(RESULT,'NIS') RESULT  FROM 
                (select distinct COURT_NO FROM COURT_MASTER) COURT 
                LEFT JOIN COURT_MASTER B ON COURT.COURT_NO = B.COURT_NO
                AND cast(B.DATE_LIST as date) = '$date'";

        return $this->db->query($sql)->result_array();
    }

    public function getCauseList($from, $to)
    {

        $sql = "select * from Tbl_CauseLists where CauseListPostedDate BETWEEN '$from' AND '$to' order  by CauseListId desc";

        //echo $sql;die;
        return $this->db->query($sql)->result_array();
    }

    public function userDetails($data)
    {
        return $this->db->insert('UserProfile', $data);
        //return $this->db->insert_id();
    }

    public function advDetails($data)
    {
        $ocidb = $this->load->database('oracle', true);
        return $ocidb->insert('ADV_DETAILS', $data);

    }
    public function getDiaryList()
    {

        $this->db->select("text_field,dte1,dte,eTime,Category");
        $this->db->from("diary");
        return $this->db->get()->result_array();

    }
    public function getGatePass()
    {
        $mssql = $this->load->database('mssql1', true);
        $mssql->select("*");
        $mssql->from("Visitor_Details");
        return $mssql->get()->result_array();

    }
    public function getGatePassById($VisRN)
    {
        $mssql = $this->load->database('mssql1', true);
        $mssql->select("Visitor_Details.*,EmpMaster.*");
        $mssql->from("Visitor_Details");
        $mssql->join('EmpMaster', 'EmpMaster.Emp_ID = Visitor_Details.Adv_Code');
        $mssql->where('VisRN', $VisRN['VisRN']);
        return $mssql->get()->result_array();

    }
	public function getAdvocateByid($id)
    {

        $ocidb = $this->load->database('oracle', true);
        $ocidb->select("*");
        $ocidb->from("ADV_DETAILS");
		$ocidb->where("BARCOUNCILNO",$id);
        $ocidb->order_by("FIRSTNAME", "asc");
        return $ocidb->get()->result_array();

    }
    public function getGatePassByCriteraia($criteria) 
    {
        
        $mssql = $this->load->database('mssql1', true);
        $mssql->select('V.Vis_Name, V.Vis_Address, V.VisRN, V.Vis_Sex, V.Vis_Date, P.Purp_Desc, Approved');
        $mssql->join('Purpose_Tab P', 'P.Purp_CD = V.Purp_CD', 'left');
        $mssql->from("Visitor_Details V");       
        $mssql->where($criteria);        
        return $mssql->get()->result_array();
    }

    public function getAdvocate()
    {
		
		$ocidb = $this->load->database('oracle', true);
        $ocidb->select("*");
        $ocidb->from("ADV_DETAILS");
        $ocidb->order_by("FIRSTNAME", "asc");
        return $ocidb->get()->result_array();

    }

    public function getNotice()
    {

        $this->db->select("*");
        $this->db->from("tbl_PublicNotice");
        return $this->db->get()->result_array();

    }

    public function getCourt()
    {
        $mssql = $this->load->database('mssql1', true);
        $mssql->select("*");
        $mssql->from("Court_Tab");
        $mssql->order_by("Crt_Name", "asc");
        return $mssql->get()->result_array();
    }

    public function checkEmail($Emailid, $UserPassword)
    {

        $condition = array("Email_id" => $Emailid, "User_Password" => $UserPassword);
        $this->db->select('Email_id,User_Password');
        $this->db->from('UserProfile');
        $this->db->where($condition);
        $rs = $this->db->get();
        //echo print_r($rs);die;
        if ($rs->num_rows() > 0) {
            $row = $rs->row();
            return $row;
        } else {
            return false;
        }
    }

    public function authenticateEmployee($EmpMobile, $EmpPassword)
    {
        $mssql = $this->load->database('mssql1', true);
        $mssql->select("*");
        $mssql->from("EmpMaster");
        $mssql->where('Emp_mobile', $EmpMobile);
        return $mssql->get()->result_array();
    }

    public function updatePassRequest($VisRN, $data)
    {
        $mssql = $this->load->database('mssql1', true);        
        $mssql->where('VisRN', $VisRN);
        $mssql->update('Visitor_Details', $data);
        return $mssql->affected_rows();
    }   

    public function emailExists($Emailid)
    {

        $condition = array("Email_id" => $Emailid);
        $this->db->select('Email_id');
        $this->db->from('UserProfile');
        $this->db->where($condition);
        $rs = $this->db->get();
        //echo print_r($rs);die;
        if ($rs->num_rows() > 0) {
            $row = $rs->row();
            return $row;
        } else {
            return false;
        }
    }

    public function getCaseType()
    {
        $mssql = $this->load->database('mssql1', true);
        $mssql->select("*");
        $mssql->from("Case_Type_Tab");
        $mssql->order_by("Case_Type", "asc");
        return $mssql->get()->result_array();

    }

    public function getJudgeDetails()
    {
        $ocidb = $this->load->database('oracle', true);
        $ocidb->select("*");
        $ocidb->from("JUDGE");
        //$this->db->order_by("JUDGE_NAME", "asc");
        return $ocidb->get()->result_array();

    }

    public function getGender()
    {
        $mssql = $this->load->database('mssql1', true);
        $mssql->select("*");
        $mssql->from("Sex_Tab");
        return $mssql->get()->result_array();

    }

    public function getPurpose()
    {
        $mssql = $this->load->database('mssql1', true);
        $mssql->select("*");
        $mssql->from("Purpose_Tab");
        $mssql->order_by("Purp_Desc", "asc");
        return $mssql->get()->result_array();

    }

    public function getOccupation()
    {
        $mssql = $this->load->database('mssql1', true);
        $mssql->select("*");
        $mssql->from("Occupation_Tab");
        $mssql->order_by("Ocu_Name", "asc");
        return $mssql->get()->result_array();
    }

    public function getidProof()
    {
        $mssql = $this->load->database('mssql1', true);
        $mssql->select("*");
        $mssql->from("IdProof_Tab");
        $mssql->order_by("idProof_Name", "asc");
        return $mssql->get()->result_array();

    }
    public function getBlock()
    {
        $mssql = $this->load->database('mssql1', true);
        $mssql->select("*");
        $mssql->from("Block_Tab");
        $mssql->order_by("Blk_Name", "asc");
        return $mssql->get()->result_array();

    }

    public function getEmployee()
    {
        $mssql = $this->load->database('mssql1', true);
        $mssql->select("*");
        $mssql->from("EmpMaster");
        $mssql->order_by("Emp_Name", "asc");
        return $mssql->get()->result_array();

    }

    public function insertVisitorDetails($data)
    {
        //echo "<pre>";
        //print_r($data['MobileNumber']);die;

        $mssql = $this->load->database('mssql1', true);
        $res = $mssql->insert('Visitor_Details', $data);
        //echo "<pre>";
        //print_r($data);die;
        $array1 = array();
        if ($res) {
            $array1 = array(
                'VisRN' => $data['VisRN'],
                'Adv_Code' => $data['Adv_Code'],
            );

            return $array1;

        }

        //var_dump($result);die;
        //return $last = $this->db->order_by('VisRN',"asc")->limit(1)->get('Visitor_Details')->row();

    }

    public function insertGatePassVisCurrNo($data)
    {
        return $this->db->insert('VisCurrNo', $data);
        //return $this->db->insert_id();
    }

    public function getAdvDetails()
    {
        $ocidb = $this->load->database('oracle', true);
        $sql1 = "select * from Visitor_Details";
        return $ocidb->query($sql1)->result_array();
    }

    public function getVisitCurrent()
    {

        $this->db->select("*");
        $this->db->from("ADV_DETAILS");
        $res = $this->db->get()->result_array();
        //print_r($res);die;

    }

    public function AdvEmailExists($Emailid)
    {
        $ocidb = $this->load->database('oracle', true);

        $condition = array("EMAIL" => $Emailid);
        $ocidb->select('EMAIL');
        $ocidb->from('ADV_DETAILS');
        $ocidb->where($condition);
        $rs = $ocidb->get();
        //echo print_r($rs);die;
        if ($rs->num_rows() > 0) {
            $row = $rs->row();
            return $row;
        } else {
            return false;
        }
    }

    public function VisitorMobileExists($mobile)
    {

        $condition = array("MobileNumber" => $mobile);
        $this->db->select('MobileNumber');
        $this->db->from('Visitor_Details');
        $this->db->where($condition);
        $rs = $this->db->get();
        //var_dump($rs);die;
        if ($rs->num_rows() > 0) {
            $row = $rs->row();
            return true;
        } else {
            return false;
        }
    }

    public function getCaseStatus($regNo, $year)
    {
        $ocidb = $this->load->database('oracle', true);
        $condition = array('MAIN.REG_NO' => $regNo, 'MAIN.REG_YR' => $year);
        $ocidb->select("MAIN.PET_NAME,MAIN.RES_NAME,MAIN.REG_NO,MAIN.REG_YR,LISTING.COURT_NO,MAIN.CTYPE,LISTING.NEXT_DATE,LISTING.DATE_LIST");
        $ocidb->from("MAIN");
        $ocidb->join('LISTING', 'LISTING.REG_NO = MAIN.REG_NO');
        $ocidb->where($condition);
        return $ocidb->get()->result_array();
    }

    public function getCaseStatusListing()
    {

        $ocidb = $this->load->database('oracle', true);
        $ocidb->select("*");
        $ocidb->from("LISTING");
        return $ocidb->get()->result_array();
        //print_r($res);die;

    }

    public function getListing()
    {

        $ocidb = $this->load->database('oracle', true);
        $ocidb->select("*");
        $ocidb->from("MAIN");
        return $ocidb->get()->result_array();
        //print_r($res);die;

    }
    public function checkMobile($Mobile)
    {
        $ocidb = $this->load->database('oracle', true);
        $condition = array("MOBILENO" => $Mobile);
        $ocidb->select('*');
        $ocidb->from('ADV_DETAILS');
        $ocidb->where($condition);
        $rs = $ocidb->get();
        if ($rs->num_rows() > 0) {
            $row = $rs->row();
            return $row;
        } else {
            return false;
        }
    }

    public function getCustomizeCauselist($start_date, $end_date, $search_key, $searchValue)
    { //echo $searchKey;die;

        //$mssql = $this->load->database('mssql1',TRUE);
        $ocidb = $this->load->database('oracle', true);
        $ocidb->select("LISTING.J_CODE1,MAIN.PET_NAME,MAIN.RES_NAME,JUDGE.JUDGE_CODE,JUDGE.JUDGE_NAME,MAIN.ADV_NAME,MAIN.REG_NO,MAIN.REG_YR,LISTING.COURT_NO,MAIN.CTYPE,LISTING.NEXT_DATE,LISTING.DATE_LIST");
        $ocidb->from("MAIN");
        $ocidb->join('LISTING', 'LISTING.REG_NO = MAIN.REG_NO');
        $ocidb->join('JUDGE', 'JUDGE.JUDGE_CODE = LISTING.J_CODE1');
        $ocidb->where("LISTING.DATE_LIST BETWEEN DATE '" . $start_date . "' AND DATE '" . $end_date . "'");
        if ($search_key == 'Exact') {
            $searchValue = strtolower($searchValue);
            $ocidb->where("LOWER(MAIN.ADV_NAME) LIKE '%$searchValue%'");
        }
        if ($search_key == 'Judge-Wise') {
            $ocidb->where("LISTING.J_CODE1", $searchValue);
        }
        if ($search_key == "SubString") {
            $searchValue = strtolower($searchValue);
            $ocidb->where("LOWER(MAIN.ADV_NAME) LIKE '%$searchValue%'");
        }
        if ($search_key == "Court-Number") {
            $ocidb->where("LISTING.COURT_NO", $searchValue);
        }
        if ($search_key == "Full") {

        }
        return $ocidb->get()->result();
    }

    public function getNextDateHiring()
    {
        $ocidb = $this->load->database('oracle', true);
        $sql = "SELECT M.PET_NAME||' vs '||M.RES_NAME CASE_TITLE, M.PET_NAME,M.RES_NAME,M.REG_NO,M.REG_YR, M.CTYPE, L.NEXT_DATE,L.DATE_LIST,L.COURT_NO, L.CTYPE||'/'||L.REG_NO||'/'||L.REG_YR CASE_NUMBER FROM MAIN M JOIN LISTING L ON L.REG_NO = M.REG_NO";
        $oracleRS = $ocidb->query($sql)->result_array();

        $mssql = $this->load->database('mssql1', true);
        $sql = "SELECT C.Description, C.Case_Type FROM Case_Type_Tab C";
        $mssqlRS = $mssql->query($sql)->result_array();

        $next_date_array = array();
        if (!empty($oracleRS)) {
            foreach ($oracleRS as $o) {
                if (!empty($mssqlRS)) {
                    foreach ($mssqlRS as $m) {
                        if ($m['Case_Type'] == $o['CTYPE']) {
                            $o['DESCRIPTION'] = $m['Description'];

                        }
                    }
                }
                array_push($next_date_array, $o);
            }
        }

        return $next_date_array;

    }

    public function eInspectionEntry($data)
    {        
        $ocidb = $this->load->database('oracle', true);
        return $ocidb->insert('INSPECTION_USER', $data);        
    }
}
