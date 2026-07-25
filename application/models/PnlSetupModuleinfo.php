<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * PnlSetupModuleinfo
 *
 * Model for the heading-based PNL setup:
 *   - CRUD for tbl_pnl_heading (headings + sub-headings)
 *   - CRUD for tbl_pnl_account_mapping (account -> heading)
 *   - Report data query that replaces pnlSectionDetails() for the
 *     new heading-driven sections (Operating Expenses, Finance Costs,
 *     Taxes, Earnings Allocation, and optionally Revenue / Cost of
 *     Sales / Other Income too).
 */
class PnlSetupModuleinfo extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // =================================================================
    // HEADINGS
    // =================================================================

    /** All top-level headings (parent_id IS NULL) for a company, ordered for display. */
    public function getTopHeadings($company_id) {
        $this->db->where('tbl_company_idtbl_company', $company_id);
        $this->db->where('parent_id IS NULL', null, false);
        $this->db->where('status', 1);
        $this->db->order_by('display_order', 'ASC');
        return $this->db->get('tbl_pnl_heading')->result_array();
    }

    /** Sub-headings directly under a given heading. */
    public function getSubHeadings($parent_id) {
        $this->db->where('parent_id', $parent_id);
        $this->db->where('status', 1);
        $this->db->order_by('display_order', 'ASC');
        return $this->db->get('tbl_pnl_heading')->result_array();
    }

    /** Full heading list (top + sub) for a company, used by the "Manage Headings" screen. */
    public function getAllHeadingsTree($company_id) {
        $tops = $this->getTopHeadings($company_id);
        foreach ($tops as &$top) {
            $top['children'] = $this->getSubHeadings($top['idtbl_pnl_heading']);
        }
        return $tops;
    }

    /** Flat list of heading id => name, used to populate the account-mapping dropdown. */
    public function getHeadingOptions($company_id) {
        $tree = $this->getAllHeadingsTree($company_id);
        $options = array();
        foreach ($tree as $top) {
            $options[$top['idtbl_pnl_heading']] = $top['heading_name'];
            foreach ($top['children'] as $child) {
                $options[$child['idtbl_pnl_heading']] = '— ' . $child['heading_name'];
            }
        }
        return $options;
    }

    public function addHeading($data) {
        $insert = array(
            'heading_name'                              => $data['heading_name'],
            'parent_id'                                 => !empty($data['parent_id']) ? $data['parent_id'] : NULL,
            'pnl_section'                               => $data['pnl_section'],
            'display_order'                             => isset($data['display_order']) ? $data['display_order'] : 0,
            'tbl_company_idtbl_company'                 => $data['company_id'],
            'tbl_company_branch_idtbl_company_branch'   => $data['branch_id'],
            'tbl_user_idtbl_user'                       => $data['user_id'],
            'insertdatetime'                            => date('Y-m-d H:i:s'),
            'status'                                    => 1
        );
        $this->db->insert('tbl_pnl_heading', $insert);
        return $this->db->insert_id();
    }

    public function updateHeading($id, $data) {
        $update = array(
            'heading_name'   => $data['heading_name'],
            'display_order'  => isset($data['display_order']) ? $data['display_order'] : 0,
            'updateuser'     => isset($data['user_id']) ? $data['user_id'] : NULL,
            'updatedatetime' => date('Y-m-d H:i:s')
        );
        $this->db->where('idtbl_pnl_heading', $id);
        return $this->db->update('tbl_pnl_heading', $update);
    }

    /** Soft-delete a heading. Refuses if it still has sub-headings or mapped accounts. */
    public function deleteHeading($id) {
        $children = $this->db->where('parent_id', $id)->where('status', 1)->get('tbl_pnl_heading')->num_rows();
        if ($children > 0) {
            return array('success' => false, 'message' => 'Remove or reassign sub-headings first.');
        }
        $mapped = $this->db->where('tbl_pnl_heading_idtbl_pnl_heading', $id)->where('status', 1)->get('tbl_pnl_account_mapping')->num_rows();
        if ($mapped > 0) {
            return array('success' => false, 'message' => 'Reassign mapped accounts before deleting this heading.');
        }
        $this->db->where('idtbl_pnl_heading', $id);
        $this->db->update('tbl_pnl_heading', array('status' => 3));
        return array('success' => true);
    }

    /** Given a heading id, return itself + all its sub-heading ids (used when summing a section). */
    public function getHeadingAndChildIds($heading_id) {
        $ids = array($heading_id);
        $children = $this->db->where('parent_id', $heading_id)->where('status', 1)->get('tbl_pnl_heading')->result_array();
        foreach ($children as $child) {
            $ids[] = $child['idtbl_pnl_heading'];
        }
        return $ids;
    }

    /** Look up the top-level heading id for a given pnl_section within a company. */
    public function getHeadingIdBySection($pnl_section, $company_id) {
        $row = $this->db->where('pnl_section', $pnl_section)
                         ->where('tbl_company_idtbl_company', $company_id)
                         ->where('parent_id IS NULL', null, false)
                         ->where('status', 1)
                         ->get('tbl_pnl_heading')->row_array();
        return $row ? $row['idtbl_pnl_heading'] : null;
    }

    // =================================================================
    // ACCOUNT <-> HEADING MAPPING
    // =================================================================

    /** All accounts for a branch with their current heading mapping (NULL if unmapped). */
    public function getAccountMappingList($company_id, $branch_id) {
        $sql = "SELECT
                    tbl_account.idtbl_account,
                    CONCAT(tbl_account.accountno, ' - ', tbl_account.accountname) AS account_display,
                    tbl_account_category.category,
                    tbl_pnl_account_mapping.tbl_pnl_heading_idtbl_pnl_heading AS mapped_heading_id,
                    tbl_pnl_heading.heading_name AS mapped_heading_name
                FROM tbl_account
                INNER JOIN tbl_account_category
                    ON tbl_account.tbl_account_category_idtbl_account_category = tbl_account_category.idtbl_account_category
                INNER JOIN tbl_account_allocation
                    ON tbl_account.idtbl_account = tbl_account_allocation.tbl_account_idtbl_account
                LEFT JOIN tbl_pnl_account_mapping
                    ON tbl_pnl_account_mapping.tbl_account_idtbl_account = tbl_account.idtbl_account
                   AND tbl_pnl_account_mapping.tbl_company_branch_idtbl_company_branch = ?
                   AND tbl_pnl_account_mapping.status = 1
                LEFT JOIN tbl_pnl_heading
                    ON tbl_pnl_heading.idtbl_pnl_heading = tbl_pnl_account_mapping.tbl_pnl_heading_idtbl_pnl_heading
                WHERE tbl_account_allocation.companybank = ?
                  AND tbl_account_allocation.branchcompanybank = ?
                  AND tbl_account_allocation.status = 1
                  AND tbl_account.status = 1
                  AND tbl_account_category.tbl_account_finacialtype_idtbl_account_finacialtype = 1
                ORDER BY tbl_account_category.category, tbl_account.code";

        $query = $this->db->query($sql, array($branch_id, $company_id, $branch_id));
        return $query->result_array();
    }

    /** Insert or update the mapping for one account. */
    public function saveAccountMapping($account_id, $heading_id, $company_id, $branch_id, $user_id = NULL) {
        $existing = $this->db->where('tbl_account_idtbl_account', $account_id)
                              ->where('tbl_company_branch_idtbl_company_branch', $branch_id)
                              ->where('status', 1)
                              ->get('tbl_pnl_account_mapping')->row_array();

        if ($existing) {
            $this->db->where('idtbl_pnl_account_mapping', $existing['idtbl_pnl_account_mapping']);
            return $this->db->update('tbl_pnl_account_mapping', array(
                'tbl_pnl_heading_idtbl_pnl_heading' => $heading_id,
                'updateuser'                        => $user_id,
                'updatedatetime'                     => date('Y-m-d H:i:s')
            ));
        }

        return $this->db->insert('tbl_pnl_account_mapping', array(
            'tbl_user_idtbl_user'                       => $user_id,
            'tbl_account_idtbl_account'                 => $account_id,
            'tbl_pnl_heading_idtbl_pnl_heading'         => $heading_id,
            'tbl_company_idtbl_company'                 => $company_id,
            'tbl_company_branch_idtbl_company_branch'   => $branch_id,
            'status'                                    => 1
        ));
    }

    public function removeAccountMapping($account_id, $branch_id) {
        $this->db->where('tbl_account_idtbl_account', $account_id);
        $this->db->where('tbl_company_branch_idtbl_company_branch', $branch_id);
        return $this->db->update('tbl_pnl_account_mapping', array('status' => 3));
    }

    // =================================================================
    // REPORT DATA — replaces pnlSectionDetails() for heading-driven sections
    // =================================================================

    /**
     * Returns the same row shape as ReportModuleinfo::pnlSectionDetails(),
     * but sourced from tbl_pnl_account_mapping instead of
     * tbl_account_subcategory. $heading_ids should include the top
     * heading id plus any of its sub-heading ids (see
     * getHeadingAndChildIds()) so a section total picks up accounts
     * mapped at either level.
     */
    public function pnlHeadingSectionDetails($heading_ids, $from_master_id, $to_master_id, $companyid, $branchid) {
        if (empty($heading_ids)) {
            return array();
        }
        $placeholders = implode(',', array_fill(0, count($heading_ids), '?'));

        $sql = "SELECT
                    tbl_pnl_heading.idtbl_pnl_heading AS fig_sect_ref,
                    tbl_pnl_heading.heading_name AS sect_name,
                    CONCAT(tbl_account.accountno, ' - ', tbl_account.accountname) AS fig_name,
                    (IFNULL(drv_crdr.dr_accamount, 0) *
                        IFNULL(NULLIF(tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype - 2, 0), 1)
                     + IFNULL(drv_crdr.cr_accamount, 0) *
                        IFNULL(NULLIF(1 - tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype, 0), 1)
                    ) AS fig_value
                FROM tbl_pnl_account_mapping
                INNER JOIN tbl_account
                    ON tbl_pnl_account_mapping.tbl_account_idtbl_account = tbl_account.idtbl_account
                INNER JOIN tbl_account_category
                    ON tbl_account.tbl_account_category_idtbl_account_category = tbl_account_category.idtbl_account_category
                INNER JOIN tbl_pnl_heading
                    ON tbl_pnl_account_mapping.tbl_pnl_heading_idtbl_pnl_heading = tbl_pnl_heading.idtbl_pnl_heading
                LEFT OUTER JOIN (
                    SELECT at.tbl_account_idtbl_account,
                           SUM(at.accamount * (at.crdr = 'D')) AS dr_accamount,
                           SUM(at.accamount * (at.crdr = 'C')) AS cr_accamount
                    FROM tbl_account_transaction at
                    INNER JOIN tbl_master m ON at.tbl_master_idtbl_master = m.idtbl_master
                    WHERE at.reversstatus = 0
                      AND m.tbl_company_idtbl_company = ?
                      AND m.tbl_company_branch_idtbl_company_branch = ?
                      AND m.idtbl_master BETWEEN ? AND ?
                    GROUP BY at.tbl_account_idtbl_account
                ) AS drv_crdr ON tbl_account.idtbl_account = drv_crdr.tbl_account_idtbl_account
                WHERE tbl_pnl_account_mapping.tbl_pnl_heading_idtbl_pnl_heading IN ($placeholders)
                  AND tbl_pnl_account_mapping.status = 1
                  AND tbl_pnl_account_mapping.tbl_company_idtbl_company = ?
                  AND tbl_pnl_account_mapping.tbl_company_branch_idtbl_company_branch = ?
                  AND tbl_account.status = 1
                ORDER BY tbl_account.code";

        $params = array_merge(
            array($companyid, $branchid, $from_master_id, $to_master_id),
            $heading_ids,
            array($companyid, $branchid)
        );

        $query = $this->db->query($sql, $params);
        return $query->result_array();
    }
}
