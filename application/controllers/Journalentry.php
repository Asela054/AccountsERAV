<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Journalentry extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Journalentryinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
        $result['companylist']=get_company_list();
		$result['accounttypelist']=$this->Journalentryinfo->Gettypelist();
		$result['accounttranstypelist']=$this->Journalentryinfo->Gettransactiontypelist();
		$this->load->view('journalentry', $result);
	}
    public function Getbranchaccocompany(){
        $recordID=$this->input->post('recordID');
        $result=get_company_branch_list($recordID);
	}
    public function Getaccountlistaccoaccounttype(){
        $acounttype=$this->input->post('recordID');
        $company=$this->input->post('company');
        $branch=$this->input->post('branch');

        // if($acounttype==1){$result=get_bank_acount_list($company, $branch);}
        // else if($acounttype==2){$result=get_chart_acount_list($company, $branch);}
        // else if($acounttype==3){$result=get_petty_account_list($company, $branch);}    
        
        $result=get_chart_acount_list($company, $branch);
    }
    public function Journalentryinsertupdate(){
        $result=$this->Journalentryinfo->Journalentryinsertupdate();
    }
    public function Getviewpostinfo(){
        $result=$this->Journalentryinfo->Getviewpostinfo();
    }
    public function Journalentryedit(){
        $result=$this->Journalentryinfo->Journalentryedit();
    }
    public function Journalentryposting(){
        $result=$this->Journalentryinfo->Journalentryposting();
    }
    public function Journalentrystatus($x, $y){
        $result=$this->Journalentryinfo->Journalentrystatus($x, $y);
	}
    public function Getglpassdatalist(){
        $result=$this->Journalentryinfo->Getglpassdatalist();
	}
    public function Passtoglentry(){
        $result=$this->Journalentryinfo->Passtoglentry();
	}
    public function Journalentrybatchinsertupdate(){
        $result=$this->Journalentryinfo->Journalentrybatchinsertupdate();
	}
    public function Journalentrybatchcomplete(){
        $result=$this->Journalentryinfo->Journalentrybatchcomplete();
	}
    public function Journalentryinfostatus(){
        $result=$this->Journalentryinfo->Journalentryinfostatus();
	}
}