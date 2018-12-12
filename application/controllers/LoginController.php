	<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

include APPPATH . 'libraries/REST_Controller.php';

class LoginController extends REST_Controller
{

    public function __construct()
    {

        parent::__construct();

        $this->load->model('login');
        $this->load->library('mpdf60/Mpdf');
        $this->load->helper('url');
    }

    public function login_post()
    {

        $res = array();
        $post = $this->post();

        if (!filter_var($post['Email_id'], FILTER_VALIDATE_EMAIL)) {
            $res['status'] = false;
            $res['response'] = "Invalid email format..";

            return $this->response($res);
        }
        if (empty($post['Email_id']) || empty($post['User_Password'])) {
            $res['status'] = false;
            $res['response'] = 'Email or Password can\'t be empty';

            return $this->response($res);
        }

        $Emailid = $post['Email_id'];
        $UserPassword = $post['User_Password'];

        $this->load->model('Login');
        $checkEmail = $this->Login->checkEmail($Emailid, $UserPassword);

        if ($checkEmail) {
            $res['status'] = true;
            $res['response'] = "Login Successfully";
        } else {
            $res['status'] = false;
            $res['response'] = "Sorry Login failed..Invalid credentials..";

        }

        $this->response($res);

    }

    /* SHOW DISPLAY BOARD START*/

    public function displayBoard_get()
    {

        try
        {

        //    error_reporting(1);
            $res = array();
            $res['status'] = false;
            $cur_date = date('Y-m-d');
            $this->load->model('Login');
            $cur_date = '2018-11-26';
            $result = $this->Login->getDisplayBoard($cur_date);
            //print_r($result); 
            // echo $this->db->last_query(); die;

            if(empty($result))
            {
                $res['status'] = false;
                $res['response'] = "No result found !!!";
                $this->response($res);
            }

            $res['status'] = true;
            foreach ($result as $c) 
            {                
                $output = array();
                $output['case_no'] = $c['CASE_NUMBER'];
                $output['court_no'] = $c['COURT_NO'];
                $output['item_no'] = $c['ITEM_NO'];
                $output['status'] = $c['RESULT'];

                $res['response'][] = $output;
            }

            $this->response($res);
        } 
        catch (Exception $e) 
        {
            $this->response($e->getMessage());       
        }

    }

    /* SHOW DISPLAY BOARD END*/

    /* USER REGISTRATION START*/

    /* USER REGISTRATION END*/

    /* SHOW CAUSE LIST START*/

    public function displayCauseList_post()
    {

        try
        {
            $res = array();
            $res['status'] = false;
            $this->load->model('Login');
            $from = $this->post('CauseListPostedStartDate');
            $to = $this->post('CauseListPostedEndDate');
            $result = $this->Login->getCauseList($from, $to);

            if ($result) {

                $res['status'] = true;

                foreach ($result as $k=>$v)
                {
                    $result[$k]['CauseListTitle'] = ucwords(strtolower($v['CauseListTitle']));
                }

                $res['response'] = $result;

            } else {

                $res['status'] = false;

            }

            $this->response($res);

        } catch (Exception $e) {
            $this->response($e->getMessage());

            exit;
        }

    }

    /* SHOW CAUSE LIST END*/

    /* SHOW DIARY START*/

    public function displayDiary_get()
    {

        date_default_timezone_set("Asia/Kolkata");

        try
        {

            error_reporting(1);
            $res = array();
            $res['status'] = false;
            $this->load->model('Login');
            $result = $this->Login->getDiaryList();
            if ($result)
            {
                $res['status'] = true;

                $output = array();
                foreach ($result as $ress) {

                    $dte1 = new DateTime($ress['dte1']);
                    $date = $dte1->format('M d Y');

                    $ress['dte1'] = $date;
                    $ress['text_field'] = ucwords(strtolower($ress['text_field']));
                    $output[] = $ress;
                }

                $res['response'] = $output;
                //print_r($result); die;
            } else {

                $res['status'] = false;

            }

            $this->response($res);
        } catch (HTML2PDF_exception $e) {
            $this->response($e->getMessage());

            exit;
        }

    }

    /* END DIARY  END*/

    //Master Table return function

    /* GET CASE TYPE START*/

    public function caseType_get()
    {

        try
        {

            //error_reporting(1);
            $res = array();
            $res['status'] = false;
            $this->load->model('Login');
            $result = $this->Login->getCaseType();
            if ($result) {

                foreach ($result as $k => $v)
                {
                    $result[$k]['Description'] = ucwords(strtolower($v['Description']));
                }

                $res['status'] = true;
                $res['response'] = $result;

            } else {

                $res['status'] = false;

            }

            $this->response($res);
        } catch (HTML2PDF_exception $e) {
            $this->response($e->getMessage());

            exit;
        }

    }

    /* END GET CASE TYPE END*/

    /* GET JUDGE DETAILS START*/

    public function judgeDetails_get()
    {

        try
        {

            //error_reporting(1);
            $res = array();
            $res['status'] = false;
            $this->load->model('Login');
            $result = $this->Login->getJudgeDetails();
            if ($result) {
                $res['status'] = true;

                foreach ($result as $k => $v)
                {
                    $result[$k]['JUDGE_NAME'] = ucwords(strtolower($v['JUDGE_NAME']));
                }

                $res['response'] = $result;

            } else {

                $res['status'] = false;

            }

            $this->response($res);
        } catch (Exception $e) {
            $this->response($e->getMessage());

            exit;
        }

    }

    /* END JUDGE DETAILS END*/

    /* GET GENDER START*/

    public function gender_get()
    {

        try
        {

            error_reporting(1);
            $res = array();
            $res['status'] = false;
            $this->load->model('Login');
            $result = $this->Login->getGender();
            if ($result) {
                $res['status'] = true;

                foreach ($result as $k=>$v)
                {
                    $result[$k]['Sex_Name'] = ucwords(strtolower($v['Sex_Name']));
                }

                $res['response'] = $result;

            } else {

                $res['status'] = false;

            }

            $this->response($res);
        } catch (Exception $e) {
            $this->response($e->getMessage());

            exit;
        }

    }

    /* GENDER END*/

    /* GET PURPOSE DETAILS START*/

    public function purpose_get()
    {

        try
        {

            error_reporting(1);
            $res = array();
            $res['status'] = false;
            $this->load->model('Login');
            $result = $this->Login->getPurpose();
            if ($result) {
                $res['status'] = true;

                foreach ($result as $k=>$v)
                {
                    $result[$k]['Purp_Desc'] = ucwords(strtolower($v['Purp_Desc']));
                }

                $res['response'] = $result;

            } else {

                $res['status'] = false;

            }

            $this->response($res);
        } catch (Exception $e) {
            $this->response($e->getMessage());

            exit;
        }

    }

    /* END PURPOSE DETAILS END*/

    /* GET OCCUPATION DETAILS START*/

    public function occupation_get()
    {
        try
        {
            error_reporting(1);
            $res = array();
            $res['status'] = false;
            $this->load->model('Login');
            $result = $this->Login->getOccupation();
            if ($result) {
                $res['status'] = true;

                foreach ($result as $k=>$v)
                {
                    $result[$k]['Ocu_Name'] = ucwords(strtolower($v['Ocu_Name']));
                }

                $res['response'] = $result;

            } else {

                $res['status'] = false;

            }

            $this->response($res);
        } catch (Exception $e) {
            $this->response($e->getMessage());

            exit;
        }

    }

    /* GET OCCUPATION DETAILS END*/

    /* GET ID PROOF DETAILS START*/

    public function idProof_get()
    {

        try
        {

            error_reporting(1);
            $res = array();
            $res['status'] = false;
            $this->load->model('Login');
            $result = $this->Login->getidProof();
            if ($result) {
                $res['status'] = true;

                foreach ($result as $k=>$v)
                {
                    $result[$k]['idProof_Name'] = ucwords(strtolower($v['idProof_Name']));
                }

                $res['response'] = $result;

            } else {

                $res['status'] = false;

            }

            $this->response($res);
        } catch (Exception $e) {
            $this->response($e->getMessage());

            exit;
        }

    }

    /* GET ID PROOF  DETAILS END*/

    /* GET BLOCK DETAILS START*/

