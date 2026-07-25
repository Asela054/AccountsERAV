<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * PnlSetupModule
 *
 * Admin screens:
 *   - manage_headings()   : list / add / edit / delete headings & sub-headings
 *   - manage_mapping()    : assign each account to a heading
 *
 * Wire these into your menu / routes the same way other admin
 * modules are registered in this app.
 */
class PnlSetupModule extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Commeninfo');
        $this->load->model('PnlSetupModuleinfo');
    }

    // =================================================================
    // HEADINGS
    // =================================================================

    public function manage_headings() {
        $company_id = $_SESSION['companyid'];

        $result['menuaccess'] = $this->Commeninfo->Getmenuprivilege();
        $result['headings'] = $this->PnlSetupModuleinfo->getAllHeadingsTree($company_id);

        $this->load->view('pnl_heading_list', $result);
    }

    public function save_heading() {
        $company_id = $_SESSION['companyid'];
        $branch_id = $_SESSION['branchid'];
        $user_id    = isset($_SESSION['userid']) ? $_SESSION['userid'] : NULL;

        $id            = $this->input->post('idtbl_pnl_heading');
        $heading_name  = trim($this->input->post('heading_name'));
        $parent_id     = $this->input->post('parent_id');
        $pnl_section   = $this->input->post('pnl_section');
        $display_order = $this->input->post('display_order');

        if ($heading_name === '') {
            echo json_encode(array('success' => false, 'message' => 'Heading name is required.'));
            return;
        }

        if (!empty($id)) {
            $this->PnlSetupModuleinfo->updateHeading($id, array(
                'heading_name'  => $heading_name,
                'display_order' => $display_order,
                'user_id'       => $user_id
            ));
        } else {
            $this->PnlSetupModuleinfo->addHeading(array(
                'heading_name'  => $heading_name,
                'parent_id'     => $parent_id,
                'pnl_section'   => $pnl_section,
                'display_order' => $display_order,
                'company_id'    => $company_id,
                'branch_id'     => $branch_id,
                'user_id'       => $user_id
            ));
        }

        echo json_encode(array('success' => true));
    }

    public function delete_heading() {
        $id = $this->input->post('idtbl_pnl_heading');
        $result = $this->PnlSetupModuleinfo->deleteHeading($id);
        echo json_encode($result);
    }

    // =================================================================
    // ACCOUNT MAPPING
    // =================================================================

    public function manage_mapping() {
        $company_id = $_SESSION['companyid'];
        $branch_id  = $_SESSION['branchid'];

        $result['menuaccess']      = $this->Commeninfo->Getmenuprivilege();
        $result['accounts']        = $this->PnlSetupModuleinfo->getAccountMappingList($company_id, $branch_id);
        $result['heading_options'] = $this->PnlSetupModuleinfo->getHeadingOptions($company_id);

        $this->load->view('pnl_account_mapping', $result);
    }

    public function save_mapping() {
        $company_id = $_SESSION['companyid'];
        $branch_id  = $_SESSION['branchid'];
        $user_id    = isset($_SESSION['userid']) ? $_SESSION['userid'] : NULL;

        $account_id = $this->input->post('account_id');
        $heading_id = $this->input->post('heading_id');

        if (empty($heading_id)) {
            $this->PnlSetupModuleinfo->removeAccountMapping($account_id, $branch_id);
            echo json_encode(array('success' => true, 'message' => 'Mapping removed.'));
            return;
        }

        $this->PnlSetupModuleinfo->saveAccountMapping($account_id, $heading_id, $company_id, $branch_id, $user_id);
        echo json_encode(array('success' => true));
    }
}
