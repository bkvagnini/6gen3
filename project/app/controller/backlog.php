<?php

namespace Controller;

class Backlog extends \Controller {

	protected $_userId;

	public function __construct() {
		$this->_userId = $this->_requireLogin();
	}

	/**
	 * GET /backlog
	 * GET /backlog/@filter
	 * GET /backlog/@filter/@groupid
	 *
	 * @param \Base $f3
	 * @param array $params
	 */
	public function index($f3, $params) {

		if(empty($params["filter"])) {
			$params["filter"] = "groups";
		}

		if(empty($params["groupid"])) {
			$params["groupid"] = "";
		}

		// Get list of all users in the user's groups
		if($params["filter"] == "groups") {
			$group_model = new \Model\User\Group();
			if(!empty($params["groupid"]) && is_numeric($params["groupid"])) {
				//Get users list from a specific Group
				$users_result = $group_model->find(array("group_id = ?", $params["groupid"]));

			} else {
				//Get users list from all groups that you are in
				$groups_result = $group_model->find(array("user_id = ?", $this->_userId));
				$filter_users = array($this->_userId);
				foreach($groups_result as $g) {
					$filter_users[] = $g["group_id"];
				}
				$groups = implode(",", $filter_users);
				$users_result = $group_model->find("group_id IN ({$groups})");
			}

			foreach($users_result as $u) {
				$filter_users[] = $u["user_id"];
			}
		} elseif($params["filter"] == "me") {
			//Just get your own id
			$filter_users = array($this->_userId);
		}

		$filter_string = empty($filter_users) ? "" : "AND owner_id IN (" . implode(",", $filter_users) . ")";
		$f3->set("filter", $params["filter"]);

		$sprint_model = new \Model\Sprint();
		$sprints = $sprint_model->find(array("end_date >= ?", $this->now(false)), array("order" => "start_date ASC"));

		$issue = new \Model\Issue\Detail();

		$sprint_details = array();
		foreach($sprints as $sprint) {
			$projects = $issue->find(array("deleted_date IS NULL AND sprint_id = ? AND type_id = ? $filter_string", $sprint->id, $f3->get("issue_type.project")),
					array('order' => 'priority DESC, due_date')
				);

			if(!empty($params["groupid"])) {
				// Add sorted projects
				$sprintBacklog = array();
				$sortModel = new \Model\Issue\Backlog;
				$sortModel->load(array("user_id = ? AND sprint_id = ?", $params["groupid"], $sprint->id));
				$sortArray = array();
				if($sortModel->id) {
					$sortArray = json_decode($sortModel->issues);
					foreach($sortArray as $id) {
						foreach($projects as $p) {
							if($p->id == $id) {
								$sprintBacklog[] = $p;
							}
						}
					}
				}

				// Add remaining projects
				foreach($projects as $p) {
					if(!in_array($p->id, $sortArray)) {
						$sprintBacklog[] = $p;
					}
				}
			} else {
				$sprintBacklog = $projects;
			}

			$sprint_details[] = $sprint->cast() + array("projects" => $sprintBacklog);
		}

		$large_projects = $f3->get("db.instance")->exec("SELECT parent_id FROM issue WHERE parent_id IS NOT NULL AND type_id = ?", $f3->get("issue_type.project"));
		$large_project_ids = array();
		foreach($large_projects as $p) {
			$large_project_ids[] = $p["parent_id"];
		}

		// Load backlog
		if(!empty($large_project_ids)) {
			$large_project_ids = implode(",", $large_project_ids);
			$unset_projects = $issue->find(
				array("deleted_date IS NULL AND sprint_id IS NULL AND type_id = ? AND status_closed = '0' AND id NOT IN ({$large_project_ids}) $filter_string", $f3->get("issue_type.project")),
				array('order' => 'priority DESC, due_date')
			);
		} else {
			$unset_projects = $issue->find(
				array("deleted_date IS NULL AND sprint_id IS NULL AND type_id = ? AND status_closed = '0' $filter_string", $f3->get("issue_type.project")),
				array('order' => 'priority DESC, due_date')
			);
		}

		// Filter projects into sorted and unsorted arrays if filtering by group
		if(!empty($params["groupid"])) {
			// Add sorted projects
			$backlog = array();
			$sortModel = new \Model\Issue\Backlog;
			$sortModel->load(array("user_id = ? AND sprint_id IS NULL", $params["groupid"]));
			$sortArray = array();
			if($sortModel->id) {
				$sortArray = json_decode($sortModel->issues);
				foreach($sortArray as $id) {
					foreach($unset_projects as $p) {
						if($p->id == $id) {
							$backlog[] = $p;
						}
					}
				}
			}

			// Add remaining projects
			$unsorted = array();
			foreach($unset_projects as $p) {
				if(!in_array($p->id, $sortArray)) {
					$unsorted[] = $p;
				}
			}
		} else {
			$backlog = $unset_projects;
		}

		$groups = new \Model\User();
		$f3->set("groups", $groups->getAllGroups());
		$f3->set("groupid", $params["groupid"]);
		$f3->set("sprints", $sprint_details);
		$f3->set("backlog", $backlog);
		$f3->set("unsorted", $unsorted);

		$f3->set("title", $f3->get("dict.backlog"));
		$f3->set("menuitem", "backlog");
		$this->_render("backlog/index.html");
	}

	/**
	 * POST /edit
	 * @param \Base $f3
	 * @throws \Exception
	 */
	public function edit($f3) {
		$post = $f3->get("POST");
		$issue = new \Model\Issue();
		$issue->load($post["itemId"]);
		$issue->sprint_id = empty($post["reciever"]["receiverId"]) ? null : $post["reciever"]["receiverId"];
		$issue->save();
		$this->_printJson($issue);
	}

	/**
	 * POST /sort
	 * @param \Base $f3
	 * @throws \Exception
	 */
	public function sort($f3) {
		$this->_requireLogin(\Model\User::RANK_MANAGER);
		$backlog = new \Model\Issue\Backlog;
		if($f3->get("POST.sprint_id")) {
			$backlog->load(array("user_id = ? AND sprint_id = ?", $f3->get("POST.user"), $f3->get("POST.sprint_id")));
			$backlog->sprint_id = $f3->get("POST.sprint_id");
		} else {
			$backlog->load(array("user_id = ? AND sprint_id IS NULL", $f3->get("POST.user")));
		}
		$backlog->user_id = $f3->get("POST.user");
		$backlog->issues = $f3->get("POST.items");
		$backlog->save();
	}

	/**
	 * GET /backlog/old
	 * @param \Base $f3
	 */
	public function index_old($f3) {

		$sprint_model = new \Model\Sprint();
		$sprints = $sprint_model->find(array("end_date < ?", $this->now(false)), array("order" => "start_date ASC"));

		$issue = new \Model\Issue\Detail();

		$sprint_details = array();
		foreach($sprints as $sprint) {
			$projects = $issue->find(array("deleted_date IS NULL AND sprint_id = ? AND type_id = ?", $sprint->id, $f3->get("issue_type.project")));
			$sprint_details[] = $sprint->cast() + array("projects" => $projects);
		}

		$f3->set("sprints", $sprint_details);

		$f3->set("title", $f3->get("dict.backlog"));
		$f3->set("menuitem", "backlog");
		$this->_render("backlog/old.html");
	}
}