    public function block_get()
    {

        try
        {

            error_reporting(1);
            $res = array();
            $res['status'] = false;
            $this->load->model('Login');
            $result = $this->Login->getBlock();
            if ($result) {
                $res['status'] = true;

                foreach ($result as $k=>$v)
                {
                    $result[$k]['Blk_Name'] = ucwords(strtolower($v['Blk_Name']));
                }

                $res['response'] = $result;

            } else {

                $res['status'] = false;

            }

            $this->response($res);
        } catch (Exception $e) {
            $this->response($e->getMessage());

            exit;
        }

    }

    /* GET ADV DETAILS END*/

    public function advocate_get()
    {

        try
        {

            error_reporting(1);
            $res = array();
            $res['status'] = false;
            $this->load->model('Login');
            $result = $this->Login->getAdvocate();
            if ($result) {
                $res['status'] = true;
               
				 foreach ($result as $k => $rs)
                {
					$result[$k]['NAME'] = $rs['FIRSTNAME'].' '.$rs['LASTNAME'];
				
                }
				
				 $res['response'] = $result;
            } else {

                $res['status'] = false;

            }

            $this->response($res);
        } catch (HTML2PDF_exception $e) {
            $this->response($e->getMessage());

            exit;
        }

    }

    /* GET ADV DETAILS END*/

    /* GATE PASS REGISTRATION START*/

    public function gatePassRegistration_post()
    {
        try
        {
            $res = array();
            $res['status'] = false;

            $VisName = $this->post('Vis_Name');
            $PassType = $this->post('Pass_Type');
            $VisDate = $this->post('Vis_Date');
            $VisFatherName = $this->post('Vis_FatherName');
            $VisAddress = $this->post('Vis_Address');
            $CrtNo = $this->post('Crt_No');
            $Itm_No = $this->post('Itm_No');
            $IdProofcd = $this->post('IdProof_cd');
            $IdProofNo = $this->post('IdProof_No');
            $OcuCd = $this->post('Ocu_Cd');
            $CaseType = $this->post('Case_Type');
            $CaseNo = $this->post('Case_No');
            //$Person_to_Meet_Name = $this->post('Person_to_Meet_Name');
            $MobileNumber = $this->post('MobileNumber');
            //$otp = $this->post('OTP');
            $VisSex = $this->post('Vis_Sex');
            $VisAge = $this->post('Vis_Age');
            $PurpCD = $this->post('Purp_CD');
            $AdvType = $this->post('Adv_Type');
            $Remark = $this->post('Remark');
			
            $ValidTo = $this->post('Valid_to');
            $EntryDate = date('Y-m-d h:i:s');
            $FatherSpouseType = $this->post('FatherSpouseType');
            $VisYear = date('Y');

            $vis_pic = $this->post('visitor_picture');

            if(empty($vis_pic)){
                $res['status'] = false;
                $res['response'] = "Please provide picture";

                $this->response($res);
            }

            file_put_contents('../upload/visitor_image/test.txt', $vis_pic);
            # Load genereal helper
            $this->load->helper('general');
            $vis_pic = base64_to_file('../upload/visitor_image/', $vis_pic);

            /* Generate Vis_No, VisRN
             * here to ensure unique set of data
             */
            /*
            $extension=array("jpeg","jpg","png","JPEG","JPG","PNG");
            if(!empty($_FILES))
            {
                $vis_pic = "";
                if(!empty($_FILES['Photo']['name']))
                {
                    $file_name=$_FILES["Photo"]["name"];
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, $_FILES['Photo']['tmp_name']);

                    $mimearray = array('image/jpeg','image/png','application/pdf');
                    if(!in_array($mime,$mimearray))
                    {

                        $res['status'] = false;
                        $res['response'] = "File Extension is not valid.";
                        $this->response($res);
                        return false;
                    }
                    $name = str_replace(" ","_",$file_name);
                    $vis_pic = time().'Photo'.$name;
                    $isValid_Extention_Size = explode('.',$vis_pic);
                    if(count($isValid_Extention_Size) > 2)
                    {

                        $res['status'] = false;
                        $res['response'] = "File name is not valid.";
                        $this->response($res);
                        return false;

                    }
                    elseif($_FILES['Photo']["size"] <= 0 || $_FILES['Photo']["size"] >5000000)
                    {


                        $res['status'] = false;
                        $res['response'] = "File size should be greater than 0 Bytes and less than 5 MB!.";
                        $this->response($res);
                        return false;
                    }
                    $file_tmp = $_FILES["Photo"]["tmp_name"];
                    $ext = pathinfo($file_name,PATHINFO_EXTENSION);

                    $imageinfo = getimagesize($_FILES['Photo']['tmp_name']);

                    if(in_array($ext,$extension))
                    {
                        $target_file = '../upload/visitor_image/'.$vis_pic;
                        move_uploaded_file($file_tmp, $target_file);
                        $post['Photo'] = $vis_pic;
                    }
                    else
                    {

                        $res['status'] = false;
                        $res['response'] = "File Extension is not valid!.";
                        $this->response($res);
                        return false;

                    }
                }
            }
            */
            /* Calculate Vis_No, by checking total number of visitor for today */
            $date = new DateTime();
            $date = $date->format('Y-m-d');

            $curr_year = date('Y');

            $mssql = $this->load->database('mssql1', true);
            $sql = "SELECT Curr_No FROM VisCurrNo WHERE Vis_Year = '$curr_year' and Pass_Type = '$PassType'";
            $mssqlRs = $mssql->query($sql)->result_array();
            $curr_no = $mssqlRs[0]['Curr_No'];

            $vis_rn = $curr_year.'/'.$PassType.'/'.$curr_no;
            $vis_no = 1;

            /* Update current number in viscurr no table*/
            $curr_no = ($curr_no + 1);
            $this->db->where(array('Vis_Year'=>$curr_year, 'Pass_Type'=>$PassType));
            $this->db->update('VisCurrNo', array('Curr_No'=>$curr_no));


            /* Prepare data for insertion */
            $data = array(
                'Vis_Name' => $VisName,
                'Pass_Type' => $PassType,
                'Vis_FatherName' => $VisFatherName,
                'Vis_Address' => $VisAddress,
                'IdProof_cd' => $IdProofcd,
                'IdProofNo' => $IdProofNo,
                'Crt_No' => $CrtNo,
                'Itm_No' => $Itm_No,
                'Ocu_Cd' => $OcuCd,
                'Purp_CD' => $PurpCD,
                'Case_Type' => $CaseType,
                'Case_No' => $CaseNo,
                'Vis_Sex' => $VisSex,
                'Vis_Age' => $VisAge,
                'Approved' => '0',
                'Adv_Type' => $AdvType,
                'Valid_to' => $ValidTo,
                'Remark' => $Remark,
                'Vis_Date' => $VisDate,
                'MobileNumber' => $MobileNumber,
                'Vis_Year' => $VisYear,
                'Vis_No' => $vis_no,
                'VisRN' => $vis_rn,
                'Entry_Date' => $EntryDate,
                //'Adv_Name' => $AdvName,
                //'Adv_Code' => $Adv_Code,
				 //'Emp_Name' => $EmpName,
                //'Emp_Code' => $EmpCode,
                'Photo' => 0,
                'MSPrint' => 0,
                'FatherSpouseType' => $FatherSpouseType,
                'admitted' => 1,
                'out' => 1,
            );
			if($AdvType == 'Adv'){
				$data['Adv_Code'] = $this->post('Adv_Code');
				$data['Adv_Name'] = $this->post('Adv_Name');
				
				}
			if($AdvType == 'Emp'){
				$data['Emp_Name'] = $this->post('Emp_Name');
			    $data['Emp_Code'] = $this->post('Emp_Code');
				}
            /* Load modal */
            $this->load->model('Login');
            $result = $this->Login->insertVisitorDetails($data);

            if ($result) {

                /* New pass is generated, create entry in database */
                $data = array(
                    'VisRN' => $vis_rn,
                    'Photo' => $vis_pic,
                    'ID_Card'=> $IdProofNo
                );

                $this->db->insert('VisPicture', $data);


                $res['status'] = true;
                $data1 = $this->Login->getGatePassById($result);
                $data1 = $data1[0];
                $date = new DateTime();
                $date = $date->format('d-m-Y');

                $passData['date'] = $date;
                $passData['ref_no'] = $data1['Vis_Year'] . '/' . $data1['Case_Type'] . '/' . $data1['Case_No'];
                $passData['item_no'] = $data1['Itm_No'];
                $passData['visitor_name'] = $data1['Vis_Name'];
                $passData['court_no'] = $data1['Crt_No'];
                $passData['contact_person'] = $data1['Emp_Name'];
                $passData['advocate_type'] = $data1['Emp_Name'];
                $passData['designation'] = $data1['Emp_Desg'];
                $passData['mobile'] = $data1['MobileNumber'];

                //$res['response'] = $this->generateMyGatePass($passData);
                // $res['response'] = str_replace(array("\n", "\r"), '', $res['response']);
                $res['response'] = "Your gate pass request is sent successfully";
            } else {
                $res['status'] = false;
                $res['response'] = "Registration failed..Try Again..";
            }

            echo json_encode($res);
            // $this->response($res, 200);
        } catch (Exception $e) {
            $this->response($e->getMessage());

            exit;
        }
    }
    /*
    public function previous_gate_pass($mobile)
    {
    $mssql = $this->load->database('mssql1',TRUE);
    $sql = "SELECT * FROM Visitor_Details WHERE MobileNumber =  '$mobile'";
    return $mssql->query($sql)->result_array();

    }
     */

