<?php
namespace Fab\Controllers;

use Fab\Database\DB;

class ItemsController extends Controller
{
    public function __construct($data = null)
    {
        parent::__construct($data);
    }

    public function showAllProjectItems()
    {
        $myDB = new DB();

        $urlName = $this->project;

        $project = $myDB->getProjectByUrlName($urlName);
        $nextProject = $myDB->getNextProject($project);
        $previousProject = $myDB->getPreviousProject($project);

        $items = $myDB->getAllProjectItemsByProjectID($project['id']);

        if (!empty($items)) {
            echo $this->twig->render('single_item.twig', array('items' => $items, 'nextProject' => $nextProject, 'previousProject' => $previousProject));
        } else {
            header("Location: /portfolio/$nextProject", true, 302);
            exit;
        }
    }

    public function showAllProjects()
    {
        $myDB = new DB();

        $projects = $myDB->getAllProjectsForPortfolio();

        //Add project fields (column values) to existing array
        $count = 0;
        foreach ($projects as $project) {
            $fullProject = $myDB->getProjectByID($project['projectID']);
            $project['projectDescription'] = $fullProject['projectDescription'];
            $project['projectName'] = $fullProject['name'];
            $project['projectTags'] = $fullProject['tags'];
            $project['urlName'] = $fullProject['urlName'];
            $projects[$count] = $project;
            $count++;
        }

        echo $this->twig->render('portfolio.twig', array('projects' => $projects));
    }

}