    /* GATE PASS REGISTRATION END*/

    /* GET EMPLOYEE DETAILS START*/

    public function employee_get()
    {

        try
        {

            error_reporting(1);
            $res = array();
            $res['status'] = false;
            $this->load->model('Login');
            $result = $this->Login->getEmployee();
            if ($result) {
                $res['status'] = true;

                foreach ($result as $k=>$v)
                {
                    $result[$k]['Emp_Name'] = ucwords(strtolower($v['Emp_Name']));
                    $result[$k]['Emp_Desg'] = ucwords(strtolower($v['Emp_Desg']));
                }

                $res['response'] = $result;

            } else {

                $res['status'] = false;

            }

            $this->response($res);
        } catch (Exception $e) {
            $this->response($e->getMessage());

            exit;
        }

    }

    /* GET EMPLOYEE DETAILS END*/

    /* USER REGISTRATION START*/

    public function registration_post()
    {

        try
        {

            $res = array();
            $res['status'] = false;
            $UserName = $this->post('User_Name');
            $UserPassword = $this->post('User_Password');
            $UserMobile = $this->post('User_Mobile');
            $Emailid = $this->post('Email_id');
            $BarCouncilId = $this->post('Bar_Council_Id');
            $otp = $this->post('OTP');

            if (!filter_var($Emailid, FILTER_VALIDATE_EMAIL)) {
                $res['status'] = false;
                $res['response'] = "Invalid email format..";

                return $this->response($res);
            }
            if (empty($UserName) || empty($UserPassword) || empty($UserMobile) || empty($Emailid)) {
                $res['status'] = false;
                $res['response'] = 'Field can\'t be empty';

                return $this->response($res);
            }

            $data = array(
                'User_Name' => $UserName,
                'User_Mobile' => $UserMobile,
                'User_Password' => $UserPassword,
                'Email_id' => $Emailid,
                'User_Active_Status' => 1,
                'User_ID' => '1',
                'User_Section' => '1',
                'Application' => '1',
                'Login_Trails' => '1',
                'Per_DataEntry' => '1',
                'Per_Query' => '1',
                'Per_Report' => '1',
                'Per_Admin' => '1',
                'Per_SuperUser' => '1',
                /*'User_Active_Status'=>1,
            'User_Section'=>1 */
            );
            $this->load->model('Login');
            $isEmailExists = $this->Login->emailExists($Emailid);
            if (!$isEmailExists) {
                //$this->form_validation->set_error_delimiters('', '');
                //if($this->form_validation->run('registration') == FALSE) {
                //$res['errors'] = validation_errors();
                //}else{
                $result = $this->Login->userDetails($data);
                //echo "<pre>";
                //print_r($result);die;
                if ($result) {
                    $res['status'] = true;
                    $res['response'] = "Registration Successfully";
                } else {
                    $res['status'] = false;
                    $res['response'] = "Registration failed..Try Again..";
                }
                //}
            } else {

                $res['status'] = false;
                $res['response'] = "!Sorry email already registered..";

            }

            $this->response($res);
        } catch (HTML2PDF_exception $e) {
            $this->response($e->getMessage());

            exit;
        }
    }

    /* USER REGISTRATION END*/

    /* GET COURT DETAILS START*/

    public function court_get()
    {

        try
        {

            error_reporting(1);
            $res = array();
            $res['status'] = false;
            $this->load->model('Login');
            $result = $this->Login->getCourt();
            if ($result) {
                $res['status'] = true;
                foreach ($result as $k=>$v)
                {
                    $result[$k]['Crt_Name'] = ucwords(strtolower($v['Crt_Name']));
                }

                $res['response'] = $result;

            } else {

                $res['status'] = false;

            }

            $this->response($res);
        } catch (Exception $e) {
            $this->response($e->getMessage());
            exit;
        }

    }

    /* GET COURT DETAILS END*/
    public function test_get()
    {
        $conn = oci_connect('dhc', 'oracledba', '180.151.3.106');
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        oci_close($conn);

    }

    /* GET COURT DETAILS START*/

    public function notice_get()
    {
        date_default_timezone_set("Asia/Kolkata");

        try
        {

            $res = array();
            $res['status'] = false;
            $this->load->model('Login');
            $result = $this->Login->getNotice();
            if ($result) {
                $res['status'] = true;

                $output = array();
                foreach ($result as $ress) {

                    $dte1 = new DateTime($ress['NoticeDate']);
                    $date = $dte1->format('M d Y');

                    $ress['NoticeDate'] = $date;
                    $ress['NoticeTitle'] = ucwords(strtolower($ress['NoticeTitle']));
                    $output[] = $ress;
                }

                $res['response'] = $output;
            } else {

                $res['status'] = false;

            }

            $this->response($res);
        } catch (Exception $e) {
            $this->response($e->getMessage());

            exit;
        }

    }

    /* GET ADV DETAILS END*/

    public function gatePass_get()
    {

        try
        {

            $res = array();
            $res['status'] = false;
            $this->load->model('Login');
            $result = $this->Login->getGatePass();

            if ($result) {
                $res['status'] = true;
                $res['response'] = $result;

            } else {

                $res['status'] = false;

            }

            $this->response($res);
        } catch (Exception $e) {
            $this->response($e->getMessage());

            exit;
        }

    }

    public function advDetails_get()
    {
        try
        {

            $res = array();
            $res['status'] = false;
            $this->load->model('Login');
            $result = $this->Login->getAdvDetails();
            if ($result) {
                $res['status'] = true;
                $res['response'] = $result;

            } else {

                $res['status'] = false;

            }

            $this->response($res);
        } catch (Exception $e) {
            $this->response($e->getMessage());

            exit;
        }

    }

    public function caseStatusListing_get()
    {
        try
        {

            $res = array();
            $res['status'] = false;
            $this->load->model('Login');
            $result = $this->Login->getCaseStatusListing();
            if ($result) {
                $res['status'] = true;
                $res['response'] = $result;

            } else {

                $res['status'] = false;

            }

            $this->response($res);
        } catch (Exception $e) {
            $this->response($e->getMessage());

            exit;
        }

    }

    public function nextDateHiring_get()
    {
        try
        {

            $res = array();
            $res['status'] = false;
            $this->load->model('Login');
            $result = $this->Login->getNextDateHiring();
            if ($result) {
                $res['status'] = true;

                foreach ($result as $k => $v)
                {
                    $result[$k]['CASE_TITLE'] = ucwords(strtolower($v['CASE_TITLE']));
                    $result[$k]['PET_NAME'] = ucwords(strtolower($v['PET_NAME']));
                    $result[$k]['RES_NAME'] = ucwords(strtolower($v['RES_NAME']));
                    $result[$k]['DESCRIPTION'] = ucwords(strtolower($v['DESCRIPTION']));
                }

                $res['response'] = $result;

            } else {

                $res['status'] = false;

            }

            $this->response($res);
        } catch (Exception $e) {
            $this->response($e->getMessage());

            exit;
        }

    }

    public function listing_get()
    {
        try
        {

            $res = array();
            $res['status'] = false;
            $this->load->model('Login');
            $result = $this->Login->getListing();
            if ($result) {
                $res['status'] = true;
                $res['response'] = $result;

            } else {

                $res['status'] = false;

            }

            $this->response($res);
        } catch (Exception $e) {
            $this->response($e->getMessage());

            exit;
        }

    }
    /* GET ADV DETAILS END*/
    /* GET COURT DETAILS END*/

    //adv login

    public function advLogin_post()
    {

        $res = array();
        $post = $this->post();
        if (empty($post['MOBILENO'])) {
            $res['status'] = false;
            $res['response'] = 'Mobile can\'t be empty';

            return $this->response($res);
        }

        $MobileNo = $post['MOBILENO'];
        //$UserPassword = $post['PASSWORD'];

        $this->load->model('Login');
        $checkMobile = $this->Login->checkMobile($MobileNo);
        if ($checkMobile) {
            $res['status'] = true;
            $res['response'] = $checkMobile;
        } else {
            $res['status'] = false;
            $res['response'] = "Sorry Login failed..Invalid credentials..";

        }

        $this->response($res);

    }

    //ready
    public function caseStatus_post()
    {

        try
        {

            $res = array();
            $res['status'] = false;
            $post = $this->post();
            //echo "<pre>";
            //print_r($post);die;
            $regNo = $post['REG_NO'];
            $year = $post['REG_YR'];
            $this->load->model('Login');
            $result = $this->Login->getCaseStatus($regNo, $year);
            if ($result) {
                $res['status'] = true;
                //$res['response'] = $result;

                foreach ($result as $key => $resData) {
                    $res['response'][$key] = $resData;
                    $res['response'][$key]['CASE_NO'] = $resData['REG_NO'] . '/' . $resData['REG_YR'];
                    $res['response'][$key]['PARTY'] = ucwords(strtolower($resData['PET_NAME'] . '/' . $resData['RES_NAME']));

                    $res['response'][$key]['PET_NAME'] = ucwords(strtolower($resData['PET_NAME']));
                    $res['response'][$key]['RES_NAME'] = ucwords(strtolower($resData['RES_NAME']));
                }

                //$res['response'][] = $output;

            } else {

                $res['status'] = false;

            }

            $this->response($res);
        } catch (Exception $e) {
            $this->response($e->getMessage());

            exit;
        }

    }

    public function customisedCauselist_post()
    {
        $res = array();
        $post = $this->post();
        $start_date = $this->post('START_DATE'); // date in YYYY-MM-DD format
        $end_date = $this->post('END_DATE');
        //print_r($post);die;
        $searchKey = $this->post('SEARCH_KEY'); /* Exact, Substring, Full, Court Number, Judge*/
        $searchValue = $this->post('SEARCH_VALUE');

        $this->load->model('Login');
        $result = $this->Login->getCustomizeCauselist($start_date, $end_date, $searchKey, $searchValue);

        if ($result) {
            $res['status'] = true;
            //$res['response'] = $result;

            foreach ($result as $key => $resData) {
                $res['response'][$key] = $resData;
                $res['response'][$key]->CASE_NO = $resData->REG_NO . '/' . $resData->REG_YR;
                $res['response'][$key]->PARTY = ucwords(strtolower($resData->PET_NAME . '/' . $resData->RES_NAME));

                $res['response'][$key]->PET_NAME = ucwords(strtolower($resData->PET_NAME));
                $res['response'][$key]->RES_NAME = ucwords(strtolower($resData->RES_NAME));
                $res['response'][$key]->JUDGE_NAME = ucwords(strtolower($resData->JUDGE_NAME));
                $res['response'][$key]->ADV_NAME = ucwords(strtolower($resData->ADV_NAME));
            }

            //$res['response'][] = $output;

        } else {

            $res['status'] = false;

        }

        $this->response($res);

    }

    public function advRegistration_post()
    {

        try
        {

            $res = array();
            $res['status'] = false;
            //echo "<pre>";
            //print_r($_POST);die;
            $FirstName = $this->post('FIRSTNAME');
            //$UserPassword = $this->post('User_Password');
            $UserMobile = $this->post('MOBILENO');
            $Emailid = $this->post('EMAIL');
            $BarCouncilNo = $this->post('BARCOUNCILNO');
            //$otp = $this->post('OTP');

            if (!filter_var($Emailid, FILTER_VALIDATE_EMAIL)) {
                $res['status'] = false;
                $res['response'] = "Invalid email format..";

                return $this->response($res);
            }
            if (empty($FirstName) || empty($Emailid) || empty($UserMobile) || empty($BarCouncilNo)) {
                $res['status'] = false;
                $res['response'] = 'Field can\'t be empty';

                return $this->response($res);
            }

            /*
             * Commented code on 18th Oct 2018
             * as per new process from Sunil Sir (IOS)
             */
            /*
            $data = array(
            'FIRSTNAME' =>$FirstName,
            'MOBILENO'=>$UserMobile,
            'EMAIL' =>$Emailid,
            'BARCOUNCILNO'=>$BarCouncilNo,
            'ID'=>rand(10,100),
            );
            $this->load->model('Login');
            $isEmailExists = $this->Login->AdvEmailExists($Emailid);
            if(!$isEmailExists)
            {
            $result = $this->Login->advDetails($data);
            if($result){
            $res['status'] = true;
            $res['response'] = "Registration Successfully";
            }else{
            $res['status'] = false;
            $res['response'] = "Registration failed..Try Again..";
            }

            }
            else
            {

            $res['status'] = false;
            $res['response'] = "!Sorry email already registered..";

            }
             */

            /* Instead of registering user we will validate mobile and otp against database values
             * Code missing add once we have the sms gatway
             */

            $res['status'] = true;
            $res['response'] = "Details Validated Successfully";

            $this->response($res);
        } catch (Exception $e) {
            $this->response($e->getMessage());

            exit;
        }
    }

    /* GET EMPLOYEE DETAILS START*/

    public function visitCurrent_get()
    {

        try
        {

            error_reporting(1);
            $res = array();
            $res['status'] = false;
            $this->load->model('Login');
            $result = $this->Login->getVisitCurrent();
            if ($result) {
                $res['status'] = true;
                $res['response'] = $result;

            } else {

                $res['status'] = false;

            }

            $this->response($res);
        } catch (HTML2PDF_exception $e) {
            $this->response($e->getMessage());

            exit;
        }

    }

    /* Validate advocate mobile number */

    public function checkAdvocateMobile_post()
    {
        $response = array();
        try
        {
            # If Empty
            if ($this->post('MOBILE_NO') == "") {
                $response['status'] = false;
                $response['response'] = "Please provide valid mobile number";
                $this->response($response);
            }

            # If valid
            if (!preg_match('/^[0-9]{10}+$/', $this->post('MOBILE_NO'))) {
                $response['status'] = false;
                $response['response'] = "Please provide valid 10 digit mobile number";
                $this->response($response);
            }

            # Check mobile number in ADV_DETAILS
            $ocidb = $this->load->database('oracle', true);
            $query = "select * from ADV_DETAILS WHERE MOBILENO = " . $this->post("MOBILE_NO");
            $oracleRs = $ocidb->query($query)->result_array();

            if (!empty($oracleRs)) {
                $response['status'] = true;

                foreach ($oracleRs as $k => $v)
                {
                    $oracleRs[$k]['FIRSTNAME'] = ucwords(strtolower($v['FIRSTNAME']));
                    $oracleRs[$k]['LASTNAME'] = ucwords(strtolower($v['LASTNAME']));
                    $oracleRs[$k]['EMAIL'] = strtolower($v['EMAIL']);
                }

                $response['result'] = $oracleRs;

                /* Once we have the details fire off a request to send otp and store again advocate */

            } else {
                $response['status'] = false;
                $response['response'] = "Mobile number does not exists, Please register with website first";
            }
            $this->response($response);
        } catch (Exception $e) {
            $this->response($e->getMessage());
        }
    }

    /* Validate gate pass mobile number registration */

    public function checkGatePassMobile_post()
    {
        $response = array();
        try
        {
            # If Empty
            if ($this->post('MOBILE_NO') == "") {
                $response['status'] = false;
                $response['response'] = "Please provide valid mobile number";
                $this->response($response);
            }

            # If valid
            if (!preg_match('/^[0-9]{10}+$/', $this->post('MOBILE_NO'))) {
                $response['status'] = false;
                $response['response'] = "Please provide valid 10 digit mobile number";
                $this->response($response);
            }

            # Check mobile number in [Visitor_Details]
            $mssqldb = $this->load->database('mssql1', true);
            $query = "select * from Visitor_Details WHERE MobileNumber = " . $this->post("MOBILE_NO");
            $mssqlRs = $mssqldb->query($query)->result_array();
            $mssqlRs = $mssqlRs[count($mssqlRs) - 1];

            if (!empty($mssqlRs)) {
                $response['status'] = true;

                $mssqlRs['Adv_Name'] = ucwords(strtolower($mssqlRs['Adv_Name']));
                $response['result'] = $mssqlRs;
            } else {
                $response['status'] = false;
                $response['response'] = "Mobile number does not exists";
            }
            $this->response($response);
        } catch (Exception $e) {
            $this->response($e->getMessage());
        }
    }

    public function getMyGatePass_post()
    {
		//error_reporting(1);
		//print_r($this->post('MOBILE_NO'));die;
        $response = array();
        try
        {
            # If Empty
            if ($this->post('MOBILE_NO') == "") {
                $response['status'] = false;
                $response['response'] = "Please provide valid mobile number";
                $this->response($response);
            }

            # If valid
            if (!preg_match('/^[0-9]{10}+$/', $this->post('MOBILE_NO'))) {
                $response['status'] = false;
                $response['response'] = "Please provide valid 10 digit mobile number";
                $this->response($response);
            }

            # Check mobile number in [Visitor_Details]
            $mssqldb = $this->load->database('mssql1', true);
            $MobileNo = $this->post("MOBILE_NO");
            $query = "select * from Visitor_Details WHERE MobileNumber = '$MobileNo' AND Approved = 1";
            $mssqlRs = $mssqldb->query($query)->result_array();
			//print_r($mssqlRs);die;
            if (!empty($mssqlRs)) {
                $response['status'] = true;
                $t = array();
                foreach ($mssqlRs as $s) {
                    $adv_name = $s['Adv_Name'];
					$emp_name = $s['Emp_Name'];
					$adv_type = $s['Adv_Type'];
                    // $advocate_name = "";
                    /* Get Emp_Name from EmpMaster */
                    // $sql = "select Emp_Name FROM EmpMaster WHERE Emp_ID = '$adv_name'";
                    // $advocate_data = $mssqldb->query($sql)->result_array();
                    // if (!empty($advocate_data)) {
                    //     $advocate_name = $advocate_data[0]['Emp_Name'];
                    // }

                    $date = new DateTime($s['Vis_Date']);
                    $date = $date->format('M d Y');
                    $output = array();
                    $output['VisRN'] = $s['VisRN'];
                    $output['Vis_Date'] = $date;
                    $output['Pass_Type'] = $s['Pass_Type'];
                    $output['Case_Type'] = $s['Case_Type'];
                    $output['Case_No'] = $s['Case_No'];
					if($adv_type == 'Adv'){
						 $output['Emp_Name'] = $adv_name;
						}
                   
					if($adv_type == 'Emp'){
						$output['Emp_Name'] = $emp_name;
						}
					
                    $output['Approved'] = $s['Approved'];
                    $response['result'][] = $output;
				
                }

            } else {
                $response['status'] = false;
                $response['response'] = "No record found !!!";
            }
            $this->response($response);
        } catch (Exception $e) {
            $this->response($e->getMessage());
        }
    }

    public function generateMyGatePassPDF_post()
    {
		
        $response = array();
		//$vis_rn = '2018/A/101605';
		//echo $vis_rn;die;
            if ($this->post("VisRN") == "") {
            $response['status'] = false;
            $response['response'] = "Please provide VisRN";
            $this->response($response);
         }    
       $vis_rn = $this->post("VisRN");
		
        $mssqldb = $this->load->database('mssql1', true);
        $query = "select * from Visitor_Details WHERE VisRN = '$vis_rn'";
        $mssqlRs = $mssqldb->query($query)->result_array();
		//echo "<pre>";
		//print_r($mssqlRs);die;
        if (empty($mssqlRs)) {
            $response['status'] = false;
            $response['response'] = "Invalid VisRN";
            $this->response($response);
        }
        $s = $mssqlRs[0];
        $emp_id = $s['Emp_Code'];
		$adv_id = $s['Adv_Code'];
		$purpose_id = $s['Purp_CD'];
		$adv_type = $s['Adv_Type'];
		if($adv_type == 'Emp'){

			 /* Get Emp_Name from EmpMaster */
        $sql = "select Emp_Name, Emp_Desg FROM EmpMaster WHERE Emp_ID = '$emp_id'";
        $employee_data = $mssqldb->query($sql)->result_array();
		
		
		$employee_name = '';
        $employee_desg = '';
		
        if (!empty($employee_data)) {
            $advocate_name = $employee_data[0]['Emp_Name'];
			
            $employee_desg = "Employee";
        }
		}
			
		if($adv_type == 'Adv'){
			
			 /* Get A from AdvDetails */
			 
		$this->load->model('Login');
        $advocate_data = $this->Login->getAdvocateByid($adv_id);
		
		//echo "<pre>";
		//print_r($advocate_data);die;
        if (!empty($advocate_data)) {
		
            $advocate_name = $advocate_data[0]['FIRSTNAME'].$advocate_data[0]['LASTNAME'];
			//echo $advocate_name;die;
            $employee_desg = "Advocate";
        }
			}
        
		/* Get Purpos from EmpMaster */
       
	   $sql = "select * FROM Purpose_Tab WHERE Purp_CD = '$purpose_id'";
       $purpose_data = $mssqldb->query($sql)->result_array();
	   
		   if (!empty($purpose_data)) {
		
            $purpose_name = $purpose_data[0]['Purp_Desc'];
			
            
        }
		

        /* Get Image from [VisPicture] Based on VisRn*/
        $vispicrs = $this->db->select('Photo')->from('VisPicture')->where('VisRN', $vis_rn)->get()->row();
        $vis_pic = '';
        if(!empty($vispicrs)){
            $vis_pic = $vispicrs->Photo;
        }

        $today = new DateTime();
        $date = $today->format('d-m-Y');
        /*===================================================================================*/

        /* require library to create barcode */
        require_once APPPATH.'third_party/vendor/autoload.php';
        /* Initialise library */
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();

        /* Generate barcode */
        $barcode = $generator->getBarcode($vis_rn, $generator::TYPE_CODE_128);

        /* Base64 String for barcode */
        $barcode = base64_encode($barcode);

        $passData['date'] = $date;
        $passData['ref_no'] = $s['Vis_Year'] . '/' . $s['Case_Type'] . '/' . $s['Case_No'];
        $passData['item_no'] = $s['Itm_No'];
        $passData['visitor_name'] = $s['Vis_Name'];
        $passData['court_no'] = $s['Crt_No'];
        $passData['contact_person'] = $advocate_name;
        //$passData['advocate_type'] = $advocate_name;
        $passData['designation'] = $employee_desg;
		$passData['purpose'] = $purpose_name;
        $passData['mobile'] = $s['MobileNumber'];
        $passData['barcode'] = $barcode;
        $passData['VisRN'] = $vis_rn;
        $passData['image'] = $vis_pic;
		//echo "<pre>";
		//print_r($passData);die;
        /*===================================================================================*/

        $response['status'] = true;
        $response['response'] = $this->generateMyGatePass($passData);

        echo json_encode($response);
        // $this->response($response, 200);
    }

    public function generateMyGatePass($passData)
    {
        $output = $this->load->view('gatepass', $passData, true);
		//echo $output;die;
        # Store in directory
        $file_name = 'assets/gate_pass/' . uniqid() . '.pdf';
        $this->generate_pdf($output, $file_name);
        $enc = base64_encode(file_get_contents($file_name));
        unlink($file_name);
        return $enc;

        /*
    $gatePassArr = array(
    chunk_split($enc)
    );

    return $gatePassArr;
     */
    }

    public function generate_pdf($output, $file_name = null, $file_path = null)
    {
        $mpdf = new Mpdf('s', 'A4', '', '', 7, 7, 05, 10, 10, 10);
        $mpdf->SetFont('Arial', 'B', 12);
        $mpdf->WriteHTML($output);

        return $mpdf->Output($file_name, 'F');
    }

    /* Advocate specific next date of hearing*/

    public function getAdvNextDateOfHearing_post()
    {
        try
        {
            $response = array();

            # Step 1 Check if advocate code is sent or not
            if ($this->post('ADV_CODE') == "") {
                $response['status'] = false;
                $response['result'] = "Please provide a valid Advocate Code";

                $this->response($response);
            }

            $adv_code = $this->post('ADV_CODE');

            # Step 2, Join Listing and Main table to get advocate specific date of hearing
            $ocidb = $this->load->database('oracle', true);
            $sql = "SELECT M.PET_NAME||' vs '||M.RES_NAME CASE_TITLE, M.PET_NAME,M.RES_NAME,M.REG_NO,M.REG_YR, M.CTYPE, L.NEXT_DATE,L.DATE_LIST,L.COURT_NO, L.CTYPE||'/'||L.REG_NO||'/'||L.REG_YR CASE_NUMBER FROM MAIN M JOIN LISTING L ON L.REG_NO = M.REG_NO WHERE ADV_CODE = $adv_code";
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
                                $o['DESCRIPTION'] = ucwords(strtolower($m['Description']));
                            }

                            $next_date = new DateTime($o['NEXT_DATE']);
                            $next_date = $next_date->format('d-m-Y');
                            $o['NEXT_DATE'] = $next_date;

                            $date_list = new DateTime($o['DATE_LIST']);
                            $date_list = $date_list->format('d-m-Y');
                            $o['DATE_LIST'] = $date_list;

                            $o['CASE_TITLE'] = ucwords(strtolower($o['CASE_TITLE']));
                            $o['PET_NAME'] = ucwords(strtolower($o['PET_NAME']));
                            $o['RES_NAME'] = ucwords(strtolower($o['RES_NAME']));
                        }
                    }
                    array_push($next_date_array, $o);
                }
            }
            if (empty($next_date_array)) {
                $response['status'] = false;
                $response['result'] = "No result found";
            } else {
                $response['status'] = true;
                $response['result'] = $next_date_array;
            }

            $this->response($response);
        } catch (Exception $e) {
            $this->response($e->getMessage());
        }
    }

    /* Advocate specific next date of hearing*/

    /* Advocate specific cause list function starts */

    public function getAdvCauseList_post()
    {
        try
        {
            $response = array();

            # Step 1 Check if advocate code is sent or not
            if ($this->post('ADV_CODE') == "") {
                $response['status'] = false;
                $response['result'] = "Please provide a valid Advocate Code";

                $this->response($response);
            }

            // if($this->post('START_DATE') =="" || $this->post('END_DATE') == "")
            // {
            //     $response['status'] = FALSE;
            //     $response['result'] = "Please provide a start date and end date";

            //     $this->response($response);
            // }

            $adv_code = $this->post('ADV_CODE');
            // $start_date = $this->post('START_DATE'); // date in YYYY-MM-DD format
            //    $end_date = $this->post('END_DATE');
            $date = new DateTime("11-10-2018");
            $date = $date->format('Y-m-d');

            $ocidb = $this->load->database('oracle', true);
            $ocidb->select("LISTING.J_CODE1,MAIN.PET_NAME,MAIN.RES_NAME,JUDGE.JUDGE_CODE,JUDGE.JUDGE_NAME,MAIN.ADV_NAME,MAIN.REG_NO,MAIN.REG_YR,LISTING.COURT_NO,MAIN.CTYPE,LISTING.NEXT_DATE,LISTING.DATE_LIST");
            $ocidb->from("MAIN");
            $ocidb->join('LISTING', 'LISTING.REG_NO = MAIN.REG_NO');
            $ocidb->join('JUDGE', 'JUDGE.JUDGE_CODE = LISTING.J_CODE1');
            $ocidb->where("LISTING.DATE_LIST BETWEEN DATE '" . $date . "' AND DATE '" . $date . "'");
            // $ocidb->where("LISTING.DATE_LIST = TO_DATE($date, 'Y-m-d')");
            $ocidb->where("MAIN.ADV_CODE", $adv_code);

            $ocidbRs = $ocidb->get()->result();

            if (empty($ocidbRs)) {
                $response['status'] = false;
                $response['result'] = "No result found";
            } else {
                $response['status'] = true;

                foreach ($ocidbRs as $key => $resData) {
                    $response['result'][$key] = $resData;
                    $response['result'][$key]->CASE_NO = $resData->REG_NO . '/' . $resData->REG_YR . '/' . $resData->CTYPE;
                    $response['result'][$key]->PARTY = ucwords(strtolower($resData->PET_NAME . '/' . $resData->RES_NAME));

                    $response['result'][$key]->PET_NAME = ucwords(strtolower($resData->PET_NAME));
                    $response['result'][$key]->RES_NAME = ucwords(strtolower($resData->RES_NAME));
                    $response['result'][$key]->JUDGE_NAME = ucwords(strtolower($resData->JUDGE_NAME));
                    $response['result'][$key]->ADV_NAME = ucwords(strtolower($resData->ADV_NAME ));

                }
                // $response['result'] = $ocidbRs;
            }

            $this->response($response);
        } catch (Exception $e) {
            $this->response($e->getMessage());
        }
    }

    /* Advocate specific cause list function ends */

    /* Advocate specific case status function starts */

    public function getAdvCaseStatus_post()
    {
        try
        {
            $response = array();

            # Step 1 Check if advocate code is sent or not
            if ($this->post('ADV_CODE') == "") {
                $response['status'] = false;
                $response['result'] = "Please provide a valid Advocate Code";

                $this->response($response);
            }

            $adv_code = $this->post('ADV_CODE');

            $ocidb = $this->load->database('oracle', true);
            $ocidb->select("MAIN.CTYPE, MAIN.REG_NO, MAIN.REG_YR, MAIN.PET_NAME,MAIN.RES_NAME,MAIN.ADV_NAME,LISTING.NEXT_DATE, LISTING.CASE_STAGE, LISTING.LIST_STAGE, LISTING.COURT_NO, LISTING.DATE_LIST, LISTING.ITEM_NO");
            $ocidb->from("MAIN");
            $ocidb->join('LISTING', 'LISTING.REG_NO = MAIN.REG_NO');
            $ocidb->where("MAIN.ADV_CODE", $adv_code);

            $ocidbRs = $ocidb->get()->result();
            // print_r($ocidbRs); die;
            if (empty($ocidbRs)) {
                $response['status'] = false;
                $response['result'] = "No result found";
            } else {
                $response['status'] = true;

                foreach ($ocidbRs as $key => $resData) {
                    $response['result'][$key] = $resData;
                    $response['result'][$key]->CASE_NO = $resData->CTYPE . '/' . $resData->REG_NO . '/' . $resData->REG_YR;
                    $response['result'][$key]->PET_NAME = ucwords(strtolower($resData->PET_NAME));
                    $response['result'][$key]->RES_NAME = ucwords(strtolower($resData->RES_NAME));
                    $response['result'][$key]->NEXT_DATE = $resData->NEXT_DATE;
                    $response['result'][$key]->CASE_STAGE = $resData->CASE_STAGE;
                    $response['result'][$key]->LIST_STAGE = $resData->LIST_STAGE;
                    $response['result'][$key]->PARTY = ucwords(strtolower($resData->PET_NAME . ' VS ' . $resData->RES_NAME));

                    $list_date = new DateTime($ocidbRs[$key]->DATE_LIST);
                    $list_date = $list_date->format('d-m-Y');
                    $response['result'][$key]->DATE_LIST = $list_date;

                    $next_date = new DateTime($ocidbRs[$key]->NEXT_DATE);
                    $next_date = $next_date->format('d-m-Y');
                    $response['result'][$key]->NEXT_DATE = $next_date;
                }
                // $response['result'] = $ocidbRs;
            }

            $this->response($response);
        } catch (Exception $e) {
            $this->response($e->getMessage());
        }
    }

    /* Advocate specific case status function ends */


    /* Employee Login start here*/

    public function employeeLogin_post()
    {

        $res = array();
        $post = $this->post();
        
        if (empty($post['Emp_mobile']) || empty($post['User_Password'])) {
            $res['status'] = false;
            $res['response'] = 'Mobile or Password can\'t be empty';

            return $this->response($res);
        }

        $EmpMobile = $post['Emp_mobile'];
        $EmpPassword = $post['User_Password'];

        $this->load->model('Login');
        $rs = $this->Login->authenticateEmployee($EmpMobile, $EmpPassword);

        if ($rs) {
            $res['status'] = true;
            $res['response']['message'] = "Login Successfully";
            $res['response']['result'] = $rs;
        } else {
            $res['status'] = false;
            $res['response'] = "Sorry Login failed..Invalid credentials..";

        }

        $this->response($res);

    }

    /* Employee Login ends here*/

    /* Employee specific gate pass requests starts here */

    public function employeePassRequests_post()
    {
		//print_r($this->post());die;
        try
        {
            $response = array(); 

			if($this->post('Adv_Type') == ""){
				
				$response['status'] = false;
                $response['result'] = "Please provide a valid Advocate Type";
                $this->response($response);
				
				};
            # Step 1 Check if emp id is sent or not
            if ($this->post('Emp_ID') == "") {
                $response['status'] = false;
                $response['result'] = "Please provide a valid Employee Id";

                $this->response($response);
            }

			
			$adv_type = $this->post('Adv_Type');
			//print_r($adv_type);
			if($adv_type == 'Adv'){
				$adv_code = $this->post('Emp_ID');
				$criteria = array('Adv_Code'=>$adv_code, 'Approved'=>0);
				//print_r($criteria);
				}
            if($adv_type == 'Emp'){
				$adv_code = $this->post('Emp_ID');
				$criteria = array('Emp_Code'=>$adv_code, 'Approved'=>0);
				//print_r($criteria);die;
				}
            
            // $criteria = "Adv_Code = $adv_code AND (Approved != 1 || Approved != 2)";
            $gatePassRequests = $this->login->getGatePassByCriteraia($criteria);
            //echo "<pre>";
			//print_r($gatePassRequests);die;
            if(empty($gatePassRequests))
            {
                $response['status'] = false;
                $response['result'] = "No result found !!!";
                $this->response($response);
            }

            $response['status'] = true;
            $response['result'] = $gatePassRequests;

            $this->response($response);
        } 
        catch (Exception $e) 
        {
            $this->response($e->getMessage());
        }
    }

    /* Employee specific gate pass requests ends here */

    /* Employee apporve pass start */

    public function approvePassRequest_post()
    {
	
        try
        {
            $response = array(); 

            # Step 1 Check if VisRN is sent or not
            if ($this->post('VisRN') == "") {
                $response['status'] = false;
                $response['result'] = "Please provide a valid VisRN";

                $this->response($response);
            }

            $VisRN = $this->post('VisRN');            
            $status = $this->post('Status');
			
            $data = array('Approved'=>$status);
            $rs = $this->login->updatePassRequest($VisRN, $data);
            
            if($rs)
            {
                $response['status'] = true;
                $response['result'] = "Gate pass status updated successfully !!!";
                $this->response($response);
            }
            else
            {
                $response['status'] = false;
                $response['result'] = "Something went wrong, please try again later !!!";
                $this->response($response);
            }

            $this->response($response);
        } 
        catch (Exception $e) 
        {
            $this->response($e->getMessage());
        }
    }

    /* Employee apporve pass ends */
	
	public function customisedCaseStatus_post()
	{
		/* 
		 * Possible values for search_type
		 * SEARCH_CASE_NUMBER
		 * SEARCH_PARTY_NAME
		 * SEARCH_ADVOCATE_NAME
		 */

		$searchType = $this->post('SEARCH_TYPE'); 
		
		$condition = "";
		$response = array();
		switch($searchType)
		{
			case "SEARCH_CASE_NUMBER" : 
				$caseType = $this->post('CASE_TYPE');
				$caseNumber = $this->post('CASE_NUMBER');
				$caseYear = $this->post('CASE_YEAR');
				
				if(empty($caseType) || empty($caseNumber) || empty($caseYear))
				{
					$response['status'] = FALSE;
					$response['result'] = "Please Check Case Type, Case Number or Case Year Missing.";
					$this->response($response);
				}

				$condition = "M.CTYPE = '$caseType' AND M.REG_NO = '$caseNumber' AND M.REG_YR = '$caseYear'";
				
			break;
			case "SEARCH_PARTY_NAME" : 
				$partyName =strtolower($this->post('PARTY_NAME'));

				/*
				 * Possible values for match type
				 * MATCH_EXACT
				 * MATCH_PART_OF_NAME
				 * MATCH_STARTING_WITH
				 */
				$matchType = $this->post('MATCH_TYPE');

				/*
				 * Possible values for party type
				 * PARTY_ALL
				 * PARTY_PET
				 * PARTY_RES
				 */ 
				$partyType = $this->post('PARTY_TYPE');
				$startYear = $this->post('START_YEAR');
				$endYear = $this->post('END_YEAR');

				if(empty($partyName) || empty($matchType) || empty($partyType) || empty($startYear) || empty($endYear))
				{
					$response['status'] = FALSE;
					$response['result'] = "Please provie Party Name, From Year and To Year";
					$this->response($response); 
				}
				
				$condition = "M.REG_YR >= $startYear AND M.REG_YR <= $endYear";
				$condition .= $this->party_filter($partyType, $matchType, $partyName);
				

			break;	
			case "SEARCH_ADVOCATE_NAME" : 
				$advocateName =strtolower($this->post('ADVOCATE_NAME'));

				/*
				* Possible values for match type
				* MATCH_EXACT
				* MATCH_PART_OF_NAME
				* MATCH_STARTING_WITH
				*/
				$matchType = $this->post('MATCH_TYPE');
				
				$startYear = $this->post('START_YEAR');
				$endYear = $this->post('END_YEAR');

				if(empty($advocateName) || empty($matchType) || empty($startYear) || empty($endYear))
				{
					$response['status'] = FALSE;
					$response['result'] = "Please provie Party Name, From Year and To Year";
					$this->response($response); 
				}

				$condition = "M.REG_YR >= $startYear AND M.REG_YR <= $endYear";
				switch($matchType)
				{
					case "MATCH_EXACT" : 
						$condition .=" AND LOWER(M.ADV_NAME) = '$advocateName'";
					break;
					case "MATCH_PART_OF_NAME" : 
						$condition .=" AND LOWER(M.ADV_NAME) LIKE '%$advocateName%'";
					break;
					case "MATCH_STARTING_WITH" : 
						$condition .=" AND LOWER(M.ADV_NAME) LIKE '$advocateName%'";
					break;
				}	
			break;
			default :
				$response['status'] = FALSE;
				$response['result'] = "Invalid Search Type, Please try again";
				$this->response($response);
			break;
		}
		
		$ocidb = $this->load->database('oracle', true);
		$ocidb->select("M.CTYPE, M.REG_NO, M.REG_YR, M.PET_NAME,M.RES_NAME,M.ADV_NAME,L.NEXT_DATE, L.CASE_STAGE, L.LIST_STAGE, L.COURT_NO, L.DATE_LIST, L.ITEM_NO");
		$ocidb->from("MAIN M");
		$ocidb->join('LISTING L', 'L.REG_NO = M.REG_NO');
		$ocidb->where($condition);
		$ocidbRs = $ocidb->get()->result();
		
		if(empty($ocidbRs))
		{
			$response['status'] = FALSE;
			$response['result'] = "No record found !!!";
			$this->response($response);
		}
		$response['status'] = true;

		foreach ($ocidbRs as $key => $resData) {
			$response['result'][$key] = $resData;
			$response['result'][$key]->CASE_NO = $resData->CTYPE . '/' . $resData->REG_NO . '/' . $resData->REG_YR;
			$response['result'][$key]->PET_NAME = ucwords(strtolower($resData->PET_NAME));
			$response['result'][$key]->RES_NAME = ucwords(strtolower($resData->RES_NAME));
			$response['result'][$key]->NEXT_DATE = $resData->NEXT_DATE;
			$response['result'][$key]->CASE_STAGE = $resData->CASE_STAGE;
			$response['result'][$key]->LIST_STAGE = $resData->LIST_STAGE;
            $response['result'][$key]->ADV_NAME = ucwords(strtolower($resData->ADV_NAME));
			$response['result'][$key]->PARTY = ucwords(strtolower($resData->PET_NAME . ' VS ' . $resData->RES_NAME));

			$list_date = new DateTime($ocidbRs[$key]->DATE_LIST);
			$list_date = $list_date->format('d-m-Y');
			$response['result'][$key]->DATE_LIST = $list_date;

			$next_date = new DateTime($ocidbRs[$key]->NEXT_DATE);
			$next_date = $next_date->format('d-m-Y');
			$response['result'][$key]->NEXT_DATE = $next_date;
		}
		
		$this->response($response);
	}

	public function party_filter($partyType, $matchType, $partyName)
	{	
		$partyTypeArray = array();
		$queryString = "";

		if($partyType == "PARTY_ALL") 
		{
			if($matchType == "MATCH_EXACT") $queryString .=" AND (LOWER(M.PET_NAME) = $partyName OR LOWER(M.RES_NAME) = $partyName)";			
			elseif($matchType == "MATCH_PART_OF_NAME") $queryString .=" AND (LOWER(M.PET_NAME) LIKE '%$partyName%' OR LOWER(M.RES_NAME) LIKE '%$partyName%')";			
			elseif($matchType == "MATCH_STARTING_WITH")$queryString .=" AND (LOWER(M.PET_NAME) LIKE '$partyName%' OR LOWER(M.RES_NAME) LIKE '$partyName%')";			
		}
		elseif($partyType == "PARTY_RES")
		{
			if($matchType == "MATCH_EXACT") $queryString .=" AND LOWER(M.RES_NAME) = $partyName";			
			elseif($matchType == "MATCH_PART_OF_NAME") $queryString .=" AND LOWER(M.RES_NAME) LIKE '%$partyName%'";			
			elseif($matchType == "MATCH_STARTING_WITH")$queryString .=" AND LOWER(M.RES_NAME) LIKE '$partyName%'";			
		}
		elseif($partyType == "PARTY_PET")
		{
			if($matchType == "MATCH_EXACT") $queryString .=" AND LOWER(M.PET_NAME) = $partyName";			
			elseif($matchType == "MATCH_PART_OF_NAME") $queryString .=" AND LOWER(M.PET_NAME) LIKE '%$partyName%'";			
			elseif($matchType == "MATCH_STARTING_WITH")$queryString .=" AND LOWER(M.PET_NAME) LIKE '$partyName%'";			
		}

		return $queryString;
    }
    
    /* E-Inspection creation start*/

    public function eInspection_post() 
    {
        $res = array();

        $caseNumber = $this->post('CASE_NUMBER');
        $inspectionDate = $this->post('INSPECTION_DATE');
        $councilFor = $this->post('COUNCIL_FOR');
        $address = $this->post('ADDRESS');
        $appliedDate = $this->post('APPLIED_DATE');
        $appliedBy = $this->post('APPLIED_BY');
        $decidedDate = $this->post('DECIDED_DATE');
        $diaryNumber = $this->post('DIARY_NUMBER');
        $diaryYear = $this->post('DIARY_YEAR');

        $caseNumberArr = explode('/', $caseNumber);
        
        $data = array(
              'CASETYPE' => $caseNumberArr[0]
            , 'REG_NO' => $caseNumberArr[1]
            , 'REG_YR' => $caseNumberArr[2]
            // , 'INSPECTION_DATE' => $inspectionDate
            // , 'DECIDED_DATE' => $decidedDate
            , 'COUNCIL_FOR' => $councilFor
            , 'ADDRESS' => $address
            // , 'APPLIED_DATE' => $appliedDate
            , 'APPLIED_BY' => $appliedBy
            // , 'DIARY_NO' => $diaryNumber
            // , 'DIARY_YR' => $diaryYear
        );

        $rs = $this->login->eInspectionEntry($data);
        if($rs)
        {
            $res['status'] = true;
            $res['response'] = 'E- Inspection Saved Successfully !!!';
            $this->response($res);
        }
        else
        {
            $res['status'] = false;
            $res['response'] = 'Unable to save E-Inspection data, please try again !!!';
            $this->response($res);
        }
    }

    /* E-Inspection creation ends*/

	public function oracle_get()
	{
		$ocidb = $this->load->database('oracle', true);
		$ocidb->select("M.*, L.*");
		$ocidb->from("MAIN M");
		$ocidb->join('LISTING L', 'L.REG_NO = M.REG_NO');		
		$ocidbRs = $ocidb->get()->result();
		echo "<pre>";
		print_r($ocidbRs); die;
    }
    
    /* Case status not for logged in advocate starts */

    public function getCaseStatusNotForAdv_post()
    {
        try
        {
            $response = array();

            # Step 1 Check if advocate code is sent or not
            if ($this->post('ADV_CODE') == "") {
                $response['status'] = false;
                $response['result'] = "Please provide a valid Advocate Code";

                $this->response($response);
            }

            $adv_code = $this->post('ADV_CODE');

            $ocidb = $this->load->database('oracle', true);
            $ocidb->select("MAIN.CTYPE, MAIN.REG_NO, MAIN.REG_YR, MAIN.PET_NAME,MAIN.RES_NAME,MAIN.ADV_NAME,LISTING.NEXT_DATE, LISTING.CASE_STAGE, LISTING.LIST_STAGE, LISTING.COURT_NO, LISTING.DATE_LIST, LISTING.ITEM_NO");
            $ocidb->from("MAIN");
            $ocidb->join('LISTING', 'LISTING.REG_NO = MAIN.REG_NO');
            $ocidb->where("MAIN.ADV_CODE <> $adv_code");

            $ocidbRs = $ocidb->get()->result();
            
            // print_r($ocidbRs); die;
            if (empty($ocidbRs)) {
                $response['status'] = false;
                $response['result'] = "No result found";
            } else {
                $response['status'] = true;

                foreach ($ocidbRs as $key => $resData) {
                    $response['result'][$key] = $resData;
                    $response['result'][$key]->CASE_NO = $resData->CTYPE . '/' . $resData->REG_NO . '/' . $resData->REG_YR;
                    $response['result'][$key]->PET_NAME = $resData->PET_NAME;
                    $response['result'][$key]->RES_NAME = $resData->RES_NAME;
                    $response['result'][$key]->NEXT_DATE = $resData->NEXT_DATE;
                    $response['result'][$key]->CASE_STAGE = $resData->CASE_STAGE;
                    $response['result'][$key]->LIST_STAGE = $resData->LIST_STAGE;
                    $response['result'][$key]->PARTY = $resData->PET_NAME . ' VS ' . $resData->RES_NAME;

                    $list_date = new DateTime($ocidbRs[$key]->DATE_LIST);
                    $list_date = $list_date->format('d-m-Y');
                    $response['result'][$key]->DATE_LIST = $list_date;

                    $next_date = new DateTime($ocidbRs[$key]->NEXT_DATE);
                    $next_date = $next_date->format('d-m-Y');
                    $response['result'][$key]->NEXT_DATE = $next_date;
                }
                // $response['result'] = $ocidbRs;
            }

            $this->response($response);
        } catch (Exception $e) {
            $this->response($e->getMessage());
        }
    }

    /* Case status not for logged in advocate ends*/


    public function getCourtData_post()
    {
        $date = new DateTime();
        $date = $date->format('Y-m-d');

        # Step 1 Check if court number is sent or not
        if ($this->post('COURT_NUMBER') == "") {
            $response['status'] = false;
            $response['result'] = "Please provide court number";

            $this->response($response);
        }

        $rs = $this->db->select('ITEM_NO, RESULT')
            ->where('COURT_NO', $this->post('COURT_NUMBER'))
            ->where("cast(dt_end as date) < '$date'")
            ->from('COURT_MASTER')->get()->result_array();

        if(empty($rs))
        {
            $response['status'] = FALSE;
            $response['result'] = "No record found !!!";
        }
        else
        {
            $response['status'] = TRUE;
            $response['result'] = $rs;
        }

        $this->response($response);
    }

	
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
